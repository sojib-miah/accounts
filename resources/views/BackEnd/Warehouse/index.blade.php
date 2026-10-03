@extends('BackEnd.Layouts.layout')

@section('title', 'Warehouse')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm mt-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    {{-- Title --}}
                    <div class="">
                        <h4 class="mb-0">
                            <i class="fa fa-warehouse me-2"></i>
                            Warehouse Receive List
                        </h4>
                    </div>
                    {{-- Filters --}}
                    <div class="">
                        <form action="{{ route('warehouse.index') }}" method="GET"
                            class="d-flex justify-content-center align-items-center gap-2">
                            <div style="width: 200px;">
                                <select name="company_id" class="form-select select2 w-100">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="search" name="search" class="form-control" value="{{ $search }}"
                                    placeholder="PO No, Part No, Item Name...">
                            </div>
                            <div style="width: 200px;">
                                <select name="party_id" class="form-select select2 w-100">
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
                            <div style="width: 200px;">
                                <select name="status" class="form-select select2 w-100">
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i>
                                Search
                            </button>
                            @if (request('search') || request('company_id') || request('party_id') || request('status'))
                                <a href="{{ route('warehouse.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>
                    {{-- Pending --}}
                    <div class="">
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
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>Company Name</th>
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
                                    <td>{{ $purchases->firstItem() + $loop->index }}</td>
                                    <td>{{ $purchase->company->name ?? '-' }}</td>
                                    <td>{{ $purchase->receipt_date ? date('d-m-Y', strtotime($purchase->receipt_date)) : '' }}
                                    </td>
                                    <td><strong>{{ $purchase->po_no }}</strong></td>
                                    <td>{{ $purchase->supplier->name ?? '-' }}</td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->sku ?? '-' }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->name ?? '-' }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->description ?? '-' }}
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
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li class="mb-2">
                                                    <a href="{{ route('warehouse.show', $purchase) }}"
                                                        class="btn btn-info btn-sm w-100" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
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
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 p-3">
                    {{ $purchases->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
