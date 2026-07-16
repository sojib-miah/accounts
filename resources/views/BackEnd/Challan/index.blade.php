@extends('BackEnd.Layouts.layout')

@section('title', 'Challan')

@section('content')
    <div class="py-4">
        <div class="mx-5">
            {{-- Page Header --}}
            <div class="mb-3">
                <h2 class="fw-bold mb-0">
                    Challan
                </h2>
            </div>
            {{-- Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{-- Top Filter --}}
                    <form method="GET" action="{{ route('challan.index') }}">
                        <div class="row mb-3">
                            <div class="col-md-4">
                            </div>
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
                                        <i class="fa fa-search"></i>
                                        Search
                                    </button>
                                    @if (request('search'))
                                        <a href="{{ route('income.receipt.index') }}" class="btn btn-secondary">
                                            Reset
                                        </a>
                                    @endif
                                    @can('income-challan-create')
                                        <a href="{{ route('challan.create') }}" class="btn btn-success">
                                            <i class="fa fa-plus me-2"></i>
                                            Create Challan
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
                                    <th class="text-center">
                                        RECEIPT ID
                                    </th>
                                    <th class="text-center">
                                        RECEiVER
                                    </th>
                                    <th class="text-center">
                                        RECEIPT BY
                                    </th>
                                    <th class="text-center">
                                        DATE & TIME
                                    </th>
                                    <th class="text-center">
                                        STATUS
                                    </th>
                                    <th class="text-center" width="90">
                                        ACTION
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receipts as $receipt)
                                    <tr>
                                        {{-- Receipt ID --}}
                                        <td class="text-center">
                                            <a href="{{ route('income.receipt.show', $receipt->id) }}"
                                                class="text-decoration-none fw-semibold">
                                                {{ $receipt->receipt_no }}
                                            </a>
                                        </td>
                                        {{-- Payee --}}
                                        <td class="text-center">
                                            <a href="{{ route('income.party.profile', $receipt->party_id) }}"
                                                class="text-decoration-none">
                                                {{ $receipt->party->name }}
                                            </a>
                                        </td>
                                        {{-- Receipt By --}}
                                        <td class="text-center">
                                            {{ $receipt->creator->name ?? '-' }}
                                        </td>
                                        {{-- Date Time --}}
                                        <td class="text-center">
                                            {{ $receipt->created_at->format('d-m-Y h:i A') }}
                                        </td>
                                        {{-- Payment Status --}}
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
                                        {{-- Action --}}
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('income.receipt.show', $receipt->id) }}">
                                                            <i class="fa fa-eye text-primary me-2"></i>
                                                            View
                                                        </a>
                                                    </li>
                                                    @if ($receipt->status != 'Cancelled')
                                                        <li>
                                                            <form
                                                                action="{{ route('income.receipt.cancel', $receipt->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button onclick="return confirm('Cancel this receipt?')"
                                                                    class="dropdown-item">
                                                                    <i class="fa fa-ban text-secondary me-2"></i>
                                                                    Cancel
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if ($receipt->payment_status == 'Pending')
                                                        <li>
                                                            <form action="{{ route('receipt.destroy', $receipt->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button onclick="return confirm('Delete this receipt?')"
                                                                    class="dropdown-item text-danger">
                                                                    <i class="fa fa-trash me-2"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fa fa-folder-open fa-4x text-secondary mb-3"></i>
                                            <br>
                                            No Income Receipt Found
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
