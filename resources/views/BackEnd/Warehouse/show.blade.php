@extends('BackEnd.Layouts.layout')

@section('title', 'Warehouse Receive')

@section('content')

    <div class="p-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h4 class="mb-0">
                    Warehouse Receive
                </h4>
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
                            Purchase Date
                        </label>
                        <input class="form-control" readonly value="{{ date('d-M-Y', strtotime($receipt->receipt_date)) }}">
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
                                    Product Code
                                </th>
                                <th>
                                    Product
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    Unit
                                </th>
                                <th class="text-end">
                                    Qty
                                </th>
                                <th class="text-end">
                                    Rate
                                </th>
                                <th class="text-end">
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = 0;
                                $amount = 0;
                            @endphp
                            @foreach ($receipt->items as $item)
                                @php
                                    $qty += $item->qty;
                                    $amount += $item->amount;
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
                                        @if ($item->serialNumbers->count())
                                            {{ $item->serialNumbers->pluck('serial_no')->implode(', ') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->product->unit }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->qty, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->rate, 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">
                                    Total
                                </th>
                                <th class="text-end">
                                    {{ number_format($qty, 2) }}
                                </th>
                                <th></th>
                                <th class="text-end">
                                    {{ number_format($amount, 2) }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-end">
                                    Discount
                                </th>
                                <th class="text-end">
                                    {{ number_format($receipt->discount, 2) }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-end">
                                    VAT ({{ $receipt->vat }}%)
                                </th>
                                <th class="text-end">
                                    {{ number_format((($receipt->sub_total - $receipt->discount) * $receipt->vat) / 100, 2) }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-end">
                                    Grand Total
                                </th>
                                <th class="text-end">
                                    {{ number_format($receipt->total_amount, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="text-end mt-4">
                    <a href="{{ route('warehouse.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>
                        Back
                    </a>
                    @if (!$receipt->is_receive)
                        <form action="{{ route('warehouse.receive', $receipt) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Receive this purchase?')">
                                <i class="fa fa-check me-2"></i>
                                Receive Goods
                            </button>
                        </form>
                    @else
                        <button class="btn btn-success" disabled>
                            Received
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
