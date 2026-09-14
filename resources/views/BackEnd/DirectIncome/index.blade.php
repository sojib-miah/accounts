@extends('BackEnd.Layouts.layout')

@section('title', 'direct Income List')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            {{-- Page Header --}}
            <div class="mb-3">
                <h2 class="fw-bold mb-0">Direct Income List</h2>
            </div>
            {{-- Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{-- Top Filter --}}
                    <form method="GET" action="{{ route('direct.income.index') }}">
                        <div class="row mb-3">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <div class="d-flex justify-content-end flex-wrap gap-2">
                                    <input type="text" name="search" class="form-control" style="max-width:220px"
                                        placeholder="Search Receipt / Party" value="{{ request('search') }}">
                                    <select name="per_page" class="form-select" style="width:90px">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="24" {{ request('per_page', 24) == 24 ? 'selected' : '' }}>24
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                        </option>
                                    </select>
                                    <select name="status" class="form-select" style="width:140px">
                                        <option value="">All Status</option>
                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>
                                            Partial
                                        </option>
                                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>
                                            Paid
                                        </option>
                                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                    </select>
                                    <button class="btn btn-primary">
                                        <i class="fa fa-search me-2"></i>
                                        Search
                                    </button>
                                    @if (request('search'))
                                        <a href="{{ route('direct.income.index') }}" class="btn btn-secondary">
                                            Reset
                                        </a>
                                    @endif
                                    @can('income-receipt-create')
                                        <a href="{{ route('direct.income.create') }}" class="btn btn-success">
                                            <i class="fa fa-plus me-2"></i>
                                            Create Direct Income
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th class="text-center">Order NO</th>
                                    <th class="text-center">Detailes</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Created BY</th>
                                    <th class="text-center">DATE & TIME</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center" width="90">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receipts as $receipt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('direct.income.show', $receipt->id) }}"
                                                class="text-decoration-none fw-semibold">
                                                {{ $receipt->receipt_no ?? '-' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                        <td class="text-center">
                                            {{ $receipt->items->pluck('details')->implode(', ') }}
                                        </td>

                                        </td>
                                        <td class="text-center">{{ $receipt->total_qty ?? '-' }}</td>
                                        <td class="text-center">{{ $receipt->creator->name ?? '-' }}</td>
                                        <td class="text-center">{{ $receipt->created_at->format('d-m-Y h:i A') }}</td>
                                        <td class="text-center">
                                            @if ($receipt->payment_status == 'Paid')
                                                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                                    Paid
                                                </span>
                                            @elseif($receipt->payment_status == 'Partial')
                                                <span class="badge rounded-pill bg-info-subtle text-info px-3 py-2">
                                                    Partial
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">
                                                    Unpaid
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('direct.income.show', $receipt->id) }}">
                                                            <i class="fa fa-eye text-primary me-2"></i>
                                                            View
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="fa fa-folder-open fa-4x text-secondary mb-3"></i>
                                            <br>
                                            No Direct Income Receipt Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <small class="text-muted">
                        Showing
                        {{ $receipts->firstItem() ?? 0 }}
                        to
                        {{ $receipts->lastItem() ?? 0 }}
                        of
                        {{ $receipts->total() }}
                        Entries
                    </small>
                </div>
                <div>
                    {{ $receipts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
