@extends('BackEnd.Layouts.layout')

@section('title', 'Make Payment')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    Make Payment
                </h4>
                <form action="{{ route('purchase.payment.index') }}" method="GET" class="d-flex gap-2">
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
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search PO / Supplier">
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                    @if (request('search') || request('company_id'))
                        <a href="{{ route('purchase.payment.index') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Company Name</th>
                                    <th>PO No</th>
                                    <th>Date</th>
                                    <th>Supplier Company</th>
                                    <th>Supplier</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Status</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchases->firstItem() + $loop->index }}</td>
                                        <td>{{ $purchase->company->name ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $purchase->po_no ?? ($purchase->receipt_no ?? '-') }}</strong>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->receipt_date)->format('d-m-Y') }}</td>
                                        <td>{{ $purchase->party->customerCompany->name ?? '-' }}</td>
                                        <td>
                                            {{ $purchase->party->name ?? '-' }}
                                            @if ($purchase->party?->phone)
                                                <br>
                                                <small class="text-muted">{{ $purchase->party->phone }}</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                        <td class="text-success">{{ number_format($purchase->paid_amount, 2) }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($purchase->due_amount, 2) }}</td>
                                        <td>
                                            @if ($purchase->payment_status === 'Pending')
                                                <span class="badge bg-danger">Pending</span>
                                            @elseif($purchase->payment_status === 'Partial')
                                                <span class="badge bg-warning">Partial</span>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        @if ($purchase->payment_status != 'Paid')
                                                            <a href="{{ route('purchase.payment.show', $purchase->id) }}"
                                                                class="btn btn-primary btn-sm w-100">
                                                                <i class="fa fa-money-bill-wave me-2"></i>
                                                                Payment
                                                            </a>
                                                        @else
                                                            <a href="{{ route('purchase.payment.show', $purchase->id) }}"
                                                                class="btn btn-success btn-sm w-100">
                                                                <i class="fa-regular fa-eye me-2"></i>
                                                                View
                                                            </a>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="fa fa-info-circle me-1"></i>
                                            No pending purchase payment found.
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
    </div>
@endsection
