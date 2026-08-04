@extends('BackEnd.Layouts.layout')

@section('title', 'Edit Purchase')

@section('content')
    <div class="p-5">
        <form action="{{ route('purchase.update', $purchase->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('BackEnd.Purchase.partials.edit-form')
            <div class="card shadow-sm mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        Purchase Items
                    </h5>
                    <button type="button" class="btn btn-primary btn-sm" id="addRow">
                        <i class="fa fa-plus me-2"></i>
                        Add Product
                    </button>
                </div>
                <div class="card-body">
                    @include('BackEnd.Purchase.partials.edit-items')
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 ms-auto">
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Qty</th>
                            <td>
                                <input type="text" id="totalQty" name="total_qty" class="form-control"
                                    value="{{ $purchase->total_qty }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Sub Total</th>
                            <td>
                                <input type="text" id="subTotal" name="sub_total" class="form-control"
                                    value="{{ number_format($purchase->sub_total, 2, '.', '') }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                <input type="number" step="0.01" id="discount" name="discount" class="form-control"
                                    value="{{ $purchase->discount }}">
                            </td>
                        </tr>
                        <tr>
                            <th>VAT</th>
                            <td>
                                <input type="number" step="0.01" id="vat" name="vat" class="form-control"
                                    value="{{ $purchase->vat }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Grand Total</th>
                            <td>
                                <input type="text" id="grandTotal" class="form-control"
                                    value="{{ number_format($purchase->total_amount, 2, '.', '') }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td>
                                <input type="number" step="0.01" id="paidAmount" name="paid_amount" class="form-control"
                                    value="{{ $purchase->paid_amount }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Due Amount</th>
                            <td>
                                <input type="text" id="dueAmount" class="form-control"
                                    value="{{ number_format($purchase->due_amount, 2, '.', '') }}" readonly>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('purchase.index') }}" class="btn btn-secondary">
                    Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-2"></i>
                    Update Purchase
                </button>
            </div>
        </form>
    </div>

    <!-- Serial Modal -->
    <div class="modal fade" id="serialModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa fa-barcode me-2"></i>
                        Serial / IMEI Number
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Product
                        </label>
                        <input type="text" id="serialProductName" class="form-control" readonly>
                    </div>
                    <!-- Serial Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Serial Number
                        </label>
                        <div class="input-group">
                            <input type="text" id="serialInput" class="form-control" placeholder="Enter Serial Number"
                                autocomplete="off">
                            <button type="button" class="btn btn-primary" id="addSerial">
                                <i class="fa fa-plus"></i>
                                Add
                            </button>
                        </div>
                    </div>
                    <!-- Status -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="alert alert-primary py-2 mb-0">
                                <strong>Total Serial :</strong>
                                <span id="serialCount">
                                    0
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success py-2 mb-0">
                                <strong>Total Qty :</strong>
                                <span id="requiredQty">
                                    0
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning py-2 mb-0 text-center">
                                <span id="serialStatus">
                                    Waiting...
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Serial List -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="70">
                                        SL
                                    </th>
                                    <th>
                                        Serial / IMEI Number
                                    </th>
                                    <th width="80" class="text-center">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="serialList">
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No Serial Added
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <small class="text-danger">
                            <b>Note :</b>
                            Add one serial at a time.
                            Quantity will be calculated automatically
                            from total serial numbers.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>
                        Close
                    </button>
                    <button type="button" id="saveSerial" class="btn btn-success">
                        <i class="fa fa-save me-1"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('BackEnd.Purchase.partials.edit-script')

    <script>
        let currentRow = null;
        let serialArray = [];
        $(document).on('click', '.serialBtn', function() {
            currentRow = $(this).closest('tr');
            $('#serialInput').val('');
            let productName = currentRow.find('.product option:selected').text();
            $('#serialProductName').val(productName);
            let json = currentRow.find('.serial_json').val();
            serialArray = [];
            if (json && json !== '' && json !== '[]') {
                try {
                    serialArray = JSON.parse(json);
                } catch (e) {
                    serialArray = [];
                }
            }
            renderSerialList();
            $('#serialModal').modal('show');
            setTimeout(function() {
                $('#serialInput').focus();
            }, 200);
        });
        $('#addSerial').click(function() {
            let serial = $('#serialInput').val().trim();
            if (serial == '') {
                $('#serialInput').focus();
                return;
            }
            if (serialArray.includes(serial)) {
                Swal.fire('Duplicate Serial');
                return;
            }
            serialArray.push(serial);
            renderSerialList();
            $('#serialInput').val('').focus();
        });
        $('#serialInput').keypress(function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $('#addSerial').click();
            }
        });

        function renderSerialList() {
            let html = '';
            if (serialArray.length == 0) {
                html = `<tr><td colspan="3" class="text-center">No Serial Added</td></tr>`;
            } else {
                $.each(serialArray, function(index, item) {
                    html += `
                    <tr>
                        <td>${index+1}</td>
                        <td>${item}</td>
                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-danger btn-sm removeSerial"
                                data-index="${index}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
            }
            $('#serialList').html(html);
            $('#serialCount').text(serialArray.length);
            $('#requiredQty').text(serialArray.length);
        }
        $(document).on('click', '.removeSerial', function() {
            let index = $(this).data('index');
            Swal.fire({
                title: 'Remove Serial?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    serialArray.splice(index, 1);
                    renderSerialList();
                }
            });
        });

        function calculateSummary() {
            let totalQty = 0;
            let subTotal = 0;
            $('#purchaseBody tr').each(function() {
                totalQty += parseFloat($(this).find('.qty').val()) || 0;
                subTotal += parseFloat($(this).find('.amount').val()) || 0;
            });
            $('#totalQty').val(totalQty);
            $('#subTotal').val(subTotal.toFixed(2));
            let discount = parseFloat($('#discount').val()) || 0;
            let vat = parseFloat($('#vat').val()) || 0;
            let vatAmount = ((subTotal - discount) * vat) / 100;
            let grandTotal = (subTotal - discount) + vatAmount;
            $('#grandTotal').val(grandTotal.toFixed(2));
            let paid = parseFloat($('#paidAmount').val()) || 0;
            let due = grandTotal - paid;
            $('#dueAmount').val(due < 0 ? 0 : due.toFixed(2));
        }

        function calculateRow(row) {
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let rate = parseFloat(row.find('.rate').val()) || 0;
            let amount = qty * rate;
            row.find('.amount').val(amount.toFixed(2));
            calculateSummary();
        }
        $('#saveSerial').click(function() {
            currentRow.find('.serial_json').val(
                JSON.stringify(serialArray)
            );
            currentRow.find('.qty').val(serialArray.length);
            calculateRow(currentRow);
            calculateSummary();
            $('#serialModal').modal('hide');
        });
        $('#serialModal').on('hidden.bs.modal', function() {
            $('#serialInput').val('');
        });
    </script>
@endpush
