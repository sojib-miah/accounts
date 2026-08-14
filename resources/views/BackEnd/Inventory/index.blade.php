@extends('BackEnd.Layouts.layout')

@section('title', 'Inventory')

@section('content')

    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fa fa-boxes me-2"></i>
                        Inventory
                    </h4>
                    <div>
                        <span class="badge bg-primary me-2">
                            Products: {{ number_format($totalProducts) }}
                        </span>
                        <span class="badge bg-success">
                            Qty: {{ number_format($totalQty) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search Product / SKU / Code..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i>
                                Search
                            </button>
                            @if (request('search'))
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-primary shadow-sm">
                            <div class="card-body">
                                <small class="text-muted">Total Products</small>
                                <h4 class="mb-0">{{ number_format($totalProducts) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success shadow-sm">
                            <div class="card-body">
                                <small class="text-muted">Total Received Qty</small>
                                <h4 class="mb-0">{{ number_format($totalQty) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info shadow-sm">
                            <div class="card-body">
                                <small class="text-muted">Total Purchase Value</small>
                                <h4 class="mb-0">{{ number_format($totalValue, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>Product Code</th>
                                <th>Part No</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Product Name</th>
                                <th>Model No</th>
                                <th>Description</th>
                                <th>UOM</th>
                                <th class="text-end">Total Stock</th>
                                <th class="text-end">Avg. Purchase Price</th>
                                <th class="text-end">Total Price</th>
                                <th width="100" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $row)
                                @php
                                    $product = $row->product;
                                @endphp
                                <tr>
                                    <td>{{ $products->firstItem() + $loop->index }}</td>
                                    <td>{{ $product->product_code ?? '-' }}</td>
                                    <td>{{ $product->sku ?? '-' }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->brand->name ?? '-' }}</td>
                                    <td><strong>{{ $product->name ?? '-' }}</strong></td>
                                    <td>{{ $product->model_no ?? '-' }}</td>
                                    <td>{{ $product->description ?? '-' }}</td>
                                    <td>{{ $product->unit ?? '-' }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ number_format($product->current_stock) }}</span>
                                    </td>
                                    <td class="text-end">{{ number_format($row->average_rate, 2) }}</td>
                                    <td class="text-end">
                                        <strong>{{ number_format($row->total_value, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('inventory.product.show', $product) }}"
                                            class="btn btn-primary btn-sm" title="View Product Details">
                                            <i class="fa fa-eye me-1"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <i class="fa fa-box-open fa-2x text-muted"></i>
                                        <br>
                                        <span class="text-muted">No Inventory Found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
