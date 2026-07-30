@extends('BackEnd.Layouts.layout')

@section('title', 'Inventory Report')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-chart-bar me-2"></i>
                    Inventory Report
                </h4>
                <div>
                    <a href="{{ route('inventory.print') }}" target="_blank" class="btn btn-success">
                        <i class="fa fa-print me-2"></i>
                        Print
                    </a>
                    <a href="{{ route('inventory.pdf') }}" target="_blank" class="btn btn-info">
                        <i class="fa-regular fa-file-pdf me"></i>
                        PDF
                    </a>
                    <a href="{{ route('inventory.index') }}" class="btn btn-primary">
                        <i class="fa fa-arrow-left me-2"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                @php
                    $totalStock = 0;
                    $purchaseValue = 0;
                    $saleValue = 0;
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="60">SL</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th class="text-end">Purchase Price</th>
                                <th class="text-end">Sale Price</th>
                                <th class="text-end">Current Stock</th>
                                <th class="text-end">Purchase Value</th>
                                <th class="text-end">Sale Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                @php
                                    $purchaseTotal = $product->purchase_price * $product->current_stock;
                                    $saleTotal = $product->sale_price * $product->current_stock;
                                    $totalStock += $product->current_stock;
                                    $purchaseValue += $purchaseTotal;
                                    $saleValue += $saleTotal;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->product_code }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->unit }}</td>
                                    <td class="text-end">
                                        {{ number_format($product->purchase_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->sale_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($product->current_stock, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($purchaseTotal, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($saleTotal, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-danger">
                                        No Inventory Found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end">
                                    Grand Total
                                </th>
                                <th class="text-end">
                                    {{ number_format($totalStock, 2) }}
                                </th>
                                <th class="text-end">
                                    {{ number_format($purchaseValue, 2) }}
                                </th>
                                <th class="text-end">
                                    {{ number_format($saleValue, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
