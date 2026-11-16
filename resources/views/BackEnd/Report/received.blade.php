@extends('BackEnd.Layouts.layout')
@section('title', 'Money Received Report')

@section('content')
    <div class="p-5 mt-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">Money Received Report</h4>
                <small class="text-muted">Customer payment collection report</small>
            </div>

            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="fa fa-print"></i> Print Report
            </button>
        </div>

        {{-- Filters --}}
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Report Filters</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('report.received') }}" method="GET">
                    <div class="row g-3">

                        <div class="col-md-2">
                            <label class="form-label">Payment Date Range</label>
                            <select name="period" id="period" class="form-select select2">
                                <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Dates</option>
                                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                <option value="last7" {{ $period == 'last7' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="last15" {{ $period == 'last15' ? 'selected' : '' }}>Last 15 Days</option>
                                <option value="last30" {{ $period == 'last30' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>This Month
                                </option>
                                <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>Last Month
                                </option>
                                <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>This Year</option>
                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Date</option>
                            </select>
                        </div>

                        <div class="col-md-2 custom-date">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-2 custom-date">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                        </div>

                        @if (auth()->user()->hasRole('Super-Admin'))
                            <div class="col-md-2">
                                <label class="form-label">Company</label>
                                <select name="company_id" class="form-select select2">
                                    <option value="">All Companies</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $companyId == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-2">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select select2">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ $customerId == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type_id" class="form-select select2">
                                <option value="">All Payment Types</option>
                                @foreach ($paymentTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $paymentTypeId == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Account</label>
                            <select name="account_id" class="form-select select2">
                                <option value="">All Accounts</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}"
                                        {{ $accountId == $account->id ? 'selected' : '' }}>
                                        {{ $account->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="Invoice, SO, customer, phone, account...">
                        </div>

                        <div class="col-md-2 gap-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Filter
                            </button>

                            <a href="{{ route('report.received') }}" class="btn btn-secondary">
                                <i class="fa fa-refresh me-1"></i> Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-3">

            <div class="col-md-3">
                <div class="card border-start border-primary border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Transactions</small>
                        <h4 class="mb-0">
                            {{ number_format($summary->total_transactions ?? 0) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-info border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Invoices</small>
                        <h4 class="mb-0">
                            {{ number_format($summary->total_invoices ?? 0) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-success border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Received</small>
                        <h4 class="mb-0 text-success">
                            ৳{{ number_format($summary->total_received ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-warning border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Average Payment</small>
                        <h4 class="mb-0">
                            ৳{{ number_format($summary->average_payment ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

        </div>

        {{-- Payment Type and Account Summary --}}
        <div class="row g-3 mb-3">

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Type Wise Summary</h5>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Payment Type</th>
                                    <th class="text-center">Transactions</th>
                                    <th class="text-end">Received</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($paymentTypeWise as $item)
                                    <tr>
                                        <td>{{ $item->payment_type_name ?? 'Unknown' }}</td>
                                        <td class="text-center">
                                            {{ number_format($item->total_transactions) }}
                                        </td>
                                        <td class="text-end fw-bold">
                                            ৳{{ number_format($item->total_received, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No payment data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if ($paymentTypeWise->count())
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th class="text-end">
                                            ৳{{ number_format($paymentTypeWise->sum('total_received'), 2) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Account Wise Summary</h5>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th class="text-center">Transactions</th>
                                    <th class="text-end">Received</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($accountWise as $item)
                                    <tr>
                                        <td>{{ $item->account_name ?? 'Unknown Account' }}</td>
                                        <td class="text-center">
                                            {{ number_format($item->total_transactions) }}
                                        </td>
                                        <td class="text-end fw-bold">
                                            ৳{{ number_format($item->total_received, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No account data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if ($accountWise->count())
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th class="text-end">
                                            ৳{{ number_format($accountWise->sum('total_received'), 2) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- Report Tabs --}}
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#transactionWise"
                            type="button">
                            Transaction Wise
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#invoiceWise" type="button">
                            Invoice Wise
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#customerWise" type="button">
                            Customer Wise
                        </button>
                    </li>

                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    {{-- Transaction Wise --}}
                    <div class="tab-pane fade show active" id="transactionWise">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Payment Date</th>
                                        <th>Receipt No</th>
                                        <th>Invoice No</th>
                                        <th>SO No</th>
                                        <th>Customer</th>
                                        <th>Company</th>
                                        <th>Payment Type</th>
                                        <th>Account</th>
                                        <th class="text-end">Received</th>
                                        <th>Note</th>
                                        <th>Received By</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($transactions as $key => $item)
                                        <tr>
                                            <td>{{ $transactions->firstItem() + $key }}</td>

                                            <td>
                                                {{ $item->payment_date ? \Carbon\Carbon::parse($item->payment_date)->format('d M Y') : '-' }}
                                            </td>

                                            <td>{{ $item->receipt_no ?? '-' }}</td>
                                            <td>{{ $item->inv_no ?? '-' }}</td>
                                            <td>{{ $item->so_no ?? '-' }}</td>

                                            <td>
                                                <strong>
                                                    {{ $item->customer_name_from_party ?? ($item->customer_name ?? 'N/A') }}
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $item->customer_phone_from_party ?? ($item->customer_phone ?? '') }}
                                                </small>
                                            </td>

                                            <td>{{ $item->company_name ?? '-' }}</td>

                                            <td>{{ $item->payment_type_name ?? '-' }}</td>

                                            <td>{{ $item->account_name ?? '-' }}</td>

                                            <td class="text-end text-success fw-bold">
                                                {{ number_format($item->amount, 2) }}
                                            </td>

                                            <td>{{ $item->note ?? '-' }}</td>

                                            <td>{{ $item->creator_name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                No received transactions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if ($transactions->count())
                                    <tfoot>
                                        <tr>
                                            <th colspan="9" class="text-end">Current Page Total</th>
                                            <th class="text-end">
                                                {{ number_format($transactions->sum('amount'), 2) }}
                                            </th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div class="p-3">
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- Invoice Wise --}}
                    <div class="tab-pane fade" id="invoiceWise">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Receipt No</th>
                                        <th>Invoice No</th>
                                        <th>SO No</th>
                                        <th>Customer</th>
                                        <th>Company</th>
                                        <th class="text-center">Transactions</th>
                                        <th class="text-end">Invoice Total</th>
                                        <th class="text-end">Total Paid</th>
                                        <th class="text-end">Due</th>
                                        <th class="text-end">Received in Period</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($invoiceWise as $key => $item)
                                        <tr>
                                            <td>{{ $invoiceWise->firstItem() + $key }}</td>

                                            <td>{{ $item->receipt_no ?? '-' }}</td>
                                            <td>{{ $item->inv_no ?? '-' }}</td>
                                            <td>{{ $item->so_no ?? '-' }}</td>

                                            <td>
                                                {{ $item->party_name ?? ($item->customer_name ?? 'N/A') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $item->party_phone ?? ($item->customer_phone ?? '') }}
                                                </small>
                                            </td>

                                            <td>{{ $item->company_name ?? '-' }}</td>

                                            <td class="text-center">
                                                {{ number_format($item->total_transactions) }}
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($item->total_amount, 2) }}
                                            </td>

                                            <td class="text-end text-success">
                                                {{ number_format($item->paid_amount, 2) }}
                                            </td>

                                            <td class="text-end text-danger">
                                                {{ number_format($item->due_amount, 2) }}
                                            </td>

                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($item->total_received_in_period, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-4">
                                                No invoice received data found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="p-3">
                                {{ $invoiceWise->appends(request()->except('invoice_page'))->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- Customer Wise --}}
                    <div class="tab-pane fade" id="customerWise">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th class="text-center">Invoices</th>
                                        <th class="text-center">Transactions</th>
                                        <th class="text-end">Total Received</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($customerWise as $key => $item)
                                        <tr>
                                            <td>{{ $customerWise->firstItem() + $key }}</td>

                                            <td>
                                                <strong>
                                                    {{ $item->party_name ?? ($item->customer_name ?? 'N/A') }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $item->party_phone ?? ($item->customer_phone ?? '-') }}
                                            </td>

                                            <td class="text-center">
                                                {{ number_format($item->total_invoices) }}
                                            </td>

                                            <td class="text-center">
                                                {{ number_format($item->total_transactions) }}
                                            </td>

                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($item->total_received, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                No customer received data found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="p-3">
                                {{ $customerWise->appends(request()->except('customer_page'))->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Custom Date Filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const period = document.getElementById('period');
            const customFields = document.querySelectorAll('.custom-date');

            function toggleCustomDate() {
                const show = period.value === 'custom';

                customFields.forEach(function(field) {
                    field.style.display = show ? 'block' : 'none';
                });
            }

            period.addEventListener('change', toggleCustomDate);
            toggleCustomDate();

            // Keep selected tab after filtering or pagination.
            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');

            tabButtons.forEach(function(button) {
                button.addEventListener('shown.bs.tab', function(event) {
                    if (event.target.dataset.bsTarget) {
                        history.replaceState(null, '', event.target.dataset.bsTarget);
                    }
                });
            });

            if (window.location.hash) {
                const target = document.querySelector(
                    '[data-bs-target="' + window.location.hash + '"]'
                );

                if (target && window.bootstrap) {
                    bootstrap.Tab.getOrCreateInstance(target).show();
                }
            }
        });
    </script>

    {{-- Print Style --}}
    <style>
        @media print {
            body {
                background: #fff !important;
            }

            .sidebar,
            .main-sidebar,
            .navbar,
            .header,
            form,
            button,
            .pagination,
            .btn {
                display: none !important;
            }

            .card {
                border: 0 !important;
                box-shadow: none !important;
            }

            .card-header {
                display: block !important;
                padding: 5px !important;
            }

            .card-body {
                padding: 5px !important;
            }

            .tab-pane {
                display: block !important;
                opacity: 1 !important;
            }

            .table {
                font-size: 9px !important;
            }

            .table th,
            .table td {
                padding: 4px !important;
            }

            .text-success,
            .text-danger {
                color: #000 !important;
            }
        }
    </style>
@endsection
