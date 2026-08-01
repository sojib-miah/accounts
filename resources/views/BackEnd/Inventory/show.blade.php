@extends('BackEnd.Layouts.layout')

@section('title', 'Inventory Details')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between">
                <h4 class="mb-0">
                    Inventory Details
                </h4>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-2"></i>
                    Back
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">
                            PO Number
                        </label>
                        <input class="form-control" readonly value="{{ $receipt->po_no }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">
                            Receive Date
                        </label>
                        <input class="form-control" readonly
                            value="{{ date('d-M-Y', strtotime($receipt->received_date)) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">
                            Supplier
                        </label>
                        <input class="form-control" readonly value="{{ $receipt->supplier->name }}">
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold">
                            Remarks
                        </label>
                        <textarea class="form-control" rows="2" readonly>{{ $receipt->remarks }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    Product Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="60">
                                    SL
                                </th>
                                <th>
                                    Code
                                </th>
                                <th>
                                    Product
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
                                    Qty
                                </th>
                                <th class="text-end">
                                    Purchase
                                </th>
                                <th class="text-end">
                                    Sale
                                </th>
                                <th class="text-end">
                                    Stock Value
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = 0;
                                $value = 0;
                            @endphp
                            @foreach ($receipt->items as $item)
                                @php
                                    $stockValue = $item->product->current_stock * $item->product->purchase_price;
                                    $qty += $item->product->current_stock;
                                    $value += $stockValue;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $item->product->product_code }}
                                    </td>
                                    <td>
                                        {{ $item->product->name }}
                                    </td>
                                    <td>
                                        {{ $item->product->category->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $item->product->brand->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $item->product->unit }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->product->current_stock, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->product->purchase_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->product->sale_price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($stockValue, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-end">
                                    Total Stock
                                </th>
                                <th class="text-end">
                                    {{ number_format($qty, 2) }}
                                </th>
                                <th colspan="2" class="text-end">
                                    Total Value
                                </th>
                                <th class="text-end">
                                    {{ number_format($value, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
