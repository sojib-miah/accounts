@extends('BackEnd.Layouts.layout')

@section('title', 'Dashboard')

@section('content')

    <div class="mt-5">
        <div class="p-5">
            {{-- dash board header  --}}
            <div class="dashboard-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center">
                            <div class="header-icon me-3">
                                <i class="fa fa-chart-line"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1">
                                    Financial Dashboard
                                </h3>
                                <p class="text-muted mb-0">
                                    Sales, Purchase, Cash Flow & Account Overview
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 mt-3 mt-md-0">
                        <div class="d-flex justify-content-md-end align-items-center gap-2">
                            @if ($package)
                                <div class="package-box">
                                    <div>
                                        <small class="text-muted d-block">
                                            Package
                                        </small>
                                        <strong>
                                            {{ $package?->package?->name ?? 'No Package' }}
                                        </strong>
                                    </div>
                                    <div>
                                        <strong class="d-block">Start:</strong>
                                        {{ $package?->start_date?->format('d M Y') ?? '-' }}
                                    </div>
                                    <div>
                                        <strong class="d-block">Expire:</strong>
                                        {{ $package?->expire_date?->format('d M Y') ?? '-' }}
                                    </div>
                                    @if ($package?->status === 'Active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @elseif($package?->status === 'Expired')
                                        <span class="badge bg-danger">
                                            Expired
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $package?->status ?? 'Inactive' }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <a href="{{ route('dashboard.pdf') }}" target="_blank" class="btn btn-danger btn-sm">
                                <i class="fa fa-file-pdf me-1"></i>
                                PDF
                            </a>
                            <a href="{{ url()->current() }}" class="btn btn-primary">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TODAY TITLE --}}
            <div class="section-title mb-3">
                <div>
                    <h5 class="fw-bold mb-0">
                        Today's Overview
                    </h5>
                    <small class="text-muted">
                        {{ now()->format('d F Y') }}
                    </small>
                </div>
            </div>

            {{-- TODAY CARDS --}}
            <div class="row g-4">
                {{-- SALES --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card sales-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Today's Sales
                                </span>
                                <h3>
                                    ৳ {{ number_format($todaySales, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PURCHASE --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card purchase-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Today's Purchase
                                </span>
                                <h3>
                                    ৳ {{ number_format($todayPurchase, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-truck"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RECEIVED --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card received-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Today's Received
                                </span>
                                <h3>
                                    ৳ {{ number_format($todayReceived, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-arrow-down"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAID --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card paid-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Today's Paid
                                </span>
                                <h3>
                                    ৳ {{ number_format($todayPaid, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-arrow-up"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- EXPENSE --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card expense-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Today's Expense
                                </span>
                                <h3>
                                    ৳ {{ number_format($todayExpense, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PROFIT --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card profit-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Today's Profit
                                </span>
                                <h3>
                                    ৳ {{ number_format($todayProfit, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RECEIVABLE --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card receivable-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Receivable
                                </span>
                                <h3>
                                    ৳ {{ number_format($receivable, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-hand-holding-usd"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAYABLE --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card payable-card shadow-lg">
                        <div class="stat-content">
                            <div>
                                <span class="stat-title">
                                    Payable
                                </span>
                                <h3>
                                    ৳ {{ number_format($payable, 2) }}
                                </h3>
                            </div>
                            <div class="stat-icon">
                                <i class="fa fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MASTER DATA CARDS --}}
            <div class="section-title mt-5 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">
                        Business Overview
                    </h5>
                    <small class="text-muted">
                        Current system statistics
                    </small>
                </div>
            </div>
            <div class="row g-4">
                {{-- CUSTOMERS --}}
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="small-stat customer-stat shadow-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>Customers</span>
                                <h4>{{ number_format($customers) }}</h4>
                            </div>
                            <div class="small-stat-icon">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUPPLIERS --}}
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="small-stat supplier-stat shadow-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>Suppliers</span>
                                <h4>{{ number_format($suppliers) }}</h4>
                            </div>
                            <div class="small-stat-icon">
                                <i class="fa fa-truck"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BRANCHES --}}
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="small-stat branch-stat shadow-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>Branches</span>
                                <h4>{{ number_format($branches) }}</h4>
                            </div>
                            <div class="small-stat-icon">
                                <i class="fa fa-code-branch"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACCOUNTS --}}
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="small-stat account-stat shadow-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>Accounts</span>
                                <h4>{{ number_format($accounts->count()) }}</h4>
                            </div>
                            <div class="small-stat-icon">
                                <i class="fa fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACCOUNT SUMMARY --}}
            <div class="section-title mt-5 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">
                        Account Summary
                    </h5>
                    <small class="text-muted">
                        All active account balances
                    </small>
                </div>
            </div>
            <div class="row g-4">
                {{-- TOTAL BALANCE --}}
                <div class="col-xl-3 col-lg-3">
                    <div class="account-total-card shadow-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>
                                    Total Account Balance
                                </span>
                                <h4>
                                    ৳ {{ number_format($totalAccountBalance, 2) }}
                                </h4>
                            </div>
                            <div class="account-big-icon">
                                <i class="fa fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- TODAY CREDIT --}}
                <div class="col-xl-3 col-lg-3">
                    <div class="account-credit-card shadow-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>
                                    Today's Credit
                                </span>
                                <h4>
                                    ৳ {{ number_format($todayAccountCredit, 2) }}
                                </h4>
                            </div>
                            <div class="account-big-icon">
                                <i class="fa fa-arrow-down"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TODAY DEBIT --}}
                <div class="col-xl-3 col-lg-3">
                    <div class="account-debit-card shadow-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>
                                    Today's Debit
                                </span>
                                <h4>
                                    ৳ {{ number_format($todayAccountDebit, 2) }}
                                </h4>
                            </div>
                            <div class="account-big-icon">
                                <i class="fa fa-arrow-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACCOUNT SUMMARY --}}
            <div class="section-title mt-5 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">
                        Sales vs Purchase
                    </h5>
                </div>
            </div>
            {{-- SALES VS PURCHASE CHART --}}
            <div class="card dashboard-panel mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0">
                                <i class="fa fa-chart-line text-primary me-2"></i>
                                Sales vs Purchase
                            </h5>
                            <small class="text-muted">
                                Monthly comparison for the last 12 months
                            </small>
                        </div>
                        <span class="badge bg-light text-primary border">
                            12 Months
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 380px;">
                        <canvas id="salesPurchaseChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- TOP CUSTOMERS / SUPPLIERS --}}
            <div class="row g-4 mt-2">
                {{-- TOP CUSTOMERS --}}
                <div class="col-lg-6">
                    <div class="card dashboard-panel h-100">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-bold mb-0">
                                    <i class="fa fa-users text-success me-2"></i>
                                    Top Customers
                                </h5>
                                <span class="badge bg-success">
                                    Top 10
                                </span>
                            </div>
                        </div>
                        <div class="card-body mt-2">
                            @forelse($topCustomers as $customer)
                                @php
                                    $percentage = $totalIncome > 0 ? ($customer->total / $totalIncome) * 100 : 0;
                                @endphp
                                <div class="customer-row mb-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>
                                            {{ $customer->name }}
                                        </strong>
                                        <span class="text-success fw-bold">
                                            ৳ {{ number_format($customer->total, 2) }}
                                        </span>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-success" style="width: {{ min($percentage, 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    No Customer Found
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                {{-- TOP SUPPLIERS --}}
                <div class="col-lg-6">
                    <div class="card dashboard-panel h-100">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-bold mb-0">
                                    <i class="fa fa-truck text-danger me-2"></i>
                                    Top Suppliers
                                </h5>
                                <span class="badge bg-danger">
                                    Top 10
                                </span>
                            </div>
                        </div>
                        <div class="card-body mt-2">
                            @forelse($topSuppliers as $supplier)
                                @php
                                    $percentage = $totalExpense > 0 ? ($supplier->total / $totalExpense) * 100 : 0;
                                @endphp
                                <div class="supplier-row mb-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>
                                            {{ $supplier->name }}
                                        </strong>
                                        <span class="text-danger fw-bold">
                                            ৳ {{ number_format($supplier->total, 2) }}
                                        </span>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-danger" style="width: {{ min($percentage, 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    No Supplier Found
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- QUICK ACTION --}}
            <div class="card dashboard-panel mt-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body text-center py-4">
                    <a href="{{ route('sales.order.create') }}" class="btn btn-success m-1">
                        <i class="fa fa-plus-circle me-1"></i>
                        New Sales
                    </a>
                    <a href="{{ route('receipt.expense.create') }}" class="btn btn-danger m-1">
                        <i class="fa fa-minus-circle me-1"></i>
                        New Expense
                    </a>
                    <a href="{{ route('sales.order.index') }}" class="btn btn-primary m-1">
                        <i class="fa fa-list me-1"></i>
                        Sales List
                    </a>
                    <a href="{{ route('receipt.expense.index') }}" class="btn btn-warning m-1">
                        <i class="fa fa-list me-1"></i>
                        Expense List
                    </a>
                    <a href="{{ route('dashboard.pdf') }}" target="_blank" class="btn btn-dark m-1">
                        <i class="fa fa-file-pdf me-1"></i>
                        Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- CHART --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.getElementById('salesPurchaseChart');
            if (!chartElement) {
                return;
            }
            const labels = @json($chartLabels);
            const salesData = @json($salesData);
            const purchaseData = @json($purchaseData);
            new Chart(chartElement, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Sales',
                            data: salesData,
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25, 135, 84, 0.08)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Purchase',
                            data: purchaseData,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.08)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label +
                                        ': ৳ ' +
                                        Number(context.raw).toLocaleString(
                                            'en-BD', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }
                                        );
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '৳ ' +
                                        Number(value).toLocaleString('en-BD');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .dashboard-header {
            padding: 20px 24px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .04);
        }

        .header-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef4ff;
            color: #0d6efd;
            border-radius: 14px;
            font-size: 23px;
        }

        .package-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 7px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        h4 {
            margin-bottom: 0;
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-card {
            border-radius: 5px;
            padding: 10px;
            border: 1px solid rgba(0, 0, 0, .04);
            transition: all .25s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .09);
        }

        .stat-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-title {
            display: block;
            font-size: 14px;
            font-weight: 600;
        }

        .stat-card h3 {
            font-size: 23px;
            font-weight: 700;
            margin: 0;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .sales-card {
            background: #eaf7ef;
            color: #176b3a;
        }

        .sales-card .stat-icon {
            background: #ccebd8;
            color: #198754;
        }

        .purchase-card {
            background: #fff1ed;
            color: #a33a28;
        }

        .purchase-card .stat-icon {
            background: #ffdcd4;
            color: #dc3545;
        }

        .received-card {
            background: #eaf4ff;
            color: #175ea8;
        }

        .received-card .stat-icon {
            background: #d2e7ff;
            color: #0d6efd;
        }

        .paid-card {
            background: #fff8df;
            color: #8a6900;
        }

        .paid-card .stat-icon {
            background: #ffefb2;
            color: #ffc107;
        }

        .expense-card {
            background: #fcecef;
            color: #a12b3d;
        }

        .expense-card .stat-icon {
            background: #f7d3da;
            color: #dc3545;
        }

        .profit-card {
            background: #e9f8f4;
            color: #087f5b;
        }

        .profit-card .stat-icon {
            background: #ccefe5;
            color: #20c997;
        }

        .receivable-card {
            background: #eef1ff;
            color: #3f51b5;
        }

        .receivable-card .stat-icon {
            background: #dfe3ff;
            color: #4c63d2;
        }

        .payable-card {
            background: #f5edff;
            color: #7139a8;
        }

        .payable-card .stat-icon {
            background: #eadcff;
            color: #6f42c1;
        }

        .small-stat {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, .05);
            transition: .25s;
        }

        .small-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .07);
        }

        .small-stat span {
            color: #6c757d;
            font-size: 13px;
        }

        .small-stat h4 {
            font-size: 24px;
            font-weight: 700;
            margin: 2px 0 0;
        }

        .small-stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 20px;
        }

        .customer-stat {
            background: #eefaf3;
        }

        .customer-stat .small-stat-icon {
            background: #d5f2df;
            color: #198754;
        }

        .supplier-stat {
            background: #fff0ed;
        }

        .supplier-stat .small-stat-icon {
            background: #ffdcd4;
            color: #dc3545;
        }

        .branch-stat {
            background: #edf5ff;
        }

        .branch-stat .small-stat-icon {
            background: #d6e8ff;
            color: #0d6efd;
        }

        .account-stat {
            background: #f4efff;
        }

        .account-stat .small-stat-icon {
            background: #e6dbff;
            color: #6f42c1;
        }

        .account-total-card,
        .account-credit-card,
        .account-debit-card {
            padding: 10px;
            border-radius: 5px;
            height: 100%;
        }

        .account-total-card {
            background: #edf5ff;
            color: #1459a6;
        }

        .account-credit-card {
            background: #eaf8ef;
            color: #16723b;
        }

        .account-debit-card {
            background: #fff0f0;
            color: #b02a37;
        }

        .account-total-card span,
        .account-credit-card span,
        .account-debit-card span {
            font-size: 14px;
            opacity: .8;
        }

        .account-big-icon {
            width: 55px;
            height: 55px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .7);
            font-size: 22px;
        }

        .dashboard-panel {
            border: 1px;
            border-radius: 14px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .dashboard-panel .card-header {
            padding: 17px 20px;
            border-bottom: 1px solid;
        }

        .dashboard-panel .table th {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
            white-space: nowrap;
        }

        .dashboard-panel .table td {
            font-size: 13px;
        }

        .progress {
            height: 7px;
            border-radius: 20px;
            background: #f0f1f3;
        }

        .progress-bar {
            border-radius: 20px;
        }

        @media (max-width: 767px) {
            .dashboard-header {
                padding: 15px;
            }

            .dashboard-header .col-md-5 .d-flex {
                justify-content: flex-start !important;
                flex-wrap: wrap;
            }

            .stat-card h3 {
                font-size: 20px;
            }

            .account-total-card h2,
            .account-credit-card h2,
            .account-debit-card h2 {
                font-size: 21px;
            }
        }
    </style>
@endpush
