@extends('BackEnd.Layouts.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="mx-5 py-4">
        <!-- ================= Header ================= -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fa fa-chart-line text-primary me-2"></i>
                    Financial Dashboard with Dhurobo
                </h2>
                <small class="text-muted">
                    Income • Expense • Cash Flow • Reports
                </small>
            </div>
            <div>
                <a href="{{ route('dashboard.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fa fa-file-pdf me-2"></i>
                    PDF
                </a>
                <a href="{{ route('dashboard.excel') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-file-excel me-2"></i>
                    Excel
                </a>
                <a href="{{ url()->current() }}" class="btn btn-primary">
                    <i class="fa fa-sync"></i>
                </a>
            </div>
        </div>
        <!-- ================= KPI Cards ================= -->
        <div class="row g-4">
            <!-- Today Income -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card dashboard-card income-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small>
                                    TODAY INCOME
                                </small>
                                <h3>
                                    {{ number_format($todayIncome, 2) }}
                                </h3>
                            </div>
                            <i class="fa fa-arrow-down fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Today Expense -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card dashboard-card expense-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small>
                                    TODAY EXPENSE
                                </small>
                                <h3>
                                    {{ number_format($todayExpense, 2) }}
                                </h3>
                            </div>
                            <i class="fa fa-arrow-up fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Today Profit -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card dashboard-card profit-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small>
                                    TODAY PROFIT
                                </small>
                                <h3>
                                    {{ number_format($todayProfit, 2) }}
                                </h3>
                            </div>
                            <i class="fa fa-chart-line fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Balance -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card dashboard-card balance-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small>
                                    CURRENT BALANCE
                                </small>
                                <h3>
                                    {{ number_format($currentBalance, 2) }}
                                </h3>

                            </div>
                            <i class="fa fa-wallet fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================ -->
        <div class="row g-4 mt-1">
            <!-- Receivable -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small>
                            TOTAL RECEIVABLE
                        </small>
                        <h4 class="text-primary mt-2">
                            {{ number_format($totalReceivable, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Payable -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small>
                            TOTAL PAYABLE
                        </small>
                        <h4 class="text-danger mt-2">
                            {{ number_format($totalPayable, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================= -->
        <div class="row g-4 mt-1">
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3>
                            {{ $totalCustomer }}
                        </h3>
                        <small>
                            Customers
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3>
                            {{ $totalSupplier }}
                        </h3>
                        <small>
                            Suppliers
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3>
                            {{ $totalBranch }}
                        </h3>
                        <small>
                            Branches
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3>
                            {{ $totalAccount }}
                        </h3>
                        <small>
                            Accounts
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3>
                            {{ $totalReceipt }}
                        </h3>
                        <small>
                            Receipts
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3>
                            {{ $totalPayment }}
                        </h3>
                        <small>
                            Payments
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <!-- =============================================== -->
        <!-- Financial Analytics -->
        <!-- =============================================== -->
        <div class="row mt-4">
            <!-- Income vs Expense -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold mb-0">
                                Monthly Income vs Expense
                            </h5>
                            <span class="badge bg-primary">
                                Last 12 Months
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeExpenseChart" height="110"></canvas>
                    </div>
                </div>
            </div>
            <!-- Financial Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Financial Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td>
                                    Total Income
                                </td>
                                <td class="text-end text-success fw-bold">
                                    {{ number_format($totalIncome, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total Expense
                                </td>
                                <td class="text-end text-danger fw-bold">
                                    {{ number_format($totalExpense, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Gross Profit
                                </td>
                                <td class="text-end text-primary fw-bold">
                                    {{ number_format($grossProfit, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Receivable
                                </td>
                                <td class="text-end text-warning">
                                    {{ number_format($totalReceivable, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Payable
                                </td>
                                <td class="text-end text-danger">
                                    {{ number_format($totalPayable, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- =============================================== -->
        <!-- Profit Trend -->
        <!-- =============================================== -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Monthly Profit Trend
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="profitChart" height="95"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Growth Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <small>
                                Income Growth
                            </small>
                            <h3 class="text-success">
                                {{ number_format($incomeGrowth, 2) }}%
                            </h3>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ min($incomeGrowth, 100) }}%">
                                </div>
                            </div>
                        </div>
                        <div>
                            <small>
                                Expense Growth
                            </small>
                            <h3 class="text-danger">
                                {{ number_format($expenseGrowth, 2) }}%
                            </h3>
                            <div class="progress">
                                <div class="progress-bar bg-danger" style="width: {{ min($expenseGrowth, 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================================================== -->
        <!-- Cash Flow -->
        <!-- ====================================================== -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold mb-0">
                                Cash Flow (Last 30 Days)
                            </h5>
                            <span class="badge bg-success">
                                Daily
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="cashFlowChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Payment Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    Paid
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-success">
                                        {{ $paymentSummary['paid'] }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Partial
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-warning text-dark">
                                        {{ $paymentSummary['partial'] }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Pending
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-danger">
                                        {{ $paymentSummary['pending'] }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    Completed
                                </td>
                                <td class="text-end">
                                    {{ $receiptSummary['completed'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Draft
                                </td>
                                <td class="text-end">
                                    {{ $receiptSummary['draft'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Cancelled
                                </td>
                                <td class="text-end">
                                    {{ $receiptSummary['cancelled'] }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================================================== -->
        <!-- Category Analysis -->
        <!-- ====================================================== -->
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Expense Category
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expenseCategoryChart" height="230"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Income Category
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeCategoryChart" height="230"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================================================== -->
        <!-- Top Analytics -->
        <!-- ====================================================== -->
        <div class="row mt-4">
            <!-- ================= Top Customers ================= -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
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
                    <div class="card-body">
                        @forelse($topCustomers as $customer)
                            @php
                                $percentage = $totalIncome > 0 ? ($customer->total / $totalIncome) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>
                                        {{ $customer->name }}
                                    </strong>
                                    <span class="text-success">
                                        {{ number_format($customer->total, 2) }}
                                    </span>
                                </div>
                                <div class="progress mt-1">
                                    <div class="progress-bar bg-success" style="width:{{ min($percentage, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">
                                No Customer Found
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- ================= Top Suppliers ================= -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
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
                    <div class="card-body">
                        @forelse($topSuppliers as $supplier)
                            @php
                                $percentage = $totalExpense > 0 ? ($supplier->total / $totalExpense) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>
                                        {{ $supplier->name }}
                                    </strong>
                                    <span class="text-danger">
                                        {{ number_format($supplier->total, 2) }}
                                    </span>
                                </div>
                                <div class="progress mt-1">
                                    <div class="progress-bar bg-danger" style="width:{{ min($percentage, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">
                                No Supplier Found
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================================================== -->
        <!-- Top Receipts -->
        <!-- ====================================================== -->
        <div class="row mt-4">
            <!-- Income -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Highest Income Receipts
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Receipt</th>
                                    <th>Customer</th>
                                    <th class="text-end">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topIncomeReceipts as $receipt)
                                    <tr>
                                        <td>
                                            <a href="{{ route('income.receipt.show', $receipt->id) }}">
                                                {{ $receipt->receipt_no }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $receipt->party->name ?? '-' }}
                                        </td>
                                        <td class="text-end text-success fw-bold">
                                            {{ number_format($receipt->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Expense -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Highest Expense Receipts
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Receipt</th>
                                    <th>Supplier</th>
                                    <th class="text-end">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topExpenseReceipts as $receipt)
                                    <tr>
                                        <td>
                                            <a href="{{ route('receipt.show', $receipt->id) }}">
                                                {{ $receipt->receipt_no }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $receipt->party->name ?? '-' }}
                                        </td>
                                        <td class="text-end text-danger fw-bold">
                                            {{ number_format($receipt->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================================================== -->
        <!-- Activity Center -->
        <!-- ====================================================== -->
        <div class="row mt-4">
            <!-- ================= Recent Receipts ================= -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            Recent Receipts
                        </h5>
                        <a href="{{ route('receipt.expense.index') }}" class="btn btn-sm btn-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Party</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReceipts as $receipt)
                                    <tr>
                                        <td>
                                            <a href="{{ route('receipt.show', $receipt->id) }}">
                                                {{ $receipt->receipt_no }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $receipt->party->name ?? '-' }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($receipt->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            No Data
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ================= Recent Payments ================= -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            Recent Payments
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Receipt</th>
                                    <th>Method</th>
                                    <th class="text-end">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                    <tr>
                                        <td>
                                            {{ $payment->receipt->receipt_no ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $payment->paymentType->name ?? '-' }}
                                        </td>
                                        <td class="text-end text-success">
                                            {{ number_format($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            No Payment
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ================= Recent Transactions ================= -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">
                            Account Transactions
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $tran)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($tran->transaction_date)->format('d M') }}
                                        </td>
                                        <td>
                                            @if ($tran->transaction_type == 'Income')
                                                <span class="badge bg-success">
                                                    Income
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    Expense
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($tran->credit + $tran->debit, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            No Transaction
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================================================== -->
        <!-- Quick Actions -->
        <!-- ====================================================== -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">
                    Quick Actions
                </h5>
            </div>
            <div class="card-body text-center">
                <a href="{{ route('income.receipt.create') }}" class="btn btn-success m-2">
                    <i class="fa fa-plus-circle me-2"></i>
                    New Income
                </a>
                <a href="{{ route('receipt.expense.create') }}" class="btn btn-danger m-2">
                    <i class="fa fa-minus-circle me-2"></i>
                    New Expense
                </a>
                <a href="{{ route('income.receipt.index') }}" class="btn btn-primary m-2">
                    <i class="fa fa-list me-2"></i>
                    Income List
                </a>
                <a href="{{ route('receipt.expense.index') }}" class="btn btn-warning m-2">
                    <i class="fa fa-list me-2"></i>
                    Expense List
                </a>
                <a href="{{ route('dashboard.pdf') }}" class="btn btn-danger m-2">
                    <i class="fa fa-file-pdf me-2"></i>
                    Export PDF
                </a>
                <a href="{{ route('dashboard.excel') }}" class="btn btn-success m-2">
                    <i class="fa fa-file-excel me-2"></i>
                    Export Excel
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .dashboard-card {
            border: 0;
            border-radius: 15px;
            color: #fff;
            transition: .3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
        }

        .income-card {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .expense-card {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
        }

        .profit-card {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
        }

        .balance-card {
            background: linear-gradient(135deg, #198754, #0dcaf0);
        }

        .card {
            border-radius: 15px;
        }

        .card-header {
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }

        .progress {
            height: 8px;
        }

        .table td {
            vertical-align: middle;
        }

        canvas {
            max-width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = 'Poppins';
        Chart.defaults.plugins.legend.position = 'bottom';
        /* =============================
         Income vs Expense
        ============================= */
        new Chart(document.getElementById('incomeExpenseChart'), {
            type: 'bar',
            data: {
                labels: @json($monthLabel),
                datasets: [{
                        label: 'Income',
                        data: @json($monthlyIncome)
                    },
                    {
                        label: 'Expense',
                        data: @json($monthlyExpense)
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        /* =============================
        Profit Trend
        ============================= */
        new Chart(document.getElementById('profitChart'), {
            type: 'line',
            data: {
                labels: @json($monthLabel),
                datasets: [{
                    label: 'Profit',
                    data: @json($monthlyProfit),
                    fill: true,
                    tension: .4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        /* =============================
        Cash Flow
        ============================= */
        new Chart(document.getElementById('cashFlowChart'), {
            type: 'line',
            data: {
                labels: @json($cashFlowLabel),
                datasets: [{
                        label: 'Cash In',
                        data: @json($cashIn),
                        tension: .4
                    },
                    {
                        label: 'Cash Out',
                        data: @json($cashOut),
                        tension: .4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        /* =============================
           Expense Category
            ============================= */
        new Chart(document.getElementById('expenseCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($expenseCategoryLabel),
                datasets: [{
                    data: @json($expenseCategoryAmount)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        /* =============================
           Income Category
            ============================= */
        new Chart(document.getElementById('incomeCategoryChart'), {
            type: 'pie',
            data: {
                labels: @json($incomeCategoryLabel),
                datasets: [{
                    data: @json($incomeCategoryAmount)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endpush
