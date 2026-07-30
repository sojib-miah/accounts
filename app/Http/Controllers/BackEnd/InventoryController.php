<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            });
        });

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('product_code', 'like', "%{$request->search}%")
                    ->orWhere('barcode', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('BackEnd.Inventory.index', compact('products'));
    }

    public function lowStock()
    {
        $products = Product::with('category')
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where(function ($q) {
                    $q->where('company_id', Auth::user()->company_id)
                        ->where('created_by', Auth::id());
                });
            })
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->paginate(20);

        return view('BackEnd.Inventory.low-stock', compact('products'));
    }

    public function report()
    {
        $products = Product::with('category')
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where(function ($q) {
                    $q->where('company_id', Auth::user()->company_id)
                        ->where('created_by', Auth::id());
                });
            })
            ->orderBy('name')
            ->get();

        return view('BackEnd.Inventory.report', compact('products'));
    }

    public function print()
    {
        $products = Product::with('category')
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where(function ($q) {
                    $q->where('company_id', Auth::user()->company_id)
                        ->where('created_by', Auth::id());
                });
            })
            ->orderBy('name')
            ->get();

        return view('BackEnd.Inventory.report-print', compact('products'));
    }

    public function pdf()
    {
        $products = Product::with('category')
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where(function ($q) {
                    $q->where('company_id', Auth::user()->company_id)
                        ->where('created_by', Auth::id());
                });
            })
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView(
            'BackEnd.Inventory.report-pdf',
            compact('products')
        );

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Inventory_Report.pdf');
        // return $pdf->download('Inventory_Report.pdf');
    }
}
