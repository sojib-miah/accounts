<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountHead;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\CustomerCompany;
use App\Models\Party;
use App\Models\PaymentType;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\ReceiptPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PackageHelper;

class ReceiptController extends Controller
{
    public function expenseIndex(Request $request)
    {
        $user = auth()->user();

        $query = Receipt::with(['party', 'branch', 'creator', 'items'])
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
        $user = Auth::user();

        $companies = Company::when(
            !$user->hasRole('Super-Admin'),
            function ($q) use ($user) {
                $q->where('id', $user->company_id);
            }
        )
            ->orderBy('name')
            ->get();

        $branches = Branch::when(
            !$user->hasRole('Super-Admin'),
            function ($q) use ($user) {
                $q->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                        ->orWhere('id', $user->branch_id);
                });
            }
        )
            ->orderBy('name')
            ->get();

        $parties = Party::where('type', 'Expense')
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->orderBy('name')
            ->get();
        $customerCompanies = CustomerCompany::where('status', 'Expense')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();

        $categories = Category::where('type', 'Expense')
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->orderBy('name')
            ->get();

        return view('BackEnd.Receipt.expense_create', compact('companies', 'branches', 'parties', 'categories', 'customerCompanies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Income,Expense,Challan',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'customer_company_id' => 'required|exists:customer_companies,id',
            'party_id' => 'required|exists:parties,id',
            'receipt_date' => 'required|date',
            'items' => 'required|string',
            'discount' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
        ]);
        $user = Auth::user();
        if (!$user->hasRole('Super-Admin')) {
            if ((int) $request->company_id !== (int) $user->company_id) {
                return back()->withInput()->with('error', 'You are not allowed to create expense for this company.');
            }
        }
        if (!$user->hasRole('Super-Admin')) {
            $current = Receipt::where('company_id', $user->company_id)->where('type', 'Expense')->count();
            if ($message = PackageHelper::checkLimit('expense_limit', $current)) {
                return back()->withInput()->with('error', $message);
            }
        }
        DB::beginTransaction();
        try {
            $items = json_decode($request->items, true);
            if (!is_array($items) || count($items) === 0) {
                throw new \Exception('Please add at least one expense item.');
            }
            $totalQty = 0;
            $subTotal = 0;
            foreach ($items as $index => $item) {
                $categoryId = $item['category_id'] ?? null;
                $accountHeadId = $item['account_head_id'] ?? null;
                $qty = (float) ($item['qty'] ?? 1);
                $rate = (float) ($item['rate'] ?? 0);
                if (!$categoryId) {
                    throw new \Exception('Category is required for item #' . ($index + 1));
                }
                if (!$accountHeadId) {
                    throw new \Exception('Expense is required for item #' . ($index + 1));
                }
                if ($qty <= 0) {
                    throw new \Exception('Quantity must be greater than 0 for item #' . ($index + 1));
                }
                if ($rate < 0) {
                    throw new \Exception('Rate cannot be negative for item #' . ($index + 1));
                }
                $amount = $qty * $rate;
                $totalQty += $qty;
                $subTotal += $amount;
            }
            $discount = (float) ($request->discount ?? 0);
            if ($discount > $subTotal) {
                throw new \Exception('Discount cannot be greater than subtotal.');
            }
            $vatPercent = (float) ($request->vat ?? 0);
            if ($vatPercent < 0) {
                throw new \Exception('VAT cannot be negative.');
            }
            $afterDiscount = $subTotal - $discount;
            $vatAmount = ($afterDiscount * $vatPercent) / 100;
            $grandTotal = $afterDiscount + $vatAmount;
            $receipt = Receipt::create([
                'receipt_no' => random_int(100000, 999999),
                'type' => $request->type,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'customer_company_id' => $request->customer_company_id,
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
                'created_by' => $user->id,
            ]);

            foreach ($items as $item) {

                $qty = (float) (
                    $item['qty'] ?? 1
                );

                $rate = (float) (
                    $item['rate'] ?? 0
                );

                $amount =
                    $qty * $rate;

                ReceiptItem::create([

                    'receipt_id' =>
                    $receipt->id,

                    'category_id' =>
                    $item['category_id'],

                    'account_head_id' =>
                    $item['account_head_id'],

                    'qty' =>
                    $qty,

                    'rate' =>
                    $rate,

                    'amount' =>
                    $amount,

                    'details' =>
                    $item['details'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route(
                    'receipt.show',
                    $receipt->id
                )
                ->with(
                    'success',
                    'Expense Receipt Created Successfully.'
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

    public function show(Receipt $receipt)
    {
        $user = Auth::user();
        $receipt->load([
            'company',
            'branch',
            'customerCompany',
            'party',
            'creator',
            'items.category',
            'items.accountHead',
            'payments.account',
            'payments.paymentType',
            'payments.user',
        ]);
        $paymentTypes = PaymentType::where('status', 'Active')->get();
        $accounts = Account::where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->orderBy('account_name')
            ->get();
        return view('BackEnd.Receipt.show', compact('receipt', 'paymentTypes', 'accounts'));
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
        $parties = Party::where('type', 'Expense')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        $customerCompanies = CustomerCompany::where('status', 'Expense')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        $categories = Category::where('type', 'Expense')->where('status', 'Active')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
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

        return view(
            'BackEnd.Receipt.edit',
            compact(
                'receipt',
                'branches',
                'parties',
                'categories',
                'receiptItems',
                'companies',
                'customerCompanies'
            )
        );
    }

    public function update(Request $request, Receipt $receipt)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'customer_company_id' => 'required|exists:customer_companies,id',
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
                'customer_company_id' => $request->customer_company_id,
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
            return redirect()->route('receipt.show', $receipt->id)->with('success', 'Receipt Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Receipt $receipt)
    {
        if ($receipt->payments()->exists()) {
            return redirect()->route('receipt.show', $receipt->id)
                ->with('error', 'This receipt has payment history. It cannot be deleted.');
        }
        DB::beginTransaction();
        try {
            $type = $receipt->type;
            $receipt->items()->delete();
            $receipt->delete();
            DB::commit();
            if ($type == 'Expense') {
                return redirect()->route('receipt.expense.index')->with('success', 'Receipt Deleted Successfully.');
            } elseif ($type == 'Income') {
                return redirect()->route('income.receipt.index')->with('success', 'Receipt Deleted Successfully.');
            } elseif ($type == 'Challan') {
                return redirect()->route('challan.index')->with('success', 'Challan Deleted Successfully.');
            } elseif ($type == 'Sales-Order') {
                return redirect()->route('sales.order.index')->with('success', 'Sales Order Deleted Successfully.');
            }
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
            if ($receipt->is_invoice) {
                return redirect()->route('sales.order.show', $receipt->id);
            } elseif ($receipt->type == 'Expense') {
                return redirect()->route('receipt.show', $receipt->id);
            } elseif ($receipt->is_challan) {
                return redirect()->route('challan.show', $receipt->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store Expense Payment
     */
    public function expensePaymentStore(Request $request, Receipt $receipt)
    {
        $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'account_id'      => 'nullable|exists:accounts,id',
            'amount'          => 'required|numeric|min:0.01',
            'payment_date'    => 'required|date',
            'note'            => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            if ($receipt->type !== 'Expense') {
                throw new \Exception('This receipt is not an expense receipt.');
            }

            if ($receipt->payment_status === 'Paid' || $receipt->due_amount <= 0) {
                throw new \Exception('This expense is already fully paid.');
            }

            $paymentAmount = (float) $request->amount;

            $dueAmount = (float) $receipt->due_amount;

            if ($paymentAmount > $dueAmount) {

                throw new \Exception(
                    'Payment amount cannot be greater than the remaining due amount.'
                );
            }

            $paymentType = PaymentType::where('id', $request->payment_type_id)
                ->where('status', 'Active')
                ->first();

            if (!$paymentType) {
                throw new \Exception('Selected payment type is inactive or invalid.');
            }

            $account = null;
            if (strtolower($paymentType->name) !== 'cash') {
                if (!$request->filled('account_id')) {
                    throw new \Exception(
                        'Please select a payment account.'
                    );
                }
                $account = Account::where('id', $request->account_id)
                    ->where('status', 'Active')
                    ->lockForUpdate()
                    ->first();

                if (!$account) {
                    throw new \Exception(
                        'Selected account is inactive or not found.'
                    );
                }

                if (
                    $account->payment_type_id &&
                    (int) $account->payment_type_id !==
                    (int) $request->payment_type_id
                ) {
                    throw new \Exception(
                        'Selected account does not belong to the selected payment type.'
                    );
                }

                $accountBalance = (float) $account->current_balance;

                if ($accountBalance < $paymentAmount) {
                    throw new \Exception(
                        'Insufficient account balance. Available balance: ' .
                            number_format($accountBalance, 2) .
                            ' TK'
                    );
                }
            }

            ReceiptPayment::create([
                'receipt_id'      => $receipt->id,
                'payment_type_id' => $request->payment_type_id,
                'account_id'      => $request->filled('account_id') ? $request->account_id : null,
                'payment_date'    => $request->payment_date,
                'amount'          => $paymentAmount,
                'note'            => $request->note,
                'created_by'      => Auth::id(),
            ]);

            $newPaidAmount = (float) $receipt->paid_amount + $paymentAmount;

            $newDueAmount = (float) $receipt->total_amount - $newPaidAmount;

            if ($newDueAmount < 0) {
                $newDueAmount = 0;
            }

            if ($newDueAmount <= 0) {

                $paymentStatus = 'Paid';
            } elseif ($newPaidAmount > 0) {

                $paymentStatus = 'Partial';
            } else {

                $paymentStatus = 'Pending';
            }


            $receipt->update([
                'paid_amount'    => $newPaidAmount,
                'due_amount'     => $newDueAmount,
                'payment_status' => $paymentStatus,
                'updated_by'     => Auth::id(),
            ]);

            if ($account) {
                $account->current_balance = $account->current_balance - $paymentAmount;
                $account->save();
            }

            if ($account) {
                AccountTransaction::create([
                    'company_id'       => $receipt->company_id,
                    'account_id'       => $account->id,
                    'transaction_date' => $request->payment_date,
                    'voucher_no'       => $receipt->receipt_no,
                    'transaction_type' => 'Expense',
                    'purpose'          => 'Expense Payment',
                    'credit'           => 0,
                    'debit'            => $paymentAmount,
                    'balance'          => $account->current_balance,
                    'receipt_id'       => $receipt->id,
                    'created_by'       => Auth::id(),
                ]);
            }
            DB::commit();
            return back()->with('success', 'Expense payment completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function paymentAccounts($paymentType)
    {
        $user = Auth::user();

        $accounts = Account::where('payment_type_id', $paymentType)
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('company_id', $user->company_id)
                        ->where('branch_id', $user->branch_id);
                }
            )
            ->orderBy('default_status', 'asc')
            ->orderBy('account_name')
            ->get([
                'id',
                'account_name',
                'account_number',
                'current_balance',
                'payment_type_id',
                'default_status'
            ]);

        return response()->json($accounts);
    }

    public function paymentHistory(Receipt $receipt)
    {
        $payments = $receipt->payments()->with(['paymentType', 'account', 'user'])->latest()->get();
        return response()->json($payments);
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

        return view('BackEnd.Receipt.print', compact('receipt'));
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

    public function getBranches($company)
    {
        $company = Company::findOrFail($company);

        $branches = Branch::where('company_id', $company->id)->get();

        return response()->json([
            'company' => [
                'id'      => $company->id,
                'name'    => $company->name,
            ],
            'branches' => $branches,
        ]);
    }

    public function customerCompanyParties(CustomerCompany $customerCompany)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super-Admin')) {

            if ($customerCompany->created_by != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to access this customer company.'
                ], 403);
            }
        }

        $parties = Party::where('customer_company_id', $customerCompany->id)
            ->where('type', 'Customer')
            ->where('status', 'Active')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
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

    public function customerExpenseParties(CustomerCompany $customerCompany)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super-Admin')) {

            if ($customerCompany->created_by != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to access this customer company.'
                ], 403);
            }
        }

        $parties = Party::where('customer_company_id', $customerCompany->id)
            ->where('type', 'Expense')
            ->where('status', 'Active')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
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

    public function customerCompanyInfo(CustomerCompany $customerCompany)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super-Admin')) {

            if ($customerCompany->created_by != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $customerCompany->id,
                'name'    => $customerCompany->name,
                'phone'   => $customerCompany->phone,
                'email'   => $customerCompany->email,
                'address' => $customerCompany->address,
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
            'account_id'      => 'nullable|exists:accounts,id',
            'payment_date'    => 'required|date',
            'amount'          => 'required|numeric|min:0.01',
            'note'            => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $paymentType = PaymentType::where('id', $request->payment_type_id)
                ->where('status', 'Active')
                ->first();

            if (!$paymentType) {
                throw new \Exception('Selected payment type is inactive or invalid.');
            }
            $totalDue = Receipt::where('party_id', $party->id)
                ->where('type', 'Expense')
                ->where('due_amount', '>', 0)
                ->sum('due_amount');

            $paymentAmount = (float) $request->amount;

            if ($totalDue <= 0) {
                throw new \Exception('This party has no outstanding due amount.');
            }

            if ($paymentAmount > $totalDue) {
                throw new \Exception(
                    'Payment amount cannot be greater than total due amount. ' .
                        'Maximum payable: ' . number_format($totalDue, 2) . ' TK'
                );
            }

            $paymentTypeName = strtolower(trim($paymentType->name));

            $isCash = $paymentTypeName === 'cash';

            $account = null;

            if (!$isCash) {

                if (!$request->account_id) {
                    throw new \Exception(
                        'Please select a payment account.'
                    );
                }

                $account = Account::where('id', $request->account_id)
                    ->where('status', 'Active')
                    ->lockForUpdate()
                    ->first();

                if (!$account) {
                    throw new \Exception(
                        'Selected account is inactive or not found.'
                    );
                }

                if (
                    $account->payment_type_id &&
                    (int) $account->payment_type_id !==
                    (int) $request->payment_type_id
                ) {
                    throw new \Exception(
                        'Selected account does not belong to the selected payment type.'
                    );
                }

                $accountBalance = (float) $account->current_balance;

                if ($accountBalance < $paymentAmount) {

                    throw new \Exception(
                        'Insufficient account balance. Available balance: ' .
                            number_format($accountBalance, 2) .
                            ' TK'
                    );
                }
            }

            $remainingPayment = $paymentAmount;

            $receipts = Receipt::where('party_id', $party->id)
                ->where('type', 'Expense')
                ->where('due_amount', '>', 0)
                ->orderBy('receipt_date', 'asc')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->get();

            foreach ($receipts as $receipt) {

                if ($remainingPayment <= 0) {
                    break;
                }

                $receiptDue = (float) $receipt->due_amount;

                $payAmount = min(
                    $remainingPayment,
                    $receiptDue
                );

                ReceiptPayment::create([
                    'receipt_id'      => $receipt->id,
                    'payment_type_id' => $request->payment_type_id,
                    'account_id'      => $account?->id,
                    'payment_date'    => $request->payment_date,
                    'amount'          => $payAmount,
                    'note'            => $request->note,
                    'created_by'      => Auth::id(),
                ]);
                $newPaid = (float) $receipt->paid_amount + $payAmount;
                $newDue = (float) $receipt->total_amount - $newPaid;
                if ($newDue < 0) {
                    $newDue = 0;
                }
                if ($newDue <= 0) {
                    $paymentStatus = 'Paid';
                } elseif ($newPaid > 0) {
                    $paymentStatus = 'Partial';
                } else {
                    $paymentStatus = 'Pending';
                }
                $receipt->update([
                    'paid_amount'    => $newPaid,
                    'due_amount'     => $newDue,
                    'payment_status' => $paymentStatus,
                    'updated_by'     => Auth::id(),
                ]);
                if ($account) {
                    $account->current_balance = (float) $account->current_balance - $payAmount;
                    $account->save();
                    AccountTransaction::create([
                        'company_id'       => $receipt->company_id,
                        'account_id'       => $account->id,
                        'transaction_date' => $request->payment_date,
                        'voucher_no'       => $receipt->receipt_no,
                        'transaction_type' => 'Expense',
                        'purpose'          => 'Party Due Payment',
                        'credit'           => 0,
                        'debit'            => $payAmount,
                        'balance'          => $account->current_balance,
                        'receipt_id'       => $receipt->id,
                        'created_by'       => Auth::id(),
                    ]);
                }
                $remainingPayment -= $payAmount;
            }
            DB::commit();
            return back()->with('success', 'Due payment completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
