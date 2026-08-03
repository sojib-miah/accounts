<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\SerialNumber;
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
        return view('BackEnd.Purchase.create', compact('receiptNo', 'suppliers', 'products'));
    }

    /**
     * Save Purchase
     */
    public function store(Request $request)
    {
        $request->validate([
            'receipt_date' => 'required|date',
            'party_id'     => 'required|exists:parties,id',
            'product_id'   => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'qty'          => 'required|array',
            'qty.*'        => 'required|numeric|min:1',
            'rate'         => 'required|array',
            'rate.*'       => 'required|numeric|min:0',
            'discount'     => 'nullable|numeric|min:0',
            'vat'          => 'nullable|numeric|min:0',
            'paid_amount'  => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $receipt = Receipt::create([
                'receipt_no'      => $this->generateReceiptNo(),
                'po_no'      => $this->generateReceiptNo(),
                'type'            => 'Purchase-Order',
                'company_id'      => auth()->user()->company_id,
                'branch_id'       => auth()->user()->branch_id,
                'party_id'        => $request->party_id,
                'receipt_date'    => $request->receipt_date,
                'remarks'         => $request->remarks,
                'discount'        => $request->discount ?? 0,
                'vat'             => $request->vat ?? 0,
                'paid_amount'     => $request->paid_amount ?? 0,
                'status'          => 'Draft',
                'is_receive' => false,
                'created_by'      => auth()->id(),
            ]);

            $totalQty = 0;
            $subTotal = 0;
            foreach ($request->product_id as $key => $productId) {
                $qty = $request->qty[$key];
                $rate = $request->rate[$key];
                $amount = $qty * $rate;
                $receiptItem = ReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'product_id' => $productId,
                    'qty'        => $qty,
                    'rate'       => $rate,
                    'amount'     => $amount,
                ]);
                // Product::where('id', $productId)->increment('current_stock', $qty);
                // Product::where('id', $productId)->update(['purchase_price' => $rate]);
                $totalQty += $qty;
                $subTotal += $amount;

                $serials = json_decode($request->serial_json[$key], true);
                if (count($serials) != $qty) {
                    throw new \Exception(
                        "Serial quantity does not match Qty."
                    );
                }
                foreach ($serials as $serial) {
                    if (
                        SerialNumber::where('serial_no', trim($serial))->exists()
                    ) {
                        throw new \Exception(
                            "Duplicate Serial : " . $serial
                        );
                    }
                    SerialNumber::create([
                        'company_id'      => auth()->user()->company_id,
                        'branch_id'       => auth()->user()->branch_id,
                        'product_id'      => $productId,
                        'receipt_id'      => $receipt->id,
                        'receipt_item_id' => $receiptItem->id,
                        'serial_no'       => trim($serial),
                        'status'          => 'Pending',
                        'created_by'      => auth()->id(),
                    ]);
                }
            }
            $discount = $request->discount ?? 0;
            $vatPercent = $request->vat ?? 0;
            $vatAmount = (($subTotal - $discount) * $vatPercent) / 100;
            $grandTotal = ($subTotal - $discount) + $vatAmount;
            $paid = $request->paid_amount ?? 0;
            $due = $grandTotal - $paid;
            if ($paid <= 0) {
                $paymentStatus = 'Pending';
            } elseif ($due <= 0) {
                $paymentStatus = 'Paid';
                $due = 0;
            } else {
                $paymentStatus = 'Partial';
            }
            $receipt->update([
                'total_qty'       => $totalQty,
                'sub_total'       => $subTotal,
                'total_amount'    => $grandTotal,
                'vat'             => $vatPercent,
                'discount'        => $discount,
                'paid_amount'     => $paid,
                'due_amount'      => $due,
                'payment_status'  => $paymentStatus,
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
        $purchase->load(['supplier', 'items.product', 'company', 'branch']);
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
        $purchase->load('items.product');
        $suppliers = Party::whereIn('type', ['Supplier', 'Both'])->where('status', 'Active')->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', auth()->id());
        })->orderBy('name')->get();
        $products = Product::where('status', 'Active')->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            });
        })->orderBy('name')->get();
        return view('BackEnd.Purchase.edit', compact('purchase', 'suppliers', 'products'));
    }

    /**
     * Update Purchase
     */
    public function update(Request $request, Receipt $purchase)
    {
        $request->validate([
            'receipt_date' => 'required|date',
            'party_id'     => 'required|exists:parties,id',
            'product_id'   => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'qty.*'        => 'required|numeric|min:1',
            'rate.*'       => 'required|numeric|min:0',
        ]);
        if ($purchase->status == 'Cancelled') {
            return back()->with('error', 'Cancelled purchase cannot be updated.');
        }
        DB::beginTransaction();
        try {
            foreach ($purchase->items as $item) {
                Product::where('id', $item->product_id)->decrement('current_stock', $item->qty);
            }
            $purchase->items()->delete();
            $purchase->update([
                'party_id'      => $request->party_id,
                'receipt_date'  => $request->receipt_date,
                'remarks'       => $request->remarks,
                'discount'      => $request->discount ?? 0,
                'vat'           => $request->vat ?? 0,
                'paid_amount'   => $request->paid_amount ?? 0,
                'updated_by'    => auth()->id()
            ]);
            $totalQty = 0;
            $subTotal = 0;
            foreach ($request->product_id as $key => $productId) {
                $qty = $request->qty[$key];
                $rate = $request->rate[$key];
                $amount = $qty * $rate;
                ReceiptItem::create([
                    'receipt_id' => $purchase->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'rate' => $rate,
                    'amount' => $amount,
                ]);
                // Product::where('id', $productId)->increment('current_stock', $qty);
                // Product::where('id', $productId)->update(['purchase_price' => $rate]);
                $totalQty += $qty;
                $subTotal += $amount;
            }
            $discount = $request->discount ?? 0;
            $vatPercent = $request->vat ?? 0;
            $vatAmount = (($subTotal - $discount) * $vatPercent) / 100;
            $grand = ($subTotal - $discount) + $vatAmount;
            $paid = $request->paid_amount ?? 0;
            $due = $grand - $paid;
            if ($paid <= 0) {
                $status = 'Pending';
            } elseif ($due <= 0) {
                $status = 'Paid';
                $due = 0;
            } else {
                $status = 'Partial';
            }

            $purchase->update([
                'total_qty'       => $totalQty,
                'sub_total'       => $subTotal,
                'vat'             => $vatPercent,
                'discount'        => $discount,
                'total_amount'    => $grand,
                'paid_amount'     => $paid,
                'due_amount'      => $due,
                'payment_status'  => $status,
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
            // foreach ($purchase->items as $item) {
            //     Product::where('id', $item->product_id)->decrement('current_stock', $item->qty);
            // }
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
}
