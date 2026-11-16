@extends('BackEnd.Layouts.layout')

@section('title', 'Receivable Report')

@section('content')

    <div class="p-5 mt-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Accounts Receivable Report</h4>
                <p class="text-muted mb-0">Outstanding customer invoices and collection details</p>
            </div>

            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="fa fa-print me-1"></i> Print Report
            </button>
        </div>

        {{-- Filters --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fa fa-filter me-1"></i> Report Filters
                </h6>
            </div>

            <div class="card-body">
                <form action="{{ route('report.receivable') }}" method="GET">

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select name="period" id="period" class="form-select select2">
                                <option value="all" @selected($period == 'all')>All Time</option>
                                <option value="today" @selected($period == 'today')>Today</option>
                                <option value="yesterday" @selected($period == 'yesterday')>Yesterday</option>
                                <option value="last_7_days" @selected($period == 'last_7_days')>Last 7 Days</option>
                                <option value="last_15_days" @selected($period == 'last_15_days')>Last 15 Days</option>
                                <option value="last_30_days" @selected($period == 'last_30_days')>Last 30 Days</option>
                                <option value="this_month" @selected($period == 'this_month')>This Month</option>
                                <option value="last_month" @selected($period == 'last_month')>Last Month</option>
                                <option value="this_year" @selected($period == 'this_year')>This Year</option>
                                <option value="custom" @selected($period == 'custom')>Custom Date</option>
                            </select>
                        </div>

                        <div class="col-md-2 custom-date">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>

                        <div class="col-md-2 custom-date">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Company</label>
                            <select name="company_id" class="form-select select2">
                                <option value="">All Companies</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" @selected($companyId == $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" value="{{ $customerName }}" class="form-control"
                                placeholder="Search customer name">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Search Invoice / Order</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="Invoice, SO, phone...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select select2">
                                <option value="">All</option>
                                <option value="Pending" @selected($paymentStatus == 'Pending')>Pending</option>
                                <option value="Partial" @selected($paymentStatus == 'Partial')>Partial</option>
                                <option value="Paid" @selected($paymentStatus == 'Paid')>Paid</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Apply Filter
                            </button>

                            <a href="{{ route('report.receivable') }}" class="btn btn-secondary">
                                <i class="fa fa-refresh me-1"></i> Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Receivable</div>
                        <h4 class="mb-0 text-danger">
                            ৳{{ number_format($summary->total_receivable, 2) }}
                        </h4>
                        <small class="text-muted">Outstanding Amount</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Invoices</div>
                        <h4 class="mb-0 text-primary">
                            {{ number_format($summary->total_invoices) }}
                        </h4>
                        <small class="text-muted">Unpaid / Partially Paid</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Invoice Amount</div>
                        <h4 class="mb-0">
                            ৳{{ number_format($summary->total_invoice_amount, 2) }}
                        </h4>
                        <small class="text-muted">Total Sales Amount</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Received</div>
                        <h4 class="mb-0 text-success">
                            ৳{{ number_format($summary->total_received, 2) }}
                        </h4>
                        <small class="text-muted">Collected Against These Invoices</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- Aging Summary --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fa fa-clock me-1"></i> Receivable Aging Analysis
                </h6>
                <small class="text-muted">
                    Aging calculated from invoice date
                </small>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-3">
                        <div class="p-3 rounded bg-light">
                            <div class="text-muted small">0–30 Days</div>
                            <h5 class="mb-0 text-success">
                                ৳{{ number_format($aging->days_0_30, 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 rounded bg-light">
                            <div class="text-muted small">31–60 Days</div>
                            <h5 class="mb-0 text-primary">
                                ৳{{ number_format($aging->days_31_60, 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 rounded bg-light">
                            <div class="text-muted small">61–90 Days</div>
                            <h5 class="mb-0 text-warning">
                                ৳{{ number_format($aging->days_61_90, 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 rounded bg-light">
                            <div class="text-muted small">Over 90 Days</div>
                            <h5 class="mb-0 text-danger">
                                ৳{{ number_format($aging->days_over_90, 2) }}
                            </h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Receivable Tabs --}}
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#invoiceReceivable"
                            type="button">
                            <i class="fa fa-file-invoice me-1"></i>
                            Invoice Wise Receivable
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#customerReceivable"
                            type="button">
                            <i class="fa fa-users me-1"></i>
                            Customer Wise Receivable
                        </button>
                    </li>

                </ul>
            </div>

            <div class="card-body">

                <div class="tab-content">

                    {{-- Invoice Wise Receivable --}}
                    <div class="tab-pane fade show active" id="invoiceReceivable">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Invoice Wise Outstanding</h5>
                            <span class="badge bg-danger">
                                {{ number_format($receivables->total()) }} Invoices
                            </span>
                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice No</th>
                                        <th>Order No</th>
                                        <th>Company</th>
                                        <th>Customer</th>
                                        <th>Branch</th>
                                        <th class="text-end">Invoice Amount</th>
                                        <th class="text-end">Received</th>
                                        <th class="text-end">Due Amount</th>
                                        <th>Age</th>
                                        <th>Payment Status</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($receivables as $key => $receipt)
                                        <tr>

                                            <td>{{ $receivables->firstItem() + $key }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d M Y') }}
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $receipt->inv_no ?? $receipt->receipt_no }}
                                                </strong>
                                            </td>

                                            <td>{{ $receipt->so_no ?? 'N/A' }}</td>

                                            <td>{{ $receipt->company->name ?? 'N/A' }}</td>

                                            <td>
                                                <strong>
                                                    {{ $receipt->customer_name ?? ($receipt->party->name ?? 'N/A') }}
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $receipt->customer_phone ?? ($receipt->party->phone ?? '') }}
                                                </small>
                                            </td>

                                            <td>{{ $receipt->branch->name ?? 'N/A' }}</td>

                                            <td class="text-end">
                                                {{ number_format($receipt->total_amount, 2) }}
                                            </td>

                                            <td class="text-end text-success">
                                                {{ number_format($receipt->paid_amount, 2) }}
                                            </td>

                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($receipt->due_amount, 2) }}
                                            </td>

                                            <td>
                                                @if ($receipt->age_days <= 30)
                                                    <span class="badge bg-success">
                                                        {{ $receipt->age_days }} Days
                                                    </span>
                                                @elseif($receipt->age_days <= 60)
                                                    <span class="badge bg-primary">
                                                        {{ $receipt->age_days }} Days
                                                    </span>
                                                @elseif($receipt->age_days <= 90)
                                                    <span class="badge bg-warning text-dark">
                                                        {{ $receipt->age_days }} Days
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        {{ $receipt->age_days }} Days
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($receipt->payment_status == 'Partial')
                                                    <span class="badge bg-warning text-dark">Partial</span>
                                                @else
                                                    <span class="badge bg-danger">Pending</span>
                                                @endif
                                            </td>

                                            <td>{{ $receipt->creator->name ?? 'N/A' }}</td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="13" class="text-center text-muted py-4">
                                                <i class="fa fa-folder-open fa-2x mb-2"></i>
                                                <br>
                                                No outstanding receivables found.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                                @if ($receivables->count())
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-end">
                                                Current Page Total:
                                            </th>

                                            <th class="text-end">
                                                {{ number_format($receivables->sum('total_amount'), 2) }}
                                            </th>

                                            <th class="text-end text-success">
                                                {{ number_format($receivables->sum('paid_amount'), 2) }}
                                            </th>

                                            <th class="text-end text-danger">
                                                {{ number_format($receivables->sum('due_amount'), 2) }}
                                            </th>

                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div class="p-3">
                                {{ $receivables->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- Customer Wise Receivable --}}
                    <div class="tab-pane fade" id="customerReceivable">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Customer Wise Outstanding</h5>

                            <span class="badge bg-primary">
                                {{ number_format($customerReceivables->total()) }} Customers
                            </span>
                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Customer Name</th>
                                        <th>Phone</th>
                                        <th class="text-end">Total Invoices</th>
                                        <th class="text-end">Invoice Amount</th>
                                        <th class="text-end">Received</th>
                                        <th class="text-end">Outstanding</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($customerReceivables as $key => $customer)
                                        <tr>

                                            <td>{{ $customerReceivables->firstItem() + $key }}</td>

                                            <td>
                                                <strong>
                                                    {{ $customer->customer_name ?? 'N/A' }}
                                                </strong>
                                            </td>

                                            <td>{{ $customer->customer_phone ?? 'N/A' }}</td>

                                            <td class="text-end">
                                                {{ number_format($customer->total_invoices) }}
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($customer->total_amount, 2) }}
                                            </td>

                                            <td class="text-end text-success">
                                                {{ number_format($customer->total_paid, 2) }}
                                            </td>

                                            <td class="text-end fw-bold text-danger">
                                                {{ number_format($customer->total_due, 2) }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No customer receivables found.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                                @if ($customerReceivables->count())
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">
                                                Current Page Total:
                                            </th>

                                            <th class="text-end">
                                                {{ number_format($customerReceivables->sum('total_amount'), 2) }}
                                            </th>

                                            <th class="text-end text-success">
                                                {{ number_format($customerReceivables->sum('total_paid'), 2) }}
                                            </th>

                                            <th class="text-end text-danger">
                                                {{ number_format($customerReceivables->sum('total_due'), 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div class="p-3">
                                {{ $customerReceivables->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const period = document.getElementById('period');
            const customDateFields = document.querySelectorAll('.custom-date');

            function toggleCustomDates() {
                const show = period.value === 'custom';

                customDateFields.forEach(function(field) {
                    field.style.display = show ? '' : 'none';
                });
            }

            period.addEventListener('change', toggleCustomDates);
            toggleCustomDates();

            // Preserve the selected tab when navigating
            document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(button) {
                button.addEventListener('shown.bs.tab', function() {
                    history.replaceState(null, '', button.getAttribute('data-bs-target'));
                });
            });

            if (window.location.hash) {
                const button = document.querySelector(
                    '[data-bs-target="' + window.location.hash + '"]'
                );

                if (button && window.bootstrap) {
                    bootstrap.Tab.getOrCreateInstance(button).show();
                }
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        @media print {

            .sidebar,
            .main-sidebar,
            .navbar,
            .main-header,
            form,
            button,
            .pagination,
            .btn,
            .card-header {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                font-size: 9px !important;
            }

            th,
            td {
                padding: 4px !important;
            }
        }
    </style>
@endpush
