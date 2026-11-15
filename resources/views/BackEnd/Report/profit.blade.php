@extends('BackEnd.Layouts.layout')

@section('title', 'Profit Report')

@section('content')

    <div class="p-5 mt-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Profit & Loss Report</h4>
                <p class="text-muted mb-0">Sales revenue, product cost and gross profit analysis</p>
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
                <form action="{{ route('report.profit') }}" method="GET">

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select name="period" id="period" class="form-select">
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
                            <select name="company_id" class="form-select">
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
                            <label class="form-label">Search Invoice / Order / Product</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="Invoice, SO, product...">
                        </div>

                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Apply Filter
                            </button>

                            <a href="{{ route('report.profit') }}" class="btn btn-secondary">
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
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Orders</div>
                        <h4 class="mb-0 text-primary">
                            {{ number_format($summary->total_orders) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Quantity</div>
                        <h4 class="mb-0 text-info">
                            {{ number_format($summary->total_qty, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Sales</div>
                        <h5 class="mb-0">
                            {{ number_format($totalSales, 2) }}
                        </h5>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Cost (COGS)</div>
                        <h5 class="mb-0 text-danger">
                            {{ number_format($totalCost, 2) }}
                        </h5>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Gross Profit / Loss</div>
                        <h5 class="mb-0 {{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($grossProfit, 2) }}
                        </h5>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Profit Margin</div>
                        <h5 class="mb-0 {{ $profitMargin >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($profitMargin, 2) }}%
                        </h5>
                    </div>
                </div>
            </div>

        </div>

        {{-- Profit Tabs --}}
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#invoiceProfit"
                            type="button">
                            <i class="fa fa-file-invoice me-1"></i>
                            Invoice Wise Profit
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#productProfit" type="button">
                            <i class="fa fa-cubes me-1"></i>
                            Product Wise Profit
                        </button>
                    </li>

                </ul>
            </div>

            <div class="card-body">

                <div class="tab-content">

                    {{-- Invoice Wise Profit --}}
                    <div class="tab-pane fade show active" id="invoiceProfit">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Invoice Wise Profit Details</h5>
                            <span class="badge bg-primary">
                                {{ number_format($sales->total()) }} Invoices
                            </span>
                        </div>

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
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Sales Amount</th>
                                        <th class="text-end">Cost (COGS)</th>
                                        <th class="text-end">Gross Profit</th>
                                        <th class="text-end">Margin</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($sales as $key => $sale)
                                        @php
                                            $invoiceProfit = (float) $sale->gross_profit;
                                            $invoiceMargin =
                                                $sale->total_amount > 0
                                                    ? ($invoiceProfit / $sale->total_amount) * 100
                                                    : 0;
                                        @endphp

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
                                                {{ $sale->customer_name ?? ($sale->party->name ?? 'N/A') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $sale->customer_phone ?? ($sale->party->phone ?? '') }}
                                                </small>
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($sale->total_qty, 2) }}
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($sale->total_amount, 2) }}
                                            </td>

                                            <td class="text-end text-danger">
                                                {{ number_format($sale->total_cost, 2) }}
                                            </td>

                                            <td
                                                class="text-end fw-bold {{ $invoiceProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($invoiceProfit, 2) }}
                                            </td>

                                            <td class="text-end">
                                                {{ number_format($invoiceMargin, 2) }}%
                                            </td>

                                            <td>{{ $sale->creator->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4 text-muted">
                                                No profit records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($sales->count())
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-end">
                                                Current Page Total:
                                            </th>
                                            <th class="text-end">
                                                {{ number_format($sales->sum('total_amount'), 2) }}
                                            </th>
                                            <th class="text-end text-danger">
                                                {{ number_format($sales->sum('total_cost'), 2) }}
                                            </th>
                                            <th class="text-end text-success">
                                                {{ number_format($sales->sum('gross_profit'), 2) }}
                                            </th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div class="p-3">
                                {{ $sales->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- Product Wise Profit --}}
                    <div class="tab-pane fade" id="productProfit">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Product Wise Profit Details</h5>
                            <span class="badge bg-primary">
                                {{ number_format($productProfits->total()) }} Products
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th class="text-end">Sold Quantity</th>
                                        <th class="text-end">Sales Amount</th>
                                        <th class="text-end">Cost (COGS)</th>
                                        <th class="text-end">Gross Profit</th>
                                        <th class="text-end">Profit Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productProfits as $key => $product)
                                        @php
                                            $productProfit = (float) $product->gross_profit;
                                            $productMargin =
                                                $product->total_sales > 0
                                                    ? ($productProfit / $product->total_sales) * 100
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $productProfits->firstItem() + $key }}</td>
                                            <td>{{ $product->product_code ?? 'N/A' }}</td>
                                            <td>
                                                <strong>{{ $product->product_name ?? 'Deleted Product' }}</strong>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($product->total_qty, 2) }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($product->total_sales, 2) }}
                                            </td>
                                            <td class="text-end text-danger">
                                                {{ number_format($product->total_cost, 2) }}
                                            </td>
                                            <td
                                                class="text-end fw-bold {{ $productProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($productProfit, 2) }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($productMargin, 2) }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                No product profit records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if ($productProfits->count())
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Current Page Total:</th>

                                            <th class="text-end">
                                                {{ number_format($productProfits->sum('total_qty'), 2) }}
                                            </th>

                                            <th class="text-end">
                                                {{ number_format($productProfits->sum('total_sales'), 2) }}
                                            </th>

                                            <th class="text-end text-danger">
                                                {{ number_format($productProfits->sum('total_cost'), 2) }}
                                            </th>

                                            <th class="text-end text-success">
                                                {{ number_format($productProfits->sum('gross_profit'), 2) }}
                                            </th>

                                            <th></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                            <div class="p-3">
                                {{ $productProfits->links() }}
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

            // Preserve selected tab during pagination/filter navigation
            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');

            tabButtons.forEach(function(button) {
                button.addEventListener('shown.bs.tab', function() {
                    const target = button.getAttribute('data-bs-target');
                    history.replaceState(null, '', target);
                });
            });

            if (window.location.hash) {
                const targetButton = document.querySelector(
                    '[data-bs-target="' + window.location.hash + '"]'
                );

                if (targetButton && window.bootstrap) {
                    bootstrap.Tab.getOrCreateInstance(targetButton).show();
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
        }
    </style>
@endpush
