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
    public function index()
    {
        $user = Auth::user();

        $isSuperAdmin = $user->hasRole('Super-Admin');
        $receiptQuery = Receipt::query();

        if (!$isSuperAdmin) {
            $receiptQuery->where('created_by', $user->id);
        }

        $today = Carbon::today();

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        $previousMonthStart = Carbon::now()
            ->subMonth()
            ->startOfMonth();

        $previousMonthEnd = Carbon::now()
            ->subMonth()
            ->endOfMonth();

        $salesQuery = (clone $receiptQuery)
            ->where('type', 'Sales-Order')
            ->where('status', 'Completed');

        $expenseQuery = (clone $receiptQuery)
            ->where('type', 'Expense')
            ->where('status', 'Completed');

        $todayIncome = (clone $salesQuery)
            ->whereDate('receipt_date', $today)
            ->sum('total_amount');

        $todayExpense = (clone $expenseQuery)
            ->whereDate('receipt_date', $today)
            ->sum('total_amount');

        $todayProfit = $todayIncome - $todayExpense;

        $monthIncome = (clone $salesQuery)
            ->whereBetween('receipt_date', [
                $monthStart,
                $monthEnd
            ])
            ->sum('total_amount');

        $monthExpense = (clone $expenseQuery)
            ->whereBetween('receipt_date', [
                $monthStart,
                $monthEnd
            ])
            ->sum('total_amount');

        $totalIncome = (clone $salesQuery)
            ->sum('total_amount');

        $totalExpense = (clone $expenseQuery)
            ->sum('total_amount');

        $grossProfit = $totalIncome - $totalExpense;

        $totalReceivable = (clone $salesQuery)
            ->where('due_amount', '>', 0)
            ->sum('due_amount');

        $totalPayable = (clone $expenseQuery)
            ->where('due_amount', '>', 0)
            ->sum('due_amount');

        $accountQuery = Account::query()
            ->where('status', 'Active');

        if (!$isSuperAdmin) {
            $accountQuery->where('created_by', $user->id);
        }

        $currentBalance = (float) $accountQuery
            ->sum('current_balance');

        $totalAccount = (clone $accountQuery)->count();

        $partyQuery = Party::query();

        if (!$isSuperAdmin) {
            $partyQuery->where('created_by', $user->id);
        }

        $totalCustomer = (clone $partyQuery)
            ->whereIn('type', [
                'Customer',
                'Both'
            ])
            ->count();

        $totalSupplier = (clone $partyQuery)
            ->whereIn('type', [
                'Supplier',
                'Both'
            ])
            ->count();

        $branchQuery = Branch::query();

        if (!$isSuperAdmin) {
            $branchQuery->where('created_by', $user->id);
        }

        $totalBranch = $branchQuery->count();

        $totalReceipt = (clone $receiptQuery)->count();

        $paymentQuery = ReceiptPayment::query();

        if (!$isSuperAdmin) {

            $paymentQuery->where('created_by', $user->id);
        }

        $totalPayment = $paymentQuery->count();

        $paymentSummary = [
            'paid' => (clone $receiptQuery)
                ->where('payment_status', 'Paid')
                ->count(),

            'partial' => (clone $receiptQuery)
                ->where('payment_status', 'Partial')
                ->count(),

            'pending' => (clone $receiptQuery)
                ->where('payment_status', 'Pending')
                ->count(),
        ];

        $receiptSummary = [
            'completed' => (clone $receiptQuery)
                ->where('status', 'Completed')
                ->count(),

            'draft' => (clone $receiptQuery)
                ->where('status', 'Draft')
                ->count(),

            'cancelled' => (clone $receiptQuery)
                ->where('status', 'Cancelled')
                ->count(),
        ];

        $previousMonthIncome = (clone $salesQuery)
            ->whereBetween('receipt_date', [
                $previousMonthStart,
                $previousMonthEnd
            ])
            ->sum('total_amount');


        if ($previousMonthIncome > 0) {

            $incomeGrowth =
                (($monthIncome - $previousMonthIncome)
                    / $previousMonthIncome) * 100;
        } else {

            $incomeGrowth = $monthIncome > 0 ? 100 : 0;
        }

        $previousMonthExpense = (clone $expenseQuery)
            ->whereBetween('receipt_date', [
                $previousMonthStart,
                $previousMonthEnd
            ])
            ->sum('total_amount');


        if ($previousMonthExpense > 0) {

            $expenseGrowth =
                (($monthExpense - $previousMonthExpense)
                    / $previousMonthExpense) * 100;
        } else {

            $expenseGrowth = $monthExpense > 0 ? 100 : 0;
        }

        $topCustomersQuery = (clone $salesQuery)
            ->whereNotNull('party_id')
            ->select(
                'party_id',
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('party_id')
            ->orderByDesc('total')
            ->limit(10);


        $topCustomers = $topCustomersQuery
            ->with('party:id,name')
            ->get()
            ->map(function ($item) {

                return (object) [
                    'name'  => $item->party->name ?? 'Unknown',
                    'total' => (float) $item->total,
                ];
            });

        $topSuppliersQuery = (clone $expenseQuery)
            ->whereNotNull('party_id')
            ->select(
                'party_id',
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('party_id')
            ->orderByDesc('total')
            ->limit(10);


        $topSuppliers = $topSuppliersQuery
            ->with('party:id,name')
            ->get()
            ->map(function ($item) {

                return (object) [
                    'name'  => $item->party->name ?? 'Unknown',
                    'total' => (float) $item->total,
                ];
            });
        $topIncomeReceipts = (clone $salesQuery)
            ->with('party:id,name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
        $topExpenseReceipts = (clone $expenseQuery)
            ->with('party:id,name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
        $recentReceipts = (clone $receiptQuery)
            ->with('party:id,name')
            ->whereIn('type', [
                'Sales-Order',
                'Expense'
            ])
            ->latest('id')
            ->limit(10)
            ->get();
        $recentPayments = ReceiptPayment::query()
            ->with([
                'receipt:id,receipt_no,company_id',
                'paymentType:id,name'
            ])
            ->when(
                !$isSuperAdmin,
                function ($query) use ($user) {

                    $query->where('created_by', $user->id);
                }
            )
            ->latest('id')
            ->limit(10)
            ->get();
        $recentTransactions = AccountTransaction::query()
            ->with('account:id,account_name')
            ->when(
                !$isSuperAdmin,
                function ($query) use ($user) {

                    $query->where('created_by', $user->id);
                }
            )
            ->latest('id')
            ->limit(10)
            ->get();
        $package = null;

        if (method_exists($user, 'package')) {

            $package = $user->package()
                ->with('package')
                ->latest()
                ->first();
        }
        return view(
            'BackEnd.Dashboard.dashboard',
            compact(

                'package',

                // Today
                'todayIncome',
                'todayExpense',
                'todayProfit',

                // Month
                'monthIncome',
                'monthExpense',

                // Total
                'totalIncome',
                'totalExpense',
                'grossProfit',

                // Balance
                'currentBalance',
                'totalReceivable',
                'totalPayable',

                // Counts
                'totalCustomer',
                'totalSupplier',
                'totalBranch',
                'totalAccount',
                'totalReceipt',
                'totalPayment',

                // Summary
                'paymentSummary',
                'receiptSummary',

                // Growth
                'incomeGrowth',
                'expenseGrowth',

                // Top
                'topCustomers',
                'topSuppliers',
                'topIncomeReceipts',
                'topExpenseReceipts',

                // Recent
                'recentReceipts',
                'recentPayments',
                'recentTransactions'
            )
        );
    }
}
