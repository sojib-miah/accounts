@extends('BackEnd.Layouts.layout')

@section('title', 'Stock Report')

@section('content')

    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <h4>
                    Stock Report
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
                </div>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>PO No</th>
                                <th>Receive Date</th>
                                <th>Supplier</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Qty</th>
                                <th>Purchase</th>
                                <th>Current Stock</th>
                                <th>Stock Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQty = 0;
                                $totalValue = 0;
                            @endphp
                            @foreach ($receipts as $receipt)
                                @foreach ($receipt->items as $item)
                                    @php
                                        $totalQty += $item->product->current_stock;
                                        $value = $item->product->current_stock * $item->product->purchase_price;
                                        $totalValue += $value;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $receipt->po_no }}
                                        </td>
                                        <td>
                                            {{ $receipt->received_date }}
                                        </td>
                                        <td>
                                            {{ $receipt->supplier->name }}
                                        </td>
                                        <td>
                                            {{ $item->product->name }}
                                        </td>
                                        <td>
                                            {{ $item->product->category->name ?? '' }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->qty, 2) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->rate, 2) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->product->current_stock, 2) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($value, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">
                                    Total
                                </th>
                                <th class="text-end">
                                    {{ number_format($totalQty, 2) }}
                                </th>
                                <th></th>
                                <th></th>
                                <th class="text-end">
                                    {{ number_format($totalValue, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $receipts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
