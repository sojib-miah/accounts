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

class DashboardController extends Controller
{
    public function index()
    {

        $user = auth()->user();
        $companyId = $user->company_id;
        if (!$user->hasRole('Super-Admin') && empty($companyId)) {

            $package = CompanyPackage::with('package')
                ->where('user_id', auth()->id())
                ->where('status', 'Active')
                ->first();

            return view('BackEnd.Dashboard.dashboard', [
                'package' => $package,
                'todayIncome' => 0,
                'todayExpense' => 0,
                'todayProfit' => 0,
                'monthIncome' => 0,
                'monthExpense' => 0,
                'totalIncome' => 0,
                'totalExpense' => 0,
                'grossProfit' => 0,

                'currentBalance' => 0,

                'totalReceivable' => 0,
                'totalPayable' => 0,

                'totalCustomer' => 0,
                'totalSupplier' => 0,

                'totalBranch' => 0,
                'totalAccount' => 0,

                'totalReceipt' => 0,
                'totalPayment' => 0,
                'recentReceipts' => collect(),
                'recentPayments' => collect(),
                'recentTransactions' => collect(),

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
        $month = Carbon::today()->subDays(29);
        $receipt = Receipt::query()->where('status', 'Completed');
        $account = Account::query();
        $party = Party::query();
        $branch = Branch::query();
        $category = Category::query();
        $transaction = AccountTransaction::query();
        $payment = ReceiptPayment::query();
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
        $todayIncome = (clone $receipt)
            ->where('type', 'Income')
            ->whereDate('receipt_date', $today)
            ->sum('total_amount');

        $todayExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->whereDate('receipt_date', $today)
            ->sum('total_amount');

        $todayProfit = $todayIncome - $todayExpense;

        $monthIncome = (clone $receipt)
            ->where('type', 'Income')
            ->whereDate('receipt_date', '>=', $month)
            ->sum('total_amount');

        $monthExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->whereDate('receipt_date', '>=', $month)
            ->sum('total_amount');

        $totalIncome = (clone $receipt)
            ->where('type', 'Income')
            ->sum('total_amount');

        $totalExpense = (clone $receipt)
            ->where('type', 'Expense')
            ->sum('total_amount');

        $grossProfit = $totalIncome - $totalExpense;

        $currentBalance = (clone $account)
            ->sum('current_balance');

        $totalReceivable = (clone $receipt)
            ->where('type', 'Income')
            ->sum('due_amount');

        $totalPayable = (clone $receipt)
            ->where('type', 'Expense')
            ->sum('due_amount');

        $totalCustomer = (clone $party)
            ->whereIn('type', ['Customer', 'Both'])
            ->count();

        $totalSupplier = (clone $party)
            ->whereIn('type', ['Supplier', 'Both'])
            ->count();

        $totalBranch = (clone $branch)->count();
        $totalAccount = (clone $account)->count();
        $totalReceipt = (clone $receipt)->count();

        $totalPayment = (clone $payment)->count();
        $recentReceipts = (clone $receipt)
            ->with([
                'party',
                'branch',
                'user',
            ])
            ->latest()
            ->take(10)
            ->get();
        $recentPayments = (clone $payment)
            ->with([
                'receipt',
                'paymentType',
                'user',
            ])
            ->latest()
            ->take(10)
            ->get();

        $recentTransactions = (clone $transaction)
            ->with([
                'account',
                'user',
            ])
            ->latest()
            ->take(10)
            ->get();
        for ($i = 11; $i >= 0; $i--) {

            $date = Carbon::now()->subMonths($i);

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
        }

        for ($i = 29; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);
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
        }
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

        $incomeGrowth = 0;

        if ($monthIncome > 0) {
            $incomeGrowth = round((($todayIncome * 30) / $monthIncome) * 100, 2);
        }

        $expenseGrowth = 0;

        if ($monthExpense > 0) {
            $expenseGrowth = round((($todayExpense * 30) / $monthExpense) * 100, 2);
        }

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

        $package = null;

        if (!auth()->user()->hasRole('Super-Admin')) {

            $package = CompanyPackage::with('package')
                ->where('user_id', auth()->id())
                ->where('status', 'Active')
                ->first();
        }

        return view('BackEnd.Dashboard.dashboard', compact(
            'todayIncome',
            'todayExpense',
            'todayProfit',
            'monthIncome',
            'monthExpense',
            'totalIncome',
            'totalExpense',
            'grossProfit',
            'currentBalance',
            'totalReceivable',
            'totalPayable',

            'totalCustomer',
            'totalSupplier',

            'totalBranch',
            'totalAccount',

            'totalReceipt',
            'totalPayment',
            'recentReceipts',
            'recentPayments',
            'recentTransactions',

            'topCustomers',
            'topSuppliers',

            'topIncomeReceipts',
            'topExpenseReceipts',

            'incomeGrowth',
            'expenseGrowth',

            'paymentSummary',
            'receiptSummary',
            'package',
        ));
    }
}
