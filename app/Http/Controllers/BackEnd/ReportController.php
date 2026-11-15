<?php

namespace App\Http\Controllers\BackEnd;

use App\Exports\DashboardExport;
use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Party;
use App\Models\Product;
use App\Models\ReceiptPayment;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function pdf()
    {
        $today = Carbon::today();
        $receipt = Receipt::where('status', 'Completed');
        $totalIncome = (clone $receipt)->where('type', 'Income')->sum('total_amount');
        $totalExpense = (clone $receipt)->where('type', 'Expense')->sum('total_amount');
        $grossProfit = $totalIncome - $totalExpense;
        $todayIncome = (clone $receipt)->where('type', 'Income')->whereDate('receipt_date', $today)->sum('total_amount');
        $todayExpense = (clone $receipt)->where('type', 'Expense')->whereDate('receipt_date', $today)->sum('total_amount');
        $currentBalance = Account::sum('current_balance');
        $totalReceivable = Receipt::where('type', 'Income')->sum('due_amount');
        $totalPayable = Receipt::where('type', 'Expense')->sum('due_amount');
        $totalCustomer = Party::where('type', 'Income')->count();
        $totalSupplier = Party::where('type', 'Expense')->count();
        $totalBranch = Branch::count();
        $totalAccount = Account::count();
        $recentReceipts = Receipt::with('party')->latest()->take(20)->get();
        $recentPayments = ReceiptPayment::with(['receipt', 'paymentType'])->latest()->take(20)->get();
        $recentTransactions = AccountTransaction::with('account')->latest()->take(20)->get();
        $pdf = Pdf::loadView(
            'BackEnd.Report.pdf',
            compact(
                'todayIncome',
                'todayExpense',
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
                'recentReceipts',
                'recentPayments',
                'recentTransactions'
            )
        );
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream(
            'Report_' . date('Ymd_His') . '.pdf'
        );
    }

    public function excel()
    {
        return Excel::download(
            new DashboardExport,
            'Report_' . date('Ymd_His') . '.xlsx'
        );
    }

    // Current Balance Details	Stock Details
    public function balance(Request $request)
    {
        $user = Auth::user();

        $isSuperAdmin = $user->hasRole('Super-Admin');
        $companyId = $isSuperAdmin ? $request->company_id : $user->company_id;
        $branchId = $isSuperAdmin ? $request->branch_id : $user->branch_id;
        $search = $request->search;

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $branches = Branch::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where('company_id', $user->company_id)
                ->where('id', $user->branch_id);
        })
            ->when($isSuperAdmin && $companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->orderBy('name')
            ->get();

        $accountQuery = Account::with([
            'company',
            'branch',
            'paymentType',
        ])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->where('company_id', $user->company_id)
                    ->where('branch_id', $user->branch_id);
            })
            ->when($isSuperAdmin && $companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($isSuperAdmin && $branchId, function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('account_name', 'like', "%{$search}%")
                        ->orWhere('account_number', 'like', "%{$search}%")
                        ->orWhere('account_holder_name', 'like', "%{$search}%");
                });
            });

        $totalOpeningBalance = (clone $accountQuery)->sum('opening_balance');

        $totalCurrentBalance = (clone $accountQuery)->sum('current_balance');

        $accounts = $accountQuery
            ->latest('id')
            ->paginate(15, ['*'], 'accounts_page')
            ->withQueryString();

        $productQuery = Product::with([
            'category',
            'brand',
        ])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->where('company_id', $user->company_id);
            })
            ->when($isSuperAdmin && $companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('product_code', 'like', "%{$search}%")
                        ->orWhere('model_no', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            });

        $totalProducts = (clone $productQuery)->count();

        $totalStock = (clone $productQuery)->sum('current_stock');

        $totalStockValue = (clone $productQuery)
            ->selectRaw('COALESCE(SUM(current_stock * purchase_price), 0) as stock_value')
            ->value('stock_value');

        $products = $productQuery
            ->orderBy('name')
            ->paginate(15, ['*'], 'products_page')
            ->withQueryString();

        $stockTransactionQuery = StockTransaction::with([
            'product',
            'branch',
            'creator',
            'receipt',
        ])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->where('company_id', $user->company_id)
                    ->where('branch_id', $user->branch_id);
            })
            ->when($isSuperAdmin && $companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($isSuperAdmin && $branchId, function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('product_code', 'like', "%{$search}%")
                        ->orWhere('model_no', 'like', "%{$search}%");
                });
            });

        $stockTransactions = $stockTransactionQuery
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(15, ['*'], 'stock_page')
            ->withQueryString();

        $transactions = AccountTransaction::with(['account', 'creator'])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->where('company_id', $user->company_id);
            })
            ->when($isSuperAdmin && $companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->latest('transaction_date')
            ->paginate(15);

        return view('BackEnd.Report.balance', compact(
            'companies',
            'branches',
            'accounts',
            'products',
            'stockTransactions',
            'totalOpeningBalance',
            'totalCurrentBalance',
            'totalProducts',
            'totalStock',
            'totalStockValue',
            'companyId',
            'branchId',
            'search',
            'transactions',
        ));
    }

    // sales 
    public function sales(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'last_7_days');
        $companyId = $request->company_id;
        $customerName = $request->customer_name;
        $search = $request->search;
        $paymentStatus = $request->payment_status;
        $status = $request->status;

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $salesQuery = Receipt::with([
            'company',
            'branch',
            'party',
            'creator',
        ])
            ->whereIn('type', ['Sales-Order', 'Direct-Income'])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($customerName, function ($query) use ($customerName) {
                $query->where(function ($q) use ($customerName) {
                    $q->where('customer_name', 'like', "%{$customerName}%")
                        ->orWhereHas('party', function ($partyQuery) use ($customerName) {
                            $partyQuery->where('name', 'like', "%{$customerName}%");
                        });
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('receipt_no', 'like', "%{$search}%")
                        ->orWhere('so_no', 'like', "%{$search}%")
                        ->orWhere('inv_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('party', function ($partyQuery) use ($search) {
                            $partyQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            });

        $today = Carbon::today();

        switch ($period) {
            case 'today':
                $salesQuery->whereDate('receipt_date', $today);
                break;

            case 'yesterday':
                $salesQuery->whereDate('receipt_date', $today->copy()->subDay());
                break;

            case 'last_7_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_15_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(14)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_30_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(29)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'this_month':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfMonth()->toDateString(),
                    $today->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'last_month':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                    $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'this_year':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfYear()->toDateString(),
                    $today->copy()->endOfYear()->toDateString(),
                ]);
                break;

            case 'custom':
                if ($request->filled('from_date')) {
                    $salesQuery->whereDate('receipt_date', '>=', $request->from_date);
                }

                if ($request->filled('to_date')) {
                    $salesQuery->whereDate('receipt_date', '<=', $request->to_date);
                }
                break;

            case 'all':
                // No date restriction
                break;

            default:
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                $period = 'last_7_days';
                break;
        }

        $summary = [
            'total_orders' => (clone $salesQuery)->count(),
            'total_qty' => (clone $salesQuery)->sum('total_qty'),
            'total_sales' => (clone $salesQuery)->sum('total_amount'),
            'total_paid' => (clone $salesQuery)->sum('paid_amount'),
            'total_due' => (clone $salesQuery)->sum('due_amount'),
            'total_discount' => (clone $salesQuery)->sum('discount'),
            'total_vat' => (clone $salesQuery)->sum('vat'),
        ];

        $sales = (clone $salesQuery)
            ->latest('receipt_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('BackEnd.Report.sales', compact(
            'companies',
            'sales',
            'summary',
            'period',
            'companyId',
            'customerName',
            'search',
            'paymentStatus',
            'status'
        ));
    }

    // purchase 
    public function purchase(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'last_7_days');
        $companyId = $request->company_id;
        $customerName = $request->customer_name;
        $search = $request->search;
        $paymentStatus = $request->payment_status;
        $status = $request->status;

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $salesQuery = Receipt::with([
            'company',
            'branch',
            'party',
            'creator',
        ])
            ->where('type', 'Purchase-Order')
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($customerName, function ($query) use ($customerName) {
                $query->where(function ($q) use ($customerName) {
                    $q->where('customer_name', 'like', "%{$customerName}%")
                        ->orWhereHas('party', function ($partyQuery) use ($customerName) {
                            $partyQuery->where('name', 'like', "%{$customerName}%");
                        });
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('receipt_no', 'like', "%{$search}%")
                        ->orWhere('so_no', 'like', "%{$search}%")
                        ->orWhere('inv_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('party', function ($partyQuery) use ($search) {
                            $partyQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            });

        $today = Carbon::today();

        switch ($period) {
            case 'today':
                $salesQuery->whereDate('receipt_date', $today);
                break;

            case 'yesterday':
                $salesQuery->whereDate('receipt_date', $today->copy()->subDay());
                break;

            case 'last_7_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_15_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(14)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_30_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(29)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'this_month':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfMonth()->toDateString(),
                    $today->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'last_month':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                    $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'this_year':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfYear()->toDateString(),
                    $today->copy()->endOfYear()->toDateString(),
                ]);
                break;

            case 'custom':
                if ($request->filled('from_date')) {
                    $salesQuery->whereDate('receipt_date', '>=', $request->from_date);
                }

                if ($request->filled('to_date')) {
                    $salesQuery->whereDate('receipt_date', '<=', $request->to_date);
                }
                break;

            case 'all':
                // No date restriction
                break;

            default:
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                $period = 'last_7_days';
                break;
        }

        $summary = [
            'total_orders' => (clone $salesQuery)->count(),
            'total_qty' => (clone $salesQuery)->sum('total_qty'),
            'total_sales' => (clone $salesQuery)->sum('total_amount'),
            'total_paid' => (clone $salesQuery)->sum('paid_amount'),
            'total_due' => (clone $salesQuery)->sum('due_amount'),
            'total_discount' => (clone $salesQuery)->sum('discount'),
            'total_vat' => (clone $salesQuery)->sum('vat'),
        ];

        $sales = (clone $salesQuery)
            ->latest('receipt_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('BackEnd.Report.purchase', compact(
            'companies',
            'sales',
            'summary',
            'period',
            'companyId',
            'customerName',
            'search',
            'paymentStatus',
            'status'
        ));
    }

    // expense 
    public function expense(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'last_7_days');
        $companyId = $request->company_id;
        $customerName = $request->customer_name;
        $search = $request->search;
        $paymentStatus = $request->payment_status;
        $status = $request->status;

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $salesQuery = Receipt::with([
            'company',
            'branch',
            'party',
            'creator',
        ])
            ->where('type', 'Expense')
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($customerName, function ($query) use ($customerName) {
                $query->where(function ($q) use ($customerName) {
                    $q->where('customer_name', 'like', "%{$customerName}%")
                        ->orWhereHas('party', function ($partyQuery) use ($customerName) {
                            $partyQuery->where('name', 'like', "%{$customerName}%");
                        });
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('receipt_no', 'like', "%{$search}%")
                        ->orWhere('so_no', 'like', "%{$search}%")
                        ->orWhere('inv_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('party', function ($partyQuery) use ($search) {
                            $partyQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            });

        $today = Carbon::today();

        switch ($period) {
            case 'today':
                $salesQuery->whereDate('receipt_date', $today);
                break;

            case 'yesterday':
                $salesQuery->whereDate('receipt_date', $today->copy()->subDay());
                break;

            case 'last_7_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_15_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(14)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_30_days':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(29)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'this_month':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfMonth()->toDateString(),
                    $today->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'last_month':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                    $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'this_year':
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfYear()->toDateString(),
                    $today->copy()->endOfYear()->toDateString(),
                ]);
                break;

            case 'custom':
                if ($request->filled('from_date')) {
                    $salesQuery->whereDate('receipt_date', '>=', $request->from_date);
                }

                if ($request->filled('to_date')) {
                    $salesQuery->whereDate('receipt_date', '<=', $request->to_date);
                }
                break;

            case 'all':
                // No date restriction
                break;

            default:
                $salesQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                $period = 'last_7_days';
                break;
        }

        $summary = [
            'total_orders' => (clone $salesQuery)->count(),
            'total_qty' => (clone $salesQuery)->sum('total_qty'),
            'total_sales' => (clone $salesQuery)->sum('total_amount'),
            'total_paid' => (clone $salesQuery)->sum('paid_amount'),
            'total_due' => (clone $salesQuery)->sum('due_amount'),
            'total_discount' => (clone $salesQuery)->sum('discount'),
            'total_vat' => (clone $salesQuery)->sum('vat'),
        ];

        $sales = (clone $salesQuery)
            ->latest('receipt_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('BackEnd.Report.expense', compact(
            'companies',
            'sales',
            'summary',
            'period',
            'companyId',
            'customerName',
            'search',
            'paymentStatus',
            'status'
        ));
    }

    // profit 
    public function profit(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'last_7_days');
        $companyId = $request->company_id;
        $customerName = $request->customer_name;
        $search = $request->search;

        $today = Carbon::today();

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $itemCosts = DB::table('receipt_items')
            ->select('receipt_id')
            ->selectRaw('SUM(qty * cost_price) as total_cost')
            ->groupBy('receipt_id');

        $profitQuery = Receipt::query()
            ->leftJoinSub($itemCosts, 'item_costs', function ($join) {
                $join->on('receipts.id', '=', 'item_costs.receipt_id');
            })
            ->with([
                'company',
                'branch',
                'party',
                'creator',
            ])
            ->where('receipts.type', 'Sales-Order')
            ->where('receipts.status', 'Completed')
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('receipts.created_by', $user->id);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('receipts.company_id', $companyId);
            })
            ->when($customerName, function ($query) use ($customerName) {
                $query->where(function ($q) use ($customerName) {
                    $q->where('receipts.customer_name', 'like', "%{$customerName}%")
                        ->orWhereHas('party', function ($partyQuery) use ($customerName) {
                            $partyQuery->where('name', 'like', "%{$customerName}%");
                        });
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('receipts.receipt_no', 'like', "%{$search}%")
                        ->orWhere('receipts.so_no', 'like', "%{$search}%")
                        ->orWhere('receipts.inv_no', 'like', "%{$search}%")
                        ->orWhere('receipts.customer_name', 'like', "%{$search}%")
                        ->orWhere('receipts.customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('party', function ($partyQuery) use ($search) {
                            $partyQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });
                });
            });

        switch ($period) {
            case 'today':
                $profitQuery->whereDate('receipts.receipt_date', $today);
                break;

            case 'yesterday':
                $profitQuery->whereDate(
                    'receipts.receipt_date',
                    $today->copy()->subDay()
                );
                break;

            case 'last_7_days':
                $profitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_15_days':
                $profitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->subDays(14)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_30_days':
                $profitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->subDays(29)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'this_month':
                $profitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->startOfMonth()->toDateString(),
                    $today->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'last_month':
                $lastMonth = $today->copy()->subMonthNoOverflow();

                $profitQuery->whereBetween('receipts.receipt_date', [
                    $lastMonth->copy()->startOfMonth()->toDateString(),
                    $lastMonth->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'this_year':
                $profitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->startOfYear()->toDateString(),
                    $today->copy()->endOfYear()->toDateString(),
                ]);
                break;

            case 'custom':
                if ($request->filled('from_date')) {
                    $profitQuery->whereDate(
                        'receipts.receipt_date',
                        '>=',
                        $request->from_date
                    );
                }

                if ($request->filled('to_date')) {
                    $profitQuery->whereDate(
                        'receipts.receipt_date',
                        '<=',
                        $request->to_date
                    );
                }
                break;

            case 'all':
                break;

            default:
                $profitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);

                $period = 'last_7_days';
                break;
        }

        $summary = (clone $profitQuery)
            ->selectRaw('
            COUNT(receipts.id) as total_orders,
            COALESCE(SUM(receipts.total_qty), 0) as total_qty,
            COALESCE(SUM(receipts.total_amount), 0) as total_sales,
            COALESCE(SUM(COALESCE(item_costs.total_cost, 0)), 0) as total_cost
        ')
            ->first();

        $totalSales = (float) $summary->total_sales;
        $totalCost = (float) $summary->total_cost;
        $grossProfit = $totalSales - $totalCost;

        $profitMargin = $totalSales > 0
            ? ($grossProfit / $totalSales) * 100
            : 0;

        $sales = (clone $profitQuery)
            ->select([
                'receipts.*',
                DB::raw('COALESCE(item_costs.total_cost, 0) as total_cost'),
                DB::raw('receipts.total_amount - COALESCE(item_costs.total_cost, 0) as gross_profit'),
            ])
            ->orderByDesc('receipts.receipt_date')
            ->orderByDesc('receipts.id')
            ->paginate(20, ['*'], 'sales_page')
            ->withQueryString();

        $productProfitQuery = DB::table('receipt_items')
            ->join('receipts', 'receipts.id', '=', 'receipt_items.receipt_id')
            ->leftJoin('products', 'products.id', '=', 'receipt_items.product_id')
            ->where('receipts.type', 'Sales-Order')
            ->where('receipts.status', 'Completed')
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('receipts.created_by', $user->id);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('receipts.company_id', $companyId);
            })
            ->when($customerName, function ($query) use ($customerName) {
                $query->where(function ($q) use ($customerName) {
                    $q->where('receipts.customer_name', 'like', "%{$customerName}%")
                        ->orWhereExists(function ($subQuery) use ($customerName) {
                            $subQuery->select(DB::raw(1))
                                ->from('parties')
                                ->whereColumn('parties.id', 'receipts.party_id')
                                ->where('parties.name', 'like', "%{$customerName}%");
                        });
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('receipts.receipt_no', 'like', "%{$search}%")
                        ->orWhere('receipts.so_no', 'like', "%{$search}%")
                        ->orWhere('receipts.inv_no', 'like', "%{$search}%")
                        ->orWhere('products.name', 'like', "%{$search}%")
                        ->orWhere('products.product_code', 'like', "%{$search}%")
                        ->orWhere('receipts.customer_name', 'like', "%{$search}%");
                });
            });

        // Apply same date range to product-wise report
        switch ($period) {
            case 'today':
                $productProfitQuery->whereDate('receipts.receipt_date', $today);
                break;

            case 'yesterday':
                $productProfitQuery->whereDate(
                    'receipts.receipt_date',
                    $today->copy()->subDay()
                );
                break;

            case 'last_7_days':
                $productProfitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_15_days':
                $productProfitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->subDays(14)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_30_days':
                $productProfitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->subDays(29)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'this_month':
                $productProfitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->startOfMonth()->toDateString(),
                    $today->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'last_month':
                $lastMonth = $today->copy()->subMonthNoOverflow();

                $productProfitQuery->whereBetween('receipts.receipt_date', [
                    $lastMonth->copy()->startOfMonth()->toDateString(),
                    $lastMonth->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'this_year':
                $productProfitQuery->whereBetween('receipts.receipt_date', [
                    $today->copy()->startOfYear()->toDateString(),
                    $today->copy()->endOfYear()->toDateString(),
                ]);
                break;

            case 'custom':
                if ($request->filled('from_date')) {
                    $productProfitQuery->whereDate(
                        'receipts.receipt_date',
                        '>=',
                        $request->from_date
                    );
                }

                if ($request->filled('to_date')) {
                    $productProfitQuery->whereDate(
                        'receipts.receipt_date',
                        '<=',
                        $request->to_date
                    );
                }
                break;

            case 'all':
                break;
        }

        $productProfits = $productProfitQuery
            ->select([
                'receipt_items.product_id',
                'products.name as product_name',
                'products.product_code',
            ])
            ->selectRaw('
            SUM(receipt_items.qty) as total_qty,
            SUM(receipt_items.amount) as total_sales,
            SUM(receipt_items.qty * receipt_items.cost_price) as total_cost,
            SUM(receipt_items.amount) - SUM(receipt_items.qty * receipt_items.cost_price) as gross_profit
        ')
            ->groupBy(
                'receipt_items.product_id',
                'products.name',
                'products.product_code'
            )
            ->orderByDesc('gross_profit')
            ->paginate(15, ['*'], 'products_page')
            ->withQueryString();

        return view('BackEnd.Report.profit', compact(
            'companies',
            'sales',
            'productProfits',
            'summary',
            'totalSales',
            'totalCost',
            'grossProfit',
            'profitMargin',
            'period',
            'companyId',
            'customerName',
            'search'
        ));
    }
}
