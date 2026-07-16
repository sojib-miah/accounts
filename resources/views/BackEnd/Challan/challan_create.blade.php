@extends('BackEnd.Layouts.layout')

@section('title', 'Create Income Receipt')

@section('content')
    <div class="py-4">
        <div class="mx-5">
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
            <form action="{{ route('income.receipt.store') }}" method="POST" id="receiptForm">
                @csrf
                <input type="hidden" name="type" value="Income">
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                <input type="hidden" name="items" id="items_json">
                <div>
                    <div class="row">
                        <!-- LEFT -->
                        <div class="col-lg-9">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : asset('default-favicon.ico') }}"
                                                height="55">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-4 text-end">
                                                    <b>Date :</b>
                                                </div>
                                                <div class="col-8">
                                                    <input type="date" name="receipt_date" class="form-control"
                                                        value="{{ date('Y-m-d') }}">
                                                </div>
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
                                    <hr>
                                    <div class="row">
                                        <!-- Branch -->
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Billing Branch <span class="text-danger">*</span>
                                            </label>
                                            <select name="branch_id" id="branch_id" class="form-select select2" required>
                                                <option value="">
                                                    Select Branch
                                                </option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="mt-3">
                                                <p><b>Name :</b> <span id="branch_name"></span></p>
                                                <p><b>Mobile :</b> <span id="branch_phone"></span></p>
                                                <p><b>Email :</b> <span id="branch_email"></span></p>
                                                <p><b>Address :</b> <span id="branch_address"></span></p>
                                            </div>
                                        </div>
                                        <!-- Party -->
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Invoice To <span class="text-danger">*</span>
                                            </label>
                                            <select name="party_id" id="party_id" class="form-select select2" required>
                                                <option value="">
                                                    Select Receiver
                                                </option>
                                                @foreach ($parties as $party)
                                                    <option value="{{ $party->id }}">
                                                        {{ $party->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="mt-3">
                                                <p><b>ID :</b> <span id="party_id_text"></span></p>
                                                <p><b>Name :</b> <span id="party_name"></span></p>
                                                <p><b>Mobile :</b> <span id="party_phone"></span></p>
                                                <p><b>Address :</b> <span id="party_address"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- ========================= --}}
                            {{-- Income Item List --}}
                            {{-- ========================= --}}
                            <div class="card shadow-sm mt-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">
                                        Invoice Items
                                    </h4>
                                    <button type="button" id="addRow" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                        Add Row
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="40">
                                                        #
                                                    </th>
                                                    <th width="180">
                                                        Category
                                                    </th>
                                                    <th width="220">
                                                        Item
                                                    </th>
                                                    <th width="120">
                                                        Qty
                                                    </th>
                                                    <th width="120">
                                                        Unit Price
                                                    </th>
                                                    <th width="130">
                                                        Total
                                                    </th>
                                                    <th>
                                                        Details
                                                    </th>
                                                    <th width="70">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="expenseBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- ========================= --}}
                            {{-- Total --}}
                            {{-- ========================= --}}
                            <div class="row mt-4">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="180">
                                                Total Qty
                                            </th>
                                            <td>
                                                <input type="text" id="total_qty" class="form-control text-end" readonly
                                                    value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Sub Total
                                            </th>
                                            <td>
                                                <input type="text" id="sub_total" class="form-control text-end" readonly
                                                    value="0.00">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Discount
                                            </th>
                                            <td>
                                                <input type="number" name="discount" id="discount" value="0"
                                                    class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                VAT
                                            </th>
                                            <td>
                                                <input type="number" name="vat" id="vat" value="0"
                                                    class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>
                                                Grand Total
                                            </th>
                                            <td>
                                                <input type="text" id="grand_total"
                                                    class="form-control text-end fw-bold" readonly value="0.00">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- ========================= --}}
                        {{-- Right Sidebar --}}
                        {{-- ========================= --}}
                        <div class="col-lg-3">
                            <div class="card shadow-sm mt-3">
                                <div class="card-header">
                                    <strong>
                                        Receipt Notes
                                    </strong>
                                </div>
                                <div class="card-body">
                                    <textarea name="remarks" rows="5" class="form-control" placeholder="Enter Notes"></textarea>
                                    <button class="btn btn-primary w-100 mt-3">
                                        Save Receipt
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let rowNo = 1;
        let categories = @json($categories);

        function addRow() {
            let options = '<option value="">Select Category</option>';
            categories.forEach(function(category) {
                options += `<option value="${category.id}">${category.name}</option>`;
            });
            let html = `
                    <tr>
                    <td class="sl">${rowNo}</td>
                    <td><select class="form-select category select2">${options}</select></td>
                    <td>
                    <select class="form-select account select2"><option value="">Select Item</option></select>
                    </td>
                    <td><input type="number" class="form-control qty" value="1"></td>
                    <td><input type="number" class="form-control rate" value="0"></td>
                    <td><input type="number" class="form-control total" readonly></td>
                    <td><input type="text" class="form-control details"></td>
                    <td><button type="button" class="btn btn-danger remove"><i class='fa fa-trash'></i></button></td>
                    </tr>
                    `;
            $('#expenseBody').append(html);
            calculate();
            let row = $('#expenseBody tr:last');
            row.find('.category').select2({
                width: '100%'
            });
            row.find('.account').select2({
                width: '100%'
            });
            rowNo++;
            let lastRow = $('#expenseBody tr:last');
            lastRow.find('.category').select2('open');
        }
        $(function() {
            addRow();
        });
        $('#addRow').click(function() {
            addRow();
        });
        $(document).on('click', '.remove', function() {
            $(this).closest('tr').remove();
            serial();
            calculate();
        });

        function serial() {
            let i = 1;
            $('#expenseBody tr').each(function() {
                $(this).find('.sl').text(i);
                i++;
            });
        }
        $(document).on('change', '.category', function() {
            let row = $(this).closest('tr');
            let categoryId = $(this).val();
            let account = row.find('.account');
            account.empty();
            account.append('<option value="">Loading...</option>');
            if (categoryId == '') {
                account.html('<option value="">Select Expense</option>');
                return;
            }
            $.ajax({
                url: "{{ route('ajax.account-head', ':id') }}".replace(':id', categoryId),
                type: 'GET',
                success: function(response) {
                    let option = '<option value="">Select Expense</option>';
                    $.each(response.data, function(index, item) {
                        option += `<option value="${item.id}">${item.name}</option>`;
                    });
                    account.html(option);
                    account.trigger('change.select2');
                },
                error: function() {
                    alert('Failed to load Expense Head.');
                }
            });
        });

        function calculate() {
            let qtyTotal = 0;
            let subTotal = 0;
            let items = [];
            $('#expenseBody tr').each(function() {
                let row = $(this);
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;
                let amount = qty * rate;
                row.find('.total').val(amount.toFixed(2));
                qtyTotal += qty;
                subTotal += amount;
                items.push({
                    category_id: row.find('.category').val(),
                    category_name: row.find('.category option:selected').text(),
                    account_head_id: row.find('.account').val(),
                    account_head_name: row.find('.account option:selected').text(),
                    qty: qty,
                    rate: rate,
                    amount: amount,
                    details: row.find('.details').val()
                });
            });
            let discount = parseFloat($('#discount').val()) || 0;
            let vat = parseFloat($('#vat').val()) || 0;
            let grandTotal = subTotal + vat - discount;
            $('#total_qty').val(qtyTotal);
            $('#sub_total').val(subTotal.toFixed(2));
            $('#grand_total').val(grandTotal.toFixed(2));
            $('#items_json').val(JSON.stringify(items));
        }
        $(document).on('keyup change', '.qty', function() {
            calculate();
        });
        $(document).on('keyup change', '.rate', function() {
            calculate();
        });
        $(document).on('keyup change', '#discount', function() {
            calculate();
        });
        $(document).on('keyup change', '#vat', function() {
            calculate();
        });
        $(document).on('keyup', '.details', function() {
            calculate();
        });
        $(document).on('change', '.account', function() {
            calculate();
        });
        $(document).on('change', '.account', function() {
            $(this)
                .closest('tr')
                .find('.qty')
                .focus();

            calculate();
        });
        $(document).on('keydown', '.qty', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $(this)
                    .closest('tr')
                    .find('.rate')
                    .focus();
            }
        });
        $(document).on('keydown', '.rate', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $(this)
                    .closest('tr')
                    .find('.details')
                    .focus();
            }
        });
        $(document).on('keydown', '.details', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addRow();
            }
        });
        //    $(document).on('change', '.account', function() {
        //         let current = $(this);
        //         let value = current.val();
        //         if (value == '') return;
        //         let duplicate = false;
        //         $('.account').not(current).each(function() {
        //             if ($(this).val() == value) {
        //                 duplicate = true;
        //             }
        //         });
        //         if (duplicate) {
        //             Swal.fire({
        //                 icon: 'warning',
        //                 title: 'Duplicate Expense',
        //                 text: 'This expense has already been added.'
        //             });
        //             current.val('').trigger('change');
        //             return;
        //         }
        //         calculate();
        //     });
        $('form').submit(function(e) {
            let valid = true;
            $('#expenseBody tr').each(function() {
                let row = $(this);
                row.removeClass('table-danger');
                if (
                    row.find('.category').val() == '' ||
                    row.find('.account').val() == '' ||
                    row.find('.qty').val() == '' ||
                    row.find('.rate').val() == ''
                ) {
                    valid = false;
                    row.addClass('table-danger');
                }
            });
            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Data',
                    text: 'Please complete all rows before saving.'
                });
            }
        });
        $(document).keydown(function(e) {
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                $('form').submit();
            }
        });
        $(document).on('focus', '.qty,.rate', function() {
            $(this).on('wheel.disableScroll', function(e) {
                e.preventDefault();
            });
        });
        $(document).on('blur', '.qty,.rate', function() {
            $(this).off('wheel.disableScroll');
        });
        $(document).on('click', '.remove', function() {
            if ($('#expenseBody tr').length == 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'At least one item is required.'
                });
                return;
            }
            let row = $(this).closest('tr');
            Swal.fire({
                title: 'Delete Item?',
                text: 'This row will be removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    row.remove();
                    serial();
                    calculate();
                }
            });
        });
        $('#branch_id').change(function() {
            let id = $(this).val();
            if (id == '') {
                $('#branch_name').text('');
                $('#branch_phone').text('');
                $('#branch_email').text('');
                $('#branch_address').text('');
                return;
            }
            $.get('/admin/ajax/branch/' + id, function(res) {
                $('#branch_name').text(res.data.name);
                $('#branch_phone').text(res.data.phone);
                $('#branch_email').text(res.data.email);
                $('#branch_address').text(res.data.address);
            });
        });

        $('#party_id').change(function() {
            let id = $(this).val();
            if (id == '') {
                $('#party_id_text').text('');
                $('#party_name').text('');
                $('#party_phone').text('');
                $('#party_address').text('');
                return;
            }
            $.get('/admin/ajax/party/' + id, function(res) {
                $('#party_id_text').text(res.data.id);
                $('#party_name').text(res.data.name);
                $('#party_phone').text(res.data.phone);
                $('#party_address').text(res.data.address);
            });
        });
    </script>
@endpush
