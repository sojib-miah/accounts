<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Party;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\ReceiptPayment;
use App\Models\SerialNumber;
use App\Models\StockTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Receipt::with(['party', 'branch', 'creator', 'items'])
            ->whereIn('type', ['Sales-Order', 'Income', 'Challan'])
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            });

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('receipt_no', 'like', "%{$search}%")
                    ->orWhereHas('party', function ($party) use ($search) {
                        $party->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('branch', function ($branch) use ($search) {
                        $branch->where('name', 'like', "%{$search}%");
                    });
            });
        }
        // Payment Status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        $perPage = $request->per_page ?? 24;
        $receipts = $query->latest()->paginate($perPage)->withQueryString();
        return view('BackEnd.SalesOrder.index', compact('receipts'));
    }

    public function createIncome()
    {
        $companies = Company::when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
            $q->where('id', Auth::user()->company_id);
        })->get();
        $branches = Branch::when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
            $q->where('created_by', Auth::id())
                ->orWhere('id', Auth::user()->branch_id);
        })->latest()->get();
        $parties = Party::where('type', 'Customer')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();

        $products = Product::with('category')
            ->where('status', 'Active')
            ->whereHas('receiptItems.receipt', function ($q) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
            })
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            })
            ->orderBy('name')
            ->get();
        return view('BackEnd.SalesOrder.income_create', compact('branches', 'parties', 'products', 'companies'));
    }

    private function generateReceiptNo()
    {
        do {
            $number = 'SR-' . date('Ymd') . rand(1000, 9999);
        } while (Receipt::where('receipt_no', $number)->exists());

        return $number;
    }

    private function generateSONo()
    {
        do {
            $number = 'SO-' . date('Ymd') . rand(1000, 9999);
        } while (Receipt::where('so_no', $number)->exists());
        return $number;
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id'       => 'required|exists:companies,id',
            'branch_id'        => 'required|exists:branches,id',
            'party_id'         => 'required|exists:parties,id',
            'receipt_date'     => 'required|date',
            'product_id'       => 'required|array|min:1',
            'product_id.*'     => 'required|exists:products,id',
            'qty'              => 'required|array',
            'qty.*'            => 'required|numeric|min:1',
            'rate'             => 'required|array',
            'rate.*'           => 'required|numeric|min:0',
            'serial_json'      => 'nullable|array',
            'serial_json.*'    => 'nullable',
            'details'          => 'nullable|array',
            'details.*'        => 'nullable|string',
            'discount'         => 'nullable|numeric|min:0',
            'vat'              => 'nullable|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $userId = $user->id;
            $companyId = (int) $request->company_id;
            if (!$user->hasRole('Super-Admin')) {
                if ($companyId != $user->company_id) {
                    throw new \Exception('You are not allowed to create sales for this company.');
                }
            }
            $branch = Branch::where('id', $request->branch_id)->firstOrFail();
            if ($branch->company_id != $companyId) {
                throw new \Exception('Selected branch does not belong to the selected company.');
            }
            $productIds = $request->product_id;
            if (count($productIds) !== count(array_unique($productIds))) {
                throw new \Exception('Duplicate product found in Sales Order.');
            }
            $totalQty = 0;
            $subTotal = 0;
            foreach ($productIds as $key => $productId) {
                $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
                if (!$user->hasRole('Super-Admin') && $product->company_id != $companyId) {
                    throw new \Exception("Product {$product->name} does not belong to your company.");
                }
                $qty = (float) $request->qty[$key];
                $rate = (float) $request->rate[$key];
                if ($qty > (float) $product->current_stock) {
                    throw new \Exception($product->name . ' stock is only ' . $product->current_stock);
                }
                $totalQty += $qty;
                $subTotal += ($qty * $rate);
            }
            $discount = (float) ($request->discount ?? 0);
            if ($discount > $subTotal) {
                $discount = $subTotal;
            }
            $vatPercent = (float) ($request->vat ?? 0);
            $afterDiscount = $subTotal - $discount;
            if ($afterDiscount < 0) {
                $afterDiscount = 0;
            }
            $vatAmount = ($afterDiscount * $vatPercent) / 100;
            $grandTotal = $afterDiscount + $vatAmount;
            $receipt = Receipt::create([
                'receipt_no' => $this->generateReceiptNo(),
                'so_no' => $this->generateSONo(),
                'type' => 'Sales-Order',
                'company_id' => $companyId,
                'branch_id' => $request->branch_id,
                'party_id' => $request->party_id,
                'receipt_date' => $request->receipt_date,
                'remarks' => $request->remarks,
                'total_qty' => $totalQty,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'vat' => $vatPercent,
                'total_amount' => $grandTotal,
                'paid_amount' => 0,
                'due_amount' => $grandTotal,
                'payment_status' => 'Pending',
                'status' => 'Completed',
                'created_by' => $userId,
            ]);
            $usedSerials = [];
            foreach ($productIds as $key => $productId) {
                $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
                $qty = (float) $request->qty[$key];
                $rate = (float) $request->rate[$key];
                $amount = $qty * $rate;
                $serialJson = $request->serial_json[$key] ?? '[]';
                $serials = json_decode($serialJson, true);
                if (!is_array($serials)) {
                    $serials = [];
                }
                $serials = collect($serials)
                    ->map(function ($serial) {
                        return strtoupper(trim((string) $serial));
                    })
                    ->filter(function ($serial) {
                        return $serial !== '';
                    })
                    ->values()
                    ->toArray();
                if (count($serials) !== count(array_unique($serials))) {
                    throw new \Exception('Duplicate serial number found for product: ' . $product->name);
                }
                foreach ($serials as $serial) {
                    if (in_array($serial, $usedSerials, true)) {
                        throw new \Exception("Serial Number {$serial} has been selected more than once.");
                    }
                    $usedSerials[] = $serial;
                }
                $availableSerialQuery = SerialNumber::where('product_id', $productId)
                    ->where('status', 'Available')
                    ->where(function ($query) use ($companyId) {
                        $query->where('company_id', $companyId)
                            ->orWhereNull('company_id');
                    })
                    ->where(function ($query) use ($request) {
                        $query->where('branch_id', $request->branch_id)
                            ->orWhereNull('branch_id');
                    });
                $availableSerialCount = $availableSerialQuery->count();
                if ($availableSerialCount > 0) {
                    if (count($serials) !== (int) $qty) {
                        throw new \Exception("Please select exactly " . (int) $qty . " serial number(s) for " . $product->name . ". Selected: " . count($serials));
                    }
                    if (count($serials) > $availableSerialCount) {
                        throw new \Exception("Only " . $availableSerialCount . " serial number(s) are available for " . $product->name . ".");
                    }
                } else {
                    throw new \Exception("No available serial number found for " . $product->name . " in the selected branch.");
                }
                $receiptItem = ReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'rate' => $rate,
                    'amount' => $amount,
                    'details' => $request->details[$key] ?? null,
                ]);
                foreach ($serials as $serial) {
                    $serialRecord = SerialNumber::where('product_id', $productId)
                        ->where('serial_no', $serial)
                        ->where('status', 'Available')
                        ->where(function ($query) use ($companyId) {
                            $query->where('company_id', $companyId)
                                ->orWhereNull('company_id');
                        })
                        ->where(function ($query) use ($request) {
                            $query->where('branch_id', $request->branch_id)
                                ->orWhereNull('branch_id');
                        })
                        ->lockForUpdate()
                        ->first();
                    if (!$serialRecord) {
                        throw new \Exception("Serial Number {$serial} " . "is no longer available.");
                    }
                    $serialRecord->update([
                        'company_id' => $companyId,
                        'branch_id' => $request->branch_id,
                        'status' => 'Sold',
                        'sale_date' => today(),
                        'receipt_id' => $receipt->id,
                        'receipt_item_id' => $receiptItem->id,
                        'updated_by' => $userId,
                    ]);
                }
                if ($qty > (float) $product->current_stock) {
                    throw new \Exception($product->name . ' stock is only ' . $product->current_stock);
                }
                $product->decrement('current_stock', $qty);
                $product->refresh();
                StockTransaction::create([
                    'company_id' => $receipt->company_id,
                    'branch_id' => $receipt->branch_id,
                    'product_id' => $product->id,
                    'receipt_id' => $receipt->id,
                    'transaction_type' => 'Sale',
                    'stock_in' => 0,
                    'stock_out' => $qty,
                    'balance' => $product->current_stock,
                    'transaction_date' => $receipt->receipt_date,
                    'remarks' => 'Sales Order',
                    'created_by' => $userId,
                ]);
            }
            DB::commit();
            return redirect()->route('sales.order.show', $receipt)->with('success', 'Sales Order Created Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Receipt $receipt)
    {
        $receipt->load([
            'company',
            'branch',
            'party',
            'creator',
            'items.product.category',
            'items.product.brand',
            'payments.account',
            'payments.paymentType',
            'payments.user',
        ]);

        $paymentTypes = PaymentType::where('status', 'Active')->get();

        return view('BackEnd.SalesOrder.show', compact('receipt', 'paymentTypes'));
    }

    public function edit(Receipt $receipt)
    {
        $receipt->load([
            'branch',
            'party',
            'creator',
            'items.category',
            'items.accountHead',
            'items.serialNumbers',
        ]);
        $companies = Company::when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
            $q->where('id', Auth::user()->company_id);
        })->get();
        $branches = Branch::when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
            $q->where('created_by', Auth::id())
                ->orWhere('id', Auth::user()->branch_id);
        })->latest()->get();
        $parties = Party::where('type', 'Customer')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();

        $products = Product::with('category')
            ->where('status', 'Active')
            ->whereHas('receiptItems.receipt', function ($q) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
            })
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            })
            ->orderBy('name')
            ->get();
        $receiptItems = $receipt->items->map(function ($item) {
            return [
                'category_id'       => $item->category_id,
                'account_head_id'   => $item->account_head_id,
                'qty'               => $item->qty,
                'rate'              => $item->rate,
                'amount'            => $item->amount,
                'details'           => $item->details,
            ];
        });

        $oldItems = $receipt->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'stock'      => $item->product->current_stock + $item->qty,
                'qty'        => $item->qty,
                'rate'       => $item->rate,
                'details'    => $item->details,
                'serials' => $item->serialNumbers->pluck('serial_no')->values()->toArray(),
            ];
        })->values();
        return view('BackEnd.SalesOrder.edit', compact('receipt', 'branches', 'parties', 'products', 'receiptItems', 'companies', 'oldItems'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $request->validate([
            'company_id'       => 'nullable|exists:companies,id',
            'branch_id'        => 'required|exists:branches,id',
            'party_id'         => 'required|exists:parties,id',
            'receipt_date'     => 'required|date',

            'product_id'       => 'required|array|min:1',
            'product_id.*'     => 'required|exists:products,id',

            'qty'              => 'required|array',
            'qty.*'            => 'required|numeric|min:1',

            'rate'             => 'required|array',
            'rate.*'           => 'required|numeric|min:0',

            'serial_json'      => 'nullable|array',
            'serial_json.*'    => 'nullable',

            'details'          => 'nullable|array',
            'details.*'        => 'nullable|string',

            'discount'         => 'nullable|numeric|min:0',
            'vat'              => 'nullable|numeric|min:0',
        ]);

        if ($receipt->status === 'Cancelled') {
            return back()->with(
                'error',
                'Cancelled Sales Order cannot be updated.'
            );
        }

        DB::beginTransaction();

        try {

            $user = auth()->user();
            $userId = $user->id;

            $companyId = $receipt->company_id;

            if ($request->filled('company_id')) {
                $companyId = (int) $request->company_id;
            }

            if (!$user->hasRole('Super-Admin')) {

                if ($companyId != $user->company_id) {
                    throw new \Exception(
                        'You are not allowed to update this Sales Order.'
                    );
                }
            }


            $branch = Branch::findOrFail($request->branch_id);

            if ($branch->company_id != $companyId) {
                throw new \Exception(
                    'Selected branch does not belong to the selected company.'
                );
            }


            $productIds = $request->product_id;

            if (count($productIds) !== count(array_unique($productIds))) {

                throw new \Exception(
                    'Duplicate product found in Sales Order.'
                );
            }

            foreach ($receipt->items as $oldItem) {

                Product::where('id', $oldItem->product_id)
                    ->increment('current_stock', $oldItem->qty);
            }

            SerialNumber::where('receipt_id', $receipt->id)
                ->update([
                    'status' => 'Available',
                    'sale_date' => null,
                    'receipt_id' => null,
                    'receipt_item_id' => null,
                    'updated_by' => $userId,
                ]);

            StockTransaction::where('receipt_id', $receipt->id)->delete();

            $receipt->items()->delete();

            $totalQty = 0;
            $subTotal = 0;

            foreach ($productIds as $key => $productId) {

                $product = Product::where('id', $productId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    !$user->hasRole('Super-Admin')
                    && $product->company_id != $companyId
                ) {

                    throw new \Exception(
                        "Product {$product->name} does not belong to your company."
                    );
                }

                $qty = (float) $request->qty[$key];
                $rate = (float) $request->rate[$key];

                if ($qty > (float) $product->current_stock) {

                    throw new \Exception(
                        $product->name .
                            ' available stock is only ' .
                            $product->current_stock
                    );
                }

                $totalQty += $qty;
                $subTotal += ($qty * $rate);
            }

            $discount = (float) ($request->discount ?? 0);

            if ($discount > $subTotal) {
                $discount = $subTotal;
            }

            $vatPercent = (float) ($request->vat ?? 0);

            $afterDiscount = $subTotal - $discount;

            if ($afterDiscount < 0) {
                $afterDiscount = 0;
            }

            $vatAmount =
                ($afterDiscount * $vatPercent) / 100;

            $grandTotal =
                $afterDiscount + $vatAmount;

            $paidAmount =
                (float) ($receipt->paid_amount ?? 0);

            $dueAmount =
                $grandTotal - $paidAmount;

            if ($dueAmount < 0) {
                $dueAmount = 0;
            }


            if ($paidAmount <= 0) {

                $paymentStatus = 'Pending';
            } elseif ($paidAmount >= $grandTotal) {

                $paymentStatus = 'Paid';
            } else {

                $paymentStatus = 'Partial';
            }

            $receipt->update([

                'company_id' => $companyId,
                'branch_id' => $request->branch_id,
                'party_id' => $request->party_id,

                'receipt_date' => $request->receipt_date,
                'remarks' => $request->remarks,

                'total_qty' => $totalQty,

                'sub_total' => $subTotal,
                'discount' => $discount,
                'vat' => $vatPercent,

                'total_amount' => $grandTotal,

                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,

                'payment_status' => $paymentStatus,

                'updated_by' => $userId,
            ]);
            $usedSerials = [];

            foreach ($productIds as $key => $productId) {

                $product = Product::where('id', $productId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $qty = (float) $request->qty[$key];

                $rate = (float) $request->rate[$key];

                $amount = $qty * $rate;

                $serialJson =
                    $request->serial_json[$key] ?? '[]';

                $serials =
                    json_decode($serialJson, true);

                if (!is_array($serials)) {
                    $serials = [];
                }


                $serials = collect($serials)
                    ->map(function ($serial) {

                        return strtoupper(
                            trim((string) $serial)
                        );
                    })
                    ->filter(function ($serial) {

                        return $serial !== '';
                    })
                    ->values()
                    ->toArray();

                if (
                    count($serials)
                    !== count(array_unique($serials))
                ) {

                    throw new \Exception(
                        'Duplicate serial number found for product: ' .
                            $product->name
                    );
                }


                foreach ($serials as $serial) {

                    if (
                        in_array(
                            $serial,
                            $usedSerials,
                            true
                        )
                    ) {

                        throw new \Exception(
                            "Serial Number {$serial} has been selected more than once."
                        );
                    }

                    $usedSerials[] = $serial;
                }

                $availableSerialQuery =
                    SerialNumber::where(
                        'product_id',
                        $productId
                    )
                    ->where('status', 'Available')

                    ->where(function ($query) use ($companyId) {

                        $query->where(
                            'company_id',
                            $companyId
                        )->orWhereNull(
                            'company_id'
                        );
                    })

                    ->where(function ($query) use ($request) {

                        $query->where(
                            'branch_id',
                            $request->branch_id
                        )->orWhereNull(
                            'branch_id'
                        );
                    });


                $availableSerialCount =
                    $availableSerialQuery->count();
                if ($availableSerialCount > 0) {

                    if (
                        count($serials)
                        != (int) $qty
                    ) {

                        throw new \Exception(
                            "Please select exactly " .
                                (int) $qty .
                                " serial number(s) for " .
                                $product->name .
                                ". Selected: " .
                                count($serials)
                        );
                    }

                    if (
                        count($serials)
                        > $availableSerialCount
                    ) {

                        throw new \Exception(
                            "Only " .
                                $availableSerialCount .
                                " serial number(s) are available for " .
                                $product->name .
                                "."
                        );
                    }
                } else {

                    throw new \Exception(
                        "No available serial number found for " .
                            $product->name .
                            " in the selected branch."
                    );
                }

                $receiptItem =
                    ReceiptItem::create([

                        'receipt_id' =>
                        $receipt->id,

                        'product_id' =>
                        $productId,

                        'qty' =>
                        $qty,

                        'rate' =>
                        $rate,

                        'amount' =>
                        $amount,

                        'details' =>
                        $request->details[$key] ?? null,
                    ]);

                foreach ($serials as $serial) {

                    $serialRecord =
                        SerialNumber::where(
                            'product_id',
                            $productId
                        )
                        ->where(
                            'serial_no',
                            $serial
                        )
                        ->where(
                            'status',
                            'Available'
                        )

                        ->where(function ($query) use ($companyId) {

                            $query->where(
                                'company_id',
                                $companyId
                            )->orWhereNull(
                                'company_id'
                            );
                        })

                        ->where(function ($query) use ($request) {

                            $query->where(
                                'branch_id',
                                $request->branch_id
                            )->orWhereNull(
                                'branch_id'
                            );
                        })

                        ->lockForUpdate()
                        ->first();


                    if (!$serialRecord) {

                        throw new \Exception(
                            "Serial Number {$serial} is no longer available."
                        );
                    }
                    $serialRecord->update([

                        'company_id' =>
                        $companyId,

                        'branch_id' =>
                        $request->branch_id,

                        'status' =>
                        'Sold',

                        'sale_date' =>
                        today(),

                        'receipt_id' =>
                        $receipt->id,

                        'receipt_item_id' =>
                        $receiptItem->id,

                        'updated_by' =>
                        $userId,
                    ]);
                }


                if (
                    $qty >
                    (float) $product->current_stock
                ) {

                    throw new \Exception(
                        $product->name .
                            ' available stock is only ' .
                            $product->current_stock
                    );
                }


                $product->decrement(
                    'current_stock',
                    $qty
                );

                $product->refresh();
                StockTransaction::create([

                    'company_id' =>
                    $companyId,

                    'branch_id' =>
                    $request->branch_id,

                    'product_id' =>
                    $product->id,

                    'receipt_id' =>
                    $receipt->id,

                    'transaction_type' =>
                    'Sale',

                    'stock_in' =>
                    0,

                    'stock_out' =>
                    $qty,

                    'balance' =>
                    $product->current_stock,

                    'transaction_date' =>
                    $request->receipt_date,

                    'remarks' =>
                    'Sales Order Update',

                    'created_by' =>
                    $userId,
                ]);
            }


            DB::commit();


            return redirect()
                ->route(
                    'sales.order.show',
                    $receipt
                )
                ->with(
                    'success',
                    'Sales Order Updated Successfully.'
                );
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function cancel(Receipt $receipt)
    {
        if ($receipt->status == 'Cancelled') {
            return back()->with('error', 'Receipt already cancelled.');
        }
        DB::beginTransaction();
        try {
            foreach ($receipt->payments as $payment) {
                $account = Account::find($payment->account_id);
                if ($account) {
                    if ($receipt->type == 'Income') {
                        $account->current_balance -= $payment->amount;
                    } else {
                        $account->current_balance += $payment->amount;
                    }
                    $account->save();
                }
                AccountTransaction::where('receipt_id', $receipt->id)->delete();
            }
            $receipt->payments()->delete();
            $receipt->update([
                'paid_amount' => 0,
                'due_amount' => $receipt->total_amount,
                'payment_status' => 'Pending',
                'status' => 'Cancelled',
                'updated_by' => auth()->id(),
            ]);
            DB::commit();
            return redirect()->route('sales.order.show', $receipt->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function profile(Request $request, Party $party)
    {
        $receiptQuery = Receipt::with([
            'creator',
            'branch',
            'company'
        ])
            ->where('party_id', $party->id)
            ->where('is_invoice', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $receiptQuery->where(function ($q) use ($search) {
                $q->where('receipt_no', 'like', "%{$search}%")
                    ->orWhere('inv_no', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $receiptQuery->where(
                'payment_status',
                $request->status
            );
        }

        $receipts = $receiptQuery->latest()->paginate(20, ['*'], 'receipt_page')->withQueryString();

        $paymentQuery = ReceiptPayment::with([
            'receipt',
            'paymentType',
            'account',
            'user'
        ])
            ->whereHas('receipt', function ($q) use ($party) {
                $q->where('party_id', $party->id)
                    ->where('is_invoice', true);
            });

        if ($request->filled('payment_search')) {
            $search = $request->payment_search;
            $paymentQuery->where(function ($q) use ($search) {
                $q->where('amount', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhereHas('receipt', function ($r) use ($search) {
                        $r->where('receipt_no', 'like', "%{$search}%")
                            ->orWhere('inv_no', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $paymentQuery->latest()->paginate(20, ['*'], 'payment_page')->withQueryString();
        $invoice = Receipt::where('party_id', $party->id)->where('is_invoice', true);

        $summary = [
            'receipt_count' => (clone $invoice)->count(),
            'qty' => (clone $invoice)->sum('total_qty'),
            'net' => (clone $invoice)->sum('total_amount'),
            'paid' => (clone $invoice)->sum('paid_amount'),
            'due' => (clone $invoice)->sum('due_amount'),
        ];

        $paymentTypes = PaymentType::where('status', 'Active')->orderBy('name')->get();

        return view(
            'BackEnd.SalesOrder.profile',
            compact(
                'party',
                'receipts',
                'payments',
                'summary',
                'paymentTypes'
            )
        );
    }

    public function duePayment(Request $request, Party $party)
    {
        $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'payment_date'    => 'required|date',
            'amount'          => 'required|numeric|min:0.01',
            'note'            => 'nullable|string',
        ]);
        DB::beginTransaction();
        try {
            $amount = $request->amount;
            $account = Account::where('default_status', 'Default')->first();
            if (!$account) {
                return back()->with('error', 'Default account not found.');
            }
            $receipts = Receipt::where('party_id', $party->id)->where('due_amount', '>', 0)->orderBy('receipt_date')->get();
            foreach ($receipts as $receipt) {
                if ($amount <= 0) {
                    break;
                }
                $pay = min($amount, $receipt->due_amount);
                ReceiptPayment::create([
                    'receipt_id'      => $receipt->id,
                    'payment_type_id' => $request->payment_type_id,
                    'account_id'      => $account->id,
                    'payment_date'    => $request->payment_date,
                    'amount'          => $pay,
                    'note'            => $request->note,
                    'created_by'      => Auth::id(),
                ]);
                $receipt->paid_amount += $pay;
                $receipt->due_amount -= $pay;
                if ($receipt->due_amount <= 0) {
                    $receipt->due_amount = 0;
                    $receipt->payment_status = 'Paid';
                } elseif ($receipt->paid_amount > 0) {
                    $receipt->payment_status = 'Partial';
                }
                $receipt->save();
                $account->current_balance -= $pay;
                $account->save();
                AccountTransaction::create([
                    'company_id' => auth()->user()->company_id,
                    'account_id'       => $account->id,
                    'receipt_id'       => $receipt->id,
                    'voucher_no'       => $receipt->receipt_no,
                    'transaction_date' => $request->payment_date,
                    'transaction_type' => 'Income',
                    'purpose'          => 'Party Due Payment',
                    'credit'           => 0,
                    'debit'            => $pay,
                    'balance'          => $account->current_balance,
                    'created_by'       => Auth::id(),
                ]);
                $amount -= $pay;
            }
            DB::commit();
            return redirect()->route('sales.order.profile', $party->id)->with('success', 'Due payment completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function generateDMNo()
    {
        do {
            $number = 'DM-' . date('Ymd') . rand(1000, 9999);
        } while (Receipt::where('dm_no', $number)->exists());
        return $number;
    }

    private function generateInvoiceNo()
    {
        do {
            $number = 'INV-' . date('Ymd') . rand(1000, 9999);
        } while (Receipt::where('inv_no', $number)->exists());
        return $number;
    }

    public function convertChallan(Receipt $receipt)
    {
        if ($receipt->is_challan) {
            return back()->with('error', 'Already converted to Challan.');
        }
        $receipt->update([
            'is_challan' => true,
            'dm_no' => $this->generateDMNo(),
        ]);
        return back()->with('success', 'Converted to Challan Successfully.');
    }

    public function convertIncome(Receipt $receipt)
    {
        if ($receipt->is_invoice) {
            return back()->with('error', 'Already converted to Invoice.');
        }
        $receipt->update([
            'is_invoice' => true,
            'inv_no' => $this->generateInvoiceNo(),
        ]);
        return back()->with('success', 'Converted to Invoice Successfully.');
    }

    public function print(Receipt $receipt)
    {
        $receipt->load([
            'company',
            'branch',
            'party',
            'creator',
            'items.category',
            'items.accountHead',
            'payments.paymentType',
            'payments.account',
            'payments.user',
        ]);

        return view('BackEnd.SalesOrder.print', compact('receipt'));
    }

    public function pdf(Receipt $receipt)
    {
        $receipt->load([
            'company',
            'branch',
            'party',
            'creator',
            'items.product',
            'payments.paymentType',
            'payments.account',
            'payments.user',
        ]);
        $pdf = Pdf::loadView('BackEnd.SalesOrder.pdf', compact('receipt'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream(
            $receipt->receipt_no . '.pdf'
        );
    }

    public function availableSerials(Request $request, Product $product)
    {
        $user = Auth::user();
        $branchId = $request->branch_id;
        if (empty($branchId)) {
            return response()->json(['success' => false, 'message' => 'Please select a branch first.'], 422);
        }
        $branch = Branch::find($branchId);
        if (!$branch) {
            return response()->json(['success' => false, 'message' => 'Selected branch not found.'], 404);
        }
        $companyId = $branch->company_id;
        if (!$user->hasRole('Super-Admin')) {
            if ($companyId && (int) $companyId !== (int) $user->company_id) {
                return response()->json(['success' => false, 'message' => 'You are not allowed to access this branch.'], 403);
            }
        }
        $serials = SerialNumber::query()
            ->where('product_id', $product->id)
            ->where('status', 'Available')
            ->where(function ($query) use ($companyId) {
                if ($companyId) {
                    $query->where('company_id', $companyId)->orWhereNull('company_id');
                } else {
                    $query->whereNull('company_id');
                }
            })
            ->where(function ($query) use ($branchId) {
                $query->where('branch_id', $branchId)->orWhereNull('branch_id');
            })
            ->orderBy('serial_no')
            ->get([
                'id',
                'serial_no',
                'product_id',
                'company_id',
                'branch_id',
                'status',
                'receipt_id',
                'receipt_item_id',
            ]);
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'stock' => $product->current_stock,
            ],
            'serials' => $serials,
            'count' => $serials->count(),
        ]);
    }

    public function serials(Receipt $receipt, Product $product)
    {
        $user = auth()->user();

        if (!$user->hasRole('Super-Admin')) {
            if ($product->company_id != $user->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized product.'
                ], 403);
            }

            if ($receipt->company_id != $user->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized sales order.'
                ], 403);
            }
        }

        $companyId = $receipt->company_id;
        $branchId  = $receipt->branch_id;

        $serials = SerialNumber::where('product_id', $product->id)
            ->where(function ($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->orWhereNull('company_id');
            })
            ->where(function ($query) use ($branchId) {
                $query->where('branch_id', $branchId)
                    ->orWhereNull('branch_id');
            })
            ->where(function ($query) use ($receipt) {

                $query->where('status', 'Available')

                    ->orWhere(function ($q) use ($receipt) {
                        $q->where('status', 'Sold')
                            ->where('receipt_id', $receipt->id);
                    });
            })
            ->orderBy('serial_no')
            ->get([
                'id',
                'serial_no',
                'status',
                'receipt_id',
                'receipt_item_id',
                'company_id',
                'branch_id',
            ]);

        return response()->json([
            'success' => true,

            'serials' => $serials->map(function ($serial) use ($receipt) {

                return [
                    'id' => $serial->id,

                    'serial_no' => $serial->serial_no,

                    'status' => $serial->status,
                    'selected' =>
                    $serial->status === 'Sold'
                        && (int) $serial->receipt_id === (int) $receipt->id,
                ];
            })->values(),
        ]);
    }
}
