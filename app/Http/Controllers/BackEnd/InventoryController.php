<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with([
            'supplier',
            'items.product'
        ])
            ->where('type', 'Purchase-Order')
            ->where('is_receive', true)
            ->latest()
            ->paginate(20);

        return view('BackEnd.Inventory.index', compact('receipts'));
    }

    public function show(Receipt $receipt)
    {
        $receipt->load([
            'supplier',
            'items.product'
        ]);

        return view(
            'BackEnd.Inventory.show',
            compact('receipt')
        );
    }

    public function lowStock()
    {
        $products = Product::with(['category', 'brand'])
            ->whereHas('receiptItems.receipt', function ($query) {
                $query->where('is_receive', true)
                    ->where('type', 'Purchase-Order');
            })
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            })
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('current_stock')
            ->paginate(20);

        return view('BackEnd.Inventory.low-stock', compact('products'));
    }

    public function report(Request $request)
    {
        $query = Receipt::with([
            'supplier',
            'items.product.category',
            'items.product.brand'
        ])
            ->where('type', 'Purchase-Order')
            ->where('is_receive', true);

        if (!auth()->user()->hasRole('Super-Admin')) {

            $query->where(function ($q) {

                $q->where('company_id', auth()->user()->company_id)
                    ->where('created_by', auth()->id());
            });
        }

        if ($request->filled('from_date')) {

            $query->whereDate(
                'received_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'received_date',
                '<=',
                $request->to_date
            );
        }

        $receipts = $query
            ->orderByDesc('received_date')
            ->paginate(20)
            ->withQueryString();

        return view(
            'BackEnd.Inventory.report',
            compact('receipts')
        );
    }

    public function print(Request $request)
    {
        $query = Receipt::with([
            'supplier',
            'items.product.category',
            'items.product.brand'
        ])
            ->where('type', 'Purchase-Order')
            ->where('is_receive', true);

        if (!auth()->user()->hasRole('Super-Admin')) {

            $query->where(function ($q) {

                $q->where('company_id', auth()->user()->company_id)
                    ->where('created_by', auth()->id());
            });
        }

        if ($request->filled('from_date')) {

            $query->whereDate(
                'received_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'received_date',
                '<=',
                $request->to_date
            );
        }

        $receipts = $query
            ->orderByDesc('received_date')
            ->get();

        return view('BackEnd.Inventory.report-print', compact('receipts'));
    }

    public function pdf(Request $request)
    {
        $query = Receipt::with([
            'supplier',
            'items.product.category',
            'items.product.brand'
        ])
            ->where('type', 'Purchase-Order')
            ->where('is_receive', true);

        if (!auth()->user()->hasRole('Super-Admin')) {

            $query->where(function ($q) {

                $q->where('company_id', auth()->user()->company_id)
                    ->where('created_by', auth()->id());
            });
        }

        if ($request->filled('from_date')) {

            $query->whereDate(
                'received_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'received_date',
                '<=',
                $request->to_date
            );
        }

        $receipts = $query
            ->orderBy('received_date')
            ->get();

        $pdf = Pdf::loadView(
            'BackEnd.Inventory.report-pdf',
            compact('receipts')
        );

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Inventory_Report.pdf');
    }
}
