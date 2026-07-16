<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Party;
use App\Models\PaymentType;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChallanController extends Controller
{
    public function index(Request $request)
    {
        $query = Receipt::with([
            'party',
            'branch',
            'creator'
        ])->where('type', 'Income');
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
        return view('BackEnd.Challan.index', compact('receipts'));
    }

    public function createChallan()
    {
        $branches = Branch::latest()->get();
        $parties = Party::where('type', 'Income')->where('status', 'Active')->get();

        $categories = Category::where('type', 'Income')->where('status', 'Active')->get();
        return view('BackEnd.Challan.challan_create', compact('branches', 'parties', 'categories'));
    }

    private function generateReceiptNo($type)
    {
        $prefix = $type == 'Income' ? 'INC' : 'EXP';
        $last = Receipt::where('type', $type)->latest('id')->first();
        $number = $last ? ((int) substr($last->receipt_no, 3)) + 1 : 10001;
        return $prefix . $number;
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Income,Expense',
            // 'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'party_id' => 'required|exists:parties,id',
            'receipt_date' => 'required|date',
            'items' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $items = json_decode($request->items, true);
            if (!$items || count($items) == 0) {
                return back()->withInput()->with('error', 'Please add at least one item.');
            }
            $totalQty = 0;
            $subTotal = 0;
            foreach ($items as $item) {
                $qty = $item['qty'] ?? 1;
                $amount = $item['amount'];
                $totalQty += $qty;
                $subTotal += $amount;
            }
            $discount = $request->discount ?? 0;
            $vat = $request->vat ?? 0;
            $grandTotal = $subTotal + $vat - $discount;
            $receipt = Receipt::create([
                'receipt_no' => $this->generateReceiptNo($request->type),
                'type' => $request->type,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'party_id' => $request->party_id,
                'receipt_date' => $request->receipt_date,
                'remarks' => $request->remarks,
                'total_qty' => $totalQty,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'vat' => $vat,
                'total_amount' => $grandTotal,
                'paid_amount' => 0,
                'due_amount' => $grandTotal,
                'payment_status' => 'Pending',
                'status' => 'Completed',
                'created_by' => Auth::id(),
            ]);
            foreach ($items as $item) {
                ReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'category_id' => $item['category_id'],
                    'account_head_id' => $item['account_head_id'],
                    'qty' => $item['qty'] ?? 1,
                    'rate' => $item['rate'] ?? $item['amount'],
                    'amount' => $item['amount'],
                    'details' => $item['details'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('challan.show', $receipt->id)->with('success', 'Challan Created Successfully.');
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
            'items.category',
            'items.accountHead',
            'payments.account',
            'payments.paymentType',
            'payments.user',
        ]);
        $paymentTypes = PaymentType::where('status', 'Active')->get();
        return view('BackEnd.Challan.show', compact('receipt', 'paymentTypes'));
    }

    public function edit(Receipt $receipt)
    {
        $receipt->load([
            'branch',
            'party',
            'creator',
            'items.category',
            'items.accountHead'
        ]);
        $branches = Branch::latest()->get();
        $parties = Party::where('type', 'Income')->where('status', 'Active')->get();
        $categories = Category::where('type', 'Income')->where('status', 'Active')->get();
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
        return view('BackEnd.Challan.edit', compact('receipt', 'branches', 'parties', 'categories', 'receiptItems'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'party_id' => 'required|exists:parties,id',
            'receipt_date' => 'required|date',
            'items' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $items = json_decode($request->items, true);
            if (!$items || count($items) == 0) {
                return back()->withInput()->with('error', 'Please add at least one item.');
            }
            $totalQty = 0;
            $subTotal = 0;
            foreach ($items as $item) {
                $qty = $item['qty'] ?? 1;
                $amount = $item['amount'];
                $totalQty += $qty;
                $subTotal += $amount;
            }
            $discount = $request->discount ?? 0;
            $vat = $request->vat ?? 0;
            $grandTotal = $subTotal + $vat - $discount;
            $receipt->update([
                'branch_id' => $request->branch_id,
                'party_id' => $request->party_id,
                'receipt_date' => $request->receipt_date,
                'remarks' => $request->remarks,
                'total_qty' => $totalQty,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'vat' => $vat,
                'total_amount' => $grandTotal,
                'due_amount' => $grandTotal - $receipt->paid_amount,
                'updated_by' => auth()->id(),
            ]);
            $receipt->items()->delete();
            foreach ($items as $item) {
                ReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'category_id' => $item['category_id'],
                    'account_head_id' => $item['account_head_id'],
                    'qty' => $item['qty'] ?? 1,
                    'rate' => $item['rate'] ?? 0,
                    'amount' => $item['amount'],
                    'details' => $item['details'] ?? null,
                ]);
            }
            if (
                $receipt->paid_amount <= 0
            ) {
                $receipt->payment_status = 'Pending';
            } elseif (
                $receipt->paid_amount < $receipt->total_amount
            ) {
                $receipt->payment_status = 'Partial';
            } else {
                $receipt->payment_status = 'Paid';
            }
            $receipt->save();
            DB::commit();
            return redirect()->route('challan.show', $receipt->id)->with('success', 'Challan Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
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
            return redirect()->route('challan.show', $receipt->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function print(Receipt $receipt)
    {
        $receipt->load(['company', 'branch', 'party', 'creator', 'items.category', 'items.accountHead', 'payments.paymentType', 'payments.account', 'payments.user',]);
        return view('BackEnd.Challan.print', compact('receipt'));
    }

    public function pdf(Receipt $receipt)
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
        $pdf = Pdf::loadView('BackEnd.Challan.pdf', compact('receipt'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream(
            $receipt->receipt_no . '.pdf'
        );
    }
}
