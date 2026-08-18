<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomerCompany;
use App\Models\Party;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\SerialNumber;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected function generateReceiptNo()
    {
        $last = Receipt::where('type', 'Purchase-Order')->orderByDesc('id')->first();
        if (!$last) {
            return 'PO-10001';
        }
        preg_match('/(\d+)$/', $last->receipt_no, $matches);
        $number = isset($matches[1]) ? (int)$matches[1] : 10000;
        return 'PO-' . ($number + 1);
    }

    /**
     * Display Purchase List
     */
    public function index(Request $request)
    {
        $query = Receipt::with('supplier')
            ->where('type', 'Purchase-Order')
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', auth()->id());
            });

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('receipt_no', 'like', "%{$search}%")
                    ->orWhere('po_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('supplier')) {
            $query->where('party_id', $request->supplier);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('receipt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('receipt_date', '<=', $request->to_date);
        }

        $purchases = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $suppliers = Party::whereIn('type', ['Supplier', 'Both'])
            ->where('status', 'Active')
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', auth()->id());
            })
            ->orderBy('name')
            ->get();

        return view('BackEnd.Purchase.index', compact('purchases', 'suppliers'));
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        $receiptNo = $this->generateReceiptNo();
        $suppliers = Party::whereIn('type', ['Supplier', 'Both'])->where('status', 'Active')->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', auth()->id());
        })->orderBy('name')->get();
        $products = Product::where('status', 'Active')->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            });
        })->orderBy('name')->get();
        $customerCompanies = CustomerCompany::where('status', 'Supplier')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        return view('BackEnd.Purchase.create', compact('receiptNo', 'suppliers', 'products', 'customerCompanies'));
    }

    /**
     * Save Purchase
     */
    public function store(Request $request)
    {
        $request->validate([
            'receipt_date'      => 'required|date',
            'customer_company_id' => 'required|exists:customer_companies,id',
            'party_id'          => 'required|exists:parties,id',
            'product_id'        => 'required|array|min:1',
            'product_id.*'      => 'required|exists:products,id',
            'qty'               => 'required|array',
            'qty.*'             => 'required|numeric|min:1',
            'rate'              => 'required|array',
            'rate.*'            => 'required|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0',
            'vat'               => 'nullable|numeric|min:0',
            'paid_amount'       => 'nullable|numeric|min:0',
            'serial_json'       => 'nullable|array',
            'serial_json.*'     => 'nullable',
        ]);
        DB::beginTransaction();
        try {
            $companyId = auth()->user()->company_id;
            $branchId  = auth()->user()->branch_id;
            $userId    = auth()->id();
            $receipt = Receipt::create([
                'receipt_no'   => $this->generateReceiptNo(),
                'po_no'        => $this->generateReceiptNo(),
                'type'         => 'Purchase-Order',
                'company_id'   => $companyId,
                'branch_id'    => $branchId,
                'customer_company_id' => $request->customer_company_id,
                'party_id'     => $request->party_id,
                'receipt_date' => $request->receipt_date,
                'remarks'      => $request->remarks,
                'discount'     => $request->discount ?? 0,
                'vat'          => $request->vat ?? 0,
                'paid_amount'  => $request->paid_amount ?? 0,
                'status'       => 'Draft',
                'is_receive'   => false,
                'created_by'   => $userId,
            ]);
            $totalQty = 0;
            $subTotal = 0;
            foreach ($request->product_id as $key => $productId) {
                $qty  = (float) $request->qty[$key];
                $rate = (float) $request->rate[$key];
                $amount = $qty * $rate;
                $product = Product::findOrFail($productId);
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

                if (count($serials) > 0 && count($serials) != $qty) {

                    throw new \Exception(
                        "Serial quantity does not match Qty for product: {$product->name}. " .
                            "Qty: {$qty}, Serial: " . count($serials)
                    );
                }
                if (count($serials) !== count(array_unique($serials))) {

                    throw new \Exception(
                        "Duplicate serial number found for product: {$product->name}"
                    );
                }
                foreach ($serials as $serial) {

                    $exists = SerialNumber::where('company_id', $companyId)
                        ->where('serial_no', $serial)
                        ->exists();

                    if ($exists) {

                        throw new \Exception(
                            "Duplicate Serial Number: {$serial}"
                        );
                    }
                }
                $receiptItem = ReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'product_id' => $productId,
                    'qty'        => $qty,
                    'rate'       => $rate,
                    'amount'     => $amount,
                ]);
                foreach ($serials as $serial) {
                    SerialNumber::create([
                        'company_id'      => $companyId,
                        'branch_id'       => $branchId,
                        'product_id'      => $productId,
                        'receipt_id'      => $receipt->id,
                        'receipt_item_id' => $receiptItem->id,
                        'serial_no'       => $serial,
                        'status'          => 'Pending',
                        'created_by'      => $userId,
                    ]);
                }
                $totalQty += $qty;
                $subTotal += $amount;
            }
            $discount   = (float) ($request->discount ?? 0);
            $vatPercent = (float) ($request->vat ?? 0);
            $afterDiscount = $subTotal - $discount;
            if ($afterDiscount < 0) {
                $afterDiscount = 0;
            }
            $vatAmount = ($afterDiscount * $vatPercent) / 100;
            $grandTotal = $afterDiscount + $vatAmount;
            $paid = (float) ($request->paid_amount ?? 0);
            $due = $grandTotal - $paid;
            if ($due < 0) {
                $due = 0;
            }
            if ($paid <= 0) {
                $paymentStatus = 'Pending';
            } elseif ($due <= 0) {
                $paymentStatus = 'Paid';
            } else {
                $paymentStatus = 'Partial';
            }
            $receipt->update([
                'total_qty'      => $totalQty,
                'sub_total'      => $subTotal,
                'total_amount'   => $grandTotal,
                'vat'            => $vatPercent,
                'discount'       => $discount,
                'paid_amount'    => $paid,
                'due_amount'     => $due,
                'payment_status' => $paymentStatus,
            ]);
            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Purchase saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show Purchase
     */
    public function show(Receipt $purchase)
    {
        $purchase->load([
            'supplier',
            'company',
            'branch',
            'items.product',
            'items.serialNumbers',
            'customerCompany'
        ]);

        return view('BackEnd.Purchase.show', compact('purchase'));
    }

    /**
     * Edit Purchase
     */
    public function edit(Receipt $purchase)
    {
        if ($purchase->status == 'Cancelled') {
            return redirect()->route('purchase.index')->with('error', 'Cancelled purchase cannot be edited.');
        }
        $purchase->load('items.product', 'items.serialNumbers', 'party');
        $suppliers = Party::whereIn('type', ['Supplier', 'Both'])->where('status', 'Active')->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', auth()->id());
        })->orderBy('name')->get();
        $products = Product::where('status', 'Active')->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            });
        })->orderBy('name')->get();
        $customerCompanies = CustomerCompany::where('status', 'Supplier')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        return view('BackEnd.Purchase.edit', compact('purchase', 'suppliers', 'products', 'customerCompanies'));
    }

    /**
     * Update Purchase
     */
    public function update(Request $request, Receipt $purchase)
    {
        $request->validate([
            'receipt_date'      => 'required|date',
            'customer_company_id' => 'required|exists:customer_companies,id',
            'party_id'          => 'required|exists:parties,id',
            'product_id'        => 'required|array|min:1',
            'product_id.*'      => 'required|exists:products,id',
            'qty'               => 'required|array',
            'qty.*'             => 'required|numeric|min:1',
            'rate'              => 'required|array',
            'rate.*'            => 'required|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0',
            'vat'               => 'nullable|numeric|min:0',
            'paid_amount'       => 'nullable|numeric|min:0',
            'serial_json'       => 'nullable|array',
            'serial_json.*'     => 'nullable',
        ]);
        if ($purchase->status == 'Cancelled') {
            return back()->with('error', 'Cancelled purchase cannot be updated.');
        }
        DB::beginTransaction();
        try {
            $companyId = auth()->user()->company_id;
            $branchId  = auth()->user()->branch_id;
            $userId    = auth()->id();
            $wasReceived = (bool) $purchase->is_receive;
            if ($wasReceived) {
                foreach ($purchase->items as $oldItem) {
                    Product::where('id', $oldItem->product_id)->decrement('current_stock', $oldItem->qty);
                }
            }
            StockTransaction::where('receipt_id', $purchase->id)->where('transaction_type', 'Purchase')->delete();
            SerialNumber::where('receipt_id', $purchase->id)->delete();
            $purchase->items()->delete();
            $purchase->update([
                'customer_company_id' => $request->customer_company_id,
                'party_id'     => $request->party_id,
                'receipt_date' => $request->receipt_date,
                'remarks'      => $request->remarks,
                'discount'     => $request->discount ?? 0,
                'vat'          => $request->vat ?? 0,
                'paid_amount'  => $request->paid_amount ?? 0,
                'updated_by'   => $userId,
            ]);
            $totalQty = 0;
            $subTotal = 0;
            foreach ($request->product_id as $key => $productId) {
                $product = Product::findOrFail(
                    $productId
                );
                $qty = (float) $request->qty[$key];
                $rate = (float) $request->rate[$key];
                $amount = $qty * $rate;
                $receiptItem = ReceiptItem::create([
                    'receipt_id' => $purchase->id,
                    'product_id' => $productId,
                    'qty'        => $qty,
                    'rate'       => $rate,
                    'amount'     => $amount,
                ]);
                $totalQty += $qty;
                $subTotal += $amount;
                $serialJson = $request->serial_json[$key] ?? '[]';
                $serials = json_decode($serialJson, true);

                if (!is_array($serials)) {
                    $serials = [];
                }
                $serials = collect($serials)
                    ->map(function ($serial) {
                        return strtoupper(
                            trim(
                                (string) $serial
                            )
                        );
                    })
                    ->filter(function ($serial) {
                        return $serial !== '';
                    })->values()->toArray();
                if (count($serials) > 0 && count($serials) != $qty) {
                    throw new \Exception(
                        "Serial quantity does not match Qty for product: " . $product->name . ". Qty: " . $qty . ", Serial: " . count($serials)
                    );
                }
                if (count($serials) !== count(array_unique($serials))) {
                    throw new \Exception(
                        "Duplicate serial found for product: " . $product->name
                    );
                }
                foreach ($serials as $serial) {
                    $exists = SerialNumber::where('company_id', $companyId)->where('serial_no', $serial)->exists();
                    if ($exists) {
                        throw new \Exception("Duplicate Serial Number: " . $serial);
                    }
                }
                foreach ($serials as $serial) {
                    SerialNumber::create([
                        'company_id'      => $companyId,
                        'branch_id'       => $branchId,
                        'product_id'      => $productId,
                        'receipt_id'      => $purchase->id,
                        'receipt_item_id' => $receiptItem->id,
                        'serial_no'       => $serial,
                        'status'          => $wasReceived ? 'Available' : 'Pending',
                        'receive_date'    => $wasReceived ? today() : null,
                        'created_by'      => $userId,
                    ]);
                }
                if ($wasReceived) {
                    $product->increment('current_stock', $qty);
                    $product->update([
                        'purchase_price' => $rate
                    ]);
                    $product->refresh();
                    StockTransaction::create([
                        'company_id' => $purchase->company_id,
                        'branch_id' => $purchase->branch_id,
                        'product_id' => $product->id,
                        'receipt_id' => $purchase->id,
                        'transaction_type' => 'Purchase',
                        'stock_in' => $qty,
                        'stock_out' => 0,
                        'balance' => $product->current_stock,
                        'transaction_date' => $purchase->receipt_date,
                        'remarks' => 'Purchase Update',
                        'created_by' => $userId,
                    ]);
                }
            }
            $discount = (float) ($request->discount ?? 0);
            $vatPercent = (float) ($request->vat ?? 0);
            $afterDiscount = $subTotal - $discount;
            if ($afterDiscount < 0) {
                $afterDiscount = 0;
            }
            $vatAmount = ($afterDiscount * $vatPercent) / 100;
            $grand = $afterDiscount + $vatAmount;
            $paid = (float) ($request->paid_amount ?? 0);
            $due = $grand - $paid;
            if ($due < 0) {
                $due = 0;
            }
            if ($paid <= 0) {
                $paymentStatus = 'Pending';
            } elseif ($due <= 0) {
                $paymentStatus = 'Paid';
            } else {
                $paymentStatus = 'Partial';
            }
            $purchase->update([
                'total_qty' => $totalQty,
                'sub_total' => $subTotal,
                'vat' => $vatPercent,
                'discount' => $discount,
                'total_amount' => $grand,
                'paid_amount' => $paid,
                'due_amount' => $due,
                'payment_status' => $paymentStatus,
            ]);
            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Purchase Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete Purchase
     */
    public function cancel(Receipt $purchase)
    {
        if ($purchase->status == 'Cancelled') {
            return back()->with('error', 'Purchase already cancelled.');
        }
        DB::beginTransaction();
        try {
            if ($purchase->is_receive) {
                foreach ($purchase->items as $item) {
                    Product::where('id', $item->product_id)->decrement('current_stock', $item->qty);
                }
                // SerialNumber::where('receipt_id', $purchase->id)
                //     ->update([
                //         'status' => 'Cancelled'
                //     ]);
                StockTransaction::where('receipt_id', $purchase->id)->where('transaction_type', 'Purchase')->delete();
            }
            $purchase->update([
                'status' => 'Cancelled',
                'updated_by' => auth()->id(),
            ]);
            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Purchase cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function supplierCompanyParties(CustomerCompany $customerCompany)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super-Admin')) {

            if ($customerCompany->created_by != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to access this supplier company.'
                ], 403);
            }
        }

        $parties = Party::where('customer_company_id', $customerCompany->id)
            ->whereIn('type', ['Supplier', 'Both'])
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->orderBy('name')
            ->get([
                'id',
                'party_id',
                'name',
                'designation',
                'phone',
                'email',
                'address',
                'status',
            ]);

        return response()->json([
            'success' => true,
            'parties' => $parties,
        ]);
    }
}
