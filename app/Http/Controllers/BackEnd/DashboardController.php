<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Party;
use App\Models\Product;
use App\Models\Receipt;
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

        $receiptScope = function ($query) use ($user) {
            if (!$user->hasRole('Super-Admin')) {
                $query->where('company_id', $user->company_id);
            }

            return $query;
        };

        $productQuery = Product::query()->where('current_stock', '>', 0);

        if (!$user->hasRole('Super-Admin')) {
            $productQuery->where('company_id', $user->company_id);
        }

        $totalStockValue = $productQuery
            ->selectRaw('COALESCE(SUM(current_stock * purchase_price), 0) as total')
            ->value('total');

        $todaySale = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->where('receipts.type', 'Sales-Order')
            // ->where('receipts.status', 'Completed')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('receipts.company_id', $user->company_id);
            })
            ->sum('receipt_payments.amount');

        $todayDirectIncomes = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->where('receipts.type', 'Direct-Income')
            // ->where('receipts.status', 'Completed')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('receipts.company_id', $user->company_id);
            })
            ->sum('receipt_payments.amount');

        $todaySalesRevenue = Receipt::where('type', 'Sales-Order')
            ->where('status', 'Completed')
            ->whereDate('receipt_date', $today)
            ->tap($receiptScope)
            ->sum('total_amount');

        $todayDirectIncome = Receipt::where('type', 'Direct-Income')
            ->where('status', 'Completed')
            ->whereDate('receipt_date', $today)
            ->tap($receiptScope)
            ->sum('total_amount');

        $todayCOGS = DB::table('receipt_items')
            ->join('receipts', 'receipts.id', '=', 'receipt_items.receipt_id')
            ->whereDate('receipts.receipt_date', $today)
            ->where('receipts.type', 'Sales-Order')
            ->where('receipts.status', 'Completed')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('receipts.company_id', $user->company_id)
                    ->where('receipts.branch_id', $user->branch_id);
            })
            ->sum(DB::raw('receipt_items.qty * receipt_items.cost_price'));

        $todayProfit = round(
            (float) $todaySalesRevenue
                + (float) $todayDirectIncome
                - (float) $todayCOGS,
            2
        );

        $todaySales = Receipt::whereDate('receipt_date', $today)
            ->whereIn('type', ['Sales-Order', 'Direct-Income'])
            ->where('status', 'Completed')
            ->tap($receiptScope)
            ->sum('total_amount');

        $todayPurchase = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->where('receipts.type', 'Purchase-Order')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('receipts.company_id', $user->company_id);
            })
            ->sum('receipt_payments.amount');

        $todayPurchases = Receipt::whereDate('receipt_date', $today)
            ->where('type', 'Purchase-Order')
            ->where('status', 'Completed')
            ->tap($receiptScope)
            ->sum('total_amount');

        $todayReceived = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->whereIn('receipts.type', ['Sales-Order', 'Direct-Income', 'Income'])
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('receipts.company_id', $user->company_id);
            })
            ->sum('receipt_payments.amount');

        $todayPaid = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->whereIn('receipts.type', ['Purchase-Order', 'Expense'])
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('receipts.company_id', $user->company_id);
            })
            ->sum('receipt_payments.amount');

        $todayExpense = DB::table('receipt_payments')
            ->join('receipts', 'receipts.id', '=', 'receipt_payments.receipt_id')
            ->whereDate('receipt_payments.payment_date', $today)
            ->where('receipts.type', 'Expense')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('receipts.company_id', $user->company_id);
            })
            ->sum('receipt_payments.amount');

        $todayExpenses = Receipt::whereDate('receipt_date', $today)
            ->where('type', 'Expense')
            ->where('status', 'Completed')
            ->tap($receiptScope)
            ->sum('total_amount');

        $receivable = Receipt::whereIn('type', [
            'Sales-Order',
            'Direct-Income',
            'Income',
        ])->where('status', 'Completed')->tap($receiptScope)->sum('due_amount');

        $payable = Receipt::whereIn('type', [
            'Purchase-Order',
            'Expense',
        ])->where('status', 'Completed')->tap($receiptScope)->sum('due_amount');

        $customers = Party::where('type', 'Customer')
            ->where('status', 'Active')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })->count();

        $suppliers = Party::where('type', 'Supplier')
            ->where('status', 'Active')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })->count();

        $branches = Branch::when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->count();

        $accountQuery = Account::where('status', 'Active');

        if (!$user->hasRole('Super-Admin')) {
            $accountQuery->where('company_id', $user->company_id);
        }

        $accounts = $accountQuery->with('paymentType')->orderBy('account_name')->get();

        $totalAccountBalance = $accounts->sum(function ($account) {
            return (float) $account->current_balance;
        });

        $todayAccountCreditQuery = AccountTransaction::whereDate(
            'transaction_date',
            $today
        );

        if (!$user->hasRole('Super-Admin')) {
            $todayAccountCreditQuery->where('company_id', $user->company_id);
        }

        $todayAccountCredit = $todayAccountCreditQuery->sum('credit');

        $todayAccountDebitQuery = AccountTransaction::whereDate(
            'transaction_date',
            $today
        );

        if (!$user->hasRole('Super-Admin')) {
            $todayAccountDebitQuery->where('company_id', $user->company_id);
        }

        $todayAccountDebit = $todayAccountDebitQuery->sum('debit');
        $todayNetCashFlow = $todayAccountCredit - $todayAccountDebit;
        $accountTransactionsQuery = AccountTransaction::with('account')->whereDate('transaction_date', $today);

        if (!$user->hasRole('Super-Admin')) {
            $accountTransactionsQuery->where('company_id', $user->company_id);
        }

        $todayAccountTransactions = $accountTransactionsQuery->orderByDesc('id')->get();

        $chartLabels = [];
        $salesData = [];
        $purchaseData = [];
        $expenseData = [];
        $directIncomeData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $chartLabels[] = $month->format('M Y');

            $salesQuery = Receipt::where('type', 'Sales-Order')
                ->where('status', 'Completed')
                ->whereBetween('receipt_date', [
                    $month->toDateString(),
                    $monthEnd->toDateString(),
                ]);

            $purchaseQuery = Receipt::where('type', 'Purchase-Order')
                ->where('status', 'Completed')
                ->whereBetween('receipt_date', [
                    $month->toDateString(),
                    $monthEnd->toDateString(),
                ]);

            $expenseQuery = Receipt::where('type', 'Expense')
                ->where('status', 'Completed')
                ->whereBetween('receipt_date', [
                    $month->toDateString(),
                    $monthEnd->toDateString(),
                ]);

            $directIncomeQuery = Receipt::where('type', 'Direct-Income')
                ->where('status', 'Completed')
                ->whereBetween('receipt_date', [
                    $month->toDateString(),
                    $monthEnd->toDateString(),
                ]);

            if (!$user->hasRole('Super-Admin')) {
                $filterCompanyBranch = function ($query) use ($user) {
                    $query->where('company_id', $user->company_id);
                };

                $filterCompanyBranch($salesQuery);
                $filterCompanyBranch($purchaseQuery);
                $filterCompanyBranch($expenseQuery);
                $filterCompanyBranch($directIncomeQuery);
            }

            $salesData[] = round((float) $salesQuery->sum('total_amount'), 2);
            $purchaseData[] = round((float) $purchaseQuery->sum('total_amount'), 2);
            $expenseData[] = round((float) $expenseQuery->sum('total_amount'), 2);
            $directIncomeData[] = round((float) $directIncomeQuery->sum('total_amount'), 2);
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

        $totalIncome = Receipt::where('type', 'Sales-Order')->tap($receiptScope)->sum('total_amount');
        $totalExpense = Receipt::where('type', 'Purchase-Order')->tap($receiptScope)->sum('total_amount');

        $package = null;

        if (method_exists($user, 'package')) {
            $package = $user->package()->with('package')->latest()->first();
        }

        return view(
            'BackEnd.Dashboard.dashboard',
            compact(
                'package',
                'totalStockValue',
                'todaySale',
                'todayDirectIncomes',
                'todaySales',
                'todayPurchase',
                'todayPurchases',
                'todayReceived',
                'todayPaid',
                'todayExpense',
                'todayExpenses',
                'todayDirectIncome',
                'todaySalesRevenue',
                'todayCOGS',
                'todayProfit',
                'receivable',
                'payable',
                'customers',
                'suppliers',
                'branches',
                'accounts',
                'totalAccountBalance',
                'todayAccountCredit',
                'todayAccountDebit',
                'todayNetCashFlow',
                'todayAccountTransactions',
                'chartLabels',
                'salesData',
                'purchaseData',
                'expenseData',
                'directIncomeData',
                'topCustomers',
                'topSuppliers',
                'totalIncome',
                'totalExpense'
            )
        );
    }
}
