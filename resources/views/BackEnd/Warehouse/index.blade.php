@extends('BackEnd.Layouts.layout')

@section('title', 'Warehouse')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm mt-3">
            <div class="card-header">
                <div class="row align-items-center g-3">
                    {{-- Title --}}
                    <div class="col-lg-4">
                        <h4 class="mb-0">
                            <i class="fa fa-warehouse me-2"></i>
                            Warehouse Receive List
                        </h4>
                    </div>
                    {{-- Filters --}}
                    <div class="col-lg-6">
                        <form action="{{ route('warehouse.index') }}" method="GET" class="row g-2">
                            {{-- Search --}}
                            <div class="col-md-5">
                                <input type="search" name="search" class="form-control" value="{{ $search }}"
                                    placeholder="PO No, Part No, Item Name...">
                            </div>
                            {{-- Supplier --}}
                            <div class="col-md-3">
                                <select name="party_id" class="form-select select2">
                                    <option value="">
                                        All Suppliers
                                    </option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $supplierId == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Status --}}
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">
                                        All Status
                                    </option>
                                    <option value="Draft" {{ $status == 'Draft' ? 'selected' : '' }}>
                                        Waiting Receive
                                    </option>
                                    <option value="Completed" {{ $status == 'Completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                    <option value="Cancelled" {{ $status == 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                            {{-- Search --}}
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-search me-1"></i>
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- Pending --}}
                    <div class="col-lg-2 text-lg-end">
                        <div class="d-inline-flex align-items-center gap-2">
                            <span class="text-muted small">
                                Pending
                            </span>
                            <span class="badge bg-warning text-dark fs-6">
                                {{ number_format($pendingCount) }}
                            </span>
                        </div>
                    </div>
                </div>
                {{-- Active Filters --}}
                @if ($search || $supplierId || $status)
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="text-muted small">
                                Active Filters:
                            </span>
                            @if ($search)
                                <span class="badge bg-primary">
                                    Search:
                                    {{ $search }}
                                </span>
                            @endif
                            @if ($supplierId)
                                @php
                                    $selectedSupplier = $suppliers->firstWhere('id', $supplierId);
                                @endphp
                                @if ($selectedSupplier)
                                    <span class="badge bg-info text-dark">
                                        Supplier:
                                        {{ $selectedSupplier->name }}
                                    </span>
                                @endif
                            @endif
                            @if ($status)
                                <span class="badge bg-secondary">
                                    Status:
                                    {{ $status == 'Draft' ? 'Waiting Receive' : $status }}
                                </span>
                            @endif
                            <a href="{{ route('warehouse.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-times me-1"></i>
                                Clear
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>Date</th>
                                <th>PO No</th>
                                <th>Supplier</th>
                                <th>Part No</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total Price</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td>
                                        {{ $purchases->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        {{ $purchase->receipt_date ? date('d-m-Y', strtotime($purchase->receipt_date)) : '' }}
                                    </td>
                                    <td>
                                        <strong>
                                            {{ $purchase->po_no }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{ $purchase->supplier->name ?? '' }}
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->sku ?? '' }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->name ?? '' }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->description ?? '' }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($purchase->total_qty) }}
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ number_format($item->rate, 2) }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($purchase->total_amount, 2) }}
                                    </td>
                                    <td>
                                        @if ($purchase->status == 'Completed')
                                            <span class="badge bg-success">
                                                Completed
                                            </span>
                                        @elseif ($purchase->status == 'Cancelled')
                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Waiting Receive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('warehouse.show', $purchase) }}" class="btn btn-info btn-sm"
                                            title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa fa-search fa-2x mb-2"></i>
                                            <div class="fw-semibold">
                                                No Purchase Found
                                            </div>
                                            @if ($search || $supplierId || $status)
                                                <small>
                                                    Try changing your search or filters.
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
@endsection
