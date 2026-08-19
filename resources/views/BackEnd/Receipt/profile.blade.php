@extends('BackEnd.Layouts.layout')

@section('title', 'Party Profile')

@section('content')
    <div class="p-5">
        <div class="row mt-3">
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="text-center py-4">
                            <img src="{{ asset('uploads/user.jpg') }}" class="rounded-circle border border-3 border-primary"
                                style="width:100px;height:100px;object-fit:cover;">
                            <h4 class="mt-3 mb-0">
                                {{ $party->customerCompany->name }}
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
                                        Company Name
                                    </th>
                                    <td class="mb-0 pb-0">
                                        :
                                    </td>
                                    <td class="mb-0 pb-0">
                                        {{ $party->customerCompany->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="mb-0 pb-0">
                                        Customer Name
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
                                Expense Receipt Details
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

            <!-- Right Side -->
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header" style="padding: 8px !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold mb-0">
                                Expense Receipt
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
                                <a href="{{ route('party.profile', $party->id) }}" class="btn btn-secondary ms-2">
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
                                                            <a href="{{ route('receipt.show', $receipt->id) }}"
                                                                class="dropdown-item">
                                                                <i class="fa fa-eye text-info me-2"></i>
                                                                View
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('receipt.edit', $receipt->id) }}"
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
                                <a href="{{ route('party.profile', $party->id) }}" class="btn btn-secondary">
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
                                                            <a href="{{ route('receipt.show', $payment->receipt_id) }}"
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
            </div>
        </div>
    </div>

    <!-- PARTY DUE PAYMENT MODAL -->
    <div class="modal fade" id="duePaymentModal" tabindex="-1" aria-labelledby="duePaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('party.due.payment', $party->id) }}" method="POST" id="partyDuePaymentForm">
                    @csrf
                    <!-- HEADER -->
                    <div class="modal-header text-white border-0">
                        <div>
                            <h4 class="modal-title fw-bold mb-1" id="duePaymentModalLabel">
                                <i class="fa fa-money-bill-wave me-2"></i>
                                Receive Due Payment
                            </h4>
                            <small class="opacity-75">
                                Customer:
                                <strong>{{ $party->name }}</strong>
                            </small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- BODY -->
                    <div class="modal-body p-4">
                        <!-- PARTY SUMMARY -->
                        <div class="card border-0 mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white
                                            d-flex align-items-center justify-content-center
                                            me-3"
                                                style="width:50px;height:50px;">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold fs-5">
                                                    {{ $party->customerCompany->name }}-{{ $party->name }}
                                                </div>
                                                @if ($party->phone)
                                                    <small class="text-muted">
                                                        <i class="fa fa-phone me-1"></i>
                                                        {{ $party->phone }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                        <small class="text-muted d-block">
                                            Outstanding Due
                                        </small>
                                        <h2 class="text-danger fw-bold mb-0">
                                            {{ number_format($summary['due'], 2) }}
                                            <small class="fs-6">TK</small>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- PAYMENT TYPE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Type
                                <span class="text-danger">*</span>
                            </label>
                            <select id="party_payment_type_id" name="payment_type_id" class="form-select select2"
                                required>
                                <option value="">
                                    Select Payment Type
                                </option>
                                @foreach ($paymentTypes as $type)
                                    <option value="{{ $type->id }}" data-name="{{ strtolower($type->name) }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- ACCOUNT -->
                        <div class="mb-3" id="party_account_wrapper" style="display:none;">
                            <label class="form-label fw-semibold">
                                Receive Account
                                <span class="text-danger">*</span>
                            </label>
                            <select id="party_account_id" name="account_id" class="form-select select2">
                                <option value="">
                                    Select Account
                                </option>
                            </select>
                            <div id="party_account_balance" class="mt-2">
                                <span class="text-muted">
                                    Select an account to see available balance.
                                </span>
                            </div>
                        </div>
                        <!-- PAYMENT AMOUNT -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Amount
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">
                                    TK
                                </span>
                                <input type="number" name="amount" id="party_pay_amount" class="form-control fw-bold"
                                    step="0.01" min="0.01" max="{{ $summary['due'] }}"
                                    value="{{ $summary['due'] }}" required>
                            </div>
                            <small class="text-muted">
                                Maximum payable:
                                <strong>
                                    {{ number_format($summary['due'], 2) }} TK
                                </strong>
                            </small>
                        </div>
                        <!-- REMAINING -->
                        <div class="card border-0 mb-3">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        Remaining Due
                                    </span>
                                    <strong id="party_remaining_due" class="text-danger">
                                        {{ number_format($summary['due'], 2) }}
                                        TK
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <!-- DATE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Date
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <!-- NOTE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Note
                            </label>
                            <textarea name="note" rows="3" class="form-control" placeholder="Write payment note..."></textarea>
                        </div>
                        <!-- WARNING -->
                        <div id="party_payment_warning" class="alert alert-warning d-none mb-0">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <span></span>
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" id="partyPaymentSubmit" class="btn btn-success px-4">
                            <i class="fa fa-check-circle me-2"></i>
                            Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            const dueAmount = {{ (float) $summary['due'] }};

            $('#party_payment_type_id').on('change', function() {

                let paymentTypeId = $(this).val();

                let selectedOption = $(this).find(':selected');

                let paymentTypeName =
                    String(selectedOption.data('name') || '').toLowerCase();

                let accountWrapper =
                    $('#party_account_wrapper');

                let accountSelect =
                    $('#party_account_id');

                let balanceBox =
                    $('#party_account_balance');

                accountSelect
                    .empty()
                    .append(
                        '<option value="">Select Account</option>'
                    );

                balanceBox.html(`
            <span class="text-muted">
                Select an account to see available balance.
            </span>
        `);

                if (!paymentTypeId) {

                    accountWrapper.hide();

                    accountSelect
                        .prop('required', false)
                        .prop('disabled', true);

                    return;
                }

                if (paymentTypeName === 'cash') {

                    accountWrapper.hide();

                    accountSelect
                        .prop('required', false)
                        .prop('disabled', true);

                    return;
                }

                accountWrapper.show();

                accountSelect
                    .prop('disabled', false)
                    .prop('required', true);


                accountSelect
                    .empty()
                    .append(
                        '<option value="">Loading accounts...</option>'
                    );

                $.ajax({

                    url: "{{ url('/admin/expense/payment/accounts') }}/" +
                        paymentTypeId,

                    type: "GET",

                    success: function(accounts) {

                        accountSelect.empty();


                        if (!accounts.length) {

                            accountSelect.append(
                                '<option value="">No account available</option>'
                            );

                            balanceBox.html(`
                        <div class="alert alert-warning py-2 mb-0">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            No active account found for this payment type.
                        </div>
                    `);

                            return;
                        }


                        accountSelect.append(
                            '<option value="">Select Account</option>'
                        );


                        $.each(accounts, function(index, account) {

                            let defaultText =
                                account.default_status === 'Default' ?
                                ' - Default' :
                                '';


                            accountSelect.append(`

                        <option
                            value="${account.id}"
                            data-balance="${account.current_balance}">

                            ${account.account_name}
                            (${account.account_number})
                            ${defaultText}

                        </option>

                    `);

                        });


                        accountSelect.trigger('change.select2');

                    },


                    error: function() {

                        accountSelect.empty().append(
                            '<option value="">Failed to load accounts</option>'
                        );

                        balanceBox.html(`
                    <div class="alert alert-danger py-2 mb-0">
                        <i class="fa fa-times-circle me-1"></i>
                        Failed to load payment accounts.
                    </div>
                `);

                    }

                });

            });

            $('#party_account_id').on('change', function() {

                let option = $(this).find(':selected');

                let balance =
                    parseFloat(option.data('balance') || 0);


                if (!$(this).val()) {

                    $('#party_account_balance').html(`
                <span class="text-muted">
                    Select an account to see available balance.
                </span>
            `);

                    return;
                }


                $('#party_account_balance').html(`

            <div class="alert alert-info py-2 mb-0">

                <i class="fa fa-wallet me-1"></i>

                Available Balance:

                <strong>
                    ${balance.toFixed(2)} TK
                </strong>

            </div>

        `);

            });

            $('#party_pay_amount').on('input', function() {

                let amount =
                    parseFloat($(this).val()) || 0;

                let remaining =
                    dueAmount - amount;


                if (remaining < 0) {
                    remaining = 0;
                }


                $('#party_remaining_due').text(
                    remaining.toFixed(2) + ' TK'
                );

                if (amount > dueAmount) {

                    $('#party_payment_warning')
                        .removeClass('d-none')
                        .find('span')
                        .text(
                            'Payment amount cannot be greater than outstanding due.'
                        );

                    $('#partyPaymentSubmit')
                        .prop('disabled', true);

                } else if (amount <= 0) {

                    $('#party_payment_warning')
                        .removeClass('d-none')
                        .find('span')
                        .text(
                            'Payment amount must be greater than zero.'
                        );

                    $('#partyPaymentSubmit')
                        .prop('disabled', true);

                } else {

                    $('#party_payment_warning')
                        .addClass('d-none');

                    $('#partyPaymentSubmit')
                        .prop('disabled', false);

                }

            });

            $('#partyDuePaymentForm').on('submit', function(e) {

                let paymentType =
                    $('#party_payment_type_id').val();

                let amount =
                    parseFloat($('#party_pay_amount').val()) || 0;

                let account =
                    $('#party_account_id').val();

                let selectedType =
                    $('#party_payment_type_id option:selected');

                let typeName =
                    String(selectedType.data('name') || '')
                    .toLowerCase();

                if (amount <= 0 || amount > dueAmount) {

                    e.preventDefault();

                    alert(
                        'Invalid payment amount.'
                    );

                    return false;
                }
                if (!paymentType) {

                    e.preventDefault();

                    alert(
                        'Please select payment type.'
                    );

                    return false;
                }

                if (
                    typeName !== 'cash' &&
                    !account
                ) {

                    e.preventDefault();

                    alert(
                        'Please select a payment account.'
                    );

                    return false;
                }

            });

        });
    </script>
@endpush
