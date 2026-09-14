@extends('BackEnd.Layouts.layout')

@section('title', 'Create Direct Income')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            <form action="{{ route('direct.income.store') }}" method="POST" id="receiptForm">
                @csrf
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                <input type="hidden" name="items" id="items_json">
                <div>
                    <div class="row">
                        <!-- LEFT -->
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : asset('default-favicon.ico') }}"
                                                height="55">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <div class="col-4 text-end">
                                                        <b>Date :</b>
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="date" name="receipt_date" class="form-control"
                                                            value="{{ date('Y-m-d') }}">
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <div class="col-4 text-end mt-2">
                                                        <b>By :</b>
                                                    </div>
                                                    <div class="col-8 mt-2">
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ auth()->user()->name }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        {{-- company --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Company Name <span
                                                    class="text-danger">*</span></label>
                                            <select name="company_id" id="company_id" class="form-select select2" required>
                                                <option value="">Select Company</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="mt-3">
                                                <p><b>Company Name :</b> <span id="name"></span></p>
                                            </div>
                                        </div>
                                        <!-- Branch -->
                                        <div class="col-md-3">
                                            <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                                            <select name="branch_id" id="branch_id" class="form-select select2" required>
                                                <option value="">Select Branch</option>
                                            </select>
                                            <div class="mt-3">
                                                <p class="mb-1"><b>Company Name :</b> <span id="company_name"></span></p>
                                                <p class="mb-1"><b>Branch Name :</b> <span id="branch_name"></span></p>
                                                <p class="mb-1"><b>Mobile :</b> <span id="branch_phone"></span></p>
                                                <p class="mb-1"><b>E-mail :</b> <span id="branch_email"></span></p>
                                                <p class="mb-1"><b>Address :</b> <span id="branch_address"></span></p>
                                            </div>
                                        </div>
                                        <!-- customer company-->
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Customer Name <span class="text-danger">*</span>
                                            </label>
                                            <select name="customer_company_id" id="customer_company_id"
                                                class="form-select select2" required>
                                                <option value="">Select Customer</option>
                                                @foreach ($customerCompanies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="mt-3">
                                                <p class="mb-1"><b>Name :</b> <span id="customer_company_name"></span></p>
                                                <p class="mb-1"><b>Mobile :</b> <span id="customer_company_phone"></span>
                                                </p>
                                                <p class="mb-1"><b>E-mail :</b> <span id="customer_company_email"></span>
                                                </p>
                                                <p class="mb-1"><b>Address :</b> <span
                                                        id="customer_company_address"></span></p>
                                            </div>
                                        </div>
                                        <!-- customer  -->
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Contact Name <span class="text-danger">*</span>
                                            </label>
                                            <select name="party_id" id="party_id" class="form-select select2" required>
                                                <option value="">Select Customer</option>
                                            </select>
                                            <div class="mt-3">
                                                <p class="mb-1"><b>Name :</b> <span id="party_name"></span></p>
                                                <p class="mb-1"><b>Designation :</b> <span id="party_designation"></span>
                                                </p>
                                                <p class="mb-1"><b>Mobile :</b> <span id="party_phone"></span></p>
                                                <p class="mb-1"><b>E-mail :</b> <span id="party_email"></span></p>
                                                <p class="mb-1"><b>Address :</b> <span id="party_address"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Income Item List --}}
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Purchase Order Items</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="addRow">
                                        <i class="fa fa-plus"></i>
                                        Add Product
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th width="60" class="text-center">
                                                        SL
                                                    </th>
                                                    <th>
                                                        Details
                                                    </th>
                                                    <th width="140" class="text-center">
                                                        Qty
                                                    </th>
                                                    <th width="180" class="text-center">
                                                        Rate
                                                    </th>
                                                    <th width="180" class="text-center">
                                                        Amount
                                                    </th>
                                                    <th width="70" class="text-center">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="productBody">
                                                {{-- Rows will be added here --}}
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-end">
                                                        Total
                                                    </th>
                                                    <th>
                                                        <input type="text" id="totalQty"
                                                            class="form-control text-end" value="0" readonly>
                                                    </th>
                                                    <th></th>
                                                    <th>
                                                        <input type="text" id="subTotal"
                                                            class="form-control text-end" value="0.00" readonly>
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- Total --}}
                            <div class="row mt-4">
                                <div class="col-md-4 offset-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Discount
                                        </label>
                                        <input type="number" name="discount" id="discount"
                                            class="form-control text-end" min="0" step="0.01"
                                            value="{{ old('discount', 0) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            VAT (%)
                                        </label>
                                        <input type="number" name="vat" id="vat"
                                            class="form-control text-end" min="0" step="0.01"
                                            value="{{ old('vat', 0) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            VAT Amount
                                        </label>
                                        <input type="text" id="vatAmount" class="form-control text-end"
                                            value="0.00" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            Total Amount
                                        </label>
                                        <input type="text" id="totalAmount" class="form-control text-end fw-bold"
                                            value="0.00" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Paid Amount
                                        </label>
                                        <input type="number" name="paid_amount" id="paid_amount"
                                            class="form-control text-end" min="0" step="0.01"
                                            value="{{ old('paid_amount', 0) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            Due Amount
                                        </label>
                                        <input type="text" id="dueAmount" class="form-control text-end fw-bold"
                                            value="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                Save Direct Income
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/template" id="rowTemplate">
        <tr class="product-row">
            <td class="sl text-center">
                1
            </td>
            <td>
                <textarea
                    name="details[]"
                    class="form-control details"
                    rows="2"
                    placeholder="Write item details..."
                    required></textarea>

            </td>
            <td>
                <input
                    type="number"
                    name="qty[]"
                    class="form-control qty text-end"
                    min="1"
                    step="1"
                    value="1"
                    required>
            </td>
            <td>
                <input
                    type="number"
                    name="rate[]"
                    class="form-control rate text-end"
                    min="0"
                    step="1"
                    value="0"
                    required>
            </td>
            <td>
                <input
                    type="text"
                    class="form-control total text-end"
                    value="0.00"
                    readonly>
            </td>
            <td class="text-center">
                <button
                    type="button"
                    class="btn btn-danger btn-sm removeRow"
                    title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    </script>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function reindexRows() {
                $('#productBody tr.product-row').each(function(index) {
                    $(this).find('.sl').text(index + 1);
                });
            }

            function calculateRow(row) {
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;
                let amount = qty * rate;
                row.find('.total').val(amount.toFixed(2));
            }

            function calculateTotals() {
                let totalQty = 0;
                let subTotal = 0;
                $('#productBody tr.product-row').each(function() {
                    let row = $(this);
                    let qty = parseFloat(row.find('.qty').val()) || 0;
                    let rate = parseFloat(row.find('.rate').val()) || 0;
                    let amount = qty * rate;
                    totalQty += qty;
                    subTotal += amount;
                    row.find('.total').val(amount.toFixed(2));
                });
                $('#totalQty').val(totalQty.toFixed(2));
                $('#subTotal').val(subTotal.toFixed(2));
                calculateGrandTotal();
            }

            function calculateGrandTotal() {
                let subTotal = parseFloat($('#subTotal').val()) || 0;
                let discount = parseFloat($('#discount').val()) || 0;
                let vatPercent = parseFloat($('#vat').val()) || 0;
                if (discount > subTotal) {
                    discount = subTotal;
                    $('#discount').val(discount.toFixed(2));
                }
                let afterDiscount = subTotal - discount;
                let vatAmount =
                    (afterDiscount * vatPercent) / 100;
                let grandTotal =
                    afterDiscount + vatAmount;
                $('#vatAmount').val(vatAmount.toFixed(2));
                $('#totalAmount').val(grandTotal.toFixed(2));
                calculateDue();
            }

            function calculateDue() {
                let totalAmount =
                    parseFloat($('#totalAmount').val()) || 0;
                let paidAmount =
                    parseFloat($('#paid_amount').val()) || 0;
                if (paidAmount > totalAmount) {
                    paidAmount = totalAmount;
                    $('#paid_amount').val(
                        paidAmount.toFixed(2)
                    );
                }
                let dueAmount =
                    totalAmount - paidAmount;
                $('#dueAmount').val(
                    dueAmount.toFixed(2)
                );
            }
            $('#addRow').on('click', function() {

                // Remove empty message if exists
                $('#productBody .empty-row').remove();

                let template =
                    $('#rowTemplate').html();

                $('#productBody').append(template);

                reindexRows();

                calculateTotals();

                // Focus newly added details field
                $('#productBody tr.product-row:last')
                    .find('.details')
                    .focus();
            });
            $(document).on(
                'click',
                '.removeRow',
                function() {
                    let row =
                        $(this).closest('tr.product-row');
                    Swal.fire({
                        title: 'Remove Item?',
                        text: 'Are you sure you want to remove this item?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Remove',
                        cancelButtonText: 'Cancel'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            row.remove();
                            reindexRows();
                            calculateTotals();
                            if (
                                $('#productBody tr.product-row').length === 0
                            ) {
                                let template =
                                    $('#rowTemplate').html();
                                $('#productBody').html(template);
                                reindexRows();
                                calculateTotals();
                                $('#productBody tr.product-row:first')
                                    .find('.details')
                                    .focus();
                            }
                        }
                    });
                }
            );
            $(document).on(
                'input',
                '.qty, .rate',
                function() {
                    let row =
                        $(this).closest('tr.product-row');
                    calculateRow(row);
                    calculateTotals();
                }
            );
            $(document).on(
                'input',
                '#discount, #vat',
                function() {
                    calculateGrandTotal();
                }
            );
            $(document).on(
                'input',
                '#paid_amount',
                function() {
                    calculateDue();
                }
            );
            if (
                $('#productBody tr.product-row').length === 0
            ) {
                let template =
                    $('#rowTemplate').html();
                $('#productBody').html(template);
            }
            reindexRows();
            calculateTotals();
            $('#productBody tr.product-row:first')
                .find('.details')
                .focus();
        });

        $('#company_id').change(function() {
            let company = $(this).val();
            if (company === '') {
                $('#branch_id')
                    .html('<option value="">Select Branch</option>');
                $('#name').text('');
                return;
            }
            $.get(
                '/admin/ajax/company/' + company + '/branches',
                function(res) {
                    $('#name')
                        .text(res.company.name ?? '');
                    let html = '<option value="">Select Branch</option>';
                    $.each(
                        res.branches,
                        function(i, item) {
                            html += '<option value="' + item.id + '">' + item.name + '</option>';
                        }
                    );
                    $('#branch_id').html(html).trigger('change');
                }
            );
        });
        $('#branch_id').change(function() {
            let id = $(this).val();
            if (id === '') {
                $('#company_name').text('');
                $('#branch_name').text('');
                $('#branch_phone').text('');
                $('#branch_email').text('');
                $('#branch_address').text('');
                return;
            }
            $.get('/admin/ajax/branch/' + id,
                function(res) {
                    $('#company_name').text(res.data.company_name ?? '');
                    $('#branch_name').text(res.data.name);
                    $('#branch_phone').text(res.data.phone);
                    $('#branch_email').text(res.data.email);
                    $('#branch_address').text(res.data.address);
                    $('#salesBody tr').each(function() {
                        let row = $(this);
                        let productId = row.find('.product').val();
                        if (productId) {
                            loadSerialCount(row, productId);
                        }
                    });
                }
            );
        });
        $('#customer_company_id').change(function() {

            let companyId = $(this).val();

            $('#party_id')
                .html('<option value="">Select Customer</option>')
                .val('')
                .trigger('change');

            $('#customer_company_name').text('');
            $('#customer_company_phone').text('');
            $('#customer_company_email').text('');
            $('#customer_company_address').text('');

            if (companyId === '') {
                return;
            }

            $.get(
                '/admin/ajax/customer-company/' + companyId,
                function(res) {

                    if (res.success) {

                        $('#customer_company_name')
                            .text(res.data.name ?? '');

                        $('#customer_company_phone')
                            .text(res.data.phone ?? '');

                        $('#customer_company_email')
                            .text(res.data.email ?? '');

                        $('#customer_company_address')
                            .text(res.data.address ?? '');
                    }
                }
            );

            $.get(
                '/admin/ajax/customer-company/' + companyId + '/parties',
                function(res) {

                    if (!res.success) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message ?? 'Unable to load customers.'
                        });

                        return;
                    }

                    let html =
                        '<option value="">Select Customer</option>';

                    if (res.parties.length === 0) {

                        html =
                            '<option value="">No Customer Found</option>';

                    } else {

                        $.each(res.parties, function(i, party) {

                            html += `
                        <option value="${party.id}">
                            ${party.name}
                            ${party.party_id
                                ? ' (' + party.party_id + ')'
                                : ''}
                        </option>
                    `;
                        });
                    }

                    $('#party_id')
                        .html(html)
                        .val('')
                        .trigger('change');

                }
            ).fail(function(xhr) {

                console.log(xhr);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to load customers.'
                });

            });

        });
        $('#party_id').change(function() {
            let id = $(this).val();
            if (id === '') {
                $('#party_id_text').text('');
                $('#party_name').text('');
                $('#party_phone').text('');
                $('#party_address').text('');
                $('#party_email').text('');
                $('#party_designation').text('');
                return;
            }
            $.get('/admin/ajax/party/' + id,
                function(res) {
                    $('#party_id_text').text(res.data.id);
                    $('#party_name').text(res.data.name);
                    $('#party_email').text(res.data.email);
                    $('#party_designation').text(res.data.designation);
                    $('#party_phone').text(res.data.phone);
                    $('#party_address').text(res.data.address);
                }
            );
        });
    </script>
@endpush
