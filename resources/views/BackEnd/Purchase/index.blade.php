@extends('BackEnd.Layouts.layout')

@section('title', 'Purchase List')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Purchase List</h4>
                <div>
                    @can('purchase-create')
                        <a href="{{ route('purchase.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i> Create Purchase
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-2">
                                <label>PO No</label>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                    placeholder="PO No">
                            </div>
                            <div class="col-md-2">
                                <label>Supplier</label>
                                <select name="supplier" class="form-select select2">
                                    <option value="">All Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>From</label>
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label>To</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 mt-5">
                                <button class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                    Search
                                </button>
                                <a href="{{ route('purchase.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>Date</th>
                                <th>PO No</th>
                                <th>Supplier name</th>
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
                                    <td>{{ $loop->iteration + ($purchases->firstItem() - 1) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($purchase->receipt_date)) }}</td>
                                    <td>{{ $purchase->receipt_no }}</td>
                                    <td>{{ $purchase->supplier->company_name ?? '-' }}</td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->sku }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->name }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->description }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end">{{ $purchase->total_qty }}</td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->rate }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end">{{ number_format($purchase->total_amount, 2) }}</td>
                                    <td>
                                        @if ($purchase->status == 'Completed')
                                            <span class="badge bg-success">
                                                Completed
                                            </span>
                                        @elseif($purchase->status == 'Cancelled')
                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                {{ $purchase->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('purchase.show', $purchase->id) }}"
                                                        class="btn btn-info btn-sm w-100 mb-1" title="View Full Page">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </li>
                                                @can('purchase-edit')
                                                    @if ($purchase->status !== 'Completed')
                                                        <li>
                                                            <a href="{{ route('purchase.edit', $purchase->id) }}"
                                                                class="btn btn-warning btn-sm w-100 mb-1" title="Edit Purchase">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endcan
                                                @can('purchase-delete')
                                                    <li>
                                                        @if ($purchase->status != 'Cancelled' && $purchase->status !== 'Completed')
                                                            <form action="{{ route('purchase.cancel', $purchase->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button class="btn btn-danger btn-sm w-100 mb-1"
                                                                    onclick="return confirm('Cancel this Purchase?')"
                                                                    title="Cancle Purchase">
                                                                    <i class="fa fa-ban"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            No Purchase Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
