@extends('BackEnd.Layouts.layout')

@section('title', 'Expense Details')

@section('content')
    <div class="py-5 px-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="fas fa-file-invoice-dollar text-danger"></i>
                    Expense Details Report
                </h3>
                <small class="text-muted">
                    Daily, Monthly & Yearly Expense Summary
                </small>
            </div>
            <div>
                <a href="{{ route('receipt.expense.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>
                    New Expense
                </a>
            </div>
        </div>
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-white-50">
                                    Today's Expense
                                </h6>
                                <h3 class="fw-bold">
                                    {{ number_format($todayExpense, 2) }}
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-white-50">
                                    This Month
                                </h6>
                                <h3 class="fw-bold">
                                    {{ number_format($monthExpense, 2) }}
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-white-50">
                                    This Year
                                </h6>
                                <h3 class="fw-bold">
                                    {{ number_format($yearExpense, 2) }}
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-line fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm bg-dark text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-white-50">
                                    Total Records
                                </h6>
                                <h3 class="fw-bold">
                                    {{ $receipts->total() }}
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-list fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter"></i>
                    Search & Filter
                </h5>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label class="form-label fw-bold">
                                Search
                            </label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Receipt No / Customer">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label fw-bold">
                                Payment Status
                            </label>
                            <select name="payment_status" class="form-select">
                                <option value="">
                                    All
                                </option>
                                <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>
                                    Paid
                                </option>
                                <option value="Partial" {{ request('payment_status') == 'Partial' ? 'selected' : '' }}>
                                    Partial
                                </option>
                                <option value="Due" {{ request('payment_status') == 'Due' ? 'selected' : '' }}>
                                    Due
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label fw-bold">
                                From Date
                            </label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label fw-bold">
                                To Date
                            </label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>

                        <div class="col-lg-3 d-flex align-items-end mb-3">
                            <button class="btn btn-primary me-2">
                                <i class="fas fa-search"></i>
                                Search
                            </button>
                            <a href="{{ route('expense.details') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-rotate-left"></i>
                                Reset
                            </a>
                            {{-- <button type="button" class="btn btn-success" onclick="window.print()">
                                <i class="fas fa-print"></i>
                                Print
                            </button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-table text-primary"></i>
                    Expense List
                </h5>
                <span class="badge bg-primary fs-6">
                    Total : {{ $receipts->total() }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="text-center">
                            <tr>
                                <th width="60">SL</th>
                                <th width="110">Date</th>
                                <th width="120">Receipt No</th>
                                <th width="180">Customer / Party</th>
                                <th>Expense Details</th>
                                <th width="120">Amount</th>
                                <th width="120">Paid</th>
                                <th width="120">Due</th>
                                <th width="120">Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $key=>$receipt)
                                <tr>
                                    <td class="text-center">
                                        {{ $receipts->firstItem() + $key }}
                                    </td>
                                    <td class="text-center">
                                        {{ date('d-M-Y', strtotime($receipt->receipt_date)) }}
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        {{ $receipt->receipt_no }}
                                    </td>
                                    <td>
                                        <strong>
                                            {{ optional($receipt->party)->name }}
                                        </strong>
                                        @if ($receipt->party)
                                            <br>
                                            <small class="text-muted">
                                                {{ $receipt->party->phone }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse($receipt->items as $item)
                                            <div class="border rounded p-2 mb-1 bg-light">
                                                <strong>
                                                    {{ optional($item->accountHead)->name }}
                                                </strong>
                                                @if ($item->details)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $item->details }}
                                                    </small>
                                                @endif
                                                <div class="small mt-1">
                                                    Qty :
                                                    <strong>{{ $item->qty }}</strong>
                                                    |
                                                    Amount :
                                                    <strong class="text-success">
                                                        {{ number_format($item->amount, 2) }}
                                                    </strong>
                                                </div>
                                            </div>
                                        @empty
                                            <span class="text-muted">
                                                No Details
                                            </span>
                                        @endforelse
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ number_format($receipt->total_amount, 2) }}
                                    </td>
                                    <td class="text-end text-success fw-bold">
                                        {{ number_format($receipt->paid_amount, 2) }}
                                    </td>
                                    <td class="text-end text-danger fw-bold">
                                        {{ number_format($receipt->due_amount, 2) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($receipt->payment_status == 'Paid')
                                            <span class="badge bg-success">
                                                Paid
                                            </span>
                                        @elseif($receipt->payment_status == 'Partial')
                                            <span class="badge bg-warning text-dark">
                                                Partial
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Due
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('receipt.show', $receipt->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('receipt.print', $receipt->id) }}" target="_blank"
                                                class="btn btn-sm btn-success">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">
                                            No Expense Found
                                        </h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">
                                    Grand Total
                                </th>
                                <th class="text-end text-danger">
                                    {{ number_format($receipts->sum('total_amount'), 2) }}
                                </th>
                                <th class="text-end text-success">
                                    {{ number_format($receipts->sum('paid_amount'), 2) }}
                                </th>
                                <th class="text-end text-danger">
                                    {{ number_format($receipts->sum('due_amount'), 2) }}
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- Report Summary -->
        <div class="row mt-4">
            <div class="col-lg-4 mb-3">
                <div class="card shadow-sm border-start border-4 border-danger h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            Total Expense
                        </h6>
                        <h3 class="text-danger fw-bold mb-0">
                            {{ number_format($receipts->sum('total_amount'), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card shadow-sm border-start border-4 border-success h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            Total Paid
                        </h6>
                        <h3 class="text-success fw-bold mb-0">
                            {{ number_format($receipts->sum('paid_amount'), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card shadow-sm border-start border-4 border-warning h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            Total Due
                        </h6>
                        <h3 class="text-warning fw-bold mb-0">
                            {{ number_format($receipts->sum('due_amount'), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if ($receipts->hasPages())
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing
                            <strong>{{ $receipts->firstItem() }}</strong>
                            to
                            <strong>{{ $receipts->lastItem() }}</strong>
                            of
                            <strong>{{ $receipts->total() }}</strong>
                            Records
                        </div>
                        <div>
                            {{ $receipts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('css')
    <style>
        .table td {
            vertical-align: middle;
        }

        .table thead th {
            white-space: nowrap;
        }

        .table tbody tr:hover {
            background: #f8fbff;
        }

        .card {
            border-radius: 10px;
        }

        .badge {
            padding: 8px 12px;
            font-size: 13px;
        }

        .btn {
            border-radius: 6px;
        }

        .table tbody td {
            font-size: 14px;
        }

        .table tfoot th {
            font-size: 15px;
        }

        .border-start {
            border-left-width: 5px !important;
        }

        @media print {

            .btn,
            .pagination,
            form,
            .card-header {
                display: none !important;
            }

            body {
                background: #fff;
            }

            .card {
                border: none;
                box-shadow: none !important;
            }
        }
    </style>
@endpush
