<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Category;
use App\Models\CompanyPackage;
use App\Models\Party;
use App\Models\Receipt;
use App\Models\ReceiptPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $today = Carbon::today();
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $receiptScope = function ($query) use ($user) {

            if (!$user->hasRole('Super-Admin')) {

                $query->where('company_id', $user->company_id)
                    ->where('branch_id', $user->branch_id);
            }

            return $query;
        };

        $todaySales = Receipt::where('type', 'Sales-Order')
            ->whereDate('receipt_date', $today)
            ->tap($receiptScope)
            ->sum('total_amount');

        $todayPurchase = Receipt::where('type', 'Purchase-Order')
            ->whereDate('receipt_date', $today)
            ->tap($receiptScope)
            ->sum('total_amount');

        $todayReceived = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->whereIn('receipts.type', [
                'Sales-Order',
                'Direct-Income',
                'Income'
            ])
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('receipts.company_id', $user->company_id)
                        ->where('receipts.branch_id', $user->branch_id);
                }
            )
            ->sum('receipt_payments.amount');

        $todayPaid = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->whereIn('receipts.type', [
                'Purchase-Order',
                'Expense'
            ])
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('receipts.company_id', $user->company_id)
                        ->where('receipts.branch_id', $user->branch_id);
                }
            )
            ->sum('receipt_payments.amount');

        $todayExpense = Receipt::where('type', 'Expense')
            ->whereDate('receipt_date', $today)
            ->tap($receiptScope)
            ->sum('total_amount');

        $todayDirectIncome = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->where('receipts.type', 'Direct-Income')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('receipts.company_id', $user->company_id)
                        ->where('receipts.branch_id', $user->branch_id);
                }
            )
            ->sum('receipt_payments.amount');

        $todayProfit = $todaySales - $todayPurchase + $todayDirectIncome;

        $receivable = Receipt::whereIn('type', [
            'Sales-Order',
            'Direct-Income',
            'Income'
        ])
            ->tap($receiptScope)
            ->sum('due_amount');

        $payable = Receipt::whereIn('type', [
            'Purchase-Order',
            'Expense'
        ])
            ->tap($receiptScope)
            ->sum('due_amount');

        $customers = Party::where('type', 'Customer')
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->count();

        $suppliers = Party::where('type', 'Supplier')
            ->where('status', 'Active')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->count();

        $branches = Branch::when(
            !$user->hasRole('Super-Admin'),
            function ($query) use ($user) {
                $query->where('company_id', $user->company_id);
            }
        )->count();

        $accountQuery = Account::where('status', 'Active');

        if (!$user->hasRole('Super-Admin')) {

            $accountQuery
                ->where('company_id', $user->company_id)
                ->where('branch_id', $user->branch_id);
        }

        $accounts = $accountQuery
            ->orderBy('account_name')
            ->get();

        $totalAccountBalance = $accounts->sum(function ($account) {
            return (float) $account->current_balance;
        });

        $todayAccountCreditQuery = AccountTransaction::whereDate(
            'transaction_date',
            $today
        );

        if (!$user->hasRole('Super-Admin')) {

            $todayAccountCreditQuery
                ->where('company_id', $user->company_id);
        }

        $todayAccountCredit = $todayAccountCreditQuery->sum('credit');

        $todayAccountDebitQuery = AccountTransaction::whereDate(
            'transaction_date',
            $today
        );

        if (!$user->hasRole('Super-Admin')) {

            $todayAccountDebitQuery
                ->where('company_id', $user->company_id);
        }

        $todayAccountDebit = $todayAccountDebitQuery->sum('debit');

        $todayNetCashFlow = $todayAccountCredit - $todayAccountDebit;

        $accountTransactionsQuery = AccountTransaction::with('account')
            ->whereDate('transaction_date', $today);

        if (!$user->hasRole('Super-Admin')) {

            $accountTransactionsQuery
                ->where('company_id', $user->company_id);
        }

        $todayAccountTransactions = $accountTransactionsQuery
            ->orderByDesc('id')
            ->get();

        $chartLabels = [];
        $salesData = [];
        $purchaseData = [];

        for ($i = 11; $i >= 0; $i--) {

            $month = Carbon::now()
                ->subMonths($i)
                ->startOfMonth();

            $monthEnd = $month->copy()->endOfMonth();

            $chartLabels[] = $month->format('M Y');


            // Sales
            $salesQuery = Receipt::where(
                'type',
                'Sales-Order'
            )
                ->whereBetween(
                    'receipt_date',
                    [
                        $month->format('Y-m-d'),
                        $monthEnd->format('Y-m-d')
                    ]
                );

            if (!$user->hasRole('Super-Admin')) {

                $salesQuery
                    ->where('company_id', $user->company_id)
                    ->where('branch_id', $user->branch_id);
            }

            $salesData[] = round(
                (float) $salesQuery->sum('total_amount'),
                2
            );


            // Purchase
            $purchaseQuery = Receipt::where(
                'type',
                'Purchase-Order'
            )
                ->whereBetween(
                    'receipt_date',
                    [
                        $month->format('Y-m-d'),
                        $monthEnd->format('Y-m-d')
                    ]
                );

            if (!$user->hasRole('Super-Admin')) {

                $purchaseQuery
                    ->where('company_id', $user->company_id)
                    ->where('branch_id', $user->branch_id);
            }

            $purchaseData[] = round(
                (float) $purchaseQuery->sum('total_amount'),
                2
            );
        }

        $topCustomerQuery = Receipt::select(
            'party_id',
            DB::raw('SUM(total_amount) as total')
        )
            ->whereIn('type', ['Sales-Order', 'Direct-Income'])
            ->whereNotNull('party_id')
            ->tap($receiptScope)
            ->groupBy('party_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();


        $topCustomers = $topCustomerQuery->map(function ($row) {

            $party = Party::find($row->party_id);

            return (object) [
                'name' => $party?->name ?? 'Unknown',
                'total' => (float) $row->total,
            ];
        });


        $topSupplierQuery = Receipt::select(
            'party_id',
            DB::raw('SUM(total_amount) as total')
        )
            ->whereIn('type', ['Purchase-Order', 'Expense'])
            ->whereNotNull('party_id')
            ->tap($receiptScope)
            ->groupBy('party_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();


        $topSuppliers = $topSupplierQuery->map(function ($row) {

            $party = Party::find($row->party_id);

            return (object) [
                'name' => $party?->name ?? 'Unknown',
                'total' => (float) $row->total,
            ];
        });

        $totalIncome = Receipt::where('type', 'Sales-Order')
            ->tap($receiptScope)
            ->sum('total_amount');


        $totalExpense = Receipt::where('type', 'Purchase-Order')
            ->tap($receiptScope)
            ->sum('total_amount');

        $package = null;

        if (method_exists($user, 'package')) {
            $package = $user->package()
                ->with('package')
                ->latest()
                ->first();
        }

        return view('BackEnd.Dashboard.dashboard', compact(

            'package',

            // Today
            'todaySales',
            'todayPurchase',
            'todayReceived',
            'todayPaid',
            'todayExpense',
            'todayDirectIncome',
            'todayProfit',

            // Due
            'receivable',
            'payable',

            // Counts
            'customers',
            'suppliers',
            'branches',
            'accounts',

            // Account
            'totalAccountBalance',
            'todayAccountCredit',
            'todayAccountDebit',
            'todayNetCashFlow',
            'todayAccountTransactions',

            // Chart
            'chartLabels',
            'salesData',
            'purchaseData',

            // Top
            'topCustomers',
            'topSuppliers',
            'totalIncome',
            'totalExpense'
        ));
    }
}
