@extends('BackEnd.Layouts.layout')

@section('title', 'Low Stock Products')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">
                            <i class="fa fa-triangle-exclamation text-danger me-2"></i>
                            Low Stock Products
                        </h4>
                        <small class="text-muted">
                            Products that reached minimum stock level
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <a href="{{ route('inventory.index') }}" class="btn btn-primary">
                            <i class="fa fa-boxes me-2"></i>
                            Inventory
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    {{-- LOW STOCK --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-warning shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">
                                            Low Stock Products
                                        </div>
                                        <h3 class="mb-0 text-warning">
                                            {{ number_format($lowStockCount) }}
                                        </h3>
                                    </div>
                                    <div>
                                        <i class="fa fa-triangle-exclamation fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- OUT OF STOCK --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-danger shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">
                                            Out of Stock
                                        </div>
                                        <h3 class="mb-0 text-danger">
                                            {{ number_format($outOfStockCount) }}
                                        </h3>
                                    </div>
                                    <div>
                                        <i class="fa fa-circle-xmark fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- LOW STOCK QTY --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-primary shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">
                                            Current Low Stock Qty
                                        </div>
                                        <h3 class="mb-0 text-primary">
                                            {{ number_format($lowStockQty, 2) }}
                                        </h3>
                                    </div>
                                    <div>
                                        <i class="fa fa-box-open fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- STOCK VALUE --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-success shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">
                                            Low Stock Value
                                        </div>
                                        <h3 class="mb-0 text-success">
                                            {{ number_format($lowStockValue, 2) }}
                                        </h3>
                                    </div>
                                    <div>
                                        <i class="fa fa-money-bill-wave fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="GET" action="{{ route('inventory.lowStock') }}" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search product, code, category or brand..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-search me-2"></i>
                                    Search
                                </button>
                            </div>
                        </div>
                        @if (request('search'))
                            <div class="col-md-2">
                                <a href="{{ route('inventory.lowStock') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh me-2"></i>
                                    Clear
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">
                                    SL
                                </th>
                                <th>
                                    Product Code
                                </th>
                                <th>
                                    Product Name
                                </th>
                                <th>
                                    Category
                                </th>
                                <th>
                                    Brand
                                </th>
                                <th>
                                    Unit
                                </th>
                                <th class="text-end">
                                    Current Stock
                                </th>
                                <th class="text-end">
                                    Minimum Stock
                                </th>
                                <th class="text-end">
                                    Purchase Price
                                </th>
                                <th class="text-end">
                                    Sale Price
                                </th>
                                <th class="text-end">
                                    Stock Value
                                </th>
                                <th width="130">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                @php
                                    $stockValue = (float) $product->current_stock * (float) $product->purchase_price;
                                @endphp
                                <tr>
                                    {{-- SL --}}
                                    <td>
                                        {{ $products->firstItem() + $loop->index }}
                                    </td>
                                    {{-- PRODUCT CODE --}}
                                    <td>
                                        <span class="fw-semibold">
                                            {{ $product->product_code ?? ($product->sku ?? '-') }}
                                        </span>
                                    </td>
                                    {{-- PRODUCT NAME --}}
                                    <td>
                                        <strong>
                                            {{ $product->name }}
                                        </strong>
                                    </td>
                                    {{-- CATEGORY --}}
                                    <td>
                                        {{ $product->category->name ?? '-' }}
                                    </td>
                                    {{-- BRAND --}}
                                    <td>
                                        {{ $product->brand->name ?? '-' }}
                                    </td>
                                    {{-- UNIT --}}
                                    <td>
                                        {{ $product->unit ?? '-' }}
                                    </td>
                                    {{-- CURRENT STOCK --}}
                                    <td class="text-end">
                                        @if ($product->current_stock <= 0)
                                            <span class="badge bg-danger">
                                                {{ number_format($product->current_stock, 2) }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                {{ number_format($product->current_stock, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    {{-- MINIMUM STOCK --}}
                                    <td class="text-end">
                                        <span class="fw-semibold">
                                            {{ number_format($product->minimum_stock, 2) }}
                                        </span>
                                    </td>
                                    {{-- PURCHASE PRICE --}}
                                    <td class="text-end">
                                        {{ number_format($product->purchase_price, 2) }}
                                    </td>
                                    {{-- SALE PRICE --}}
                                    <td class="text-end">
                                        {{ number_format($product->sale_price, 2) }}
                                    </td>
                                    {{-- STOCK VALUE --}}
                                    <td class="text-end">
                                        <strong>
                                            {{ number_format($stockValue, 2) }}
                                        </strong>
                                    </td>
                                    {{-- STATUS --}}
                                    <td>
                                        @if ($product->current_stock <= 0)
                                            <span class="badge bg-danger">
                                                <i class="fa fa-circle-xmark me-1"></i>
                                                Out of Stock
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fa fa-triangle-exclamation me-1"></i>
                                                Low Stock
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <i class="fa fa-circle-check fa-2x text-success mb-2"></i>
                                        <br>
                                        <strong>
                                            No Low Stock Products Found
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            All received products are above their minimum stock level.
                                        </small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($products->count())
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">
                                        Page Total:
                                    </th>
                                    <th class="text-end">
                                        {{ number_format($products->sum('current_stock'), 2) }}
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end">
                                        {{ number_format(
                                            $products->sum(function ($product) {
                                                return $product->current_stock * $product->purchase_price;
                                            }),
                                            2,
                                        ) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
