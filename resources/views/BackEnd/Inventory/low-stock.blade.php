@extends('BackEnd.Layouts.layout')

@section('title', 'Low Stock Products')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                    Low Stock Products
                </h4>
                <a href="{{ route('inventory.index') }}" class="btn btn-primary">
                    <i class="fa fa-arrow-left me-2"></i>
                    Back to Inventory
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SL</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th class="text-end">Current Stock</th>
                                <th class="text-end">Minimum Stock</th>
                                <th class="text-end">Need to Purchase</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                @php
                                    $need = max(0, $product->minimum_stock - $product->current_stock);
                                @endphp
                                <tr>
                                    <td>
                                        {{ $products->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        {{ $product->product_code }}
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        @if ($product->barcode)
                                            <br>
                                            <small class="text-muted">
                                                Barcode : {{ $product->barcode }}
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
                                        <span class="badge bg-danger">
                                            {{ number_format($product->current_stock, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->minimum_stock, 2) }}
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-warning text-dark">
                                            {{ number_format($need, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($product->status == 'Active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase.create', ['product' => $product->id]) }}"
                                            class="btn btn-sm btn-success">
                                            <i class="fa fa-cart-plus me-2"></i> Purchase
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-danger">
                                        No Low Stock Product Found.
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
