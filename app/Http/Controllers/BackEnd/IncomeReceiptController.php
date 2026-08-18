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
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\ReceiptPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PackageHelper;

class IncomeReceiptController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Receipt::with(['party', 'branch', 'creator', 'items', 'customerCompany'])->where('is_invoice', true)->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
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
        return view('BackEnd.IncomeReceipt.index', compact('receipts'));
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
        $parties = Party::where('type', 'Income')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();

        $categories = Category::where('type', 'Income')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        return view('BackEnd.IncomeReceipt.income_create', compact('branches', 'parties', 'categories', 'companies'));
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
            'type' => 'required|in:Income,Expense,Challan',
            'company_id' => 'nullable',
            'branch_id' => 'required|exists:branches,id',
            'party_id' => 'required|exists:parties,id',
            'receipt_date' => 'required|date',
            'items' => 'required',
        ]);
        if (!Auth::user()->hasRole('Super-Admin')) {
            $current = Receipt::where('company_id', Auth::user()->company_id)
                ->where('type', 'Income')
                ->count();
            if ($message = PackageHelper::checkLimit('income_limit', $current)) {
                return back()->with('error', $message);
            }
        }
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
            $discount = (float) ($request->discount ?? 0);
            $vatPercent = (float) ($request->vat ?? 0);
            $afterDiscount = $subTotal - $discount;
            $vatAmount = ($afterDiscount * $vatPercent) / 100;
            $grandTotal = $afterDiscount + $vatAmount;
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
                'vat' => $vatPercent,
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
            return redirect()->route('income.receipt.show', $receipt->id)->with('success', 'Invoice Created Successfully.');
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
            'customerCompany',
            'party',
            'creator',
            'items.category',
            'items.product',
            'items.accountHead',
            'payments.account',
            'payments.paymentType',
            'payments.user',
        ]);
        $paymentTypes = PaymentType::where('status', 'Active')
            ->orderBy('name')
            ->get();

        $accounts = Account::where('status', 'Active')
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('company_id', Auth::user()->company_id)
                    ->where('branch_id', Auth::user()->branch_id);
            })
            ->orderBy('account_name')
            ->get();
        return view('BackEnd.IncomeReceipt.show', compact('receipt', 'paymentTypes', 'accounts'));
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
        $companies = Company::when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
            $q->where('id', Auth::user()->company_id);
        })->get();
        $branches = Branch::when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
            $q->where('created_by', Auth::id())
                ->orWhere('id', Auth::user()->branch_id);
        })->latest()->get();
        $parties = Party::where('type', 'Income')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();

        $categories = Category::where('type', 'Income')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
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
        return view('BackEnd.IncomeReceipt.edit', compact('receipt', 'branches', 'parties', 'categories', 'receiptItems', 'companies'));
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
            $discount = (float) ($request->discount ?? 0);
            $vatPercent = (float) ($request->vat ?? 0);
            $afterDiscount = $subTotal - $discount;
            $vatAmount = ($afterDiscount * $vatPercent) / 100;
            $grandTotal = $afterDiscount + $vatAmount;
            $receipt->update([
                'branch_id' => $request->branch_id,
                'party_id' => $request->party_id,
                'receipt_date' => $request->receipt_date,
                'remarks' => $request->remarks,
                'total_qty' => $totalQty,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'vat' => $vatPercent,
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
            return redirect()->route('income.receipt.show', $receipt->id)->with('success', 'Receipt Updated Successfully.');
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
            return redirect()->route('income.receipt.show', $receipt->id);
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

        $receipts = $receiptQuery
            ->latest()
            ->paginate(20, ['*'], 'receipt_page')
            ->withQueryString();

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


        $payments = $paymentQuery
            ->latest()
            ->paginate(20, ['*'], 'payment_page')
            ->withQueryString();
        $invoice = Receipt::where('party_id', $party->id)
            ->where('is_invoice', true);


        $summary = [
            'receipt_count' => (clone $invoice)->count(),
            'qty'           => (clone $invoice)->sum('total_qty'),
            'net'            => (clone $invoice)->sum('total_amount'),
            'paid'           => (clone $invoice)->sum('paid_amount'),
            'due'            => (clone $invoice)->sum('due_amount'),
        ];

        $paymentTypes = PaymentType::where('status', 'Active')
            ->orderBy('name')
            ->get();

        $accounts = Account::where('status', 'Active')
            ->where('current_balance', '>', 0)
            ->with('paymentType')
            ->when(
                !Auth::user()->hasRole('Super-Admin'),
                function ($query) {
                    $query->where('created_by', Auth::id());
                }
            )
            ->orderBy('account_name')
            ->get();


        return view(
            'BackEnd.IncomeReceipt.profile',
            compact(
                'party',
                'receipts',
                'payments',
                'summary',
                'paymentTypes',
                'accounts'
            )
        );
    }

    public function paymentStore(Request $request, Receipt $receipt)
    {
        $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'account_id'      => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:0.01',
            'payment_date'    => 'required|date',
            'note'            => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {

            $user = Auth::user();
            $userId = $user->id;
            $receipt = Receipt::where('id', $receipt->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($receipt->status !== 'Completed') {
                throw new \Exception(
                    'Payment can only be made for a completed receipt.'
                );
            }

            if ($receipt->payment_status === 'Paid') {
                throw new \Exception(
                    'This receipt has already been fully paid.'
                );
            }

            $amount = (float) $request->amount;

            $dueAmount = (float) $receipt->due_amount;

            if ($amount > $dueAmount) {
                throw new \Exception(
                    'Payment amount cannot be greater than due amount. ' .
                        'Remaining Due: ' . number_format($dueAmount, 2)
                );
            }

            $paymentType = PaymentType::where('id', $request->payment_type_id)
                ->where('status', 'Active')
                ->first();

            if (!$paymentType) {
                throw new \Exception(
                    'Selected payment type is inactive or invalid.'
                );
            }

            $account = Account::where('id', $request->account_id)
                ->where('status', 'Active')
                ->lockForUpdate()
                ->first();

            if (!$account) {
                throw new \Exception(
                    'Selected account is inactive or invalid.'
                );
            }

            if ((int) $account->payment_type_id !== (int) $paymentType->id) {

                throw new \Exception(
                    'Selected account does not belong to the selected payment type.'
                );
            }

            if (
                !$user->hasRole('Super-Admin') &&
                (int) $account->company_id !== (int) $user->company_id
            ) {
                throw new \Exception(
                    'You are not allowed to use this account.'
                );
            }

            if (
                !$user->hasRole('Super-Admin') &&
                (int) $account->branch_id !== (int) $user->branch_id
            ) {
                throw new \Exception(
                    'You are not allowed to use this account.'
                );
            }

            $isPurchase = $receipt->type === 'Purchase-Order';

            $isSales = $receipt->type === 'Sales-Order';

            if (!$isPurchase && !$isSales) {

                throw new \Exception(
                    'Payment is not supported for this receipt type.'
                );
            }

            if ($isPurchase) {

                $currentBalance = (float) $account->current_balance;

                if ($currentBalance < $amount) {

                    throw new \Exception(
                        'Insufficient account balance. ' .
                            'Available Balance: ' .
                            number_format($currentBalance, 2) .
                            ' TK, Required: ' .
                            number_format($amount, 2) .
                            ' TK.'
                    );
                }

                $newBalance = $currentBalance - $amount;

                $account->update([
                    'current_balance' => $newBalance,
                    'updated_by'     => $userId,
                ]);

                AccountTransaction::create([
                    'company_id'      => $account->company_id,
                    'account_id'      => $account->id,
                    'transaction_date' => $request->payment_date,
                    'voucher_no'      => $receipt->po_no ?? $receipt->receipt_no,
                    'transaction_type' => 'Purchase-Order',
                    'purpose'         => 'Purchase Payment - ' . $receipt->receipt_no,
                    'credit'          => 0,
                    'debit'           => $amount,
                    'balance'         => $newBalance,
                    'receipt_id'      => $receipt->id,
                    'created_by'      => $userId,
                ]);
            }

            if ($isSales) {

                $currentBalance = (float) $account->current_balance;

                $newBalance = $currentBalance + $amount;

                $account->update([
                    'current_balance' => $newBalance,
                    'updated_by'      => $userId,
                ]);

                AccountTransaction::create([
                    'company_id'       => $account->company_id,
                    'account_id'       => $account->id,
                    'transaction_date' => $request->payment_date,
                    'voucher_no'       => $receipt->so_no ?? $receipt->receipt_no,
                    'transaction_type' => 'Sales-Order',
                    'purpose'          => 'Sales Payment - ' . $receipt->receipt_no,
                    'credit'           => $amount,
                    'debit'            => 0,
                    'balance'          => $newBalance,
                    'receipt_id'       => $receipt->id,
                    'created_by'       => $userId,
                ]);
            }

            ReceiptPayment::create([
                'receipt_id'      => $receipt->id,
                'payment_type_id' => $paymentType->id,
                'account_id'      => $account->id,
                'payment_date'    => $request->payment_date,
                'amount'          => $amount,
                'note'            => $request->note,
                'created_by'      => $userId,
            ]);

            $newPaidAmount =
                (float) $receipt->paid_amount + $amount;

            $newDueAmount =
                (float) $receipt->total_amount - $newPaidAmount;

            if ($newDueAmount < 0) {
                $newDueAmount = 0;
            }

            if ($newDueAmount <= 0) {

                $paymentStatus = 'Paid';

                $newDueAmount = 0;
            } elseif ($newPaidAmount > 0) {

                $paymentStatus = 'Partial';
            } else {

                $paymentStatus = 'Pending';
            }

            $receipt->update([
                'paid_amount'    => $newPaidAmount,
                'due_amount'     => $newDueAmount,
                'payment_status' => $paymentStatus,
                'updated_by'     => $userId,
            ]);

            DB::commit();

            return back()->with(
                'success',
                'Payment successfully completed.'
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
