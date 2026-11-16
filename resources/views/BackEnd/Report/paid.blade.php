@extends('BackEnd.Layouts.layout')
@section('title', 'Money Paid Report')
@section('content')
    <div class="p-5 mt-5">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">Paid Report</h4>
                <small class="text-muted">
                    Fully paid customer sales and supplier purchase invoices
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
                <form action="{{ route('report.paid') }}" method="GET">
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
                            <label class="form-label">Customer / Supplier</label>
                            <select name="party_id" class="form-select select2">
                                <option value="">All Customers & Suppliers</option>
                                @foreach ($parties as $party)
                                    <option value="{{ $party->id }}" {{ $partyId == $party->id ? 'selected' : '' }}>
                                        {{ $party->name }}
                                        @if ($party->phone)
                                            - {{ $party->phone }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="Invoice, PO, SO, customer, supplier, phone...">
                        </div>

                        <div class="col-md-4 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Filter
                            </button>

                            <a href="{{ route('report.paid') }}" class="btn btn-secondary">
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
                        <small class="text-muted">Customer Paid Invoices</small>
                        <div class="d-flex gap-2 align-items-center">
                            <h4 class="mb-0">
                                {{ number_format($customerSummary->total_invoices ?? 0) }}
                            </h4>
                            <small class="text-muted">
                                Qty: {{ number_format($customerSummary->total_qty ?? 0, 2) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-success border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Customer Total Received</small>
                        <h4 class="mb-0 text-success">
                            ৳{{ number_format($customerSummary->total_paid ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-info border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Supplier Paid Invoices</small>
                        <div class="d-flex gap-2 align-items-center">
                            <h4 class="mb-0">
                                {{ number_format($supplierSummary->total_invoices ?? 0) }}
                            </h4>
                            <small class="text-muted">
                                Qty: {{ number_format($supplierSummary->total_qty ?? 0, 2) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-start border-warning border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Supplier Total Paid</small>
                        <h4 class="mb-0">
                            ৳{{ number_format($supplierSummary->total_paid ?? 0, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

        </div>

        {{-- Customer and Supplier Main Tabs --}}
        <div class="card">

            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#customerPaid"
                            type="button">
                            <i class="fa fa-users"></i> Customer Paid
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#supplierPaid" type="button">
                            <i class="fa fa-truck"></i> Supplier Paid
                        </button>
                    </li>

                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    {{-- ================================================= --}}
                    {{-- CUSTOMER PAID --}}
                    {{-- ================================================= --}}
                    <div class="tab-pane fade show active" id="customerPaid">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Customer Paid Report</h5>
                            <span class="badge bg-success">Fully Paid Sales Invoices</span>
                        </div>

                        <ul class="nav nav-pills mb-3" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#customerInvoiceWise" type="button">
                                    Invoice Wise
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#customerWise"
                                    type="button">
                                    Customer Wise
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">

                            {{-- Customer Invoice Wise --}}
                            <div class="tab-pane fade show active" id="customerInvoiceWise">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Receipt No</th>
                                                <th>Invoice No</th>
                                                <th>SO No</th>
                                                <th>Customer</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Invoice Amount</th>
                                                <th class="text-end">Paid Amount</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Payment Details</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($customerInvoices as $key => $invoice)
                                                <tr>
                                                    <td>
                                                        {{ $customerInvoices->firstItem() + $key }}
                                                    </td>

                                                    <td>
                                                        {{ $invoice->receipt_date ? \Carbon\Carbon::parse($invoice->receipt_date)->format('d M Y') : '-' }}
                                                    </td>

                                                    <td>{{ $invoice->receipt_no }}</td>
                                                    <td>{{ $invoice->inv_no ?? '-' }}</td>
                                                    <td>{{ $invoice->so_no ?? '-' }}</td>

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
                                                        {{ number_format($invoice->total_qty, 2) }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ number_format($invoice->total_amount, 2) }}
                                                    </td>

                                                    <td class="text-end text-success fw-bold">
                                                        {{ number_format($invoice->paid_amount, 2) }}
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-success">Paid</span>
                                                    </td>

                                                    <td>{{ $invoice->creator->name ?? '-' }}</td>

                                                    <td>
                                                        @if ($invoice->payments->count())
                                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#customerPayments{{ $invoice->id }}">
                                                                <i class="fa fa-eye me-1"></i>
                                                                ({{ $invoice->payments->count() }})
                                                            </button>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>

                                                @if ($invoice->payments->count())
                                                    <tr class="collapse" id="customerPayments{{ $invoice->id }}">
                                                        <td colspan="14">
                                                            <strong>Payment History</strong>

                                                            <div class="table-responsive mt-2">
                                                                <table class="table table-sm table-bordered mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Payment Date</th>
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
                                                        No fully paid customer invoices found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                        @if ($customerInvoices->count())
                                            <tfoot>
                                                <tr>
                                                    <th colspan="8" class="text-end">
                                                        Current Page Total
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($customerInvoices->sum('total_qty'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($customerInvoices->sum('total_amount'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($customerInvoices->sum('paid_amount'), 2) }}
                                                    </th>
                                                    <th colspan="3"></th>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                    <div class="p-3">
                                        {{ $customerInvoices->links() }}
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
                                                <th>Customer Name</th>
                                                <th>Phone</th>
                                                <th class="text-center">Invoices</th>
                                                <th class="text-end">Total Qty</th>
                                                <th class="text-end">Total Sales</th>
                                                <th class="text-end">Total Received</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($customerWise as $key => $item)
                                                <tr>
                                                    <td>{{ $customerWise->firstItem() + $key }}</td>

                                                    <td>
                                                        <strong>{{ $item->party_name }}</strong>
                                                    </td>

                                                    <td>{{ $item->party_phone ?: '-' }}</td>

                                                    <td class="text-center">
                                                        {{ number_format($item->total_invoices) }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ number_format($item->total_qty, 2) }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ number_format($item->total_amount, 2) }}
                                                    </td>

                                                    <td class="text-end text-success fw-bold">
                                                        {{ number_format($item->total_paid, 2) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        No customer data found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                        @if ($customerWise->count())
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-end">Current Page Total</th>
                                                    <th class="text-end">
                                                        {{ number_format($customerWise->sum('total_qty'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($customerWise->sum('total_amount'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($customerWise->sum('total_paid'), 2) }}
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                    <div class="p-3">
                                        {{ $customerWise->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- SUPPLIER PAID --}}
                    {{-- ================================================= --}}
                    <div class="tab-pane fade" id="supplierPaid">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Supplier Paid Report</h5>
                            <span class="badge bg-success">Fully Paid Purchase Invoices</span>
                        </div>

                        <ul class="nav nav-pills mb-3" role="tablist">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#supplierInvoiceWise" type="button">
                                    Invoice Wise
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#supplierWise"
                                    type="button">
                                    Supplier Wise
                                </button>
                            </li>

                        </ul>

                        <div class="tab-content">

                            {{-- Supplier Invoice Wise --}}
                            <div class="tab-pane fade show active" id="supplierInvoiceWise">

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
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Purchase Amount</th>
                                                <th class="text-end">Paid Amount</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Payment Details</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($supplierInvoices as $key => $invoice)
                                                <tr>
                                                    <td>
                                                        {{ $supplierInvoices->firstItem() + $key }}
                                                    </td>

                                                    <td>
                                                        {{ $invoice->receipt_date ? \Carbon\Carbon::parse($invoice->receipt_date)->format('d M Y') : '-' }}
                                                    </td>

                                                    <td>{{ $invoice->receipt_no }}</td>
                                                    <td>{{ $invoice->po_no ?? '-' }}</td>

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
                                                        {{ number_format($invoice->total_qty, 2) }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ number_format($invoice->total_amount, 2) }}
                                                    </td>

                                                    <td class="text-end text-success fw-bold">
                                                        {{ number_format($invoice->paid_amount, 2) }}
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-success">Paid</span>
                                                    </td>

                                                    <td>{{ $invoice->creator->name ?? '-' }}</td>

                                                    <td>
                                                        @if ($invoice->payments->count())
                                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#supplierPayments{{ $invoice->id }}">
                                                                <i class="fa fa-eye me-1"></i>
                                                                ({{ $invoice->payments->count() }})
                                                            </button>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>

                                                @if ($invoice->payments->count())
                                                    <tr class="collapse" id="supplierPayments{{ $invoice->id }}">
                                                        <td colspan="13">
                                                            <strong>Payment History</strong>

                                                            <div class="table-responsive mt-2">
                                                                <table class="table table-sm table-bordered mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Payment Date</th>
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
                                                    <td colspan="13" class="text-center py-4">
                                                        No fully paid supplier invoices found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                        @if ($supplierInvoices->count())
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" class="text-end">
                                                        Current Page Total
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($supplierInvoices->sum('total_qty'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($supplierInvoices->sum('total_amount'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($supplierInvoices->sum('paid_amount'), 2) }}
                                                    </th>
                                                    <th colspan="3"></th>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                    <div class="p-3">
                                        {{ $supplierInvoices->links() }}
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
                                                <th class="text-center">Invoices</th>
                                                <th class="text-end">Total Qty</th>
                                                <th class="text-end">Total Purchase</th>
                                                <th class="text-end">Total Paid</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($supplierWise as $key => $item)
                                                <tr>
                                                    <td>{{ $supplierWise->firstItem() + $key }}</td>

                                                    <td>
                                                        <strong>{{ $item->party_name }}</strong>
                                                    </td>

                                                    <td>{{ $item->party_phone ?: '-' }}</td>

                                                    <td class="text-center">
                                                        {{ number_format($item->total_invoices) }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ number_format($item->total_qty, 2) }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ number_format($item->total_amount, 2) }}
                                                    </td>

                                                    <td class="text-end text-success fw-bold">
                                                        {{ number_format($item->total_paid, 2) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        No supplier data found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                        @if ($supplierWise->count())
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-end">Current Page Total</th>
                                                    <th class="text-end">
                                                        {{ number_format($supplierWise->sum('total_qty'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($supplierWise->sum('total_amount'), 2) }}
                                                    </th>
                                                    <th class="text-end">
                                                        {{ number_format($supplierWise->sum('total_paid'), 2) }}
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                    <div class="p-3">
                                        {{ $supplierWise->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Date Filter and Tab Handling --}}
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
            // Restore active main and inner tab after page load.
            function activateTabFromHash() {
                const hash = window.location.hash;
                if (!hash) return;
                const target = document.querySelector(
                    '[data-bs-target="' + hash + '"]'
                );
                if (target && window.bootstrap) {
                    bootstrap.Tab.getOrCreateInstance(target).show();
                }
            }
            document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(button) {
                button.addEventListener('shown.bs.tab', function(event) {
                    const target = event.target.getAttribute('data-bs-target');
                    if (target) {
                        history.replaceState(null, '', target);
                    }
                });
            });
            activateTabFromHash();
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

            .text-success {
                color: #000 !important;
            }

            .badge {
                border: 1px solid #000;
                color: #000 !important;
                background: #fff !important;
            }
        }
    </style>
@endsection
