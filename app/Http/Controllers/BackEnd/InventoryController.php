<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\SerialNumber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = ReceiptItem::query()
            ->select(['receipt_items.product_id',])
            ->selectRaw('SUM(receipt_items.qty) as total_qty')
            ->selectRaw('SUM(receipt_items.amount) as total_value')
            ->selectRaw(
                'CASE 
                WHEN SUM(receipt_items.qty) > 0
                THEN SUM(receipt_items.amount) / SUM(receipt_items.qty)
                ELSE 0 END as average_rate'
            )
            ->whereHas('receipt', function ($q) use ($user) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
                if (!$user->hasRole('Super-Admin')) {
                    $q->where('company_id', $user->company_id)
                        ->where('created_by', $user->id);
                }
            })
            ->with(['product.category', 'product.brand',]);
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas(
                'product',
                function ($product) use ($search) {
                    $product
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('product_code', 'like', "%{$search}%");
                }
            );
        }
        $query->groupBy('receipt_items.product_id');
        $products = $query->orderByDesc('total_qty')->paginate(20)->withQueryString();
        $totalQuery = ReceiptItem::query()
            ->whereHas('receipt', function ($q) use ($user) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
                if (!$user->hasRole('Super-Admin')) {
                    $q->where('company_id', $user->company_id)
                        ->where('created_by', $user->id);
                }
            });
        $totalProducts = (clone $totalQuery)->distinct('product_id')->count('product_id');
        $totalQty = (clone $totalQuery)->sum('qty');
        $totalValue = (clone $totalQuery)->sum('amount');
        return view(
            'BackEnd.Inventory.index',
            compact(
                'products',
                'totalProducts',
                'totalQty',
                'totalValue'
            )
        );
    }

    public function productShow(Request $request, Product $product)
    {
        $user = auth()->user();
        $product->load(['category', 'brand',]);
        $query = ReceiptItem::with(['receipt.supplier', 'receipt.customerCompany', 'receipt.branch', 'serialNumbers',])
            ->where('product_id', $product->id)
            ->whereHas('receipt', function ($q) use ($user) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
                if (!$user->hasRole('Super-Admin')) {
                    $q->where('company_id', $user->company_id)
                        ->where('created_by', $user->id);
                }
            });
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas(
                'receipt',
                function ($receipt) use ($search) {
                    $receipt
                        ->where('po_no', 'like', "%{$search}%")
                        ->orWhere('receipt_no', 'like', "%{$search}%")
                        ->orWhereHas(
                            'supplier',
                            function ($supplier) use ($search) {
                                $supplier->where('name', 'like', "%{$search}%");
                            }
                        );
                }
            );
        }
        $items = $query->latest('id')->paginate(20)->withQueryString();
        $totalQty = ReceiptItem::where('product_id', $product->id)
            ->whereHas('receipt', function ($q) use ($user) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
                if (!$user->hasRole('Super-Admin')) {
                    $q->where('company_id', $user->company_id)
                        ->where('created_by', $user->id);
                }
            })->sum('qty');
        $totalValue = ReceiptItem::where('product_id', $product->id)
            ->whereHas('receipt', function ($q) use ($user) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
                if (!$user->hasRole('Super-Admin')) {
                    $q->where('company_id', $user->company_id)
                        ->where('created_by', $user->id);
                }
            })->sum('amount');
        $averageRate = $totalQty > 0 ? $totalValue / $totalQty : 0;
        $serialCount = SerialNumber::where('product_id', $product->id)
            ->whereHas('receipt', function ($q) use ($user) {
                $q->where('type', 'Purchase-Order')
                    ->where('is_receive', true);
                if (!$user->hasRole('Super-Admin')) {
                    $q->where('company_id', $user->company_id)
                        ->where('created_by', $user->id);
                }
            })->count();
        return view(
            'BackEnd.Inventory.show',
            compact(
                'product',
                'items',
                'totalQty',
                'totalValue',
                'averageRate',
                'serialCount'
            )
        );
    }

    public function lowStock(Request $request)
    {
        $user = Auth::user();
        $baseQuery = Product::query()
            ->with(['category', 'brand',])
            ->whereHas('receiptItems.receipt', function ($query) {
                $query->where('is_receive', true)
                    ->where('type', 'Purchase-Order');
            });

        if (!$user->hasRole('Super-Admin')) {
            $baseQuery->where('company_id', $user->company_id)->where('created_by', $user->id);
        }

        $lowStockQuery = clone $baseQuery;

        $lowStockQuery->whereColumn('current_stock', '<=', 'minimum_stock');

        if ($request->filled('search')) {
            $search = $request->search;
            $lowStockQuery->where(function ($query) use ($search) {
                $query->where(
                    'name',
                    'like',
                    "%{$search}%"
                )->orWhere('product_code', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('brand', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $products = $lowStockQuery->orderBy('current_stock')->paginate(20)->withQueryString();
        $lowStockCount = (clone $baseQuery)->whereColumn('current_stock', '<=', 'minimum_stock')->count();
        $outOfStockCount = (clone $baseQuery)->where('current_stock', '<=', 0)->count();
        $lowStockQty = (clone $baseQuery)->whereColumn('current_stock', '<=', 'minimum_stock')->sum('current_stock');

        $lowStockValue = (clone $baseQuery)
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->selectRaw(
                'COALESCE(SUM(current_stock * purchase_price), 0) as total'
            )->value('total');

        $totalProductCount = (clone $baseQuery)->count();

        $totalCurrentStock = (clone $baseQuery)->sum('current_stock');
        return view(
            'BackEnd.Inventory.low-stock',
            compact(
                'products',
                'lowStockCount',
                'outOfStockCount',
                'lowStockQty',
                'lowStockValue',
                'totalProductCount',
                'totalCurrentStock'
            )
        );
    }

    private function stockReportQuery(Request $request)
    {
        $user = Auth::user();

        $query = Product::with(['category', 'brand', 'receiptItems.receipt.supplier',])
            ->where('status', 'Active')
            ->whereHas('receiptItems.receipt', function ($q) use ($request) {
                $q->where('type', 'Purchase-Order')->where('is_receive', true);
                if ($request->filled('from_date')) {
                    $q->whereDate('received_date', '>=', $request->from_date);
                }

                if ($request->filled('to_date')) {
                    $q->whereDate('received_date', '<=', $request->to_date);
                }
            });

        if (!$user->hasRole('Super-Admin')) {
            $query->where('company_id', $user->company_id)->where('created_by', $user->id);
        }

        return $query;
    }

    /**
     * Stock Report
     */
    public function report(Request $request)
    {
        $products = $this->stockReportQuery($request)->orderBy('name')->paginate(20)->withQueryString();
        $summaryQuery = $this->stockReportQuery($request);
        $totalProducts = (clone $summaryQuery)->count();
        $totalCurrentStock = (clone $summaryQuery)->sum('current_stock');
        $totalStockValue = (clone $summaryQuery)->selectRaw('COALESCE(SUM(current_stock * purchase_price), 0) as total')->value('total');
        $totalSaleValue = (clone $summaryQuery)->selectRaw('COALESCE(SUM(current_stock * sale_price), 0) as total')->value('total');
        $receivedQuery = Receipt::query()->where('type', 'Purchase-Order')->where('is_receive', true);
        if (!$request->user()->hasRole('Super-Admin')) {
            $receivedQuery
                ->where('company_id', $request->user()->company_id)
                ->where('created_by', $request->user()->id);
        }

        if ($request->filled('from_date')) {
            $receivedQuery->whereDate('received_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $receivedQuery->whereDate('received_date', '<=', $request->to_date);
        }

        $totalReceivedQty = $receivedQuery->withSum('items', 'qty')->get()->sum('items_sum_qty');

        return view(
            'BackEnd.Inventory.report',
            compact(
                'products',
                'totalProducts',
                'totalCurrentStock',
                'totalStockValue',
                'totalSaleValue',
                'totalReceivedQty'
            )
        );
    }

    /**
     * Print Stock Report
     */
    public function print(Request $request)
    {
        $products = $this->stockReportQuery($request)->orderBy('name')->get();
        $totalProducts = $products->count();
        $totalCurrentStock = $products->sum('current_stock');
        $totalStockValue = $products->sum(function ($product) {
            return
                (float) $product->current_stock *
                (float) $product->purchase_price;
        });

        $totalSaleValue = $products->sum(function ($product) {
            return
                (float) $product->current_stock *
                (float) $product->sale_price;
        });

        $totalReceivedQty = 0;

        foreach ($products as $product) {
            $totalReceivedQty += $product->receiptItems
                ->filter(function ($item) {
                    return
                        $item->receipt &&
                        $item->receipt->type === 'Purchase-Order' &&
                        $item->receipt->is_receive == true;
                })->sum('qty');
        }

        return view(
            'BackEnd.Inventory.report-print',
            compact(
                'products',
                'totalProducts',
                'totalCurrentStock',
                'totalStockValue',
                'totalSaleValue',
                'totalReceivedQty'
            )
        );
    }

    /**
     * PDF Stock Report
     */
    public function pdf(Request $request)
    {
        $products = $this->stockReportQuery($request)->orderBy('name')->get();
        $totalProducts = $products->count();
        $totalCurrentStock = $products->sum('current_stock');
        $totalStockValue = $products->sum(function ($product) {
            return
                (float) $product->current_stock *
                (float) $product->purchase_price;
        });

        $totalSaleValue = $products->sum(function ($product) {
            return
                (float) $product->current_stock *
                (float) $product->sale_price;
        });

        $totalReceivedQty = 0;

        foreach ($products as $product) {
            $totalReceivedQty += $product->receiptItems
                ->filter(function ($item) {
                    return
                        $item->receipt &&
                        $item->receipt->type === 'Purchase-Order' &&
                        $item->receipt->is_receive == true;
                })->sum('qty');
        }

        $pdf = Pdf::loadView(
            'BackEnd.Inventory.report-pdf',
            compact(
                'products',
                'totalProducts',
                'totalCurrentStock',
                'totalStockValue',
                'totalSaleValue',
                'totalReceivedQty'
            )
        );

        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Stock_Report.pdf');
    }
}
