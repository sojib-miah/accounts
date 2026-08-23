@extends('BackEnd.Layouts.layout')

@section('title', 'Invoice Details')

@section('content')
    <div class="p-5">
        <div class="row">
            <!-- LEFT SIDE -->
            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <!-- Company Logo -->
                            <div class="col-md-6">
                                <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : asset('default-favicon.ico') }}"
                                    height="55">
                            </div>
                            <!-- Receipt Info -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <div class="col-4 text-end">
                                            <b>INV No:</b>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" readonly
                                                value="{{ $receipt->inv_no }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <div class="col-4 text-end mt-2">
                                            <b>Date:</b>
                                        </div>
                                        <div class="col-8 mt-2">
                                            <input type="text" class="form-control" readonly
                                                value="{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <div class="col-4 text-end mt-2">
                                            <b>By:</b>
                                        </div>
                                        <div class="col-8 mt-2">
                                            <input type="text" class="form-control" readonly
                                                value="{{ $receipt->creator->name ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- Company -->
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Company Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->company->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1"><b>Company Name :</b> {{ $receipt->company->name ?? '' }}</p>
                                </div>
                            </div>
                            <!-- Branch -->
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Branch Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->branch->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1"><b>Company Name :</b> {{ $receipt->branch->company->name ?? '' }}</p>
                                    <p class="mb-1">
                                        <b>Branch Name:</b>
                                        {{ $receipt->branch->name ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Mobile:</b>
                                        {{ $receipt->branch->phone_one ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>E-mail:</b>
                                        {{ $receipt->branch->email ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Address:</b>
                                        {{ $receipt->branch->address ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <!-- company customer -->
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Customer Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->customerCompany->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1">
                                        <b>Name:</b>
                                        {{ $receipt->customerCompany->name ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Mobile:</b>
                                        {{ $receipt->customerCompany->phone ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>E-mail:</b>
                                        {{ $receipt->customerCompany->email ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Address:</b>
                                        {{ $receipt->customerCompany->address ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <!-- customer -->
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Contact Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->party->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1">
                                        <b>Name:</b>
                                        {{ $receipt->party->name ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Designation:</b>
                                        {{ $receipt->party->designation ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Mobile:</b>
                                        {{ $receipt->party->phone ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>E-mail:</b>
                                        {{ $receipt->party->email ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Address:</b>
                                        {{ $receipt->party->address ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Income Receipt List --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header" style="padding: 8px !important;">
                        <h3 class="mb-0 fw-bold">
                            Invoice Item List
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="60" class="text-center">SN</th>
                                        <th>Part No</th>
                                        <th>Item Description</th>
                                        <th width="90" class="text-center">Qty</th>
                                        <th width="120" class="text-end">Unit Price</th>
                                        <th width="120" class="text-end">Amount</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQty = 0;
                                    @endphp
                                    @forelse($receipt->items as $item)
                                        @php
                                            $totalQty += $item->qty;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $item->product->sku ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $item->product->description ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($item->qty) }}
                                            </td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($item->rate, 2) }}
                                            </td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($item->amount, 2) }}
                                            </td>
                                            <td>
                                                {{ $item->details }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No Item Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Total Section --}}
                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="border">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="180">
                                        Total Qty
                                    </th>
                                    <td class="text-end">
                                        {{ number_format($totalQty) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Sub Total
                                    </th>
                                    <td class="text-end">
                                        {{ number_format($receipt->sub_total, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Discount
                                    </th>
                                    <td class="text-end text-danger">
                                        {{ number_format($receipt->discount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="d-flex align-items-center gap-2">
                                        <span>VAT</span>
                                        <i class="fa-solid fa-circle-info mt-1" title="Vat Count Percentege."></i>
                                    </th>
                                    <td class="text-end">
                                        {{ number_format($receipt->vat, 2) }}
                                    </td>
                                </tr>
                                <tr class="table-primary">
                                    <th>
                                        Grand Total
                                    </th>
                                    <td class="text-end fw-bold">
                                        {{ number_format($receipt->total_amount, 2) }}
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <th>
                                        Paid Amount
                                    </th>
                                    <td class="text-end fw-bold">
                                        {{ number_format($receipt->paid_amount, 2) }}
                                    </td>
                                </tr>
                                <tr class="table-danger">
                                    <th>
                                        Due Amount
                                    </th>
                                    <td class="text-end fw-bold">
                                        {{ number_format($receipt->due_amount, 2) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- RIGHT SIDE -->
            <div class="col-lg-3">
                {{-- Receipt Status --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header">
                        <i class="fa fa-chart-bar me-2"></i>
                        <strong>Payment Status</strong>
                    </div>
                    <div class="card-body text-center">
                        @if ($receipt->payment_status == 'Paid')
                            <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                            <h2 class="text-success fw-bold">
                                Paid
                            </h2>
                        @elseif($receipt->payment_status == 'Partial')
                            <i class="fa fa-clock fa-3x text-warning mb-3"></i>
                            <h2 class="text-warning fw-bold">
                                Partial
                            </h2>
                        @else
                            <i class="fa fa-exclamation-circle fa-3x text-danger mb-3"></i>
                            <h2 class="text-danger fw-bold">
                                Pending
                            </h2>
                        @endif
                    </div>
                </div>
                {{-- Receipt Notes --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header">
                        <strong>
                            Invoice Notes
                        </strong>
                        <span class="text-danger">*</span>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" rows="6" readonly>{{ $receipt->remarks }}</textarea>
                    </div>
                </div>
                {{-- Action Buttons --}}
                <div class="d-grid gap-2">
                    @if ($receipt->payment_status != 'Paid')
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fa fa-money-bill-wave me-2"></i>
                            Bill Pay
                        </button>
                    @endif
                    {{-- @if ($receipt->status != 'Cancelled')
                        @can('income-receipt-edit')
                            <a href="{{ route('income.receipt.edit', $receipt->id) }}"
                                class="btn btn-warning btn-lg text-white">
                                <i class="fa fa-edit me-2"></i>
                                Modify
                            </a>
                        @endcan
                    @endif --}}
                    {{-- @if ($receipt->payment_status == 'Pending')
                        @can('income-receipt-delete')
                            <form action="{{ route('receipt.destroy', $receipt->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this receipt?')" class="btn btn-danger btn-lg w-100">
                                    <i class="fa fa-trash me-2"></i>
                                    Delete
                                </button>
                            </form>
                        @endcan
                    @endif --}}
                    <a href="{{ route('sales.order.print', $receipt->id) }}" target="_blank"
                        class="btn btn-primary btn-lg">
                        <i class="fa fa-print me-2"></i>
                        Print
                    </a>
                    <a href="{{ route('sales.order.pdf', $receipt->id) }}" target="_blank"
                        class="btn btn-danger btn-lg">
                        <i class="fa fa-file-pdf me-2"></i>
                        PDF
                    </a>
                </div>
                {{-- Payment Summary --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header">
                        <strong>
                            Payment Summary
                        </strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <tr>
                                <th>Total Amount</th>
                                <td class="text-end">
                                    {{ number_format($receipt->total_amount, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Paid</th>
                                <td class="text-end text-success">
                                    {{ number_format($receipt->paid_amount, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Due</th>
                                <td class="text-end text-danger fw-bold">
                                    {{ number_format($receipt->due_amount, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Beautiful Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <form action="{{ route('income.receipt.payment.store', $receipt->id) }}" method="POST"
                    id="paymentForm">
                    @csrf
                    <!-- Header -->
                    <div class="modal-header text-white border-0 px-4 py-3">

                        <div>
                            <h4 class="modal-title fw-bold mb-1" id="paymentModalLabel">
                                <i class="fa fa-money-bill-wave me-2"></i>
                                Bill Payment
                            </h4>

                            <small class="opacity-75">
                                Receipt No: {{ $receipt->receipt_no }}
                            </small>
                        </div>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>

                    </div>
                    <!-- Body -->
                    <div class="modal-body p-4">
                        <!-- Payment Summary -->
                        <div class="row g-3 mb-4">
                            <!-- Total -->
                            <div class="col-md-4">
                                <div class="payment-summary-card border shadow rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-box bg-primary-subtle text-primary">
                                            <i class="fa fa-file-invoice-dollar"></i>
                                        </div>
                                        <span class="text-muted ms-2">
                                            Total Amount
                                        </span>
                                    </div>
                                    <h4 class="fw-bold mb-0">
                                        {{ number_format($receipt->total_amount, 2) }}
                                        <small class="fs-6 text-muted">TK</small>
                                    </h4>
                                </div>
                            </div>
                            <!-- Paid -->
                            <div class="col-md-4">
                                <div class="payment-summary-card shadow border rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-box bg-success-subtle text-success">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                        <span class="text-muted ms-2">
                                            Paid Amount
                                        </span>
                                    </div>
                                    <h4 class="fw-bold text-success mb-0">
                                        {{ number_format($receipt->paid_amount, 2) }}
                                        <small class="fs-6 text-muted">TK</small>
                                    </h4>
                                </div>
                            </div>
                            <!-- Due -->
                            <div class="col-md-4">
                                <div class="payment-summary-card shadow border border-danger rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-box bg-danger-subtle text-danger">
                                            <i class="fa fa-exclamation-circle"></i>
                                        </div>
                                        <span class="text-muted ms-2">
                                            Remaining Due
                                        </span>
                                    </div>
                                    <h4 class="fw-bold text-danger mb-0">
                                        {{ number_format($receipt->due_amount, 2) }}
                                        <small class="fs-6 text-muted">TK</small>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Information -->
                        <div class="card border-0 shadow rounded-3">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-4">
                                    <i class="fa fa-credit-card me-2 text-primary"></i>
                                    Payment Information
                                </h6>
                                <div class="row g-3">
                                    {{-- Payment Type --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Payment Type
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select id="payment_type_id" name="payment_type_id" class="form-select select2"
                                            required>

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


                                    {{-- Account --}}
                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Account
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select id="account_id" name="account_id" class="form-select select2" required>

                                            <option value="">
                                                Select Payment Type First
                                            </option>

                                        </select>

                                        <div id="account_balance" class="mt-2"></div>

                                    </div>
                                    <!-- Paid Amount -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Payment Amount
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                TK
                                            </span>
                                            <input type="number" min="1" max="{{ $receipt->due_amount }}"
                                                id="payment_amount" name="amount" class="form-control fw-bold"
                                                value="{{ $receipt->due_amount }}" required>
                                        </div>
                                        <small class="text-muted">
                                            Maximum payable:
                                            <strong>
                                                {{ number_format($receipt->due_amount, 2) }} TK
                                            </strong>
                                        </small>
                                    </div>
                                    <!-- Payment Date -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Payment Date
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="payment_date" class="form-control"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <!-- Note -->
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            Payment Note
                                        </label>
                                        <textarea name="note" rows="3" class="form-control" placeholder="Write a payment note..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Warning -->
                        <div class="alert alert-warning d-flex align-items-start mt-4 mb-0">
                            <i class="fa fa-info-circle fs-5 me-3 mt-1"></i>
                            <div>
                                <strong>Payment Information</strong>
                                <div class="small mt-1">
                                    Please make sure the selected account has sufficient
                                    balance before submitting the payment.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer border-0 px-4 py-3">
                        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-4" id="paymentSubmitBtn">
                            <i class="fa fa-check-circle me-1"></i>
                            Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #paymentModal .modal-content {
            border-radius: 18px;
        }

        #paymentModal .modal-header {
            min-height: 80px;
        }

        #paymentModal .form-control,
        #paymentModal .form-select {
            min-height: 44px;
            border-radius: 8px;
        }

        #paymentModal textarea.form-control {
            min-height: 90px;
        }

        .payment-summary-card {
            transition: all .2s ease;
        }

        .payment-summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .06);
        }

        .icon-box {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 16px;
        }

        #account_balance {
            padding-left: 3px;
        }

        #paymentSubmitBtn {
            min-width: 170px;
            min-height: 44px;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('change', '#payment_type_id', function() {

                let paymentTypeId = $(this).val();

                let accountSelect = $('#account_id');

                let accountBalance = $('#account_balance');
                accountSelect
                    .empty()
                    .append(
                        '<option value="">Loading accounts...</option>'
                    )
                    .prop('disabled', true)
                    .prop('required', true);

                accountBalance.html('');

                accountSelect.trigger('change.select2');
                if (!paymentTypeId) {

                    accountSelect
                        .empty()
                        .append(
                            '<option value="">Select Payment Type First</option>'
                        )
                        .prop('disabled', true)
                        .prop('required', true);

                    accountBalance.html('');

                    accountSelect.trigger('change.select2');

                    return;
                }

                $.ajax({

                    url: "{{ url('/admin/ajax/payment-type') }}/" +
                        paymentTypeId +
                        "/accounts",

                    type: "GET",

                    success: function(response) {

                        accountSelect.empty();

                        if (
                            response.success &&
                            response.accounts &&
                            response.accounts.length > 0
                        ) {

                            accountSelect.append(
                                '<option value="">Select Account</option>'
                            );


                            $.each(
                                response.accounts,
                                function(index, account) {

                                    let balance =
                                        parseFloat(
                                            account.current_balance
                                        ) || 0;


                                    let option = $('<option>', {

                                        value: account.id,

                                        text: account.account_name +
                                            ' - ' +
                                            account.account_number

                                    });


                                    option.attr(
                                        'data-balance',
                                        balance
                                    );


                                    option.attr(
                                        'data-payment-type',
                                        account.payment_type_id
                                    );


                                    accountSelect.append(option);

                                }
                            );


                            accountSelect
                                .prop('disabled', false)
                                .prop('required', true);


                            accountBalance.html('');

                        } else {

                            accountSelect.append(
                                '<option value="">No Account Found</option>'
                            );

                            accountSelect
                                .prop('disabled', true)
                                .prop('required', true);


                            accountBalance.html(`
                        <div class="alert alert-warning py-2 mb-0">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            No active account found for this payment type.
                        </div>
                    `);

                        }


                        accountSelect.trigger('change.select2');

                    },

                    error: function() {

                        accountSelect
                            .empty()
                            .append(
                                '<option value="">Unable to load accounts</option>'
                            )
                            .prop('disabled', true)
                            .prop('required', true);


                        accountBalance.html(`
                    <div class="alert alert-danger py-2 mb-0">
                        <i class="fa fa-times-circle me-1"></i>
                        Unable to load payment accounts.
                    </div>
                `);


                        accountSelect.trigger('change.select2');


                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text: 'Unable to load accounts.'

                        });

                    }

                });

            });

            $(document).on('change', '#account_id', function() {

                let accountId = $(this).val();

                let accountBalance = $('#account_balance');
                if (!accountId) {

                    accountBalance.html('');

                    return;
                }

                let option = $(this).find(':selected');

                let balance =
                    parseFloat(
                        option.attr('data-balance')
                    ) || 0;

                accountBalance.html(`

            <div class="alert alert-info py-2 mb-0">

                <i class="fa fa-wallet me-1"></i>

                Available Balance:

                <strong class="${balance > 0
                    ? 'text-success'
                    : 'text-danger'
                }">

                    ৳ ${balance.toFixed(2)}

                </strong>

            </div>

        `);

            });

            $(document).on(
                'input',
                '#payment_amount',
                function() {

                    let account =
                        $('#account_id option:selected');

                    let balance =
                        parseFloat(
                            account.attr('data-balance')
                        ) || 0;

                    let amount =
                        parseFloat(
                            $('#payment_amount').val()
                        ) || 0;


                    if (!$('#account_id').val()) {

                        return;
                    }


                    if (amount > balance) {

                        $('#account_balance').html(`

                    <div class="alert alert-danger py-2 mb-0">

                        <i class="fa fa-exclamation-triangle me-1"></i>

                        Insufficient Balance.

                        Available:

                        <strong>
                            ৳ ${balance.toFixed(2)}
                        </strong>

                    </div>

                `);

                    } else {

                        $('#account_balance').html(`

                    <div class="alert alert-success py-2 mb-0">

                        <i class="fa fa-check-circle me-1"></i>

                        Available Balance:

                        <strong>
                            ৳ ${balance.toFixed(2)}
                        </strong>

                    </div>

                `);

                    }

                }
            );

            $('#paymentForm').on('submit', function(e) {

                let paymentTypeId =
                    $('#payment_type_id').val();


                let accountId =
                    $('#account_id').val();


                let amount =
                    parseFloat(
                        $('#payment_amount').val()
                    ) || 0;


                let due =
                    parseFloat(
                        "{{ $receipt->due_amount }}"
                    ) || 0;


                let account =
                    $('#account_id option:selected');


                let balance =
                    parseFloat(
                        account.attr('data-balance')
                    ) || 0;


                let paymentTypeName =
                    $('#payment_type_id option:selected')
                    .text()
                    .trim();

                if (!paymentTypeId) {

                    e.preventDefault();

                    Swal.fire({

                        icon: 'warning',

                        title: 'Payment Type Required',

                        text: 'Please select a payment type.'

                    });

                    return false;
                }

                if (!accountId) {

                    e.preventDefault();

                    Swal.fire({

                        icon: 'warning',

                        title: 'Account Required',

                        text: 'Please select an account for ' +
                            paymentTypeName +
                            ' payment.'

                    });

                    return false;
                }

                if (amount <= 0) {

                    e.preventDefault();

                    Swal.fire({

                        icon: 'warning',

                        title: 'Invalid Amount',

                        text: 'Payment amount must be greater than zero.'

                    });

                    return false;
                }

                if (amount > due) {

                    e.preventDefault();

                    Swal.fire({

                        icon: 'error',

                        title: 'Amount Exceeds Due',

                        text: 'Maximum payment allowed is ৳ ' +
                            due.toFixed(2)

                    });

                    return false;
                }

                if (amount > balance) {

                    e.preventDefault();

                    Swal.fire({

                        icon: 'error',

                        title: 'Insufficient Balance',

                        html: 'Available Balance: <strong>৳ ' +
                            balance.toFixed(2) +
                            '</strong><br><br>' +

                            'Payment Amount: <strong>৳ ' +
                            amount.toFixed(2) +
                            '</strong>'

                    });

                    return false;
                }

                e.preventDefault();


                Swal.fire({

                    title: 'Confirm Payment',

                    html:

                        'Payment Type: <strong>' +
                        paymentTypeName +
                        '</strong><br>' +

                        'Account: <strong>' +
                        account.text().trim() +
                        '</strong><br>' +

                        'Available Balance: <strong>৳ ' +
                        balance.toFixed(2) +
                        '</strong><br>' +

                        'Payment Amount: <strong>৳ ' +
                        amount.toFixed(2) +
                        '</strong><br><br>' +

                        'Are you sure you want to make this payment?',

                    icon: 'question',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, Make Payment',

                    cancelButtonText: 'Cancel',

                    reverseButtons: true

                }).then(function(result) {

                    if (result.isConfirmed) {

                        $('#payButton')
                            .prop('disabled', true)
                            .html(
                                '<i class="fa fa-spinner fa-spin me-1"></i>' +
                                ' Processing...'
                            );


                        $('#paymentForm')[0].submit();

                    }

                });


                return false;

            });

        });
    </script>
@endpush
