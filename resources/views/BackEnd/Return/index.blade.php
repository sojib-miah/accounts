@extends('BackEnd.Layouts.layout')
@section('title', 'Return list')
@section('content')
    <div class="p-5 mt-5">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Return List</h4>
                <p class="text-muted mb-0">All Sales Returns and Purchase Returns</p>
            </div>
        </div>
        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Returns</p>
                        <h4 class="mb-0">{{ number_format($totalReturns) }}</h4>
                        <small class="text-muted">Return transactions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Sales Return</p>
                        <h4 class="text-danger mb-0">
                            {{ number_format($salesReturnAmount, 2) }}
                        </h4>
                        <small class="text-muted">Customer returns</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Purchase Return</p>
                        <h4 class="text-warning mb-0">
                            {{ number_format($purchaseReturnAmount, 2) }}
                        </h4>
                        <small class="text-muted">Supplier returns</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Return Qty</p>
                        <h4 class="mb-0">{{ number_format($totalReturnQty, 2) }}</h4>
                        <small class="text-muted">Returned quantities</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('receipt.return.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="Return No, Invoice, Party, Product">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Return Type</label>
                            <select name="type" class="form-select select2">
                                <option value="">All Types</option>
                                <option value="Sales-Return" @selected(request('type') == 'Sales-Return')>
                                    Sales Return
                                </option>
                                <option value="Purchase-Return" @selected(request('type') == 'Purchase-Return')>
                                    Purchase Return
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Company</label>
                            <select name="company_id" class="form-select select2">
                                <option value="">All Companies</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select select2">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected(request('branch_id') == $branch->id)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select select2">
                                <option value="">All Status</option>
                                @foreach (['Completed', 'Draft', 'Cancelled'] as $status)
                                    <option value="{{ $status }}" @selected(request('status') == $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Per Page</label>
                            <select name="per_page" class="form-select select2">
                                @foreach ([15, 24, 50, 100] as $number)
                                    <option value="{{ $number }}" @selected(request('per_page', 24) == $number)>
                                        {{ $number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('receipt.return.index') }}" class="btn btn-light border">
                                <i class="fa fa-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Return Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Return Transactions</h5>
                <span class="badge bg-secondary">
                    {{ $returns->total() }} Records
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Return No</th>
                                <th>Original Invoice</th>
                                <th>Return Type</th>
                                <th>Company / Branch</th>
                                <th>Customer / Supplier</th>
                                <th>Products</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Return Amount</th>
                                <th class="text-center">Return Date</th>
                                <th class="text-center">Created By</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returns as $return)
                                <tr>
                                    <td class="text-center">
                                        {{ $returns->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        <strong>{{ $return->receipt_no }}</strong>
                                    </td>
                                    <td>
                                        {{ $return->originalReceipt?->receipt_no ?? '-' }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $return->originalReceipt?->so_no ??
                                                ($return->originalReceipt?->po_no ?? ($return->originalReceipt?->inv_no ?? '')) }}
                                        </small>
                                    </td>
                                    <td>
                                        @if ($return->type === 'Sales-Return')
                                            <span class="badge bg-danger-subtle text-danger">
                                                Sales Return
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">
                                                Purchase Return
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $return->company?->name ?? '-' }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $return->branch?->name ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        {{ $return->party?->name ?? ($return->customer_name ?? '-') }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $return->party?->phone ?? ($return->customer_phone ?? '') }}
                                        </small>
                                    </td>
                                    <td>
                                        @foreach ($return->items as $item)
                                            <div>
                                                {{ $item->product?->name ?? 'Deleted Product' }}
                                                <small class="text-muted">
                                                    ({{ number_format($item->qty, 2) }})
                                                </small>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($return->total_qty, 2) }}
                                    </td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($return->total_amount, 2) }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($return->receipt_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $return->creator?->name ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($return->status === 'Completed')
                                            <span class="badge bg-success-subtle text-success">
                                                Completed
                                            </span>
                                        @elseif($return->status === 'Draft')
                                            <span class="badge bg-warning-subtle text-warning">
                                                Draft
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <i class="fa fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">
                                            No return records found.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-3">
                        {{ $returns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
