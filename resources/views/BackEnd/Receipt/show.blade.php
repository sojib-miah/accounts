@extends('BackEnd.Layouts.layout')

@section('title', 'Receipt Details')

@section('content')
    <div class="p-5">
        <div class="row mt-3">
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
                                            <b>Expense Id:</b>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" readonly
                                                value="{{ $receipt->receipt_no }}">
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
                            <!-- customer company -->
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Party Name
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

                {{-- Expense Receipt List --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header" style="padding: 8px !important;">
                        <h3 class="mb-0 fw-bold">
                            {{ $receipt->type }} Item List
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="60" class="text-center">Sn</th>
                                        <th>Category</th>
                                        <th>{{ $receipt->type }}</th>
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
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->category->name ?? '-' }}</td>
                                            <td>{{ $item->accountHead->name ?? '-' }}</td>
                                            <td class="text-center">{{ number_format($item->qty) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->rate, 2) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->amount, 2) }}</td>
                                            <td>{{ $item->details }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No Item Found</td>
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
                                    <th width="180">Total Qty</th>
                                    <td class="text-end">{{ number_format($totalQty) }}</td>
                                </tr>
                                <tr>
                                    <th>Sub Total</th>
                                    <td class="text-end">{{ number_format($receipt->sub_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td class="text-end text-danger">{{ number_format($receipt->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>VAT</th>
                                    <td class="text-end">{{ number_format($receipt->vat, 2) }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <th>Grand Total</th>
                                    <td class="text-end fw-bold">{{ number_format($receipt->total_amount, 2) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <th>Paid Amount</th>
                                    <td class="text-end fw-bold">{{ number_format($receipt->paid_amount, 2) }}</td>
                                </tr>
                                <tr class="table-danger">
                                    <th>Due Amount</th>
                                    <td class="text-end fw-bold">{{ number_format($receipt->due_amount, 2) }}</td>
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
                            <h2 class="text-success fw-bold">Paid</h2>
                        @elseif($receipt->payment_status == 'Partial')
                            <i class="fa fa-clock fa-3x text-warning mb-3"></i>
                            <h2 class="text-warning fw-bold">Partial</h2>
                        @else
                            <i class="fa fa-exclamation-circle fa-3x text-danger mb-3"></i>
                            <h2 class="text-danger fw-bold">Pending</h2>
                        @endif
                    </div>
                </div>
                {{-- Receipt Notes --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header">
                        <strong>Receipt Notes</strong>
                        <span class="text-danger">*</span>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" rows="6" readonly>{{ $receipt->remarks }}</textarea>
                    </div>
                </div>
                {{-- Action Buttons --}}
                <div class="d-grid gap-2">
                    @if ($receipt->payment_status != 'Paid')
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal"
                            data-bs-target="#expensePaymentModal">
                            <i class="fa fa-money-bill-wave me-2"></i>
                            Bill Pay
                        </button>
                    @endif
                    @if ($receipt->status != 'Cancelled')
                        @can('expense-receipt-edit')
                            <a href="{{ route('receipt.edit', $receipt->id) }}" class="btn btn-warning btn-lg text-white">
                                <i class="fa fa-edit me-2"></i>
                                Modify
                            </a>
                        @endcan
                    @endif
                    @if ($receipt->payment_status == 'Pending')
                        @can('expense-receipt-delete')
                            <form action="{{ route('receipt.destroy', $receipt->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this receipt?')" class="btn btn-danger btn-lg w-100">
                                    <i class="fa fa-trash me-2"></i>
                                    Delete
                                </button>
                            </form>
                        @endcan
                    @endif
                    <a href="{{ route('receipt.print', $receipt->id) }}" target="_blank" class="btn btn-primary btn-lg">
                        <i class="fa fa-print me-2"></i>
                        Print
                    </a>
                    <a href="{{ route('receipt.pdf', $receipt->id) }}" target="_blank" class="btn btn-danger btn-lg">
                        <i class="fa fa-file-pdf me-2"></i>
                        PDF
                    </a>
                </div>
                {{-- Payment Summary --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header">
                        <strong>Payment Summary</strong>
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

    <!-- Expense Payment Modal -->
    <div class="modal fade" id="expensePaymentModal" tabindex="-1" aria-labelledby="expensePaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('expense.payment.store', $receipt->id) }}" method="POST"
                    id="expensePaymentForm">
                    @csrf
                    <!-- HEADER -->
                    <div class="modal-header text-white">
                        <div>
                            <h4 class="modal-title mb-1" id="expensePaymentModalLabel">
                                <i class="fa fa-money-bill-wave me-2"></i>
                                Expense Payment
                            </h4>
                            <small class="opacity-75">
                                Payment against Receipt
                                <strong>
                                    {{ $receipt->receipt_no }}
                                </strong>
                            </small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- BODY -->
                    <div class="modal-body p-4">
                        <!-- PAYMENT SUMMARY -->
                        <div class="row g-3 mb-4">
                            <!-- TOTAL -->
                            <div class="col-md-4">
                                <div class="card border-0 h-100">
                                    <div class="card-body">
                                        <small class="text-muted">
                                            Total Expense
                                        </small>
                                        <h4 class="fw-bold text-dark mb-0">
                                            {{ number_format($receipt->total_amount, 2) }}
                                            <small class="fs-6">
                                                TK
                                            </small>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <!-- PAID -->
                            <div class="col-md-4">
                                <div class="card border-0 h-100">
                                    <div class="card-body">
                                        <small class="text-success">
                                            Already Paid
                                        </small>
                                        <h4 class="fw-bold text-success mb-0">
                                            {{ number_format($receipt->paid_amount, 2) }}
                                            <small class="fs-6">
                                                TK
                                            </small>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <!-- DUE -->
                            <div class="col-md-4">
                                <div class="card border-0 h-100">
                                    <div class="card-body">
                                        <small class="text-danger">
                                            Remaining Due
                                        </small>
                                        <h4 class="fw-bold text-danger mb-0" id="expense_remaining_due">
                                            {{ number_format($receipt->due_amount, 2) }}
                                            <small class="fs-6">
                                                TK
                                            </small>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- PAYMENT TYPE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Type
                                <span class="text-danger">
                                    *
                                </span>
                            </label>
                            <select id="expense_payment_type_id" name="payment_type_id" class="form-select select2"
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
                        <!-- ACCOUNT -->
                        <div class="mb-3" id="expense_account_wrapper">
                            <label class="form-label fw-semibold">
                                Payment Account
                            </label>

                            <select id="expense_account_id" name="account_id" class="form-select select2">
                                <option value="">Select Account</option>
                            </select>

                            <div id="expense_account_balance" class="mt-2">
                                <span class="text-muted">
                                    Select an account to see available balance.
                                </span>
                            </div>
                        </div>

                        <!-- AMOUNT -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Amount
                                <span class="text-danger">
                                    *
                                </span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">
                                    TK
                                </span>
                                <input type="number" name="amount" id="expense_paid_amount"
                                    class="form-control fw-bold" step="0.01" min="0.01"
                                    max="{{ $receipt->due_amount }}" value="{{ $receipt->due_amount }}" required>
                            </div>
                            <small class="text-muted">
                                Maximum payment:
                                <strong>
                                    {{ number_format($receipt->due_amount, 2) }} TK
                                </strong>
                            </small>
                        </div>
                        <!-- DATE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Payment Date
                                <span class="text-danger">
                                    *
                                </span>
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
                        <div id="expense_payment_warning" class="alert alert-warning d-none mb-0">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <span></span>
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" id="expensePaymentSubmit" class="btn btn-danger px-4">
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

            $('#expense_payment_type_id').on('change', function() {

                let paymentTypeId = $(this).val();

                let accountWrapper = $('#expense_account_wrapper');
                let accountSelect = $('#expense_account_id');
                let balanceBox = $('#expense_account_balance');

                // Reset
                accountSelect.empty().append(
                    '<option value="">Select Account</option>'
                );

                accountSelect.val('').trigger('change');

                balanceBox.html(`
            <span class="text-muted">
                Select an account to see available balance.
            </span>
        `);

                // No payment type selected
                if (!paymentTypeId) {

                    accountWrapper.hide();

                    accountSelect.prop('disabled', true);
                    accountSelect.prop('required', false);

                    return;
                }

                // Show Account
                accountWrapper.show();

                accountSelect.prop('disabled', false);
                // accountSelect.prop('required', true);

                // Loading
                accountSelect.empty().append(
                    '<option value="">Loading accounts...</option>'
                );

                $.ajax({

                    url: "{{ url('/admin/expense/payment/accounts') }}/" + paymentTypeId,

                    type: "GET",

                    success: function(accounts) {

                        accountSelect.empty();

                        if (accounts.length === 0) {

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
                            data-balance="${account.current_balance}"
                            data-account-name="${account.account_name}"
                            data-account-number="${account.account_number}">
                            
                            ${account.account_name}
                            (${account.account_number})
                            ${defaultText}

                        </option>
                    `);

                        });

                        // Select2 refresh
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


            // Account Change
            $('#expense_account_id').on('change', function() {

                let option = $(this).find(':selected');

                let balance = parseFloat(
                    option.data('balance') || 0
                );

                if (!$(this).val()) {

                    $('#expense_account_balance').html(`
                <span class="text-muted">
                    Select an account to see available balance.
                </span>
            `);

                    return;
                }

                $('#expense_account_balance').html(`
            <div class="alert alert-info py-2 mb-0">
                <i class="fa fa-wallet me-1"></i>
                Available Balance:
                <strong>
                    ${balance.toFixed(2)} TK
                </strong>
            </div>
        `);

            });

        });
    </script>
@endpush
