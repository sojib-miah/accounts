@extends('BackEnd.Layouts.layout')

@section('title', 'Payments')

@section('content')
    <div class="mt-5">
        <div class="p-5">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Payments</h4>
                    <p class="text-muted mb-0">Manage package subscription payments</p>
                </div>
            </div>
            {{-- Filter --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.payment.index') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Search transaction/user/package...">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>
                                        Paid
                                    </option>
                                    <option value="Failed" {{ request('status') === 'Failed' ? 'selected' : '' }}>
                                        Failed
                                    </option>
                                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                    Search
                                </button>
                            </div>
                            <div class="col-md-1">
                                @if (request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                    <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-refresh me-1"></i>
                                        Reset
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('admin.payment.index') }}" class="btn btn-success w-100">
                                    <i class="fas fa-refresh me-1"></i>
                                    Reload
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Payment Table --}}
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th>User</th>
                                    <th>Package</th>
                                    <th>Company</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Paid At</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>
                                            <strong>
                                                {{ $payment->user?->name ?? '-' }}
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $payment->user?->email ?? '-' }}
                                            </small>
                                        </td>
                                        <td>{{ $payment->package?->name ?? '-' }}</td>
                                        <td>
                                            @if ($payment->company)
                                                {{ $payment->company->name }}
                                            @else
                                                <span class="text-muted">
                                                    No Company
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ $payment->tran_id }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>
                                                {{ number_format($payment->amount, 2) }}
                                            </strong>
                                            {{ $payment->currency }}
                                        </td>
                                        <td>
                                            {{ $payment->payment_method ?? '-' }}
                                        </td>
                                        <td>
                                            @if ($payment->status === 'Paid')
                                                <span class="badge bg-success">
                                                    Paid
                                                </span>
                                            @elseif($payment->status === 'Pending')
                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>
                                            @elseif($payment->status === 'Failed')
                                                <span class="badge bg-danger">
                                                    Failed
                                                </span>
                                            @elseif($payment->status === 'Cancelled')
                                                <span class="badge bg-secondary">
                                                    Cancelled
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($payment->paid_at)
                                                {{ $payment->paid_at->format('d M Y h:i A') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.payment.show', $payment->id) }}"
                                                            class="dropdown-item">
                                                            <i class="fas fa-eye me-2"></i>
                                                            View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    @if ($payment->status !== 'Paid')
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.payment.update-status', $payment->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to mark this payment as Paid?');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="Paid">
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-check me-2"></i>
                                                                    Mark as Paid
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if ($payment->status !== 'Failed')
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.payment.update-status', $payment->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="Failed">
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-times me-2"></i>
                                                                    Mark as Failed
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if ($payment->status !== 'Cancelled')
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.payment.update-status', $payment->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="Cancelled">
                                                                <button type="submit" class="dropdown-item text-secondary">
                                                                    <i class="fas fa-ban me-2"></i>
                                                                    Cancel
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
                                        <td colspan="10" class="text-center py-5">
                                            <i class="fas fa-credit-card fa-2x text-muted mb-2"></i>
                                            <div class="text-muted">
                                                No payment found.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
