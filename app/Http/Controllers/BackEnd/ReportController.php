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

    // receivable report
    public function receivable(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'all');
        $companyId = $request->company_id;
        $customerName = $request->customer_name;
        $search = $request->search;
        $paymentStatus = $request->payment_status;

        $today = Carbon::today();

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $receivableQuery = Receipt::with([
            'company',
            'branch',
            'party',
            'creator',
            'payments.paymentType',
            'payments.account',
        ])
            ->whereIn('type', ['Sales-Order', 'Direct-Income'])
            ->where('status', 'Completed')
            ->where('due_amount', '>', 0)
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
            });

        switch ($period) {
            case 'today':
                $receivableQuery->whereDate('receipt_date', $today);
                break;

            case 'yesterday':
                $receivableQuery->whereDate(
                    'receipt_date',
                    $today->copy()->subDay()
                );
                break;

            case 'last_7_days':
                $receivableQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(6)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_15_days':
                $receivableQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(14)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'last_30_days':
                $receivableQuery->whereBetween('receipt_date', [
                    $today->copy()->subDays(29)->toDateString(),
                    $today->toDateString(),
                ]);
                break;

            case 'this_month':
                $receivableQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfMonth()->toDateString(),
                    $today->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'last_month':
                $lastMonth = $today->copy()->subMonthNoOverflow();

                $receivableQuery->whereBetween('receipt_date', [
                    $lastMonth->copy()->startOfMonth()->toDateString(),
                    $lastMonth->copy()->endOfMonth()->toDateString(),
                ]);
                break;

            case 'this_year':
                $receivableQuery->whereBetween('receipt_date', [
                    $today->copy()->startOfYear()->toDateString(),
                    $today->copy()->endOfYear()->toDateString(),
                ]);
                break;

            case 'custom':
                if ($request->filled('from_date')) {
                    $receivableQuery->whereDate(
                        'receipt_date',
                        '>=',
                        $request->from_date
                    );
                }

                if ($request->filled('to_date')) {
                    $receivableQuery->whereDate(
                        'receipt_date',
                        '<=',
                        $request->to_date
                    );
                }
                break;

            case 'all':
                break;

            default:
                $period = 'all';
                break;
        }

        $summary = (clone $receivableQuery)
            ->reorder()
            ->selectRaw('
            COUNT(*) as total_invoices,
            COALESCE(SUM(total_amount), 0) as total_invoice_amount,
            COALESCE(SUM(paid_amount), 0) as total_received,
            COALESCE(SUM(due_amount), 0) as total_receivable
        ')
            ->first();

        $aging = (clone $receivableQuery)
            ->reorder()
            ->selectRaw("
            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipt_date) BETWEEN 0 AND 30
                THEN due_amount ELSE 0 END), 0) as days_0_30,

            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipt_date) BETWEEN 31 AND 60
                THEN due_amount ELSE 0 END), 0) as days_31_60,

            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipt_date) BETWEEN 61 AND 90
                THEN due_amount ELSE 0 END), 0) as days_61_90,

            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipt_date) > 90
                THEN due_amount ELSE 0 END), 0) as days_over_90
        ")
            ->first();

        $receivables = (clone $receivableQuery)
            ->select('receipts.*')
            ->selectRaw('DATEDIFF(CURDATE(), receipt_date) as age_days')
            ->orderBy('receipt_date')
            ->orderBy('id')
            ->paginate(20, ['*'], 'receivables_page')
            ->withQueryString();

        $customerReceivables = (clone $receivableQuery)
            ->reorder()
            ->select([
                'party_id',
                'customer_name',
                'customer_phone',
            ])
            ->selectRaw('
            COUNT(*) as total_invoices,
            COALESCE(SUM(total_amount), 0) as total_amount,
            COALESCE(SUM(paid_amount), 0) as total_paid,
            COALESCE(SUM(due_amount), 0) as total_due
        ')
            ->groupBy('party_id', 'customer_name', 'customer_phone')
            ->orderByDesc('total_due')
            ->paginate(15, ['*'], 'customers_page')
            ->withQueryString();

        return view('BackEnd.Report.receivable', compact(
            'companies',
            'receivables',
            'customerReceivables',
            'summary',
            'aging',
            'period',
            'companyId',
            'customerName',
            'search',
            'paymentStatus'
        ));
    }

    // payable report
    public function payable(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'all');
        $companyId = $request->company_id;
        $supplierId = $request->supplier_id;
        $paymentStatus = $request->payment_status;
        $search = trim($request->search ?? '');

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $suppliers = Party::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where('created_by', $user->id);
        })
            ->orderBy('name')
            ->get();

        $payableQuery = Receipt::with([
            'company',
            'branch',
            'party',
            'creator',
            'payments.paymentType',
            'payments.account',
        ])
            ->whereIn('receipts.type', ['Purchase-Order', 'Expense'])
            ->where('receipts.status', 'Completed')
            ->where('receipts.due_amount', '>', 0);

        if (!$isSuperAdmin) {
            $payableQuery->where('receipts.created_by', $user->id);
        }

        if ($companyId) {
            $payableQuery->where('receipts.company_id', $companyId);
        }

        if ($supplierId) {
            $payableQuery->where('receipts.party_id', $supplierId);
        }

        if ($paymentStatus) {
            $payableQuery->where('receipts.payment_status', $paymentStatus);
        }

        if ($search !== '') {
            $payableQuery->where(function ($query) use ($search) {
                $query->where('receipts.receipt_no', 'like', "%{$search}%")
                    ->orWhere('receipts.po_no', 'like', "%{$search}%")
                    ->orWhere('receipts.so_no', 'like', "%{$search}%")
                    ->orWhere('receipts.inv_no', 'like', "%{$search}%")
                    ->orWhere('receipts.customer_name', 'like', "%{$search}%")
                    ->orWhere('receipts.customer_phone', 'like', "%{$search}%")
                    ->orWhereHas('party', function ($q) use ($search) {
                        $q->where('parties.name', 'like', "%{$search}%")
                            ->orWhere('parties.phone', 'like', "%{$search}%");
                    });
            });
        }

        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;

            case 'last7':
                $startDate = Carbon::today()->subDays(6);
                $endDate = Carbon::today();
                break;

            case 'last15':
                $startDate = Carbon::today()->subDays(14);
                $endDate = Carbon::today();
                break;

            case 'last30':
                $startDate = Carbon::today()->subDays(29);
                $endDate = Carbon::today();
                break;

            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $lastMonth = Carbon::now()->subMonthNoOverflow();

                $startDate = $lastMonth->copy()->startOfMonth();
                $endDate = $lastMonth->copy()->endOfMonth();
                break;

            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;

            case 'custom':
                if ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                }

                if ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        if ($startDate) {
            $payableQuery->whereDate('receipts.receipt_date', '>=', $startDate);
        }

        if ($endDate) {
            $payableQuery->whereDate('receipts.receipt_date', '<=', $endDate);
        }

        $summary = (clone $payableQuery)
            ->reorder()
            ->selectRaw('
            COUNT(receipts.id) as total_invoices,
            COALESCE(SUM(receipts.total_qty), 0) as total_qty,
            COALESCE(SUM(receipts.total_amount), 0) as total_purchase,
            COALESCE(SUM(receipts.paid_amount), 0) as total_paid,
            COALESCE(SUM(receipts.due_amount), 0) as total_due
        ')
            ->first();

        $aging = (clone $payableQuery)
            ->reorder()
            ->selectRaw("
            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipts.receipt_date) BETWEEN 0 AND 30
                THEN receipts.due_amount ELSE 0 END), 0) as days_0_30,

            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipts.receipt_date) BETWEEN 31 AND 60
                THEN receipts.due_amount ELSE 0 END), 0) as days_31_60,

            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipts.receipt_date) BETWEEN 61 AND 90
                THEN receipts.due_amount ELSE 0 END), 0) as days_61_90,

            COALESCE(SUM(CASE
                WHEN DATEDIFF(CURDATE(), receipts.receipt_date) > 90
                THEN receipts.due_amount ELSE 0 END), 0) as days_over_90
        ")
            ->first();

        $invoices = (clone $payableQuery)
            ->select('receipts.*')
            ->selectRaw('DATEDIFF(CURDATE(), receipts.receipt_date) as age_days')
            ->latest('receipts.receipt_date')
            ->latest('receipts.id')
            ->paginate(15, ['*'], 'invoice_page')
            ->withQueryString();

        $supplierQuery = (clone $payableQuery)
            ->reorder()
            ->leftJoin('parties', 'receipts.party_id', '=', 'parties.id')
            ->select([
                'receipts.party_id',
                'receipts.customer_name',
                'receipts.customer_phone',
            ])
            ->selectRaw("
            COALESCE(
                parties.name,
                receipts.customer_name,
                'Unknown Supplier'
            ) as supplier_name
        ")
            ->selectRaw("
            COALESCE(
                parties.phone,
                receipts.customer_phone,
                ''
            ) as supplier_phone
        ")
            ->selectRaw('COUNT(receipts.id) as total_invoices')
            ->selectRaw('COALESCE(SUM(receipts.total_amount), 0) as total_purchase')
            ->selectRaw('COALESCE(SUM(receipts.paid_amount), 0) as total_paid')
            ->selectRaw('COALESCE(SUM(receipts.due_amount), 0) as total_due')
            ->groupBy(
                'receipts.party_id',
                'receipts.customer_name',
                'receipts.customer_phone',
                'parties.name',
                'parties.phone'
            )
            ->orderByDesc('total_due');

        $supplierWise = $supplierQuery
            ->paginate(15, ['*'], 'supplier_page')
            ->withQueryString();

        return view('BackEnd.Report.payable', compact(
            'companies',
            'suppliers',
            'period',
            'companyId',
            'supplierId',
            'paymentStatus',
            'search',
            'summary',
            'aging',
            'invoices',
            'supplierWise'
        ));
    }

    // received report 
    public function received(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'all');
        $companyId = $request->company_id;
        $customerId = $request->customer_id;
        $paymentTypeId = $request->payment_type_id;
        $accountId = $request->account_id;
        $search = trim($request->search ?? '');

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $customers = Party::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where('created_by', $user->id);
        })
            ->orderBy('name')
            ->get();

        $paymentTypes = DB::table('payment_types')
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->orderBy('name')
            ->get();

        $accounts = Account::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where('created_by', $user->id)
                ->where('company_id', $user->company_id);
        })
            ->orderBy('account_name')
            ->get();

        $receivedQuery = DB::table('receipt_payments as rp')
            ->join('receipts as r', 'rp.receipt_id', '=', 'r.id')
            ->leftJoin('parties as p', 'r.party_id', '=', 'p.id')
            ->leftJoin('companies as c', 'r.company_id', '=', 'c.id')
            ->leftJoin('branches as b', 'r.branch_id', '=', 'b.id')
            ->leftJoin('payment_types as pt', 'rp.payment_type_id', '=', 'pt.id')
            ->leftJoin('accounts as a', 'rp.account_id', '=', 'a.id')
            ->leftJoin('users as u', 'rp.created_by', '=', 'u.id')
            ->where('r.type', 'Sales-Order')
            ->where('r.status', 'Completed');

        if (!$isSuperAdmin) {
            $receivedQuery->where('rp.created_by', $user->id);
        }

        if ($companyId) {
            $receivedQuery->where('r.company_id', $companyId);
        }

        if ($customerId) {
            $receivedQuery->where('r.party_id', $customerId);
        }

        if ($paymentTypeId) {
            $receivedQuery->where('rp.payment_type_id', $paymentTypeId);
        }

        if ($accountId) {
            $receivedQuery->where('rp.account_id', $accountId);
        }

        if ($search !== '') {
            $receivedQuery->where(function ($query) use ($search) {
                $query->where('r.receipt_no', 'like', "%{$search}%")
                    ->orWhere('r.inv_no', 'like', "%{$search}%")
                    ->orWhere('r.so_no', 'like', "%{$search}%")
                    ->orWhere('r.customer_name', 'like', "%{$search}%")
                    ->orWhere('r.customer_phone', 'like', "%{$search}%")
                    ->orWhere('p.name', 'like', "%{$search}%")
                    ->orWhere('p.phone', 'like', "%{$search}%")
                    ->orWhere('a.account_name', 'like', "%{$search}%")
                    ->orWhere('pt.name', 'like', "%{$search}%")
                    ->orWhere('rp.note', 'like', "%{$search}%");
            });
        }

        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;

            case 'last7':
                $startDate = Carbon::today()->subDays(6);
                $endDate = Carbon::today();
                break;

            case 'last15':
                $startDate = Carbon::today()->subDays(14);
                $endDate = Carbon::today();
                break;

            case 'last30':
                $startDate = Carbon::today()->subDays(29);
                $endDate = Carbon::today();
                break;

            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $lastMonth = Carbon::now()->subMonthNoOverflow();

                $startDate = $lastMonth->copy()->startOfMonth();
                $endDate = $lastMonth->copy()->endOfMonth();
                break;

            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;

            case 'custom':
                if ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                }

                if ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        if ($startDate) {
            $receivedQuery->whereDate('rp.payment_date', '>=', $startDate);
        }

        if ($endDate) {
            $receivedQuery->whereDate('rp.payment_date', '<=', $endDate);
        }

        $summary = (clone $receivedQuery)
            ->selectRaw('
            COUNT(rp.id) as total_transactions,
            COUNT(DISTINCT r.id) as total_invoices,
            COALESCE(SUM(rp.amount), 0) as total_received,
            COALESCE(AVG(rp.amount), 0) as average_payment
        ')
            ->first();

        $paymentTypeWise = (clone $receivedQuery)
            ->reorder()
            ->select([
                'rp.payment_type_id',
                'pt.name as payment_type_name',
            ])
            ->selectRaw('COUNT(rp.id) as total_transactions')
            ->selectRaw('COALESCE(SUM(rp.amount), 0) as total_received')
            ->groupBy('rp.payment_type_id', 'pt.name')
            ->orderByDesc('total_received')
            ->get();

        $accountWise = (clone $receivedQuery)
            ->reorder()
            ->select([
                'rp.account_id',
                'a.account_name',
            ])
            ->selectRaw('COUNT(rp.id) as total_transactions')
            ->selectRaw('COALESCE(SUM(rp.amount), 0) as total_received')
            ->groupBy('rp.account_id', 'a.account_name')
            ->orderByDesc('total_received')
            ->get();

        $transactions = (clone $receivedQuery)
            ->select([
                'rp.id',
                'rp.receipt_id',
                'rp.payment_type_id',
                'rp.account_id',
                'rp.payment_date',
                'rp.amount',
                'rp.note',
                'rp.created_by',

                'r.receipt_no',
                'r.inv_no',
                'r.so_no',
                'r.customer_name',
                'r.customer_phone',
                'r.company_id',
                'r.branch_id',

                'p.name as customer_name_from_party',
                'p.phone as customer_phone_from_party',

                'c.name as company_name',
                'b.name as branch_name',

                'pt.name as payment_type_name',
                'a.account_name',

                'u.name as creator_name',
            ])
            ->orderByDesc('rp.payment_date')
            ->orderByDesc('rp.id')
            ->paginate(15, ['*'], 'transaction_page')
            ->withQueryString();

        $invoiceWise = (clone $receivedQuery)
            ->reorder()
            ->select([
                'r.id as receipt_id',
                'r.receipt_no',
                'r.inv_no',
                'r.so_no',
                'r.receipt_date',
                'r.total_amount',
                'r.paid_amount',
                'r.due_amount',
                'r.customer_name',
                'r.customer_phone',
                'r.party_id',
                'p.name as party_name',
                'p.phone as party_phone',
                'c.name as company_name',
            ])
            ->selectRaw('COUNT(rp.id) as total_transactions')
            ->selectRaw('COALESCE(SUM(rp.amount), 0) as total_received_in_period')
            ->groupBy(
                'r.id',
                'r.receipt_no',
                'r.inv_no',
                'r.so_no',
                'r.receipt_date',
                'r.total_amount',
                'r.paid_amount',
                'r.due_amount',
                'r.customer_name',
                'r.customer_phone',
                'r.party_id',
                'p.name',
                'p.phone',
                'c.name'
            )
            ->orderByDesc('total_received_in_period')
            ->paginate(15, ['*'], 'invoice_page')
            ->withQueryString();

        $customerWise = (clone $receivedQuery)
            ->reorder()
            ->select([
                'r.party_id',
                'r.customer_name',
                'r.customer_phone',
                'p.name as party_name',
                'p.phone as party_phone',
            ])
            ->selectRaw('COUNT(DISTINCT r.id) as total_invoices')
            ->selectRaw('COUNT(rp.id) as total_transactions')
            ->selectRaw('COALESCE(SUM(rp.amount), 0) as total_received')
            ->groupBy(
                'r.party_id',
                'r.customer_name',
                'r.customer_phone',
                'p.name',
                'p.phone'
            )
            ->orderByDesc('total_received')
            ->paginate(15, ['*'], 'customer_page')
            ->withQueryString();

        return view('BackEnd.Report.received', compact(
            'companies',
            'customers',
            'paymentTypes',
            'accounts',
            'period',
            'companyId',
            'customerId',
            'paymentTypeId',
            'accountId',
            'search',
            'summary',
            'paymentTypeWise',
            'accountWise',
            'transactions',
            'invoiceWise',
            'customerWise'
        ));
    }

    // paid report 
    public function paid(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $period = $request->get('period', 'all');
        $companyId = $request->company_id;
        $partyId = $request->party_id;
        $search = trim($request->search ?? '');

        $companies = Company::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->company_id)
                    ->orWhere('created_by', $user->id);
            });
        })
            ->orderBy('name')
            ->get();

        $parties = Party::orderBy('name')->get();

        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;

            case 'last7':
                $startDate = Carbon::today()->subDays(6);
                $endDate = Carbon::today();
                break;

            case 'last15':
                $startDate = Carbon::today()->subDays(14);
                $endDate = Carbon::today();
                break;

            case 'last30':
                $startDate = Carbon::today()->subDays(29);
                $endDate = Carbon::today();
                break;

            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $lastMonth = Carbon::now()->subMonthNoOverflow();

                $startDate = $lastMonth->copy()->startOfMonth();
                $endDate = $lastMonth->copy()->endOfMonth();
                break;

            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;

            case 'custom':
                if ($request->filled('start_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                }

                if ($request->filled('end_date')) {
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        $applyFilters = function ($query) use (
            $isSuperAdmin,
            $user,
            $companyId,
            $partyId,
            $search,
            $startDate,
            $endDate
        ) {
            if (!$isSuperAdmin) {
                $query->where('receipts.created_by', $user->id);
            }

            if ($companyId) {
                $query->where('receipts.company_id', $companyId);
            }

            if ($partyId) {
                $query->where('receipts.party_id', $partyId);
            }

            if ($startDate) {
                $query->whereDate('receipts.receipt_date', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('receipts.receipt_date', '<=', $endDate);
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('receipts.receipt_no', 'like', "%{$search}%")
                        ->orWhere('receipts.po_no', 'like', "%{$search}%")
                        ->orWhere('receipts.so_no', 'like', "%{$search}%")
                        ->orWhere('receipts.inv_no', 'like', "%{$search}%")
                        ->orWhere('receipts.customer_name', 'like', "%{$search}%")
                        ->orWhere('receipts.customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('party', function ($partyQuery) use ($search) {
                            $partyQuery->where('parties.name', 'like', "%{$search}%")
                                ->orWhere('parties.phone', 'like', "%{$search}%");
                        });
                });
            }

            return $query;
        };

        $customerPaidQuery = Receipt::with([
            'company',
            'branch',
            'party',
            'creator',
            'payments.paymentType',
            'payments.account',
        ])
            ->whereIn('receipts.type', ['Sales-Order', 'Direct-Income'])
            ->where('receipts.status', 'Completed')
            ->where('receipts.payment_status', 'Paid');

        $customerPaidQuery = $applyFilters($customerPaidQuery);

        $supplierPaidQuery = Receipt::with([
            'company',
            'branch',
            'party',
            'creator',
            'payments.paymentType',
            'payments.account',
        ])
            ->where('receipts.type', 'Purchase-Order')
            ->where('receipts.status', 'Completed')
            ->where('receipts.payment_status', 'Paid');

        $supplierPaidQuery = $applyFilters($supplierPaidQuery);

        $customerSummary = (clone $customerPaidQuery)
            ->reorder()
            ->selectRaw('
            COUNT(receipts.id) as total_invoices,
            COALESCE(SUM(receipts.total_qty), 0) as total_qty,
            COALESCE(SUM(receipts.total_amount), 0) as total_amount,
            COALESCE(SUM(receipts.paid_amount), 0) as total_paid
        ')
            ->first();

        $supplierSummary = (clone $supplierPaidQuery)
            ->reorder()
            ->selectRaw('
            COUNT(receipts.id) as total_invoices,
            COALESCE(SUM(receipts.total_qty), 0) as total_qty,
            COALESCE(SUM(receipts.total_amount), 0) as total_amount,
            COALESCE(SUM(receipts.paid_amount), 0) as total_paid
        ')
            ->first();

        $customerInvoices = (clone $customerPaidQuery)
            ->select('receipts.*')
            ->latest('receipts.receipt_date')
            ->latest('receipts.id')
            ->paginate(15, ['*'], 'customer_invoice_page')
            ->withQueryString();

        $supplierInvoices = (clone $supplierPaidQuery)
            ->select('receipts.*')
            ->latest('receipts.receipt_date')
            ->latest('receipts.id')
            ->paginate(15, ['*'], 'supplier_invoice_page')
            ->withQueryString();

        $customerWise = (clone $customerPaidQuery)
            ->reorder()
            ->leftJoin('parties', 'receipts.party_id', '=', 'parties.id')
            ->select([
                'receipts.party_id',
                'receipts.customer_name',
                'receipts.customer_phone',
            ])
            ->selectRaw("
            COALESCE(
                parties.name,
                receipts.customer_name,
                'Unknown Customer'
            ) as party_name
        ")
            ->selectRaw("
            COALESCE(
                parties.phone,
                receipts.customer_phone,
                ''
            ) as party_phone
        ")
            ->selectRaw('COUNT(receipts.id) as total_invoices')
            ->selectRaw('COALESCE(SUM(receipts.total_qty), 0) as total_qty')
            ->selectRaw('COALESCE(SUM(receipts.total_amount), 0) as total_amount')
            ->selectRaw('COALESCE(SUM(receipts.paid_amount), 0) as total_paid')
            ->groupBy(
                'receipts.party_id',
                'receipts.customer_name',
                'receipts.customer_phone',
                'parties.name',
                'parties.phone'
            )
            ->orderByDesc('total_paid')
            ->paginate(15, ['*'], 'customer_page')
            ->withQueryString();

        $supplierWise = (clone $supplierPaidQuery)
            ->reorder()
            ->leftJoin('parties', 'receipts.party_id', '=', 'parties.id')
            ->select([
                'receipts.party_id',
                'receipts.customer_name',
                'receipts.customer_phone',
            ])
            ->selectRaw("
            COALESCE(
                parties.name,
                receipts.customer_name,
                'Unknown Supplier'
            ) as party_name
        ")
            ->selectRaw("
            COALESCE(
                parties.phone,
                receipts.customer_phone,
                ''
            ) as party_phone
        ")
            ->selectRaw('COUNT(receipts.id) as total_invoices')
            ->selectRaw('COALESCE(SUM(receipts.total_qty), 0) as total_qty')
            ->selectRaw('COALESCE(SUM(receipts.total_amount), 0) as total_amount')
            ->selectRaw('COALESCE(SUM(receipts.paid_amount), 0) as total_paid')
            ->groupBy(
                'receipts.party_id',
                'receipts.customer_name',
                'receipts.customer_phone',
                'parties.name',
                'parties.phone'
            )
            ->orderByDesc('total_paid')
            ->paginate(15, ['*'], 'supplier_page')
            ->withQueryString();

        return view('BackEnd.Report.paid', compact(
            'companies',
            'parties',
            'period',
            'companyId',
            'partyId',
            'search',
            'customerSummary',
            'supplierSummary',
            'customerInvoices',
            'supplierInvoices',
            'customerWise',
            'supplierWise'
        ));
    }
}
