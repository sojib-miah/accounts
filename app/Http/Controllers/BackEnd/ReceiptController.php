<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountHead;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Party;
use App\Models\PaymentType;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\ReceiptPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function expenseIndex(Request $request)
    {
        $user = auth()->user();

        $query = Receipt::with(['party', 'branch', 'creator'])
            ->where('type', 'Expense')
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

        return view('BackEnd.Receipt.index', compact('receipts'));
    }

    public function expenseCreate()
    {
        $branches = Branch::when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();
        $parties = Party::where('type', 'Expense')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        $categories = Category::where('type', 'Expense')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        return view('BackEnd.Receipt.expense_create', compact('branches', 'parties', 'categories'));
    }

    private function generateReceiptNo()
    {
        $last = Receipt::orderByDesc('receipt_no')->first();

        return $last
            ? ((int) $last->receipt_no + 1)
            : 10001;
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
                'receipt_no' => $this->generateReceiptNo(),
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
            return redirect()->route('receipt.show', $receipt->id)->with('success', 'Receipt Created Successfully.');
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
        return view('BackEnd.Receipt.show', compact('receipt', 'paymentTypes'));
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

        $parties = Party::where('type', 'Expense')->where('status', 'Active')->get();

        $categories = Category::where('type', 'Expense')->where('status', 'Active')->get();

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

        return view(
            'BackEnd.Receipt.edit',
            compact(
                'receipt',
                'branches',
                'parties',
                'categories',
                'receiptItems'
            )
        );
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
            return redirect()->route('receipt.show', $receipt->id)->with('success', 'Receipt Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Receipt $receipt)
    {
        if ($receipt->payments()->exists()) {
            return redirect()->route('receipt.show', $receipt->id)->with('error', 'This receipt has payment history. It cannot be deleted.');
        }
        DB::beginTransaction();
        try {
            $receipt->items()->delete();
            $receipt->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Receipt Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
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
            return redirect()->route('receipt.show', $receipt->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function paymentStore(Request $request, Receipt $receipt)
    {
        $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'payment_date'    => 'required|date',
            'amount'          => 'required|numeric|min:0.01',
            'note'            => 'nullable|string',
        ]);
        if ($request->amount > $receipt->due_amount) {
            return back()->with('error', 'Payment amount cannot exceed the due amount.');
        }
        DB::beginTransaction();
        try {
            $account = Account::where('default_status', 'Default')->first();
            if (!$account) {
                DB::rollBack();
                return back()->with('error', 'No default account found for the selected payment type.');
            }
            ReceiptPayment::create([
                'receipt_id'      => $receipt->id,
                'payment_type_id' => $request->payment_type_id,
                'account_id'      => $account->id,
                'payment_date'    => $request->payment_date,
                'amount'          => $request->amount,
                'note'            => $request->note,
                'created_by'      => Auth::id(),
            ]);
            $receipt->paid_amount += $request->amount;
            $receipt->due_amount = $receipt->total_amount - $receipt->paid_amount;
            if ($receipt->due_amount <= 0) {
                $receipt->due_amount = 0;
                $receipt->payment_status = 'Paid';
            } elseif ($receipt->paid_amount > 0) {
                $receipt->payment_status = 'Partial';
            } else {
                $receipt->payment_status = 'Pending';
            }
            $receipt->save();
            if ($receipt->type == 'Income') {
                $account->current_balance += $request->amount;
            } else {
                $account->current_balance -= $request->amount;
            }
            $account->save();
            AccountTransaction::create([
                'account_id'       => $account->id,
                'transaction_date' => $request->payment_date,
                'voucher_no'       => $receipt->receipt_no,
                'transaction_type' => $receipt->type,
                'purpose'          => $receipt->type == 'Income' ? 'Income Receipt Payment' : 'Expense Bill Payment',
                'credit'           => $receipt->type == 'Income' ? $request->amount : 0,
                'debit'            => $receipt->type == 'Expense' ? $request->amount : 0,
                'balance'          => $account->current_balance,
                'receipt_id'       => $receipt->id,
                'created_by'       => Auth::id(),
            ]);
            DB::commit();
            return redirect()->route($receipt->type == 'Income' ? 'income.receipt.index' : 'receipt.expense.index')->with('success', 'Payment completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function paymentHistory(Receipt $receipt)
    {
        $payments = $receipt->payments()->with(['paymentType', 'account', 'user'])->latest()->get();
        return response()->json($payments);
    }

    public function print(Receipt $receipt)
    {
        $receipt->load(['company', 'branch', 'party', 'creator', 'items.category', 'items.accountHead', 'payments.paymentType', 'payments.account', 'payments.user',]);
        return view('BackEnd.Receipt.print', compact('receipt'));
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
        $pdf = Pdf::loadView('BackEnd.Receipt.pdf', compact('receipt'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream(
            $receipt->receipt_no . '.pdf'
        );
    }

    public function branchInfo(Branch $branch)
    {
        $branch->load('company');
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $branch->id,
                'name' => $branch->name,
                'company_name' => optional($branch->company)->name,
                'phone' => $branch->phone_one,
                'email' => $branch->email,
                'address' => $branch->address,
            ]
        ]);
    }

    public function partyInfo(Party $party)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $party->id,
                'name' => $party->name,
                'designation' => $party->designation,
                'phone' => $party->phone,
                'email' => $party->email,
                'address' => $party->address,
                'type' => $party->type,
            ]
        ]);
    }

    public function accountHeads(Category $category)
    {
        $heads = AccountHead::where('category_id', $category->id)->where('status', 'Active')->orderBy('name')->get(['id', 'name']);
        return response()->json(['success' => true, 'data' => $heads]);
    }

    public function profile(Request $request, Party $party)
    {
        $receiptQuery = Receipt::with('creator')->where('party_id', $party->id)->where('type', 'Expense');

        // Receipt Search
        if ($request->filled('search')) {
            $search = $request->search;
            $receiptQuery->where(function ($q) use ($search) {
                $q->where('receipt_no', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Receipt Status Filter
        if ($request->filled('status')) {
            $receiptQuery->where(
                'payment_status',
                $request->status
            );
        }

        $receipts = $receiptQuery->latest()->paginate(20, ['*'], 'receipt_page')->withQueryString();

        $paymentQuery = ReceiptPayment::with([
            'receipt',
            'paymentType'
        ])
            ->whereHas('receipt', function ($q) use ($party) {
                $q->where('party_id', $party->id);
            });

        // Payment Search
        if ($request->filled('payment_search')) {
            $search = $request->payment_search;
            $paymentQuery->where(function ($q) use ($search) {
                $q->where('amount', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhereHas('receipt', function ($r) use ($search) {
                        $r->where('receipt_no', 'like', "%{$search}%");
                    });
            });
        }
        $payments = $paymentQuery->latest()->paginate(20, ['*'], 'payment_page')->withQueryString();
        $paymentTypes = PaymentType::where('status', 'Active')->orderBy('name')->get();
        $summary = [
            'receipt_count' => Receipt::where('party_id', $party->id)->where('type', 'Expense')->count(),
            'qty' => Receipt::where('party_id', $party->id)->where('type', 'Expense')->sum('total_qty'),
            'net' => Receipt::where('party_id', $party->id)->where('type', 'Expense')->sum('total_amount'),
            'paid' => Receipt::where('party_id', $party->id)->where('type', 'Expense')->sum('paid_amount'),
            'due' => Receipt::where('party_id', $party->id)->where('type', 'Expense')->sum('due_amount'),
        ];

        return view(
            'BackEnd.Receipt.profile',
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
                    'account_id'       => $account->id,
                    'receipt_id'       => $receipt->id,
                    'voucher_no'       => $receipt->receipt_no,
                    'transaction_date' => $request->payment_date,
                    'transaction_type' => 'Expense',
                    'purpose'          => 'Party Due Payment',
                    'credit'           => 0,
                    'debit'            => $pay,
                    'balance'          => $account->current_balance,
                    'created_by'       => Auth::id(),
                ]);
                $amount -= $pay;
            }
            DB::commit();
            return redirect()->route('party.profile', $party->id)->with('success', 'Due payment completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
