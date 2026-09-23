<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
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

class DirectIncomeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Receipt::with(['party', 'branch', 'creator', 'items', 'customerCompany'])
            ->where('type', 'Direct-Income')
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
        return view('BackEnd.DirectIncome.index', compact('receipts'));
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
        $customerCompanies = CustomerCompany::where('status', 'Customer')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();

        return view('BackEnd.DirectIncome.create', compact('branches', 'parties', 'companies', 'customerCompanies'));
    }

    private function generateReceiptNo()
    {
        do {
            $number = 'DI-' . date('Ymd') . rand(1000, 9999);
        } while (Receipt::where('receipt_no', $number)->exists());

        return $number;
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'receipt_date' => ['required', 'date'],

            'customer_company_id' => [
                'nullable',
                'exists:customer_companies,id'
            ],

            'party_id' => [
                'required',
                'exists:parties,id'
            ],

            'details' => [
                'required',
                'array',
                'min:1'
            ],

            'details.*' => [
                'required',
                'string',
                'max:5000'
            ],

            'qty' => [
                'required',
                'array',
                'min:1'
            ],

            'qty.*' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'rate' => [
                'required',
                'array',
                'min:1'
            ],

            'rate.*' => [
                'required',
                'numeric',
                'min:0'
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'vat' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ]);

        DB::beginTransaction();

        try {

            $companyId = $user->hasRole('Super-Admin')
                ? ($request->company_id ?? $user->company_id)
                : $user->company_id;

            $branchId = $user->hasRole('Super-Admin')
                ? ($request->branch_id ?? $user->branch_id)
                : $user->branch_id;
            $totalQty = 0;
            $subTotal = 0;

            foreach ($request->details as $index => $details) {

                $details = trim($details);

                $qty = (float) ($request->qty[$index] ?? 0);
                $rate = (float) ($request->rate[$index] ?? 0);

                if ($qty <= 0) {
                    throw new \Exception(
                        'Quantity must be greater than zero.'
                    );
                }

                if ($rate < 0) {
                    throw new \Exception(
                        'Rate cannot be negative.'
                    );
                }

                $amount = round($qty * $rate, 2);

                $totalQty += $qty;
                $subTotal += $amount;
            }
            $subTotal = round($subTotal, 2);

            $discount = (float) ($request->discount ?? 0);

            if ($discount > $subTotal) {
                throw new \Exception(
                    'Discount cannot be greater than subtotal.'
                );
            }

            $vatPercent = (float) ($request->vat ?? 0);

            $afterDiscount = $subTotal - $discount;

            $vatAmount = round(
                ($afterDiscount * $vatPercent) / 100,
                2
            );

            $totalAmount = round(
                $afterDiscount + $vatAmount,
                2
            );

            $paidAmount = round(
                (float) ($request->paid_amount ?? 0),
                2
            );

            if ($paidAmount > $totalAmount) {
                throw new \Exception(
                    'Paid amount cannot be greater than total amount.'
                );
            }
            $dueAmount = round(
                $totalAmount - $paidAmount,
                2
            );

            if ($paidAmount <= 0) {

                $paymentStatus = 'Pending';
            } elseif ($dueAmount > 0) {

                $paymentStatus = 'Partial';
            } else {

                $paymentStatus = 'Paid';
            }

            $paymentType = null;
            $cashAccount = null;

            if ($paidAmount > 0) {
                $paymentType = PaymentType::where('name', 'Cash')
                    ->where('status', 'Active')
                    ->first();

                if (!$paymentType) {

                    throw new \Exception(
                        'Cash payment type is not available or inactive. Please create/activate the Cash payment type first.'
                    );
                }
                $cashAccountQuery = Account::where(
                    'payment_type_id',
                    $paymentType->id
                )
                    ->where('is_default', true)
                    ->where('status', 'Active');

                if (!$user->hasRole('Super-Admin')) {

                    $cashAccountQuery
                        ->where('company_id', $companyId)
                        ->where('branch_id', $branchId);
                } else {

                    $cashAccountQuery
                        ->where(function ($query) use ($companyId) {
                            $query->where('company_id', $companyId)
                                ->orWhereNull('company_id');
                        })
                        ->where(function ($query) use ($branchId) {
                            $query->where('branch_id', $branchId)
                                ->orWhereNull('branch_id');
                        });
                }

                $cashAccount = $cashAccountQuery
                    ->lockForUpdate()
                    ->first();

                if (!$cashAccount) {

                    throw new \Exception(
                        'Default Cash account not found. Please create a Cash account and set it as Default.'
                    );
                }
            }

            $receiptNo = $this->generateReceiptNo();

            $receipt = Receipt::create([

                'receipt_no' => $receiptNo,

                'type' => 'Direct-Income',

                'is_challan' => false,
                'is_invoice' => false,
                'is_receive' => false,

                'company_id' => $companyId,
                'branch_id' => $branchId,

                'customer_company_id' =>
                $request->customer_company_id,

                'party_id' =>
                $request->party_id,

                'receipt_date' =>
                $request->receipt_date,

                'remarks' =>
                $request->remarks,

                'total_qty' => $totalQty,

                'sub_total' =>
                $subTotal,

                'discount' =>
                round($discount, 2),

                'vat' =>
                round($vatAmount, 2),

                'total_amount' =>
                $totalAmount,

                'paid_amount' =>
                $paidAmount,

                'due_amount' =>
                $dueAmount,

                'payment_status' =>
                $paymentStatus,

                'status' =>
                'Draft',

                'created_by' =>
                $user->id,

                'updated_by' =>
                $user->id,
            ]);
            foreach ($request->details as $index => $details) {

                $details = trim($details);

                $qty = (float) ($request->qty[$index] ?? 0);

                $rate = (float) ($request->rate[$index] ?? 0);

                $amount = round(
                    $qty * $rate,
                    2
                );

                ReceiptItem::create([

                    'receipt_id' =>
                    $receipt->id,

                    'category_id' =>
                    null,

                    'account_head_id' =>
                    null,

                    'product_id' =>
                    null,

                    'qty' =>
                    $qty,

                    'rate' =>
                    $rate,

                    'amount' =>
                    $amount,

                    'details' =>
                    $details,
                ]);
            }
            if ($paidAmount > 0) {
                $currentBalance = round(
                    (float) $cashAccount->current_balance,
                    2
                );

                $newBalance = round(
                    $currentBalance + $paidAmount,
                    2
                );

                $cashAccount->update([

                    'current_balance' =>
                    $newBalance,

                    'updated_by' =>
                    $user->id,
                ]);

                AccountTransaction::create([

                    'company_id' =>
                    $cashAccount->company_id,

                    'account_id' =>
                    $cashAccount->id,

                    'transaction_date' =>
                    $request->receipt_date,

                    'voucher_no' =>
                    $receipt->receipt_no,

                    'transaction_type' =>
                    'Direct-Income',

                    'purpose' =>
                    'Direct Income Cash Payment - ' .
                        $receipt->receipt_no,

                    'credit' =>
                    $paidAmount,

                    'debit' =>
                    0,

                    'balance' =>
                    $newBalance,

                    'receipt_id' =>
                    $receipt->id,

                    'created_by' =>
                    $user->id,
                ]);

                ReceiptPayment::create([

                    'receipt_id' =>
                    $receipt->id,

                    'payment_type_id' =>
                    $paymentType->id,

                    'account_id' =>
                    $cashAccount->id,

                    'payment_date' =>
                    $request->receipt_date,

                    'amount' =>
                    $paidAmount,

                    'note' =>
                    'Initial Cash Payment',

                    'created_by' =>
                    $user->id,
                ]);
            }


            DB::commit();

            return redirect()
                ->route(
                    'direct.income.show',
                    ['receipt' => $receipt->id]
                )
                ->with(
                    'success',
                    'Direct Income created successfully.'
                );
        } catch (\Throwable $e) {

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
            'items.product.category',
            'items.product.brand',
            'payments.account',
            'payments.paymentType',
            'payments.user',
        ]);
        $paymentTypes = PaymentType::where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->orderBy('name')
            ->get();
        $accounts = Account::where('status', 'Active')
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('company_id', Auth::user()->company_id)
                    ->where('branch_id', Auth::user()->branch_id);
            })
            ->orderBy('account_name')
            ->get();

        return view('BackEnd.DirectIncome.show', compact('receipt', 'paymentTypes', 'accounts'));
    }

    public function edit(Receipt $receipt)
    {
        // Make sure only Direct Income receipts can be edited
        abort_unless($receipt->type === 'Direct-Income', 404);

        $receipt->load([
            'company',
            'branch.company',
            'party',
            'customerCompany',
            'creator',
            'items',
        ]);

        $user = Auth::user();

        $companies = Company::when(
            !$user->hasRole('Super-Admin'),
            function ($q) use ($user) {
                $q->where('id', $user->company_id);
            }
        )->get();

        $branches = Branch::when(
            !$user->hasRole('Super-Admin'),
            function ($q) use ($user) {
                $q->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                        ->orWhere('id', $user->branch_id);
                });
            }
        )->latest()->get();

        $parties = Party::where('type', 'Customer')
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->get();

        $customerCompanies = CustomerCompany::where('status', 'Customer')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->get();

        $receiptItems = $receipt->items->map(function ($item) {
            return [
                'details' => $item->details,
                'qty'     => $item->qty,
                'rate'    => $item->rate,
                'amount'  => $item->amount,
            ];
        })->values();

        $vatPercent = 0;

        if ($receipt->sub_total > 0 && $receipt->vat > 0) {

            $afterDiscount = $receipt->sub_total - $receipt->discount;

            if ($afterDiscount > 0) {
                $vatPercent = ($receipt->vat / $afterDiscount) * 100;
            }
        }


        return view(
            'BackEnd.DirectIncome.edit',
            compact(
                'receipt',
                'branches',
                'parties',
                'companies',
                'customerCompanies',
                'receiptItems',
                'vatPercent'
            )
        );
    }

    public function update(Request $request, Receipt $receipt)
    {
        $user = Auth::user();

        // Make sure this is a Direct Income receipt
        abort_unless(
            $receipt->type === 'Direct-Income',
            404
        );

        $request->validate([

            'receipt_date' => [
                'required',
                'date'
            ],

            'company_id' => [
                'required',
                'exists:companies,id'
            ],

            'branch_id' => [
                'required',
                'exists:branches,id'
            ],

            'customer_company_id' => [
                'nullable',
                'exists:customer_companies,id'
            ],

            'party_id' => [
                'required',
                'exists:parties,id'
            ],

            'details' => [
                'required',
                'array',
                'min:1'
            ],

            'details.*' => [
                'required',
                'string',
                'max:5000'
            ],

            'qty' => [
                'required',
                'array',
                'min:1'
            ],

            'qty.*' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'rate' => [
                'required',
                'array',
                'min:1'
            ],

            'rate.*' => [
                'required',
                'numeric',
                'min:0'
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'vat' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ]);

        DB::beginTransaction();

        try {
            $companyId = $user->hasRole('Super-Admin')
                ? $request->company_id
                : $user->company_id;

            $branchId = $user->hasRole('Super-Admin')
                ? $request->branch_id
                : $user->branch_id;

            if (!$user->hasRole('Super-Admin')) {

                $branchExists = Branch::where(
                    'id',
                    $branchId
                )
                    ->where(function ($query) use ($user) {

                        $query->where(
                            'created_by',
                            $user->id
                        )
                            ->orWhere(
                                'id',
                                $user->branch_id
                            );
                    })
                    ->exists();

                if (!$branchExists) {

                    throw new \Exception(
                        'You are not authorized to use this branch.'
                    );
                }
            }
            $totalQty = 0;
            $subTotal = 0;

            $details = $request->input(
                'details',
                []
            );

            $qtys = $request->input(
                'qty',
                []
            );

            $rates = $request->input(
                'rate',
                []
            );
            $receipt->items()->delete();
            foreach ($details as $index => $detail) {

                $detail = trim($detail);

                $qty = (float) (
                    $qtys[$index] ?? 0
                );

                $rate = (float) (
                    $rates[$index] ?? 0
                );


                if ($qty <= 0) {

                    throw new \Exception(
                        'Quantity must be greater than zero.'
                    );
                }


                if ($rate < 0) {

                    throw new \Exception(
                        'Rate cannot be negative.'
                    );
                }


                $amount = round(
                    $qty * $rate,
                    2
                );


                ReceiptItem::create([

                    'receipt_id' =>
                    $receipt->id,

                    'category_id' =>
                    null,

                    'account_head_id' =>
                    null,

                    'product_id' =>
                    null,

                    'qty' =>
                    $qty,

                    'rate' =>
                    $rate,

                    'amount' =>
                    $amount,

                    'details' =>
                    $detail,
                ]);


                $totalQty += $qty;

                $subTotal += $amount;
            }


            $subTotal = round(
                $subTotal,
                2
            );
            $discount = (float) (
                $request->discount ?? 0
            );

            if ($discount > $subTotal) {

                throw new \Exception(
                    'Discount cannot be greater than subtotal.'
                );
            }
            $vatPercent = (float) (
                $request->vat ?? 0
            );

            $afterDiscount =
                $subTotal - $discount;


            $vatAmount = round(
                ($afterDiscount * $vatPercent) / 100,
                2
            );
            $totalAmount = round(
                $afterDiscount + $vatAmount,
                2
            );
            $newPaidAmount = round(
                (float) (
                    $request->paid_amount ?? 0
                ),
                2
            );


            if ($newPaidAmount > $totalAmount) {

                throw new \Exception(
                    'Paid amount cannot be greater than total amount.'
                );
            }
            $oldPaidAmount = round(
                (float) $receipt->paid_amount,
                2
            );
            $paymentDifference = round(
                $newPaidAmount - $oldPaidAmount,
                2
            );
            $dueAmount = round(
                $totalAmount - $newPaidAmount,
                2
            );

            if ($dueAmount < 0) {

                $dueAmount = 0;
            }
            if ($newPaidAmount <= 0) {

                $paymentStatus = 'Pending';
            } elseif ($dueAmount > 0) {

                $paymentStatus = 'Partial';
            } else {

                $paymentStatus = 'Paid';
            }

            if ($paymentDifference != 0) {
                $paymentType = PaymentType::where(
                    'name',
                    'Cash'
                )
                    ->where(
                        'status',
                        'Active'
                    )
                    ->first();


                if (!$paymentType) {

                    throw new \Exception(
                        'Cash payment type is not available or inactive. Please create/activate the Cash payment type first.'
                    );
                }

                $cashAccountQuery = Account::where(
                    'payment_type_id',
                    $paymentType->id
                )
                    ->where(
                        'is_default',
                        true
                    )
                    ->where(
                        'status',
                        'Active'
                    );

                if (!$user->hasRole('Super-Admin')) {

                    $cashAccountQuery
                        ->where(
                            'company_id',
                            $companyId
                        )
                        ->where(
                            'branch_id',
                            $branchId
                        );
                } else {

                    $cashAccountQuery
                        ->where(function ($query) use ($companyId) {

                            $query->where(
                                'company_id',
                                $companyId
                            )
                                ->orWhereNull(
                                    'company_id'
                                );
                        })
                        ->where(function ($query) use ($branchId) {

                            $query->where(
                                'branch_id',
                                $branchId
                            )
                                ->orWhereNull(
                                    'branch_id'
                                );
                        });
                }


                $cashAccount = $cashAccountQuery
                    ->lockForUpdate()
                    ->first();


                if (!$cashAccount) {

                    throw new \Exception(
                        'Default Cash account not found. Please create a Cash account and set it as Default.'
                    );
                }

                $currentBalance = round(
                    (float) $cashAccount->current_balance,
                    2
                );


                if (
                    $paymentDifference < 0 &&
                    $currentBalance < abs($paymentDifference)
                ) {

                    throw new \Exception(
                        'Cash account does not have enough balance to reverse this payment.'
                    );
                }

                $newBalance = round(
                    $currentBalance + $paymentDifference,
                    2
                );

                $cashAccount->update([

                    'current_balance' =>
                    $newBalance,

                    'updated_by' =>
                    $user->id,
                ]);

                $accountTransaction =
                    AccountTransaction::where(
                        'receipt_id',
                        $receipt->id
                    )
                    ->where(
                        'transaction_type',
                        'Direct-Income'
                    )
                    ->where(
                        'account_id',
                        $cashAccount->id
                    )
                    ->lockForUpdate()
                    ->first();
                if ($newPaidAmount > 0) {
                    if (!$accountTransaction) {

                        AccountTransaction::create([

                            'company_id' =>
                            $cashAccount->company_id,

                            'account_id' =>
                            $cashAccount->id,

                            'transaction_date' =>
                            $request->receipt_date,

                            'voucher_no' =>
                            $receipt->receipt_no,

                            'transaction_type' =>
                            'Direct-Income',

                            'purpose' =>
                            'Direct Income Cash Payment - ' .
                                $receipt->receipt_no,

                            'credit' =>
                            $newPaidAmount,

                            'debit' =>
                            0,

                            'balance' =>
                            $newBalance,

                            'receipt_id' =>
                            $receipt->id,

                            'created_by' =>
                            $user->id,
                        ]);
                    } else {
                        $accountTransaction->update([

                            'transaction_date' =>
                            $request->receipt_date,

                            'credit' =>
                            $newPaidAmount,

                            'debit' =>
                            0,

                            'balance' =>
                            $newBalance,

                            'updated_by' =>
                            $user->id,
                        ]);
                    }
                } else {
                    if ($accountTransaction) {

                        $accountTransaction->delete();
                    }
                }
                $receiptPayment =
                    ReceiptPayment::where(
                        'receipt_id',
                        $receipt->id
                    )
                    ->where(
                        'payment_type_id',
                        $paymentType->id
                    )
                    ->where(
                        'account_id',
                        $cashAccount->id
                    )
                    ->lockForUpdate()
                    ->first();


                if ($newPaidAmount > 0) {

                    if (!$receiptPayment) {

                        ReceiptPayment::create([

                            'receipt_id' =>
                            $receipt->id,

                            'payment_type_id' =>
                            $paymentType->id,

                            'account_id' =>
                            $cashAccount->id,

                            'payment_date' =>
                            $request->receipt_date,

                            'amount' =>
                            $newPaidAmount,

                            'note' =>
                            'Initial Cash Payment',

                            'created_by' =>
                            $user->id,
                        ]);
                    } else {

                        $receiptPayment->update([

                            'payment_date' =>
                            $request->receipt_date,

                            'amount' =>
                            $newPaidAmount,

                            'updated_by' =>
                            $user->id,
                        ]);
                    }
                } else {

                    if ($receiptPayment) {

                        $receiptPayment->delete();
                    }
                }
            }
            $receipt->update([

                'company_id' =>
                $companyId,

                'branch_id' =>
                $branchId,

                'customer_company_id' =>
                $request->customer_company_id,

                'party_id' =>
                $request->party_id,

                'receipt_date' =>
                $request->receipt_date,

                'remarks' =>
                $request->remarks,

                'total_qty' =>
                round($totalQty, 2),

                'sub_total' =>
                round($subTotal, 2),

                'discount' =>
                round($discount, 2),
                'vat' =>
                round($vatAmount, 2),

                'total_amount' =>
                $totalAmount,

                'paid_amount' =>
                $newPaidAmount,

                'due_amount' =>
                $dueAmount,

                'payment_status' =>
                $paymentStatus,

                'updated_by' =>
                $user->id,
            ]);
            DB::commit();


            return redirect()
                ->route(
                    'direct.income.show',
                    [
                        'receipt' => $receipt->id
                    ]
                )
                ->with(
                    'success',
                    'Direct Income updated successfully.'
                );
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function paymentStore(Request $request, Receipt $receipt)
    {
        $user = Auth::user();
        $userId = $user->id;

        $request->validate([
            'payment_type_id' => ['required', 'exists:payment_types,id',],
            'account_id' => ['required', 'exists:accounts,id',],
            'amount' => ['required', 'numeric', 'gt:0',],
            'payment_date' => ['required', 'date',],
            'note' => ['nullable', 'string', 'max:1000',],
        ]);
        DB::beginTransaction();
        try {
            $receipt = Receipt::where('id', $receipt->id)
                ->where('type', 'Direct-Income')
                ->lockForUpdate()
                ->firstOrFail();

            if ($receipt->type !== 'Direct-Income') {
                throw new \Exception(
                    'Payment is only allowed for Direct Income.'
                );
            }
            if ($receipt->payment_status === 'Paid') {
                throw new \Exception(
                    'This receipt has already been fully paid.'
                );
            }

            $amount = round(
                (float) $request->amount,
                2
            );

            if ($amount <= 0) {
                throw new \Exception(
                    'Payment amount must be greater than zero.'
                );
            }

            $dueAmount = round(
                (float) $receipt->due_amount,
                2
            );

            if ($dueAmount <= 0) {
                throw new \Exception(
                    'This Direct Income has no outstanding due amount.'
                );
            }

            if ($amount > $dueAmount) {
                throw new \Exception(
                    'Payment amount cannot be greater than due amount. ' .
                        'Remaining Due: ' .
                        number_format($dueAmount, 2)
                );
            }

            $paymentType = PaymentType::where(
                'id',
                $request->payment_type_id
            )
                ->where('status', 'Active')
                ->first();

            if (!$paymentType) {

                throw new \Exception(
                    'Selected payment type is inactive or invalid.'
                );
            }

            $account = Account::where(
                'id',
                $request->account_id
            )
                ->where('status', 'Active')
                ->lockForUpdate()
                ->first();

            if (!$account) {

                throw new \Exception(
                    'Selected account is inactive or invalid.'
                );
            }

            if (
                (int) $account->payment_type_id !==
                (int) $paymentType->id
            ) {

                throw new \Exception(
                    'Selected account does not belong to the selected payment type.'
                );
            }

            if (
                !$user->hasRole('Super-Admin') &&
                (int) $account->company_id !==
                (int) $user->company_id
            ) {

                throw new \Exception(
                    'You are not allowed to use this account.'
                );
            }

            if (
                !$user->hasRole('Super-Admin') &&
                (int) $account->branch_id !==
                (int) $user->branch_id
            ) {

                throw new \Exception(
                    'You are not allowed to use this account.'
                );
            }

            $currentBalance = round(
                (float) $account->current_balance,
                2
            );

            $newBalance = round(
                $currentBalance + $amount,
                2
            );

            $account->update([
                'current_balance' => $newBalance,
                'updated_by'      => $userId,
            ]);
            AccountTransaction::create([
                'company_id'       => $account->company_id,
                'account_id'       => $account->id,
                'transaction_date' => $request->payment_date,
                'voucher_no'       => $receipt->so_no ?? $receipt->receipt_no,
                'transaction_type' => 'Direct-Income',
                'purpose'          =>
                'Direct Income Payment - ' .
                    $receipt->receipt_no,
                'credit'           => $amount,
                'debit'            => 0,
                'balance'          => $newBalance,
                'receipt_id'       => $receipt->id,
                'created_by'       => $userId,
            ]);

            ReceiptPayment::create([
                'receipt_id'      => $receipt->id,
                'payment_type_id' => $paymentType->id,
                'account_id'      => $account->id,
                'payment_date'    => $request->payment_date,
                'amount'          => $amount,
                'note'            => $request->note,
                'created_by'      => $userId,
            ]);

            $oldPaidAmount = round(
                (float) $receipt->paid_amount,
                2
            );

            $newPaidAmount = round(
                $oldPaidAmount + $amount,
                2
            );

            $totalAmount = round(
                (float) $receipt->total_amount,
                2
            );

            $newDueAmount = round(
                $totalAmount - $newPaidAmount,
                2
            );

            if ($newDueAmount < 0) {
                $newDueAmount = 0;
            }

            if ($newDueAmount <= 0) {

                $newDueAmount = 0;

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
                'updated_by'     => $userId,
            ]);
            DB::commit();
            return back()->with('success', 'Direct Income payment successfully completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function print(Receipt $receipt)
    {
        $receipt->load([
            'company',
            'branch',
            'customerCompany',
            'party',
            'creator',
            'items.product',
            'items.category',
            'items.accountHead',
            'payments.account',
            'payments.paymentType',
            'payments.user',
        ]);

        return view('BackEnd.DirectIncome.print', compact('receipt'));
    }

    public function pdf(Receipt $receipt)
    {
        $receipt->load([
            'company',
            'branch',
            'customerCompany',
            'party',
            'creator',
            'items.product',
            'items.category',
            'items.accountHead',
            'payments.account',
            'payments.paymentType',
            'payments.user',
        ]);

        $pdf = Pdf::loadView('BackEnd.DirectIncome.pdf', compact('receipt'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'Direct-Income-' . $receipt->receipt_no . '.pdf'
        );
    }
}
