@extends('BackEnd.Layouts.layout')

@section('title', 'Party Profile')

@section('content')
    <div class="mx-5 py-4">
        <div class="row">
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="text-center py-4">
                            <img src="{{ asset('uploads/user.jpg') }}" class="rounded-circle border border-3 border-primary"
                                style="width:100px;height:100px;object-fit:cover;">
                            <h4 class="mt-3 mb-0">
                                {{ $party->name }}
                            </h4>
                            <small class="text-muted">
                                {{ $party->type }}
                            </small>
                        </div>
                        <hr class="m-0">
                        <div class="p-4">
                            <h5 class="fw-bold mb-1">
                                Details
                            </h5>
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="90" class="mb-0 pb-0">
                                        ID
                                    </th>
                                    <td width="20" class="mb-0 pb-0">
                                        :
                                    </td>
                                    <td class="mb-0 pb-0">
                                        {{ $party->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="mb-0 pb-0">
                                        Name
                                    </th>
                                    <td class="mb-0 pb-0">
                                        :
                                    </td>
                                    <td class="mb-0 pb-0">
                                        {{ $party->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="mb-0 pb-0">
                                        Phone
                                    </th>
                                    <td class="mb-0 pb-0">
                                        :
                                    </td>
                                    <td class="mb-0 pb-0">
                                        {{ $party->phone }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="mb-0 pb-0">
                                        Email
                                    </th>
                                    <td class="mb-0 pb-0">
                                        :
                                    </td>
                                    <td class="mb-0 pb-0">
                                        {{ $party->email ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="mb-0 pb-0">
                                        Address
                                    </th>
                                    <td class="mb-0 pb-0">
                                        :
                                    </td>
                                    <td class="mb-0 pb-0">
                                        {{ $party->address }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="mb-0 pb-0">
                                        Status
                                    </th>
                                    <td class="mb-0 pb-0">
                                        :
                                    </td>
                                    <td class="mb-0 pb-0">
                                        @if ($party->status == 'Active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm mt-5">
                    <div class="card-header" style="padding: 8px !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                Income Receipt Details
                            </h5>
                            <h5 class="text-danger fw-bold mb-0">
                                {{ number_format($summary['due'], 2) }} TK
                            </h5>
                        </div>
                    </div>
                    <div class="">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>
                                    <i class="fa fa-circle text-secondary me-2"></i>
                                    Total Receipts
                                </span>
                                <strong>
                                    {{ $summary['receipt_count'] }}
                                    (Qty)
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>
                                    <i class="fa fa-circle text-secondary me-2"></i>
                                    Net Amount
                                </span>
                                <strong>
                                    {{ number_format($summary['net'], 2) }}
                                    TK
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>
                                    <i class="fa fa-circle text-secondary me-2"></i>
                                    Paid Amount
                                </span>
                                <strong class="text-success">
                                    {{ number_format($summary['paid'], 2) }}
                                    TK
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>
                                    <i class="fa fa-circle text-secondary me-2"></i>
                                    Due Amount
                                </span>
                                <strong class="text-danger">
                                    {{ number_format($summary['due'], 2) }}
                                    TK
                                </strong>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        @if ($summary['due'] > 0)
                            <button class="btn btn-primary w-100 mt-2" data-bs-toggle="modal"
                                data-bs-target="#duePaymentModal">
                                <i class="fa fa-money-bill-wave me-2"></i>
                                Due Pay
                            </button>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fa fa-check-circle me-2"></i>
                                No Due
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <!-- ===================================== -->
            <!-- Right Side -->
            <!-- ===================================== -->

            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header" style="padding: 8px !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold mb-0">
                                Income Receipt
                            </h3>
                            <div class="d-flex">
                                <form method="GET">
                                    <div class="input-group">
                                        <input type="hidden" name="payment_search"
                                            value="{{ request('payment_search') }}">
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="form-control" placeholder="Search Receipt">
                                        <button class="btn btn-primary">
                                            Search
                                        </button>
                                    </div>
                                </form>
                                <form method="GET" class="ms-2">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <input type="hidden" name="payment_search" value="{{ request('payment_search') }}">
                                    <select name="status" onchange="this.form.submit()" class="form-select">
                                        <option value="">All</option>
                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>
                                            Partial
                                        </option>
                                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>
                                            Paid
                                        </option>
                                    </select>
                                </form>
                                <a href="{{ route('income.party.profile', $party->id) }}" class="btn btn-secondary ms-2">
                                    Reset
                                </a>
                                <button class="btn btn-primary ms-2" data-bs-toggle="modal"
                                    data-bs-target="#duePaymentModal">
                                    Due Pay
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            RECEIPT ID
                                        </th>
                                        <th class="text-center">
                                            QTY
                                        </th>
                                        <th class="text-center">
                                            NET
                                        </th>
                                        <th class="text-center">
                                            PAID
                                        </th>
                                        <th class="text-center">
                                            DUE
                                        </th>
                                        <th class="text-center">
                                            STATUS
                                        </th>
                                        <th class="text-center">
                                            CREATED
                                        </th>
                                        <th class="text-center">
                                            ACTION
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($receipts as $receipt)
                                        <tr>
                                            <td class="text-center">
                                                <a href="{{ route('receipt.show', $receipt->id) }}"
                                                    class="text-decoration-none text-primary">
                                                    {{ $receipt->receipt_no }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ $receipt->total_qty }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($receipt->total_amount, 2) }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($receipt->paid_amount, 2) }}
                                            </td>
                                            <td class="text-center text-danger fw-bold">
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
                                                        Unpaid
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $receipt->created_at->format('d-m-Y h:i A') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a href="{{ route('income.receipt.show', $receipt->id) }}"
                                                                class="dropdown-item">
                                                                <i class="fa fa-eye text-info me-2"></i>
                                                                View
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('income.receipt.edit', $receipt->id) }}"
                                                                class="dropdown-item">
                                                                <i class="fa fa-edit text-warning me-2"></i>
                                                                Modify
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('receipt.print', $receipt->id) }}"
                                                                target="_blank" class="dropdown-item">
                                                                <i class="fa fa-print text-primary me-2"></i>
                                                                Print
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                No Receipt Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-5">
                    <div class="card-header" style="padding: 8px !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold mb-0">
                                Receipt Due Payment
                            </h3>
                            <div class="d-flex align-items-center gap-2">
                                <form method="GET">
                                    <div class="input-group">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                        <input type="text" name="payment_search"
                                            value="{{ request('payment_search') }}" class="form-control"
                                            placeholder="Search Payment">
                                        <button class="btn btn-primary">
                                            Search
                                        </button>
                                    </div>
                                </form>
                                <a href="{{ route('income.party.profile', $party->id) }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="80">
                                            PAYMENT ID
                                        </th>
                                        <th class="text-center">
                                            RECEIPT ID
                                        </th>
                                        <th class="text-center">
                                            PAID AMOUNT
                                        </th>
                                        <th class="text-center">
                                            PAYMENT METHOD
                                        </th>
                                        <th class="text-center">
                                            ACCOUNT
                                        </th>
                                        <th class="text-center">
                                            CREATED
                                        </th>
                                        <th class="text-center" width="80">
                                            ACTION
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="text-center">
                                                <a href="{{ route('receipt.show', $payment->receipt_id) }}"
                                                    class="text-decoration-none text-primary">
                                                    {{ $payment->id }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ $payment->receipt->receipt_no ?? '' }}
                                            </td>
                                            <td class="text-center fw-bold text-success">
                                                {{ number_format($payment->amount, 2) }} TK
                                            </td>
                                            <td class="text-center">
                                                {{ $payment->paymentType->name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $payment->account->account_name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $payment->created_at->format('d-m-Y h:i A') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a href="{{ route('income.receipt.show', $payment->receipt_id) }}"
                                                                class="dropdown-item">
                                                                <i class="fa fa-eye text-info me-2"></i>
                                                                View Receipt
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('receipt.print', $payment->receipt_id) }}"
                                                                target="_blank" class="dropdown-item">
                                                                <i class="fa fa-print text-primary me-2"></i>
                                                                Print
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('receipt.pdf', $payment->receipt_id) }}"
                                                                target="_blank" class="dropdown-item">
                                                                <i class="fa fa-file-pdf text-danger me-2"></i>
                                                                PDF
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                No Payment History Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $payments->links() }}
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- Due Payment Modal -->
                <!-- ========================================== -->
                <div class="modal fade" id="duePaymentModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('income.party.due.payment', $party->id) }}" method="POST">
                                @csrf
                                <div class="modal-header bg-primary text-white">
                                    <h4 class="modal-title">
                                        Due Payment
                                    </h4>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <h5 class="mb-0">
                                            Current Due :
                                            <strong class="float-end">
                                                {{ number_format($summary['due'], 2) }} TK
                                            </strong>
                                        </h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Payment Type
                                            </label>
                                            <select name="payment_type_id" class="form-select" required>
                                                <option value="">
                                                    Select Payment Type
                                                </option>
                                                @foreach ($paymentTypes as $type)
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Payment Date
                                            </label>
                                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Pay Amount
                                            </label>
                                            <input type="number" step="0.01" min="1"
                                                max="{{ $summary['due'] }}" value="{{ $summary['due'] }}"
                                                id="pay_amount" name="amount" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Remaining Due
                                            </label>
                                            <input type="text" id="remaining_due" class="form-control bg-light"
                                                readonly value="{{ number_format($summary['due'], 2) }}">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label">
                                            Note
                                        </label>
                                        <textarea name="note" rows="4" class="form-control" placeholder="Write payment note..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        Save Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#pay_amount').keyup(function() {
                let due = {{ $summary['due'] }};
                let pay = parseFloat($(this).val()) || 0;
                if (pay > due) {
                    pay = due;
                    $(this).val(pay);
                }
                let remain = due - pay;
                if (remain < 0) {
                    remain = 0;
                }
                $('#remaining_due').val(
                    remain.toFixed(2)
                );
            });
        });
    </script>
@endpush
