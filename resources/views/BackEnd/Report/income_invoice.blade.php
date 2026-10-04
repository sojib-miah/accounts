@extends('BackEnd.Layouts.layout')

@section('title', 'Invoice Details')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="fas fa-file-invoice-dollar text-danger"></i>
                        Invoice Details Report
                    </h3>
                    <small class="text-muted">
                        Daily, Monthly & Yearly Invoice Summary
                    </small>
                </div>
                <div>
                    <a href="{{ route('income.receipt.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>
                        New Invoice
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
                                        Today's Invoice
                                    </h6>
                                    <h3 class="fw-bold">
                                        {{ number_format($todayIncome, 2) }}
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
                                        {{ number_format($monthIncome, 2) }}
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
                                        {{ number_format($yearIncome, 2) }}
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
                            <div class="col-md-2">
                                <label class="form-label fw-bold">
                                    Select Company
                                </label>
                                <select name="company_id" class="form-select select2">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label fw-bold">
                                    Search
                                </label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Receipt No / Customer">
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label fw-bold">
                                    Payment Status
                                </label>
                                <select name="payment_status" class="form-select select2">
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
                            <div class="col-lg-2">
                                <label class="form-label fw-bold">
                                    From Date
                                </label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}"
                                    class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label fw-bold">
                                    To Date
                                </label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                            </div>
                            <div class="col-lg-2 d-flex align-items-end gap-3">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                    Search
                                </button>
                                @if (request('company_id') ||
                                        request('search') ||
                                        request('payment_status') ||
                                        request('from_date') ||
                                        request('to_date'))
                                    <a href="{{ route('income.invoice') }}" class="btn btn-secondary">
                                        <i class="fas fa-rotate-left"></i>
                                        Reset
                                    </a>
                                @endif
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
                        Income Invoice List
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
                                    <th>Company Name</th>
                                    <th width="110">Date</th>
                                    <th width="120">Receipt No</th>
                                    <th width="180">Customer / Party</th>
                                    <th>Invoice Details</th>
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
                                            {{ $receipt->company->name ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ date('d-M-Y', strtotime($receipt->receipt_date)) }}
                                        </td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ $receipt->receipt_no ?? '-' }}
                                        </td>
                                        <td>
                                            <strong>
                                                {{ $receipt->party->customerCompany->name ?? '-' }}-{{ optional($receipt->party)->name ?? '-' }}
                                            </strong>
                                            @if ($receipt->party)
                                                <br>
                                                <small class="text-muted">
                                                    {{ $receipt->party->phone ?? '-' }} |
                                                    {{ $receipt->party->email ?? '-' }}
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
                                                            {{ $item->details ?? '' }}
                                                        </small>
                                                    @endif
                                                    <div class="small mt-1">
                                                        Qty :
                                                        <strong>{{ $item->qty ?? '' }}</strong>
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
                                            {{ number_format($receipt->total_amount, 2) ?? '-' }}
                                        </td>
                                        <td class="text-end text-success fw-bold">
                                            {{ number_format($receipt->paid_amount, 2) ?? '-' }}
                                        </td>
                                        <td class="text-end text-danger fw-bold">
                                            {{ number_format($receipt->due_amount, 2) ?? '-' }}
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
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        <a href="{{ route('receipt.show', $receipt->id) }}"
                                                            class="dropdown-item" title="View Invoice Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('receipt.print', $receipt->id) }}"
                                                            target="_blank" class="dropdown-item">
                                                            <i class="fas fa-print"></i> Print
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">
                                                No Income Invoice Found
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
                    <div class="p-3">
                        {{ $receipts->links() }}
                    </div>
                </div>
            </div>
            <!-- Report Summary -->
            <div class="row mt-4">
                <div class="col-lg-4 mb-3">
                    <div class="card shadow-sm border-start border-4 border-danger h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                Total Invoice
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
        </div>
    </div>
@endsection
