<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Party;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\ReceiptPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $user = auth()->user();
        $companyId = $user->company_id;
        if (!$user->hasRole('Super-Admin') && empty($companyId)) {

            return view('BackEnd.Dashboard.dashboard', [
                'todayIncome' => 0,
                'todayExpense' => 0,
                'todayProfit' => 0,

                'weekIncome' => 0,
                'weekExpense' => 0,
                'weekProfit' => 0,

                'monthIncome' => 0,
                'monthExpense' => 0,
                'monthProfit' => 0,

                'sixMonthIncome' => 0,
                'sixMonthExpense' => 0,
                'sixMonthProfit' => 0,

                'yearIncome' => 0,
                'yearExpense' => 0,
                'yearProfit' => 0,

                'totalIncome' => 0,
                'totalExpense' => 0,
                'grossProfit' => 0,

                'currentBalance' => 0,

                'totalReceivable' => 0,
                'totalPayable' => 0,

                'totalCustomer' => 0,
                'totalSupplier' => 0,

                'totalBranch' => 0,
                'totalCategory' => 0,
                'totalAccount' => 0,

                'totalReceipt' => 0,
                'totalPayment' => 0,

                'pendingReceipt' => 0,
                'partialReceipt' => 0,
                'paidReceipt' => 0,

                'recentReceipts' => collect(),
                'recentPayments' => collect(),
                'recentTransactions' => collect(),

                'monthLabel' => [],
                'monthlyIncome' => [],
                'monthlyExpense' => [],
                'monthlyProfit' => [],

                'cashFlowLabel' => [],
                'cashIn' => [],
                'cashOut' => [],

                'expenseCategory' => collect(),
                'expenseCategoryLabel' => [],
                'expenseCategoryAmount' => [],

                'incomeCategory' => collect(),
                'incomeCategoryLabel' => [],
                'incomeCategoryAmount' => [],

                'dashboardExpensePie' => [
                    'labels' => [],
                    'data' => [],
                ],

                'dashboardIncomePie' => [
                    'labels' => [],
                    'data' => [],
                ],

                'topCustomers' => collect(),
                'topSuppliers' => collect(),

                'topIncomeReceipts' => collect(),
                'topExpenseReceipts' => collect(),

                'incomeGrowth' => 0,
                'expenseGrowth' => 0,

                'paymentSummary' => [
                    'paid' => 0,
                    'partial' => 0,
                    'pending' => 0,
                ],

                'receiptSummary' => [
                    'income' => 0,
                    'expense' => 0,
                    'completed' => 0,
                    'draft' => 0,
                    'cancelled' => 0,
                ],
            ]);
        }

        $today = Carbon::today();
        $week = Carbon::today()->subDays(6);
        $month = Carbon::today()->subDays(29);
        $sixMonth = Carbon::today()->subMonths(6);
        $year = Carbon::today()->startOfYear();

        /*
    |--------------------------------------------------------------------------
    | Base Queries
    |--------------------------------------------------------------------------
    */

        $receipt = Receipt::query()->where('status', 'Completed');
        $account = Account::query();
        $party = Party::query();
        $branch = Branch::query();
        $category = Category::query();
        $transaction = AccountTransaction::query();
        $payment = ReceiptPayment::query();

        /*
    |--------------------------------------------------------------------------
    | Company Filter
    |--------------------------------------------------------------------------
    */

        if (!$user->hasRole('Super-Admin')) {

            $receipt->where('company_id', $companyId);

            $account->where('company_id', $companyId);

            $party->where('company_id', $companyId);

            $branch->where('company_id', $companyId);

            $category->where('company_id', $companyId);

            $transaction->where('company_id', $companyId);

            $payment->whereHas('receipt', function ($q) use ($companyId) {

                $q->where('company_id', $companyId);
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Today Summary
    |--------------------------------------------------------------------------
    */

        $todayIncome = (clone $receipt)
            ->where('type', 'Income')
            ->whereDate('receipt_date', $today)
            ->sum('total_amount');

        $todayExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->whereDate('receipt_date', $today)
            ->sum('total_amount');

        $todayProfit = $todayIncome - $todayExpense;

        /*
    |--------------------------------------------------------------------------
    | Last 7 Days
    |--------------------------------------------------------------------------
    */

        $weekIncome = (clone $receipt)
            ->where('type', 'Income')
            ->whereDate('receipt_date', '>=', $week)
            ->sum('total_amount');

        $weekExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->whereDate('receipt_date', '>=', $week)
            ->sum('total_amount');

        $weekProfit = $weekIncome - $weekExpense;

        /*
    |--------------------------------------------------------------------------
    | Last 30 Days
    |--------------------------------------------------------------------------
    */

        $monthIncome = (clone $receipt)
            ->where('type', 'Income')
            ->whereDate('receipt_date', '>=', $month)
            ->sum('total_amount');

        $monthExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->whereDate('receipt_date', '>=', $month)
            ->sum('total_amount');

        $monthProfit = $monthIncome - $monthExpense;

        /*
    |--------------------------------------------------------------------------
    | Last 6 Months
    |--------------------------------------------------------------------------
    */

        $sixMonthIncome = (clone $receipt)
            ->where('type', 'Income')
            ->whereDate('receipt_date', '>=', $sixMonth)
            ->sum('total_amount');

        $sixMonthExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->whereDate('receipt_date', '>=', $sixMonth)
            ->sum('total_amount');

        $sixMonthProfit = $sixMonthIncome - $sixMonthExpense;

        /*
    |--------------------------------------------------------------------------
    | Current Year
    |--------------------------------------------------------------------------
    */

        $yearIncome = (clone $receipt)
            ->where('type', 'Income')
            ->whereDate('receipt_date', '>=', $year)
            ->sum('total_amount');

        $yearExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->whereDate('receipt_date', '>=', $year)
            ->sum('total_amount');

        $yearProfit = $yearIncome - $yearExpense;
        /*
    |--------------------------------------------------------------------------
    | Overall Summary
    |--------------------------------------------------------------------------
    */

        $totalIncome = (clone $receipt)
            ->where('type', 'Income')
            ->sum('total_amount');

        $totalExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->sum('total_amount');

        $grossProfit = $totalIncome - $totalExpense;

        /*
    |--------------------------------------------------------------------------
    | Account Summary
    |--------------------------------------------------------------------------
    */

        $currentBalance = (clone $account)
            ->sum('current_balance');

        /*
    |--------------------------------------------------------------------------
    | Receivable / Payable
    |--------------------------------------------------------------------------
    */

        $totalReceivable = (clone $receipt)
            ->where('type', 'Income')
            ->sum('due_amount');

        $totalPayable = (clone $receipt)
            ->where('type', 'Expense')
            ->sum('due_amount');

        /*
    |--------------------------------------------------------------------------
    | Party Summary
    |--------------------------------------------------------------------------
    */

        $totalCustomer = (clone $party)
            ->whereIn('type', ['Income', 'Both'])
            ->count();

        $totalSupplier = (clone $party)
            ->whereIn('type', ['Expense', 'Both'])
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Master Data Summary
    |--------------------------------------------------------------------------
    */

        $totalBranch = (clone $branch)->count();

        $totalCategory = (clone $category)->count();

        $totalAccount = (clone $account)->count();

        /*
    |--------------------------------------------------------------------------
    | Receipt Summary
    |--------------------------------------------------------------------------
    */

        $totalReceipt = (clone $receipt)->count();

        $totalPayment = (clone $payment)->count();

        $pendingReceipt = (clone $receipt)
            ->where('payment_status', 'Pending')
            ->count();

        $partialReceipt = (clone $receipt)
            ->where('payment_status', 'Partial')
            ->count();

        $paidReceipt = (clone $receipt)
            ->where('payment_status', 'Paid')
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Recent Receipts
    |--------------------------------------------------------------------------
    */

        $recentReceipts = (clone $receipt)
            ->with([
                'party',
                'branch',
                'user',
            ])
            ->latest()
            ->take(10)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Recent Payments
    |--------------------------------------------------------------------------
    */

        $recentPayments = (clone $payment)
            ->with([
                'receipt',
                'paymentType',
                'user',
            ])
            ->latest()
            ->take(10)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Recent Account Transactions
    |--------------------------------------------------------------------------
    */

        $recentTransactions = (clone $transaction)
            ->with([
                'account',
                'user',
            ])
            ->latest()
            ->take(10)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Monthly Income / Expense / Profit (Last 12 Months)
    |--------------------------------------------------------------------------
    */

        $monthLabel = [];
        $monthlyIncome = [];
        $monthlyExpense = [];
        $monthlyProfit = [];

        for ($i = 11; $i >= 0; $i--) {

            $date = Carbon::now()->subMonths($i);

            $monthLabel[] = $date->format('M Y');

            $incomeQuery = Receipt::query()
                ->where('status', 'Completed')
                ->where('type', 'Income')
                ->whereYear('receipt_date', $date->year)
                ->whereMonth('receipt_date', $date->month);

            $expenseQuery = Receipt::query()
                ->where('status', 'Completed')
                ->where('type', 'Expense')
                ->whereYear('receipt_date', $date->year)
                ->whereMonth('receipt_date', $date->month);

            if (!$user->hasRole('Super-Admin')) {

                $incomeQuery->where('company_id', $companyId);

                $expenseQuery->where('company_id', $companyId);
            }

            $income = $incomeQuery->sum('total_amount');

            $expense = $expenseQuery->sum('total_amount');

            $monthlyIncome[] = $income;

            $monthlyExpense[] = $expense;

            $monthlyProfit[] = $income - $expense;
        }

        /*
    |--------------------------------------------------------------------------
    | Daily Cash Flow (Last 30 Days)
    |--------------------------------------------------------------------------
    */

        $cashFlowLabel = [];

        $cashIn = [];

        $cashOut = [];

        for ($i = 29; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $cashFlowLabel[] = $date->format('d M');

            $cashInQuery = Receipt::query()
                ->where('status', 'Completed')
                ->where('type', 'Income')
                ->whereDate('receipt_date', $date);

            $cashOutQuery = Receipt::query()
                ->where('status', 'Completed')
                ->where('type', 'Expense')
                ->whereDate('receipt_date', $date);

            if (!$user->hasRole('Super-Admin')) {

                $cashInQuery->where('company_id', $companyId);

                $cashOutQuery->where('company_id', $companyId);
            }

            $cashIn[] = $cashInQuery->sum('total_amount');

            $cashOut[] = $cashOutQuery->sum('total_amount');
        }
        /*
    |--------------------------------------------------------------------------
    | Expense Category Chart
    |--------------------------------------------------------------------------
    */

        $expenseCategory = ReceiptItem::join('receipts', 'receipt_items.receipt_id', '=', 'receipts.id')
            ->join('categories', 'receipt_items.category_id', '=', 'categories.id')
            ->selectRaw('categories.id, categories.name, SUM(receipt_items.amount) as total')
            ->where('receipts.status', 'Completed')
            ->where('receipts.type', 'Expense');

        if (!$user->hasRole('Super-Admin')) {
            $expenseCategory->where('receipts.company_id', $companyId);
        }

        $expenseCategory = $expenseCategory
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $expenseCategoryLabel = $expenseCategory->pluck('name')->toArray();

        $expenseCategoryAmount = $expenseCategory->pluck('total')->toArray();

        /*
    |--------------------------------------------------------------------------
    | Income Category Chart
    |--------------------------------------------------------------------------
    */

        $incomeCategory = ReceiptItem::join('receipts', 'receipt_items.receipt_id', '=', 'receipts.id')
            ->join('categories', 'receipt_items.category_id', '=', 'categories.id')
            ->selectRaw('categories.id, categories.name, SUM(receipt_items.amount) as total')
            ->where('receipts.status', 'Completed')
            ->where('receipts.type', 'Income');

        if (!$user->hasRole('Super-Admin')) {
            $incomeCategory->where('receipts.company_id', $companyId);
        }

        $incomeCategory = $incomeCategory
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $incomeCategoryLabel = $incomeCategory->pluck('name')->toArray();

        $incomeCategoryAmount = $incomeCategory->pluck('total')->toArray();

        /*
    |--------------------------------------------------------------------------
    | Dashboard Pie Chart Data
    |--------------------------------------------------------------------------
    */

        $dashboardExpensePie = [
            'labels' => $expenseCategoryLabel,
            'data'   => $expenseCategoryAmount,
        ];

        $dashboardIncomePie = [
            'labels' => $incomeCategoryLabel,
            'data'   => $incomeCategoryAmount,
        ];
        /*
    |--------------------------------------------------------------------------
    | Top Customers
    |--------------------------------------------------------------------------
    */

        $topCustomers = Receipt::join('parties', 'receipts.party_id', '=', 'parties.id')
            ->selectRaw('parties.id, parties.name, SUM(receipts.total_amount) as total')
            ->where('receipts.status', 'Completed')
            ->where('receipts.type', 'Income');

        if (!$user->hasRole('Super-Admin')) {
            $topCustomers->where('receipts.company_id', $companyId);
        }

        $topCustomers = $topCustomers
            ->groupBy('parties.id', 'parties.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Top Suppliers
    |--------------------------------------------------------------------------
    */

        $topSuppliers = Receipt::join('parties', 'receipts.party_id', '=', 'parties.id')
            ->selectRaw('parties.id, parties.name, SUM(receipts.total_amount) as total')
            ->where('receipts.status', 'Completed')
            ->where('receipts.type', 'Expense');

        if (!$user->hasRole('Super-Admin')) {
            $topSuppliers->where('receipts.company_id', $companyId);
        }

        $topSuppliers = $topSuppliers
            ->groupBy('parties.id', 'parties.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Growth Percentage
    |--------------------------------------------------------------------------
    */

        $incomeGrowth = 0;

        if ($monthIncome > 0) {
            $incomeGrowth = round((($todayIncome * 30) / $monthIncome) * 100, 2);
        }

        $expenseGrowth = 0;

        if ($monthExpense > 0) {
            $expenseGrowth = round((($todayExpense * 30) / $monthExpense) * 100, 2);
        }

        /*
    |--------------------------------------------------------------------------
    | Top Income Receipts
    |--------------------------------------------------------------------------
    */

        $topIncomeReceipts = Receipt::with([
            'party',
            'branch',
            'user'
        ])
            ->where('status', 'Completed')
            ->where('type', 'Income');

        if (!$user->hasRole('Super-Admin')) {
            $topIncomeReceipts->where('company_id', $companyId);
        }

        $topIncomeReceipts = $topIncomeReceipts
            ->orderByDesc('total_amount')
            ->take(5)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Top Expense Receipts
    |--------------------------------------------------------------------------
    */

        $topExpenseReceipts = Receipt::with([
            'party',
            'branch',
            'user'
        ])
            ->where('status', 'Completed')
            ->where('type', 'Expense');

        if (!$user->hasRole('Super-Admin')) {
            $topExpenseReceipts->where('company_id', $companyId);
        }

        $topExpenseReceipts = $topExpenseReceipts
            ->orderByDesc('total_amount')
            ->take(5)
            ->get();
        /*
    |--------------------------------------------------------------------------
    | Payment Summary
    |--------------------------------------------------------------------------
    */

        $paymentSummary = [

            'paid' => (clone $receipt)
                ->where('payment_status', 'Paid')
                ->count(),

            'partial' => (clone $receipt)
                ->where('payment_status', 'Partial')
                ->count(),

            'pending' => (clone $receipt)
                ->where('payment_status', 'Pending')
                ->count(),

        ];

        /*
    |--------------------------------------------------------------------------
    | Receipt Summary
    |--------------------------------------------------------------------------
    */

        $receiptSummary = [

            'income' => (clone $receipt)
                ->where('type', 'Income')
                ->count(),

            'expense' => (clone $receipt)
                ->where('type', 'Expense')
                ->count(),

            'completed' => (clone $receipt)
                ->count(),

            'draft' => Receipt::query()
                ->when(
                    !$user->hasRole('Super-Admin'),
                    fn($q) => $q->where('company_id', $companyId)
                )
                ->where('status', 'Draft')
                ->count(),

            'cancelled' => Receipt::query()
                ->when(
                    !$user->hasRole('Super-Admin'),
                    fn($q) => $q->where('company_id', $companyId)
                )
                ->where('status', 'Cancelled')
                ->count(),

        ];

        /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

        return view('BackEnd.Dashboard.dashboard', compact(

            'todayIncome',
            'todayExpense',
            'todayProfit',

            'weekIncome',
            'weekExpense',
            'weekProfit',

            'monthIncome',
            'monthExpense',
            'monthProfit',

            'sixMonthIncome',
            'sixMonthExpense',
            'sixMonthProfit',

            'yearIncome',
            'yearExpense',
            'yearProfit',

            'totalIncome',
            'totalExpense',
            'grossProfit',

            'currentBalance',

            'totalReceivable',
            'totalPayable',

            'totalCustomer',
            'totalSupplier',

            'totalBranch',
            'totalCategory',
            'totalAccount',

            'totalReceipt',
            'totalPayment',

            'pendingReceipt',
            'partialReceipt',
            'paidReceipt',

            'recentReceipts',
            'recentPayments',
            'recentTransactions',

            'monthLabel',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyProfit',

            'cashFlowLabel',
            'cashIn',
            'cashOut',

            'expenseCategory',
            'expenseCategoryLabel',
            'expenseCategoryAmount',

            'incomeCategory',
            'incomeCategoryLabel',
            'incomeCategoryAmount',

            'dashboardExpensePie',
            'dashboardIncomePie',

            'topCustomers',
            'topSuppliers',

            'topIncomeReceipts',
            'topExpenseReceipts',

            'incomeGrowth',
            'expenseGrowth',

            'paymentSummary',
            'receiptSummary'
        ));
    }
}
