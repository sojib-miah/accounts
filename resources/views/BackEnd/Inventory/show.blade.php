@extends('BackEnd.Layouts.layout')

@section('title', 'Inventory Item')

@section('content')
    <div class="p-5">
        <div class="mb-3">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-2"></i>
                Back to Inventory
            </a>
        </div>
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fa fa-box me-2"></i>
                    Purchase Item Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- PRODUCT --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            Product
                        </label>
                        <div class="fw-bold fs-5">
                            {{ $item->product->name ?? '-' }}
                        </div>
                    </div>
                    {{-- SKU --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            SKU
                        </label>
                        <div class="fw-bold">
                            {{ $item->product->sku ?? '-' }}
                        </div>
                    </div>
                    {{-- UNIT --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            Unit
                        </label>
                        <div class="fw-bold">
                            {{ $item->product->unit ?? '-' }}
                        </div>
                    </div>
                    {{-- PO --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            PO No
                        </label>
                        <div>
                            {{ $item->receipt->po_no ?? $item->receipt->receipt_no }}
                        </div>
                    </div>
                    {{-- RECEIVE DATE --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            Receive Date
                        </label>
                        <div>
                            @if ($item->receipt->received_date)
                                {{ \Carbon\Carbon::parse($item->receipt->received_date)->format('d-M-Y') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    {{-- SUPPLIER --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            Supplier
                        </label>
                        <div>
                            {{ $item->receipt->supplier->name ?? '-' }}
                        </div>
                    </div>
                    {{-- PURCHASE QTY --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            Purchase Qty
                        </label>
                        <div>
                            <span class="badge bg-primary fs-6">
                                {{ number_format($item->qty, 2) }}
                            </span>
                        </div>
                    </div>
                    {{-- PURCHASE RATE --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            Purchase Rate
                        </label>
                        <div class="fw-bold">
                            {{ number_format($item->rate, 2) }}
                        </div>
                    </div>
                    {{-- TOTAL --}}
                    <div class="col-md-4 mb-3">
                        <label class="text-muted">
                            Total Amount
                        </label>
                        <div class="fw-bold text-success">
                            {{ number_format($item->amount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-barcode me-2"></i>
                    Serial / IMEI Numbers
                </h5>
                <span class="badge bg-success">
                    Total:
                    {{ $serials->count() }}
                </span>
            </div>
            <div class="card-body">
                @if ($serials->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="70">
                                        SL
                                    </th>
                                    <th>
                                        Serial / IMEI No
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Receive Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serials as $serial)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <strong>
                                                {{ $serial->serial_no }}
                                            </strong>
                                        </td>
                                        <td>
                                            @if ($serial->status == 'Available')
                                                <span class="badge bg-success">
                                                    Available
                                                </span>
                                            @elseif($serial->status == 'Sold')
                                                <span class="badge bg-danger">
                                                    Sold
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    {{ $serial->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($serial->receive_date)
                                                {{ \Carbon\Carbon::parse($serial->receive_date)->format('d-M-Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-barcode fa-3x text-muted"></i>
                        <p class="mt-3 text-muted">
                            No serial number found for this purchase item.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
