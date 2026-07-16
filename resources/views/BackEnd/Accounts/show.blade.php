@extends('BackEnd.Layouts.layout')

@section('title', 'Account Details')

@section('content')
    <div class="container-p-y mx-5">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    {{ $account->account_name }}
                </h3>
            </div>
            <div>
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i>
                    Back
                </a>
                {{-- <a href="{{ route('account.print', $account->id) }}" target="_blank" class="btn btn-warning">
                    <i class="fa fa-print me-1"></i>
                    Print
                </a>
                <a href="{{ route('account.pdf', $account->id) }}" target="_blank" class="btn btn-danger">
                    <i class="fa fa-file-pdf me-1"></i>
                    PDF
                </a>
                <a href="{{ route('account.excel', $account->id) }}" class="btn btn-success">
                    <i class="fa fa-file-excel me-1"></i>
                    Excel
                </a> --}}
            </div>
        </div>
        {{-- Account Information --}}
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    Account Information
                </h5>
            </div>
            <div class="card-body mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Payment Type</strong>
                        <br>
                        {{ $account->paymentType->name ?? '' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Account Holder</strong>
                        <br>
                        {{ $account->account_holder_name }}
                    </div>
                    <div class="col-md-3">
                        <strong>Account Number</strong>
                        <br>
                        {{ $account->account_number }}
                    </div>
                    <div class="col-md-3">
                        <strong>Status</strong>
                        <br>
                        @if ($account->status == 'Active')
                            <span class="badge bg-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-danger">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- Summary --}}
        <div class="row mb-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow bg-primary text-white">
                    <div class="card-body">
                        <h6>
                            Opening Balance
                        </h6>
                        <h3>
                            {{ number_format($openingBalance, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card border-0 shadow bg-success text-white">
                    <div class="card-body">
                        <h6>
                            Total Credit
                        </h6>
                        <h3>
                            {{ number_format($totalCredit, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card border-0 shadow bg-danger text-white">
                    <div class="card-body">
                        <h6>
                            Total Debit
                        </h6>
                        <h3>
                            {{ number_format($totalDebit, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card border-0 shadow bg-dark text-white">
                    <div class="card-body">
                        <h6>
                            Current Balance
                        </h6>
                        <h3>
                            {{ number_format($currentBalance, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Filter --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <label>
                                From Date
                            </label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>
                                To Date
                            </label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary me-2">
                                <i class="fa fa-search"></i>
                                Search
                            </button>
                            <a href="{{ route('accounts.show', $account->id) }}" class="btn btn-danger">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- Ledger --}}
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">
                    Transaction History
                </h5>
                <div>
                    <input type="text" id="ledgerSearch" class="form-control" placeholder="Search Voucher, Purpose...">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="60">
                                    #
                                </th>
                                <th>
                                    Date
                                </th>
                                <th>
                                    Voucher
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Purpose
                                </th>
                                <th class="text-end">
                                    Credit
                                </th>
                                <th class="text-end">
                                    Debit
                                </th>
                                <th class="text-end">
                                    Balance
                                </th>
                                <th>
                                    Created By
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $runningBalance = $openingBalance;
                            @endphp
                            @forelse($transactions as $transaction)
                                @php
                                    $runningBalance += $transaction->credit;
                                    $runningBalance -= $transaction->debit;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        @if ($transaction->receipt)
                                            <a href="{{ route('receipt.show', $transaction->receipt_id) }}"
                                                target="_blank">
                                                {{ $transaction->voucher_no }}
                                            </a>
                                        @else
                                            {{ $transaction->voucher_no }}
                                        @endif
                                    </td>
                                    <td>
                                        @switch($transaction->transaction_type)
                                            @case('Income')
                                                <span class="badge bg-success">
                                                    Income
                                                </span>
                                            @break

                                            @case('Expense')
                                                <span class="badge bg-danger">
                                                    Expense
                                                </span>
                                            @break

                                            @case('Transfer')
                                                <span class="badge bg-primary">
                                                    Transfer
                                                </span>
                                            @break

                                            @case('Opening Balance')
                                                <span class="badge bg-dark">
                                                    Opening
                                                </span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">
                                                    {{ $transaction->transaction_type }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ $transaction->purpose }}
                                    </td>
                                    <td class="text-end text-success fw-bold">
                                        @if ($transaction->credit > 0)
                                            {{ number_format($transaction->credit, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end text-danger fw-bold">
                                        @if ($transaction->debit > 0)
                                            {{ number_format($transaction->debit, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>
                                            {{ number_format($runningBalance, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{ $transaction->user->name ?? '' }}
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fa fa-folder-open fa-4x text-secondary mb-3"></i>
                                            <br>
                                            No Transaction Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="5" class="text-end">
                                        Grand Total
                                    </th>
                                    <th class="text-end text-success">
                                        {{ number_format($totalCredit, 2) }}
                                    </th>
                                    <th class="text-end text-danger">
                                        {{ number_format($totalDebit, 2) }}
                                    </th>
                                    <th class="text-end">
                                        {{ number_format($currentBalance, 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Showing
                            {{ $transactions->firstItem() ?? 0 }}
                            to
                            {{ $transactions->lastItem() ?? 0 }}
                            of
                            {{ $transactions->total() }}
                            transactions
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .summary-card {
                border: none;
                border-radius: 10px;
            }

            .table th {
                white-space: nowrap;
                vertical-align: middle;
            }

            .table td {
                vertical-align: middle;
            }

            .table-hover tbody tr:hover {
                background: #f8f9fa;
            }

            @media print {

                .btn,
                .pagination,
                form,
                .sidebar,
                .navbar,
                .layout-menu,
                .layout-navbar,
                footer {
                    display: none !important;
                }

                .card {
                    border: none !important;
                    box-shadow: none !important;
                }

                body {
                    background: #fff !important;
                }

                @page {
                    size: A4 portrait;
                    margin: 10mm;

                }
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            $(document).ready(function() {
                $("#ledgerSearch").on("keyup", function() {
                    let value = $(this).val().toLowerCase();
                    $("table tbody tr").filter(function() {
                        $(this).toggle(
                            $(this).text().toLowerCase().indexOf(value) > -1
                        );
                    });
                });
            });

            function printLedger() {
                window.print();
            }
        </script>
    @endpush
