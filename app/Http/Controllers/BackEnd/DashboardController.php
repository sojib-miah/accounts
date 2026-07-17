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
        $today = Carbon::today();
        $week = Carbon::today()->subDays(6);
        $month = Carbon::today()->subDays(29);
        $sixMonth = Carbon::today()->subMonths(6);
        $year = Carbon::today()->startOfYear();
        $receipt = Receipt::where('status', 'Completed');
        $todayIncome = (clone $receipt)->where('type', 'Income')->whereDate('receipt_date', $today)->sum('total_amount');
        $todayExpense = (clone $receipt)->where('type', 'Expense')->whereDate('receipt_date', $today)->sum('total_amount');
        $todayProfit = $todayIncome - $todayExpense;
        $weekIncome = (clone $receipt)->where('type', 'Income')->whereDate('receipt_date', '>=', $week)->sum('total_amount');
        $weekExpense = (clone $receipt)->where('type', 'Expense')->whereDate('receipt_date', '>=', $week)->sum('total_amount');
        $weekProfit = $weekIncome - $weekExpense;
        $monthIncome = (clone $receipt)->where('type', 'Income')->whereDate('receipt_date', '>=', $month)->sum('total_amount');
        $monthExpense = (clone $receipt)->where('type', 'Expense')->whereDate('receipt_date', '>=', $month)->sum('total_amount');
        $monthProfit = $monthIncome - $monthExpense;
        $sixMonthIncome = (clone $receipt)->where('type', 'Income')->whereDate('receipt_date', '>=', $sixMonth)->sum('total_amount');
        $sixMonthExpense = (clone $receipt)->where('type', 'Expense')->whereDate('receipt_date', '>=', $sixMonth)->sum('total_amount');
        $sixMonthProfit = $sixMonthIncome - $sixMonthExpense;
        $yearIncome = (clone $receipt)->where('type', 'Income')->whereDate('receipt_date', '>=', $year)->sum('total_amount');
        $yearExpense = (clone $receipt)->where('type', 'Expense')->whereDate('receipt_date', '>=', $year)->sum('total_amount');
        $yearProfit = $yearIncome - $yearExpense;
        $totalIncome = (clone $receipt)->where('type', 'Income')->sum('total_amount');
        $totalExpense = (clone $receipt)->where('type', 'Expense')->sum('total_amount');
        $grossProfit = $totalIncome - $totalExpense;
        $currentBalance = Account::sum('current_balance');
        $totalReceivable = Receipt::where('type', 'Income')->sum('due_amount');
        $totalPayable = Receipt::where('type', 'Expense')->sum('due_amount');
        $totalCustomer = Party::whereIn('type', ['Income'])->count();
        $totalSupplier = Party::whereIn('type', ['Expense'])->count();
        $totalBranch = Branch::count();
        $totalCategory = Category::count();
        $totalAccount = Account::count();
        $totalReceipt = Receipt::count();
        $totalPayment = ReceiptPayment::count();
        $pendingReceipt = Receipt::where('payment_status', 'Pending')->count();
        $partialReceipt = Receipt::where('payment_status', 'Partial')->count();
        $paidReceipt = Receipt::where('payment_status', 'Paid')->count();
        $recentReceipts = Receipt::with(['party', 'branch'])->latest()->take(10)->get();
        $recentPayments = ReceiptPayment::with(['receipt', 'paymentType'])->latest()->take(10)->get();
        $recentTransactions = AccountTransaction::with(['account'])->latest()->take(10)->get();
        $monthLabel = [];
        $monthlyIncome = [];
        $monthlyExpense = [];
        $monthlyProfit = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel[] = $date->format('M Y');
            $income = Receipt::where('status', 'Completed')->where('type', 'Income')->whereYear('receipt_date', $date->year)->whereMonth('receipt_date', $date->month)->sum('total_amount');
            $expense = Receipt::where('status', 'Completed')->where('type', 'Expense')->whereYear('receipt_date', $date->year)->whereMonth('receipt_date', $date->month)->sum('total_amount');
            $monthlyIncome[] = $income;
            $monthlyExpense[] = $expense;
            $monthlyProfit[] = $income - $expense;
        }
        $cashFlowLabel = [];
        $cashIn = [];
        $cashOut = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $cashFlowLabel[] = $date->format('d M');
            $cashIn[] = Receipt::where('status', 'Completed')->where('type', 'Income')->whereDate('receipt_date', $date)->sum('total_amount');
            $cashOut[] = Receipt::where('status', 'Completed')->where('type', 'Expense')->whereDate('receipt_date', $date)->sum('total_amount');
        }
        $expenseCategory = ReceiptItem::join('receipts', 'receipt_items.receipt_id', '=', 'receipts.id')->join('categories', 'receipt_items.category_id', '=', 'categories.id')->selectRaw('categories.name,SUM(receipt_items.amount) total')->where('receipts.type', 'Expense')->where('receipts.status', 'Completed')->groupBy('categories.id', 'categories.name')->orderByDesc('total')->limit(10)->get();
        $expenseCategoryLabel = $expenseCategory->pluck('name');
        $expenseCategoryAmount = $expenseCategory->pluck('total');

        $incomeCategory = ReceiptItem::join(
            'receipts',
            'receipt_items.receipt_id',
            '=',
            'receipts.id'
        )
            ->join(
                'categories',
                'receipt_items.category_id',
                '=',
                'categories.id'
            )
            ->selectRaw('categories.name,SUM(receipt_items.amount) total')->where('receipts.type', 'Income')->where('receipts.status', 'Completed')->groupBy('categories.id', 'categories.name')->orderByDesc('total')->limit(10)->get();
        $incomeCategoryLabel = $incomeCategory->pluck('name');
        $incomeCategoryAmount = $incomeCategory->pluck('total');
        $topCustomers = Receipt::join(
            'parties',
            'receipts.party_id',
            '=',
            'parties.id'
        )->selectRaw('parties.name,SUM(receipts.total_amount) total')->where('receipts.type', 'Income')->where('receipts.status', 'Completed')->groupBy('parties.id', 'parties.name')->orderByDesc('total')->limit(10)->get();

        $topSuppliers = Receipt::join(
            'parties',
            'receipts.party_id',
            '=',
            'parties.id'
        )->selectRaw('parties.name,SUM(receipts.total_amount) total')->where('receipts.type', 'Expense')->where('receipts.status', 'Completed')->groupBy('parties.id', 'parties.name')->orderByDesc('total')->limit(10)->get();


        $incomeGrowth = $monthIncome > 0 ? round((($todayIncome * 30) / $monthIncome) * 100, 2) : 0;
        $expenseGrowth = $monthExpense > 0 ? round((($todayExpense * 30) / $monthExpense) * 100, 2) : 0;

        $topIncomeReceipts = Receipt::with(['party'])->where('status', 'Completed')->where('type', 'Income')->orderByDesc('total_amount')->take(5)->get();

        $topExpenseReceipts = Receipt::with(['party'])->where('status', 'Completed')->where('type', 'Expense')->orderByDesc('total_amount')->take(5)->get();

        $paymentSummary = [
            'paid' => Receipt::where('payment_status', 'Paid')->count(),
            'partial' => Receipt::where('payment_status', 'Partial')->count(),
            'pending' => Receipt::where('payment_status', 'Pending')->count(),
        ];
        $receiptSummary = [
            'income' => Receipt::where('type', 'Income')->count(),
            'expense' => Receipt::where('type', 'Expense')->count(),
            'completed' => Receipt::where('status', 'Completed')->count(),
            'draft' => Receipt::where('status', 'Draft')->count(),
            'cancelled' => Receipt::where('status', 'Cancelled')->count(),
        ];

        return view(
            'BackEnd.Dashboard.dashboard',
            compact(
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

                'topCustomers',
                'topSuppliers',

                'topIncomeReceipts',
                'topExpenseReceipts',

                'incomeGrowth',
                'expenseGrowth',

                'paymentSummary',
                'receiptSummary'

            )
        );
    }
}
