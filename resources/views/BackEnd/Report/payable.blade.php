@extends('BackEnd.Layouts.layout')
@section('title', 'Payable Report')
@section('content')
    <div class="p-5 mt-5">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">Accounts Payable Report</h4>
                <small class="text-muted">
                    Outstanding purchase payments and supplier balances
                </small>
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
                <form action="{{ route('report.payable') }}" method="GET">
                    <div class="row g-3">

                        <div class="col-md-2">
                            <label class="form-label">Date Range</label>
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
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select select2">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $supplierId == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select select2">
                                <option value="">All Status</option>
                                <option value="Pending" {{ $paymentStatus == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Partial" {{ $paymentStatus == 'Partial' ? 'selected' : '' }}>Partial
                                </option>
                                <option value="Paid" {{ $paymentStatus == 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="PO, Invoice, Receipt, Supplier, Phone">
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('report.payable') }}" class="btn btn-secondary">
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
                        <small class="text-muted">Total Purchase Invoices</small>
                        <div class="d-flex gap-3 align-items-center">
                            <h4 class="mb-0">{{ number_format($summary->total_invoices ?? 0) }}</h4>
                            <small class="text-muted">
                                Qty: {{ number_format($summary->total_qty ?? 0, 2) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-info border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Purchase</small>
                        <h4 class="mb-0">
                            ৳{{ number_format($summary->total_purchase ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-success border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Paid</small>
                        <h4 class="mb-0 text-success">
                            ৳{{ number_format($summary->total_paid ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-danger border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Payable / Due</small>
                        <h4 class="mb-0 text-danger">
                            ৳{{ number_format($summary->total_due ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

        </div>

        {{-- Aging Summary --}}
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Payable Aging Summary</h5>
                <small class="text-muted">
                    Invoice age calculated from purchase receipt date
                </small>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">0–30 Days</small>
                            <h5 class="mb-0">
                                ৳{{ number_format($aging->days_0_30 ?? 0, 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">31–60 Days</small>
                            <h5 class="mb-0">
                                ৳{{ number_format($aging->days_31_60 ?? 0, 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">61–90 Days</small>
                            <h5 class="mb-0">
                                ৳{{ number_format($aging->days_61_90 ?? 0, 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">Over 90 Days</small>
                            <h5 class="mb-0 text-danger">
                                ৳{{ number_format($aging->days_over_90 ?? 0, 2) }}
                            </h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Report Tabs --}}
        <div class="card">

            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="payableTabs" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#invoiceWise"
                            type="button">
                            Invoice Wise
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#supplierWise" type="button">
                            Supplier Wise
                        </button>
                    </li>

                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    {{-- Invoice Wise --}}
                    <div class="tab-pane fade show active" id="invoiceWise">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Receipt No</th>
                                        <th>PO No</th>
                                        <th>Supplier</th>
                                        <th>Company</th>
                                        <th>Branch</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Paid</th>
                                        <th class="text-end">Due</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Payment Details</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($invoices as $key => $invoice)
                                        <tr>
                                            <td>{{ $invoices->firstItem() + $key }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($invoice->receipt_date)->format('d M Y') }}
                                            </td>

                                            <td>
                                                {{ $invoice->receipt_no }}
                                            </td>

                                            <td>
                                                {{ $invoice->po_no ?? '-' }}
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $invoice->party->name ?? ($invoice->customer_name ?? 'N/A') }}
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $invoice->party->phone ?? ($invoice->customer_phone ?? '') }}
                                                </small>
                                            </td>

                                            <td>{{ $invoice->company->name ?? '-' }}</td>

                                            <td>{{ $invoice->branch->name ?? '-' }}</td>

                                            <td class="text-end">
                                                {{ number_format($invoice->total_amount, 2) }}
                                            </td>

                                            <td class="text-end text-success">
                                                {{ number_format($invoice->paid_amount, 2) }}
                                            </td>

                                            <td class="text-end text-danger fw-bold">
                                                {{ number_format($invoice->due_amount, 2) }}
                                            </td>

                                            <td>
                                                {{ max(0, (int) $invoice->age_days) }} Days
                                            </td>

                                            <td>
                                                @if ($invoice->payment_status == 'Paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($invoice->payment_status == 'Partial')
                                                    <span class="badge bg-warning text-dark">Partial</span>
                                                @else
                                                    <span class="badge bg-danger">Pending</span>
                                                @endif
                                            </td>

                                            <td>{{ $invoice->creator->name ?? '-' }}</td>

                                            <td>
                                                @if ($invoice->payments->count())
                                                    <button class="btn btn-sm btn-outline-primary" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#payments{{ $invoice->id }}">
                                                        View ({{ $invoice->payments->count() }})
                                                    </button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>

                                        @if ($invoice->payments->count())
                                            <tr class="collapse" id="payments{{ $invoice->id }}">
                                                <td colspan="14">
                                                    <strong>Payment History</strong>

                                                    <div class="table-responsive mt-2">
                                                        <table class="table table-sm table-bordered mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Payment Type</th>
                                                                    <th>Account</th>
                                                                    <th>Note</th>
                                                                    <th class="text-end">Amount</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($invoice->payments as $payment)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '-' }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $payment->paymentType->name ?? '-' }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $payment->account->account_name ?? '-' }}
                                                                        </td>

                                                                        <td>{{ $payment->note ?? '-' }}</td>

                                                                        <td class="text-end">
                                                                            {{ number_format($payment->amount, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center py-4">
                                                No payable records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if ($invoices->count())
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-end">Current Page Total</th>
                                            <th class="text-end">
                                                {{ number_format($invoices->sum('total_amount'), 2) }}
                                            </th>
                                            <th class="text-end">
                                                {{ number_format($invoices->sum('paid_amount'), 2) }}
                                            </th>
                                            <th class="text-end">
                                                {{ number_format($invoices->sum('due_amount'), 2) }}
                                            </th>
                                            <th colspan="4"></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div class="p-3">
                                {{ $invoices->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- Supplier Wise --}}
                    <div class="tab-pane fade" id="supplierWise">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Supplier Name</th>
                                        <th>Phone</th>
                                        <th class="text-center">Total Invoices</th>
                                        <th class="text-end">Total Purchase</th>
                                        <th class="text-end">Total Paid</th>
                                        <th class="text-end">Total Due</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($supplierWise as $key => $supplier)
                                        <tr>
                                            <td>{{ $supplierWise->firstItem() + $key }}</td>

                                            <td>
                                                <strong>{{ $supplier->supplier_name }}</strong>
                                            </td>

                                            <td>{{ $supplier->supplier_phone ?: '-' }}</td>

                                            <td class="text-center">
                                                {{ number_format($supplier->total_invoices) }}
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($supplier->total_purchase, 2) }}
                                            </td>

                                            <td class="text-end text-success">
                                                {{ number_format($supplier->total_paid, 2) }}
                                            </td>

                                            <td class="text-end text-danger fw-bold">
                                                {{ number_format($supplier->total_due, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                No supplier payable records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $supplierWise->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Custom Date Visibility --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const period = document.getElementById('period');
            const customDateFields = document.querySelectorAll('.custom-date');

            function toggleCustomDate() {
                const show = period.value === 'custom';

                customDateFields.forEach(function(field) {
                    field.style.display = show ? 'block' : 'none';
                });
            }
            period.addEventListener('change', toggleCustomDate);
            toggleCustomDate();
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
            .card-header,
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

            .card-body {
                padding: 5px !important;
            }

            .tab-pane {
                display: block !important;
                opacity: 1 !important;
            }

            .table {
                font-size: 10px !important;
            }

            .table th,
            .table td {
                padding: 4px !important;
            }

            .collapse {
                display: table-row !important;
            }

            .text-danger {
                color: #000 !important;
            }
        }
    </style>
@endsection
