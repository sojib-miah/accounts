@extends('BackEnd.Layouts.layout')

@section('title', 'Inventory')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">
                            <i class="fa fa-boxes me-2"></i>
                            Inventory
                        </h4>
                        <small class="text-muted">
                            Individual Purchase Items
                        </small>
                    </div>

                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <span class="badge bg-primary me-1">
                            Items:
                            {{ number_format($totalItems) }}
                        </span>
                        <span class="badge bg-success me-1">
                            Qty:
                            {{ number_format($totalQty, 2) }}
                        </span>
                        <span class="badge bg-dark">
                            Value:
                            {{ number_format($totalValue, 2) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- SEARCH --}}
                <form method="GET" action="{{ route('inventory.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search product, SKU, PO or supplier..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-2"></i>
                                    Search
                                </button>
                            </div>
                        </div>
                        @if (request('search'))
                            <div class="col-md-2">
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
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
                                    PO No
                                </th>
                                <th>
                                    Receive Date
                                </th>
                                <th>
                                    Product
                                </th>
                                <th>
                                    SKU
                                </th>
                                <th>
                                    Supplier
                                </th>
                                <th class="text-center">
                                    Qty
                                </th>
                                <th class="text-end">
                                    Rate
                                </th>
                                <th class="text-end">
                                    Amount
                                </th>
                                <th class="text-center">
                                    Serial
                                </th>
                                <th class="text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                @php
                                    $receipt = $item->receipt;
                                    $product = $item->product;
                                    $serialCount = $item->serialNumbers()->count();
                                @endphp
                                <tr>
                                    {{-- SL --}}
                                    <td>
                                        {{ $items->firstItem() + $loop->index }}
                                    </td>
                                    {{-- PO --}}
                                    <td>
                                        <span class="fw-semibold">
                                            {{ $receipt->po_no ?? $receipt->receipt_no }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $receipt->receipt_no }}
                                        </small>
                                    </td>
                                    {{-- RECEIVE DATE --}}
                                    <td>
                                        @if ($receipt->received_date)
                                            {{ \Carbon\Carbon::parse($receipt->received_date)->format('d-M-Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    {{-- PRODUCT --}}
                                    <td>
                                        <strong>
                                            {{ $product->name ?? '-' }}
                                        </strong>
                                    </td>
                                    {{-- SKU --}}
                                    <td>
                                        {{ $product->sku ?? '-' }}
                                    </td>
                                    {{-- SUPPLIER --}}
                                    <td>
                                        {{ $receipt->supplier->name ?? '-' }}
                                    </td>
                                    {{-- QTY --}}
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            {{ number_format($item->qty, 2) }}
                                        </span>
                                    </td>
                                    {{-- RATE --}}
                                    <td class="text-end">
                                        {{ number_format($item->rate, 2) }}
                                    </td>
                                    {{-- AMOUNT --}}
                                    <td class="text-end">
                                        <strong>
                                            {{ number_format($item->amount, 2) }}
                                        </strong>
                                    </td>
                                    {{-- SERIAL --}}
                                    <td class="text-center">
                                        @if ($serialCount > 0)
                                            <a href="{{ route('inventory.item.show', $item) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fa fa-barcode me-2"></i>
                                                {{ $serialCount }}
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">
                                                No Serial
                                            </span>
                                        @endif
                                    </td>
                                    {{-- ACTION --}}
                                    <td class="text-center">
                                        <a href="{{ route('inventory.item.show', $item) }}" class="btn btn-primary btn-sm"
                                            title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="fa fa-box-open fa-2x text-muted mb-2"></i>
                                        <br>
                                        <span class="text-muted">
                                            No Received Inventory Found
                                        </span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($items->count())
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">
                                        Page Total:
                                    </th>
                                    <th class="text-center">
                                        {{ number_format($items->sum('qty'), 2) }}
                                    </th>
                                    <th></th>
                                    <th class="text-end">
                                        {{ number_format($items->sum('amount'), 2) }}
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
