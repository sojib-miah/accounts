@extends('BackEnd.Layouts.layout')

@section('title', 'Low Stock Products')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-triangle-exclamation text-danger me-2"></i>
                    Low Stock Products
                </h4>
                <div>
                    <a href="{{ route('inventory.index') }}" class="btn btn-primary">
                        <i class="fa fa-boxes me-2"></i>
                        Inventory
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                                <th width="120">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                @php
                                    $stockValue = $product->current_stock * $product->purchase_price;
                                @endphp
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
                                    </td>
                                    <td>
                                        {{ $product->category->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $product->brand->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $product->unit }}
                                    </td>
                                    <td class="text-end">
                                        @if ($product->current_stock == 0)
                                            <span class="badge bg-danger">
                                                {{ number_format($product->current_stock, 2) }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                {{ number_format($product->current_stock, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->minimum_stock, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->purchase_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->sale_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($stockValue, 2) }}
                                    </td>
                                    <td>
                                        @if ($product->current_stock == 0)
                                            <span class="badge bg-danger">
                                                Out of Stock
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Low Stock
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-danger">
                                        No Low Stock Products Found.
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
