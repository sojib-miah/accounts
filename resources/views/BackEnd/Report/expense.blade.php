@extends('BackEnd.Layouts.layout')

@section('title', 'Expense Report')

@section('content')

    <div class="p-5 mt-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Expense Report</h4>
                <p class="text-muted mb-0">Expense performance and payment details</p>
            </div>

            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="fa fa-print me-1"></i> Print Report
            </button>
        </div>

        {{-- Filters --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fa fa-filter me-1"></i> Filter Expense
                </h6>
            </div>

            <div class="card-body">
                <form action="{{ route('report.expense') }}" method="GET">

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select name="period" id="period" class="form-select select2">
                                <option value="today" @selected($period == 'today')>Today</option>
                                <option value="yesterday" @selected($period == 'yesterday')>Yesterday</option>
                                <option value="last_7_days" @selected($period == 'last_7_days')>Last 7 Days</option>
                                <option value="last_15_days" @selected($period == 'last_15_days')>Last 15 Days</option>
                                <option value="last_30_days" @selected($period == 'last_30_days')>Last 30 Days</option>
                                <option value="this_month" @selected($period == 'this_month')>This Month</option>
                                <option value="last_month" @selected($period == 'last_month')>Last Month</option>
                                <option value="this_year" @selected($period == 'this_year')>This Year</option>
                                <option value="custom" @selected($period == 'custom')>Custom Date</option>
                                <option value="all" @selected($period == 'all')>All Time</option>
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

                        <div class="col-md-2">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select select2">
                                <option value="">All</option>
                                <option value="Pending" @selected($paymentStatus == 'Pending')>Pending</option>
                                <option value="Partial" @selected($paymentStatus == 'Partial')>Partial</option>
                                <option value="Paid" @selected($paymentStatus == 'Paid')>Paid</option>
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
                                placeholder="Invoice, SO, phone, company...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select select2">
                                <option value="">All</option>
                                <option value="Draft" @selected($status == 'Draft')>Draft</option>
                                <option value="Completed" @selected($status == 'Completed')>Completed</option>
                                <option value="Cancelled" @selected($status == 'Cancelled')>Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Apply Filter
                            </button>

                            <a href="{{ route('report.sales') }}" class="btn btn-secondary">
                                <i class="fa fa-refresh me-1"></i> Reset
                            </a>
                        </div>

                    </div>

                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Orders</div>
                        <h4 class="mb-0 text-primary">
                            {{ number_format($summary['total_orders']) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Quantity</div>
                        <h4 class="mb-0 text-info">
                            {{ number_format($summary['total_qty'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Sales</div>
                        <h5 class="mb-0 text-primary">
                            {{ number_format($summary['total_sales'], 2) }}
                        </h5>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Received</div>
                        <h5 class="mb-0 text-success">
                            {{ number_format($summary['total_paid'], 2) }}
                        </h5>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Due</div>
                        <h5 class="mb-0 text-danger">
                            {{ number_format($summary['total_due'], 2) }}
                        </h5>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Discount</div>
                        <h5 class="mb-0 text-warning">
                            {{ number_format($summary['total_discount'], 2) }}
                        </h5>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sales Table --}}
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sales Details</h5>
                <span class="badge bg-primary">
                    {{ number_format($sales->total()) }} Records
                </span>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Order No</th>
                                <th>Company</th>
                                <th>Customer</th>
                                <th>Branch</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Due</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Created By</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($sales as $key => $sale)
                                <tr>
                                    <td>{{ $sales->firstItem() + $key }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($sale->receipt_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        <strong>{{ $sale->inv_no ?? $sale->receipt_no }}</strong>
                                    </td>

                                    <td>{{ $sale->so_no ?? 'N/A' }}</td>

                                    <td>{{ $sale->company->name ?? 'N/A' }}</td>

                                    <td>
                                        <strong>
                                            {{ $sale->customer_name ?? ($sale->party->name ?? 'N/A') }}
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $sale->customer_phone ?? ($sale->party->phone ?? '') }}
                                        </small>
                                    </td>

                                    <td>{{ $sale->branch->name ?? 'N/A' }}</td>

                                    <td class="text-end">
                                        {{ number_format($sale->total_qty, 2) }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        {{ number_format($sale->total_amount, 2) }}
                                    </td>

                                    <td class="text-end text-success">
                                        {{ number_format($sale->paid_amount, 2) }}
                                    </td>

                                    <td class="text-end text-danger">
                                        {{ number_format($sale->due_amount, 2) }}
                                    </td>

                                    <td>
                                        @if ($sale->payment_status == 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($sale->payment_status == 'Partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-danger">Pending</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($sale->status == 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($sale->status == 'Cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>

                                    <td>{{ $sale->creator->name ?? 'N/A' }}</td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="14" class="text-center py-4 text-muted">
                                        <i class="fa fa-folder-open fa-2x mb-2"></i>
                                        <br>
                                        No sales records found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                        @if ($sales->count())
                            <tfoot>
                                <tr>
                                    <th colspan="8" class="text-end">Current Page Total:</th>
                                    <th class="text-end">
                                        {{ number_format($sales->sum('total_amount'), 2) }}
                                    </th>
                                    <th class="text-end text-success">
                                        {{ number_format($sales->sum('paid_amount'), 2) }}
                                    </th>
                                    <th class="text-end text-danger">
                                        {{ number_format($sales->sum('due_amount'), 2) }}
                                    </th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        @endif

                    </table>
                    <div class="p-3">
                        {{ $sales->links() }}
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
        });
    </script>
@endpush

@push('styles')
    <style>
        @media print {
            body {
                background: #fff !important;
            }

            .sidebar,
            .main-sidebar,
            .navbar,
            .main-header,
            .card-header,
            form,
            button,
            .pagination,
            .btn {
                display: none !important;
            }

            .container-fluid {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
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

            .badge {
                border: 1px solid #777;
                color: #000 !important;
                background: transparent !important;
            }
        }
    </style>
@endpush
