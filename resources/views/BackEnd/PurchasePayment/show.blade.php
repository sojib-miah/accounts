@extends('BackEnd.Layouts.layout')

@section('title', 'Purchase Payment')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>
                    Purchase Payment
                </h4>
                <a href="{{ route('purchase.payment.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-2"></i>
                    Back
                </a>
            </div>
            {{-- Purchase Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        Purchase Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <strong>PO No</strong>
                            <div>
                                {{ $receipt->po_no ?? $receipt->receipt_no }}
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Purchase Date</strong>
                            <div>
                                {{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') }}
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Supplier Company</strong>
                            <div>
                                {{ $receipt->party->customerCompany->name ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Supplier</strong>
                            <div>
                                {{ $receipt->party->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <small>Total Amount</small>
                                <h4 class="mb-0">
                                    ৳
                                    {{ number_format($receipt->total_amount, 2) }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <small>Total Paid</small>
                                <h4 class="text-success mb-0">
                                    ৳
                                    {{ number_format($receipt->paid_amount, 2) }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border border-danger rounded p-3">
                                <small>Outstanding Due</small>
                                <h4 class="text-danger mb-0">
                                    ৳
                                    {{ number_format($receipt->due_amount, 2) }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Products --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        Purchase Items
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipt->items as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $item->product->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ number_format($item->qty) }}
                                        </td>
                                        <td>
                                            {{ number_format($item->rate, 2) }}
                                        </td>
                                        <td>
                                            {{ number_format($item->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Make Payment --}}
            @if ($receipt->due_amount > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">
                            Make Payment
                        </h5>
                        <span class="badge bg-danger fs-6">
                            Due:
                            ৳
                            {{ number_format($receipt->due_amount, 2) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchase.payment.store', $receipt->id) }}" method="POST" id="paymentForm">
                            @csrf
                            <div class="row">
                                {{-- Payment Type --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Payment Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="payment_type_id" id="payment_type_id" class="form-select select2"
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
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Account
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="account_id" id="account_id" class="form-select select2" required>
                                        <option value="">
                                            Select Account
                                        </option>
                                    </select>
                                    <div id="accountBalance" class="mt-2">
                                    </div>
                                </div>
                                {{-- Payment Date --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Payment Date
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="payment_date" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                {{-- Amount --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Payment Amount
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            ৳
                                        </span>
                                        <input type="number" name="amount" id="payment_amount" class="form-control"
                                            min="0.01" max="{{ $receipt->due_amount }}" step="0.01"
                                            value="{{ $receipt->due_amount }}" required>
                                    </div>
                                    <small class="text-muted">
                                        Maximum:
                                        ৳
                                        {{ number_format($receipt->due_amount, 2) }}
                                    </small>
                                </div>
                                {{-- Note --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Note
                                    </label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="Payment note"></textarea>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success" id="payButton">
                                    <i class="fa fa-money-bill-wave me-1"></i>
                                    Make Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            {{-- Payment History --}}
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        Payment History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Payment Type</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receipt->payments as $payment)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $payment->payment_date->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            {{ $payment->paymentType->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $payment->account->account_name ?? '-' }}
                                        </td>
                                        <td class="text-danger fw-bold">
                                            ৳
                                            {{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td>
                                            {{ $payment->note ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No payment history.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#payment_type_id').on('change', function() {

                let paymentTypeId = $(this).val();

                let accountSelect = $('#account_id');

                $('#accountBalance').html('');

                accountSelect.html(
                    '<option value="">Loading Account...</option>'
                );

                if (!paymentTypeId) {

                    accountSelect.html(
                        '<option value="">Select Account</option>'
                    );

                    return;
                }


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
                            response.accounts.length
                        ) {

                            $.each(
                                response.accounts,
                                function(index, account) {

                                    let balance =
                                        parseFloat(
                                            account.current_balance
                                        ) || 0;

                                    accountSelect.append(

                                        '<option ' +
                                        'value="' +
                                        account.id +
                                        '" ' +
                                        'data-balance="' +
                                        balance +
                                        '">' +

                                        account.account_name +

                                        ' - ' +

                                        account.account_number +

                                        ' | Balance: ' +

                                        balance.toFixed(2) +

                                        '</option>'

                                    );

                                }
                            );

                        } else {

                            accountSelect.append(
                                '<option value="">No Account Found</option>'
                            );

                        }

                    },

                    error: function() {

                        accountSelect.html(
                            '<option value="">Unable to load Account</option>'
                        );

                    }

                });

            });


            $('#account_id').on('change', function() {

                let option = $(this).find(':selected');

                let balance =
                    parseFloat(
                        option.data('balance')
                    ) || 0;

                let paymentAmount =
                    parseFloat(
                        $('#payment_amount').val()
                    ) || 0;


                if (!$(this).val()) {

                    $('#accountBalance').html('');

                    return;
                }


                let html = '';

                if (balance >= paymentAmount) {

                    html =
                        '<div class="alert alert-success py-2 mb-0">' +

                        '<i class="fa fa-check-circle me-1"></i>' +

                        'Available Balance: <strong>৳ ' +

                        balance.toFixed(2) +

                        '</strong>' +

                        '</div>';

                } else {

                    html =
                        '<div class="alert alert-danger py-2 mb-0">' +

                        '<i class="fa fa-exclamation-triangle me-1"></i>' +

                        'Insufficient Balance. Available: <strong>৳ ' +

                        balance.toFixed(2) +

                        '</strong>' +

                        '</div>';

                }


                $('#accountBalance').html(html);

            });

            $('#payment_amount').on('input', function() {

                $('#account_id').trigger('change');

            });

            $('#paymentForm').on('submit', function(e) {

                let account =
                    $('#account_id option:selected');

                let balance =
                    parseFloat(
                        account.data('balance')
                    ) || 0;

                let amount =
                    parseFloat(
                        $('#payment_amount').val()
                    ) || 0;

                let due =
                    parseFloat(
                        "{{ $receipt->due_amount }}"
                    ) || 0;


                if (!$('#payment_type_id').val()) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Payment Type Required',
                        text: 'Please select a payment type.'
                    });

                    return false;
                }


                if (!$('#account_id').val()) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Account Required',
                        text: 'Please select an account.'
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


                if (balance < amount) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'error',
                        title: 'Insufficient Balance',

                        html: 'Account Balance: <strong>৳ ' +
                            balance.toFixed(2) +
                            '</strong><br>' +

                            'Payment Amount: <strong>৳ ' +
                            amount.toFixed(2) +
                            '</strong>'
                    });

                    return false;
                }


                Swal.fire({

                    title: 'Confirm Payment',

                    html: 'Payment Amount: <strong>৳ ' +
                        amount.toFixed(2) +
                        '</strong><br><br>' +

                        'Are you sure you want to make this payment?',

                    icon: 'question',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, Make Payment',

                    cancelButtonText: 'Cancel'

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
