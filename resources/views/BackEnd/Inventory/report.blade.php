@extends('BackEnd.Layouts.layout')

@section('title', 'Stock Report')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            {{-- HEADER --}}
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">
                            <i class="fa fa-chart-column me-2"></i>
                            Stock Report
                        </h4>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <a href="{{ route('inventory.print', request()->query()) }}" target="_blank" class="btn btn-success">
                            <i class="fa fa-print me-1"></i>
                            Print
                        </a>
                        <a href="{{ route('inventory.pdf', request()->query()) }}" target="_blank" class="btn btn-danger">
                            <i class="fa fa-file-pdf me-1"></i>
                            PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.report') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-1"></i>
                                Search
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('inventory.report') }}" class="btn btn-secondary w-100">
                                <i class="fa fa-refresh me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
                <div class="row g-3 mb-4">
                    {{-- Products --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-primary shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="text-muted">Total Products</small>
                                        <h4 class="mb-0 text-primary">{{ number_format($totalProducts) }}</h4>
                                    </div>
                                    <i class="fa fa-boxes fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Received Qty --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-info shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="text-muted">Received Qty</small>
                                        <h4 class="mb-0 text-info">{{ number_format($totalReceivedQty) }}</h4>
                                    </div>
                                    <i class="fa fa-arrow-down fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Current Stock --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-warning shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="text-muted">Current Stock</small>
                                        <h4 class="mb-0 text-warning">{{ number_format($totalCurrentStock) }}</h4>
                                    </div>
                                    <i class="fa fa-cubes fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Stock Value --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-success shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="text-muted">Current Stock Value</small>
                                        <h4 class="mb-0 text-success">{{ number_format($totalStockValue, 2) }}</h4>
                                    </div>
                                    <i class="fa fa-money-bill-wave fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="50">SN</th>
                                <th>Product Code</th>
                                <th>Part No</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Product</th>
                                <th>Unit</th>
                                <th class="text-end">Received Qty</th>
                                <th class="text-end">Purchase Price</th>
                                <th class="text-end">Sale Price</th>
                                <th class="text-end">Current Stock</th>
                                <th class="text-end">Stock Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                @php
                                    $receivedQty = $product->receiptItems
                                        ->filter(function ($item) {
                                            return $item->receipt &&
                                                $item->receipt->type === 'Purchase-Order' &&
                                                $item->receipt->is_receive == true;
                                        })
                                        ->sum('qty');
                                    $stockValue = $product->current_stock * $product->purchase_price;
                                @endphp
                                <tr>
                                    <td>{{ $products->firstItem() + $loop->index }}</td>
                                    <td>{{ $product->product_code ?? '-' }}</td>
                                    <td>{{ $product->sku ?? '-' }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->brand->name ?? '-' }}</td>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td>{{ $product->unit ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($receivedQty) }}</td>
                                    <td class="text-end">{{ number_format($product->purchase_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($product->sale_price, 2) }}</td>
                                    <td class="text-end">
                                        @if ($product->current_stock <= 0)
                                            <span
                                                class="badge bg-danger">{{ number_format($product->current_stock) }}</span>
                                        @else
                                            <strong>{{ number_format($product->current_stock) }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ number_format($stockValue, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">No Stock Data Found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        {{-- PAGE TOTAL --}}
                        @if ($products->count())
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-end">Page Total</th>
                                    <th class="text-end">
                                        {{ number_format(
                                            $products->sum(function ($product) {
                                                return $product->receiptItems->filter(function ($item) {
                                                        return $item->receipt &&
                                                            $item->receipt->type === 'Purchase-Order' &&
                                                            $item->receipt->is_receive == true;
                                                    })->sum('qty');
                                            }),
                                        ) }}
                                    </th>
                                    <th colspan="2"></th>
                                    <th class="text-end">{{ number_format($products->sum('current_stock')) }}</th>
                                    <th class="text-end">
                                        {{ number_format(
                                            $products->sum(function ($product) {
                                                return $product->current_stock * $product->purchase_price;
                                            }),
                                            2,
                                        ) }}
                                    </th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                {{-- PAGINATION --}}
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
