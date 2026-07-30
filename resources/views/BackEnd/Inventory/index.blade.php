@extends('BackEnd.Layouts.layout')

@section('title', 'Inventory')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-boxes me-2"></i>
                    Inventory
                </h4>
                <div>
                    <a href="{{ route('inventory.lowStock') }}" class="btn btn-warning">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        Low Stock
                    </a>
                    <a href="{{ route('inventory.report') }}" class="btn btn-success">
                        <i class="fa fa-chart-bar me-2"></i>
                        Stock Report
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="Product / Code / Barcode / SKU">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Category</option>
                                @foreach (App\Models\Category::where('type', 'Product')->orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button class="btn btn-primary me-2">
                                <i class="fa fa-search"></i>
                                Search
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SL</th>
                                <th>Product Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th class="text-end">Purchase</th>
                                <th class="text-end">Sale</th>
                                <th class="text-end">Stock</th>
                                <th class="text-end">Min Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        {{ $products->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        {{ $product->product_code }}
                                    </td>
                                    <td>
                                        <strong>
                                            {{ $product->name }}
                                        </strong>
                                        @if ($product->barcode)
                                            <br>
                                            <small class="text-muted">
                                                Barcode :
                                                {{ $product->barcode }}
                                            </small>
                                        @endif
                                        @if ($product->sku)
                                            <br>
                                            <small class="text-muted">
                                                SKU :
                                                {{ $product->sku }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->category->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $product->unit }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->purchase_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->sale_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        @if ($product->current_stock <= $product->minimum_stock)
                                            <span class="badge bg-danger">
                                                {{ number_format($product->current_stock, 2) }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ number_format($product->current_stock, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->minimum_stock, 2) }}
                                    </td>
                                    <td>
                                        @if ($product->status == 'Active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-danger">
                                        No Product Found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
