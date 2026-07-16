@extends('BackEnd.Layouts.layout')

@section('title', 'Receipt Details')

@section('content')
    <div class="mx-5 py-3">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                                    <div class="col-4 text-end">
                                        <b>Id:</b>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control" readonly
                                            value="{{ $receipt->receipt_no }}">
                                    </div>
                                    <div class="col-4 text-end mt-2">
                                        <b>Date:</b>
                                    </div>
                                    <div class="col-8 mt-2">
                                        <input type="text" class="form-control" readonly
                                            value="{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') }}">
                                    </div>
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
                        <hr>
                        <div class="row">
                            <!-- Branch -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Billing Branch
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->branch->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1">
                                        <b>Name:</b>
                                        {{ $receipt->branch->name ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Mobile:</b>
                                        {{ $receipt->branch->phone_one ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Email:</b>
                                        {{ $receipt->branch->email ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Address:</b>
                                        {{ $receipt->branch->address ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <!-- Party -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Receipt To
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->party->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1">
                                        <b>Id:</b>
                                        {{ $receipt->party->id ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Name:</b>
                                        {{ $receipt->party->name ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Mobile:</b>
                                        {{ $receipt->party->phone ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Email:</b>
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

                {{-- ========================= --}}
                {{-- Income Receipt List --}}
                {{-- ========================= --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header" style="padding: 8px !important;">
                        <h3 class="mb-0 fw-bold">
                            Invoice Receipt List
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="60" class="text-center">#</th>
                                        <th>Category</th>
                                        <th>Invoice</th>
                                        <th width="90" class="text-center">Qty</th>
                                        <th width="120" class="text-end">Rate</th>
                                        <th width="120" class="text-end">Amount</th>
                                        <th>Details</th>
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
                                                {{ $item->category->name ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $item->accountHead->name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($item->qty, 2) }}
                                            </td>
                                            <td class="text-end">
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
                {{-- ========================= --}}
                {{-- Total Section --}}
                {{-- ========================= --}}
                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <table class="table table-bordered">
                            <tr>
                                <th width="180">
                                    Total Qty
                                </th>
                                <td class="text-end">
                                    {{ number_format($totalQty, 2) }}
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
                                <th>
                                    VAT
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
            <!-- RIGHT SIDE -->
            <div class="col-lg-3">
                {{-- Receipt Status --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header">
                        <i class="fa fa-chart-bar me-2"></i>
                        <strong>Receipt Status</strong>
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
                            Receipt Notes
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
                    @if ($receipt->status != 'Cancelled')
                        @can('income-receipt-edit')
                            <a href="{{ route('income.receipt.edit', $receipt->id) }}"
                                class="btn btn-warning btn-lg text-white">
                                <i class="fa fa-edit me-2"></i>
                                Modify
                            </a>
                        @endcan
                    @endif
                    @if ($receipt->payment_status == 'Pending')
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

    <!-- ========================================= -->
    <!-- Payment Modal -->
    <!-- ========================================= -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('receipt.payment.store', $receipt->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Bill Payment
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ITEMS</th>
                                    <th class="text-end">
                                        AMOUNT (TK)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Total Due Amount
                                    </td>
                                    <td class="text-end text-primary fw-bold">
                                        {{ number_format($receipt->total_amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Current Paid Amount
                                    </td>
                                    <td class="text-end text-primary fw-bold">
                                        {{ number_format($receipt->paid_amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Remaining Due
                                    </td>
                                    <td id="remaining_due" class="text-end text-danger fw-bold">
                                        {{ number_format($receipt->due_amount, 2) }}

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mb-3">
                            <label>
                                Payment Type
                                <span class="text-danger">*</span>
                            </label>
                            <select id="payment_type_id" name="payment_type_id" class="form-select" required>
                                <option value="">
                                    Select Type
                                </option>
                                @foreach ($paymentTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>
                                Paid Amount
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="1" max="{{ $receipt->due_amount }}"
                                id="paid_amount" name="amount" class="form-control" value="{{ $receipt->due_amount }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>
                                Payment Date
                            </label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>
                                Payment Note
                            </label>
                            <textarea name="note" rows="4" class="form-control" placeholder="Write a note..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
