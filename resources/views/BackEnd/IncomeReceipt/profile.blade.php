@extends('BackEnd.Layouts.layout')

@section('title', 'Sales Order Profile')

@section('content')
    <div class="p-5">
        <div class="row mt-3">
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="text-center py-4">
                            <img src="{{ asset('uploads/user.jpg') }}" class="rounded-circle border-3 border-primary"
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
                                        Company
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
                            <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#duePaymentModal">

                                <i class="fa fa-money-bill-wave me-2"></i>
                                Receive Due Payment

                                <span class="badge bg-light text-primary ms-2">
                                    {{ number_format($summary['due'], 2) }} TK
                                </span>

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
                                        <th>sn</th>
                                        <th class="text-center">
                                            sales order no
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ $receipt->so_no }}
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
                                        <th>sn</th>
                                        <th class="text-center">
                                            sales order no
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ $payment->receipt->so_no ?? '' }}
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
            </div>
        </div>
    </div>

    <!-- DUE PAYMENT MODAL -->
    <div class="modal fade" id="duePaymentModal" tabindex="-1" aria-labelledby="duePaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <!-- HEADER -->
                <div class="modal-header text-white">
                    <div>
                        <h5 class="modal-title mb-1" id="duePaymentModalLabel">
                            <i class="fa fa-money-bill-wave me-2"></i>
                            Receive Due Payment
                        </h5>
                        <small class="opacity-75">
                            Customer:
                            <strong>{{ $party->name }}</strong>
                        </small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
                </div>
                <form action="{{ route('sales.order.due.payment', $party->id) }}" method="POST" id="duePaymentForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">
                                        Total Invoice
                                    </small>
                                    <h5 class="mb-0 mt-1">
                                        {{ number_format($summary['net'], 2) }}
                                        TK
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">
                                        Total Paid
                                    </small>
                                    <h5 class="mb-0 mt-1 text-success">
                                        {{ number_format($summary['paid'], 2) }}
                                        TK
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-danger">
                                        Total Due
                                    </small>
                                    <h5 class="mb-0 mt-1 text-danger">
                                        {{ number_format($summary['due'], 2) }}
                                        TK
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Type -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Type
                                <span class="text-danger">*</span>
                            </label>
                            <select name="payment_type_id" id="due_payment_type_id" class="form-select select2" required>
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
                        <!-- Account -->
                        <div class="mb-3" id="due_account_wrapper" style="display: none;">
                            <label class="form-label fw-semibold">
                                Receive Account
                                <span class="text-danger">*</span>
                            </label>
                            <select name="account_id" id="due_account_id" class="form-select select2" disabled>
                                <option value="">
                                    Select Account
                                </option>
                            </select>
                            <div id="due_account_balance" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Amount
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="number" name="amount" id="due_payment_amount" class="form-control"
                                    min="1" max="{{ $summary['due'] }}" value="{{ $summary['due'] }}" required>
                                <span class="input-group-text">
                                    TK
                                </span>
                            </div>
                            <small class="text-muted">
                                Maximum payable:
                                <strong>
                                    {{ number_format($summary['due'], 2) }}
                                    TK
                                </strong>
                            </small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Date
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Note
                            </label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Write payment note..."></textarea>
                        </div>
                        <div id="due_payment_warning" class="alert alert-warning d-none mb-0">
                            <i class="fa fa-triangle-exclamation me-2"></i>
                            <span></span>
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" id="due_payment_submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-2"></i>
                            Receive Payment
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
            $(document).on('change', '#due_payment_type_id', function() {

                let paymentTypeId = $(this).val();

                let selectedOption = $(this).find(':selected');

                let paymentTypeName =
                    String(selectedOption.data('name') || '').toLowerCase();

                let isCash = paymentTypeName === 'cash';

                let accountWrapper = $('#due_account_wrapper');
                let accountSelect = $('#due_account_id');
                let balanceBox = $('#due_account_balance');

                accountSelect
                    .empty()
                    .append('<option value="">Select Account</option>')
                    .val('')
                    .trigger('change');

                balanceBox.html('');

                if (!paymentTypeId) {

                    accountWrapper.hide();

                    accountSelect
                        .prop('disabled', true)
                        .prop('required', false);

                    return;
                }
                if (isCash) {

                    accountWrapper.hide();

                    accountSelect
                        .prop('disabled', true)
                        .prop('required', false)
                        .val('');

                    balanceBox.html('');

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

                    url: "{{ url('/admin/ajax/payment-type') }}/" +
                        paymentTypeId +
                        "/accounts",

                    type: "GET",

                    success: function(response) {

                        accountSelect.empty();

                        accountSelect.append(
                            '<option value="">Select Account</option>'
                        );


                        if (
                            response.success &&
                            response.accounts &&
                            response.accounts.length > 0
                        ) {

                            $.each(
                                response.accounts,
                                function(index, account) {

                                    let balance =
                                        parseFloat(
                                            account.current_balance
                                        ) || 0;


                                    accountSelect.append(
                                        $('<option>', {
                                            value: account.id,
                                            text: account.account_name +
                                                ' - ' +
                                                account.account_number +
                                                ' (' +
                                                balance.toFixed(2) +
                                                ' TK)'
                                        })
                                        .attr(
                                            'data-balance',
                                            balance
                                        )
                                        .attr(
                                            'data-payment-type',
                                            account.payment_type_id
                                        )
                                    );

                                }
                            );


                        } else {

                            accountSelect.append(
                                '<option value="">No Account Found</option>'
                            );

                        }

                        accountSelect.trigger('change');

                    },

                    error: function() {

                        accountSelect
                            .empty()
                            .append(
                                '<option value="">Unable to load accounts</option>'
                            )
                            .trigger('change');


                        Swal.fire({
                            icon: 'error',
                            title: 'Account Loading Failed',
                            text: 'Unable to load accounts for the selected payment type.'
                        });

                    }

                });

            });

            $(document).on('change', '#due_account_id', function() {

                let option = $(this).find(':selected');

                let balance =
                    parseFloat(
                        option.attr('data-balance') || 0
                    ) || 0;


                if (!$(this).val()) {

                    $('#due_account_balance').html('');

                    return;
                }


                $('#due_account_balance').html(`

            <div class="alert alert-info py-2 mb-0">

                <i class="fa fa-wallet me-2"></i>

                Available Balance:

                <strong>
                    ${balance.toLocaleString('en-BD', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })} TK
                </strong>

            </div>

        `);

            });

        });
    </script>
@endpush
