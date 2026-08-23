<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\PaymentType;
use App\Models\Receipt;
use App\Models\ReceiptPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    /**
     * Purchase Payment List
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $purchases = Receipt::with([
            'party',
            'customerCompany',
            'company',
            'branch',
        ])
            ->where('type', 'Purchase-Order')
            ->where('status', 'Completed')->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('company_id', $user->company_id)
                        ->where('created_by', $user->id);
                }
            )

            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('receipt_no', 'like', "%{$search}%")
                        ->orWhere('po_no', 'like', "%{$search}%")

                        ->orWhereHas('party', function ($party) use ($search) {
                            $party->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })

                        ->orWhereHas('customerCompany', function ($company) use ($search) {
                            $company->where('name', 'like', "%{$search}%");
                        });
                });
            })

            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('BackEnd.PurchasePayment.index', compact('purchases'));
    }


    /**
     * Show Payment Details
     */
    public function show(Receipt $receipt)
    {
        $user = Auth::user();

        // Security
        if (!$user->hasRole('Super-Admin')) {

            if (
                $receipt->company_id != $user->company_id ||
                $receipt->created_by != $user->id
            ) {
                abort(403);
            }
        }

        // Only completed purchase
        abort_unless(
            $receipt->type === 'Purchase-Order' &&
                $receipt->status === 'Completed',
            404
        );

        $receipt->load([
            'party',
            'customerCompany',
            'company',
            'branch',
            'items.product',
            'payments.paymentType',
            'payments.account',
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

        return view(
            'BackEnd.PurchasePayment.show',
            compact(
                'receipt',
                'paymentTypes'
            )
        );
    }


    /**
     * Store Purchase Payment
     */
    public function store(Request $request, Receipt $receipt)
    {
        $user = Auth::user();
        if (!$user->hasRole('Super-Admin')) {

            if (
                $receipt->company_id != $user->company_id ||
                $receipt->created_by != $user->id
            ) {
                abort(403);
            }
        }

        if (
            $receipt->type !== 'Purchase-Order' ||
            $receipt->status !== 'Completed'
        ) {

            return back()->with(
                'error',
                'This purchase is not available for payment.'
            );
        }
        $request->validate([

            'payment_type_id' => [
                'required',
                'exists:payment_types,id'
            ],

            'account_id' => [
                'required',
                'exists:accounts,id'
            ],

            'payment_date' => [
                'required',
                'date'
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0'
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000'
            ],

        ]);

        DB::beginTransaction();

        try {

            $receipt = Receipt::where('id', $receipt->id)
                ->where('type', 'Purchase-Order')
                ->where('status', 'Completed')
                ->lockForUpdate()
                ->firstOrFail();


            $dueAmount = round(
                (float) $receipt->due_amount,
                2
            );

            if ($dueAmount <= 0) {

                throw new \Exception(
                    'This purchase has no outstanding balance.'
                );
            }

            $paymentAmount = round(
                (float) $request->amount,
                2
            );

            if ($paymentAmount > $dueAmount) {

                throw new \Exception(
                    'Payment amount cannot be greater than due amount. ' .
                        'Due: ' .
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
                    'Selected payment type is not active.'
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
                    'Selected account is not active or does not exist.'
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
                (int) $account->payment_type_id !==
                (int) $paymentType->id
            ) {

                throw new \Exception(
                    'Selected account does not belong to the selected payment type.'
                );
            }

            $accountBalance = round(
                (float) $account->current_balance,
                2
            );

            if ($accountBalance < $paymentAmount) {

                throw new \Exception(
                    'Insufficient account balance. ' .
                        'Available: ' .
                        number_format($accountBalance, 2) .
                        ', Required: ' .
                        number_format($paymentAmount, 2)
                );
            }

            ReceiptPayment::create([

                'receipt_id' =>
                $receipt->id,

                'payment_type_id' =>
                $paymentType->id,

                'account_id' =>
                $account->id,

                'payment_date' =>
                $request->payment_date,

                'amount' =>
                $paymentAmount,

                'note' =>
                $request->note,

                'created_by' =>
                $user->id,

            ]);

            $newAccountBalance = round(
                $accountBalance - $paymentAmount,
                2
            );

            $account->update([

                'current_balance' =>
                $newAccountBalance,

                'updated_by' =>
                $user->id,

            ]);

            $lastTransaction = AccountTransaction::where(
                'account_id',
                $account->id
            )
                ->latest('id')
                ->lockForUpdate()
                ->first();

            $previousBalance = $lastTransaction
                ? (float) $lastTransaction->balance
                : (float) $account->opening_balance;

            $newTransactionBalance = round(
                $previousBalance - $paymentAmount,
                2
            );
            AccountTransaction::create([

                'company_id' =>
                $account->company_id,

                'account_id' =>
                $account->id,

                'transaction_date' =>
                $request->payment_date,

                'voucher_no' =>
                $receipt->po_no ??
                    $receipt->receipt_no,

                'transaction_type' =>
                'Purchase-Order',

                'purpose' =>
                'Purchase Payment - ' .
                    (
                        $receipt->po_no ??
                        $receipt->receipt_no
                    ),

                'credit' =>
                0,

                'debit' =>
                $paymentAmount,

                'balance' =>
                $newTransactionBalance,

                'receipt_id' =>
                $receipt->id,

                'created_by' =>
                $user->id,

            ]);
            $newPaidAmount = round(
                (float) $receipt->paid_amount +
                    $paymentAmount,
                2
            );

            $newDueAmount = round(
                (float) $receipt->total_amount -
                    $newPaidAmount,
                2
            );


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

                'paid_amount' =>
                $newPaidAmount,

                'due_amount' =>
                $newDueAmount,

                'payment_status' =>
                $paymentStatus,

                'updated_by' =>
                $user->id,

            ]);
            DB::commit();


            return redirect()
                ->route(
                    'purchase.payment.show',
                    $receipt->id
                )
                ->with(
                    'success',
                    'Payment completed successfully.'
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

    public function paymentTypeAccounts(PaymentType $paymentType)
    {
        $user = Auth::user();

        $accounts = Account::where('payment_type_id', $paymentType->id)
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('company_id', $user->company_id);
                }
            )
            ->orderBy('account_name')
            ->get([
                'id',
                'account_name',
                'account_number',
                'current_balance',
                'payment_type_id',
            ]);

        return response()->json([
            'success' => true,
            'accounts' => $accounts,
        ]);
    }
}
