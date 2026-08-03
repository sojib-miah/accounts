@extends('BackEnd.Layouts.layout')

@section('title', 'Add Purchase')

@section('content')
    <div class="p-5 mt-3">
        <form action="{{ route('purchase.store') }}" method="POST">
            @csrf
            @include('BackEnd.Purchase.partials.form')
            <input type="hidden" name="serial_json[]" class="serial_json" value="[]">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Purchase Items</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="addRow">
                        <i class="fa fa-plus"></i>
                        Add Product
                    </button>
                </div>
                <div class="card-body">
                    @include('BackEnd.Purchase.partials.items')
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 ms-auto">
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Qty</th>
                            <td>
                                <input type="text" id="totalQty" name="total_qty" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Sub Total</th>
                            <td>
                                <input type="text" id="subTotal" name="sub_total" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                <input type="number" step="0.01" id="discount" name="discount" value="0"
                                    class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>VAT</th>
                            <td>
                                <input type="number" step="0.01" id="vat" name="vat" value="0"
                                    class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Grand Total</th>
                            <td>
                                <input type="text" id="grandTotal" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td>
                                <input type="number" step="0.01" id="paidAmount" name="paid_amount" value="0"
                                    class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Due Amount</th>
                            <td>
                                <input type="text" id="dueAmount" class="form-control" readonly>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-end mt-3">
                <button class="btn btn-success btn-lg">
                    <i class="fa fa-save me-2"></i>
                    Save Purchase
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
    @include('BackEnd.Purchase.partials.script')

    <script>
        let currentRow = null;
        let serialArray = [];
        $(document).on('click', '.serialBtn', function() {

            currentRow = $(this).closest('tr');

            serialArray = [];

            let productName = currentRow.find('.product option:selected').text();

            $('#serialProductName').val(productName);

            $('#serialInput').val('');

            let json = currentRow.find('.serial_json').val();

            if (json && json !== '[]') {

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

            }, 300);

        });
        $('#addSerial').click(function() {

            let serial = $('#serialInput').val().trim();

            if (serial == '') {

                Swal.fire({

                    icon: 'warning',

                    title: 'Please enter Serial Number'

                });

                return;

            }

            if (serialArray.includes(serial)) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Duplicate Serial Number'

                });

                return;

            }

            serialArray.push(serial);

            $('#serialInput').val('');

            renderSerialList();

            $('#serialInput').focus();

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

                html = `

            <tr>

                <td colspan="3"
                    class="text-center text-muted">

                    No Serial Added

                </td>

            </tr>

        `;

            } else {

                $.each(serialArray, function(index, serial) {

                    html += `

            <tr>

                <td>

                    ${index+1}

                </td>

                <td>

                    ${serial}

                </td>

                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-danger btn-sm removeSerial"
                        data-index="${index}">

                        <i class="fa fa-trash"></i>

                    </button>

                </td>

            </tr>

            `;

                });

            }

            $('#serialList').html(html);

            $('#serialCount').text(serialArray.length);

            $('#requiredQty').text(serialArray.length);

            $('#serialStatus').html(

                '<span class="text-success">Qty Auto Updated</span>'

            );

            if (currentRow) {

                currentRow.find('.qty').val(serialArray.length);

            }

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
        $('#saveSerial').click(function() {

            if (serialArray.length == 0) {

                Swal.fire({

                    icon: 'warning',

                    title: 'No Serial Found'

                });

                return;

            }

            currentRow.find('.serial_json').val(

                JSON.stringify(serialArray)

            );

            currentRow.find('.qty').val(
                serialArray.length
            );
            currentRow.find('.serialBadge').removeClass(
                'bg-secondary'
            ).addClass(
                'bg-success'

            ).text(

                serialArray.length + ' Serial'

            );

            currentRow.find('.serialBtn').removeClass(

                'btn-info'

            ).addClass(

                'btn-success'

            );

            $('#serialModal').modal('hide');

        });
        $('#serialModal').on('hidden.bs.modal', function() {

            $('#serialInput').val('');

        });
    </script>
@endpush
