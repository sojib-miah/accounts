@extends('BackEnd.Layouts.layout')

@section('title', 'Inventory Product Details')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fa fa-box me-2"></i>
                        Product Details
                    </h4>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="fw-bold">Product</label>
                        <input class="form-control" readonly value="{{ $product->name }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="fw-bold">Description</label>
                        <input class="form-control" readonly value="{{ $product->description }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="fw-bold">SKU</label>
                        <input class="form-control" readonly value="{{ $product->sku ?? '-' }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="fw-bold">Category</label>
                        <input class="form-control" readonly value="{{ $product->category->name ?? '-' }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="fw-bold">Brand</label>
                        <input class="form-control" readonly value="{{ $product->brand->name ?? '-' }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="fw-bold">Unit</label>
                        <input class="form-control" readonly value="{{ $product->unit ?? '-' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <small class="text-muted">Total Received Qty</small>
                                <h4>{{ number_format($totalQty) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body">
                                <small class="text-muted">Current Stock</small>
                                <h4>{{ number_format($product->current_stock) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body">
                                <small class="text-muted">Average Purchase Price</small>
                                <h4>{{ number_format($averageRate, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <small class="text-muted">Serial Count</small>
                                <h4>{{ number_format($serialCount) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fa fa-history me-2"></i>
                    Purchase History
                </h5>
            </div>
            <div class="card-body">
                {{-- Search --}}
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search PO / Receipt / Supplier..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>PO No</th>
                                <th>Receive Date</th>
                                <th>Supplier Company</th>
                                <th>Supplier</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Stock</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Amount</th>
                                <th>Serial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $items->firstItem() + $loop->index }}</td>
                                    <td><strong>{{ $item->receipt->po_no ?? '-' }}</strong></td>
                                    <td>
                                        @if ($item->receipt->received_date)
                                            {{ \Carbon\Carbon::parse($item->receipt->received_date)->format('d-M-Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->receipt->customerCompany->name ?? '-' }}</td>
                                    <td>{{ $item->receipt->supplier->name ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($item->qty) }}</td>
                                    <td class="text-end">{{ number_format($item->product->current_stock) }}</td>
                                    <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                    <td class="text-end"><strong>{{ number_format($item->amount, 2) }}</strong></td>
                                    <td>
                                        @if ($item->serialNumbers->count())
                                            <div style="max-width: 350px; white-space: normal; word-break: break-word;">
                                                @foreach ($item->serialNumbers as $serial)
                                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                                        {{ $serial->serial_no }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No Serial</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">No Purchase History Found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th class="text-end">{{ number_format($totalQty) }}</th>
                                <th></th>
                                <th class="text-end">{{ number_format($totalValue, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
