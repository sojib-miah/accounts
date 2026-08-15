@extends('BackEnd.Layouts.layout')

@section('title', 'Warehouse Receive')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fa fa-warehouse me-2"></i>
                    Warehouse Receive
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- PO Number --}}
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">
                            PO Number
                        </label>
                        <input class="form-control" readonly value="{{ $receipt->po_no }}">
                    </div>
                    {{-- Purchase Date --}}
                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">
                            Purchase Date
                        </label>
                        <input class="form-control" readonly value="{{ date('d-M-Y', strtotime($receipt->receipt_date)) }}">
                    </div>
                    {{-- Supplier --}}
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">
                            Supplier
                        </label>
                        <input class="form-control" readonly value="{{ $receipt->supplier->name ?? '' }}">
                    </div>
                    {{-- Remarks --}}
                    <div class="col-md-12">
                        <label class="fw-bold">
                            Remarks
                        </label>
                        <textarea class="form-control" rows="2" readonly>{{ $receipt->remarks }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-boxes me-2"></i>
                        Product Details
                    </h5>
                    @if (!$receipt->is_receive)
                        <span class="badge bg-warning text-dark">
                            Pending Receive
                        </span>
                    @else
                        <span class="badge bg-success">
                            Received
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="50">SL</th>
                                <th>Product Code</th>
                                <th>Product</th>
                                <th>Part No</th>
                                <th>Serial Number</th>
                                <th width="100">Unit</th>
                                <th width="100" class="text-end">Qty</th>
                                <th width="120" class="text-end">Rate</th>
                                <th width="140" class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQty = 0;
                                $totalAmount = 0;
                            @endphp
                            @forelse($receipt->items as $item)
                                @php
                                    $totalQty += $item->qty;
                                    $totalAmount += $item->amount;
                                    $serials = $item->serialNumbers->pluck('serial_no')->values();
                                @endphp
                                <tr>
                                    {{-- SL --}}
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- Product Code --}}
                                    <td>{{ $item->product->product_code ?? ($item->product->sku ?? '-') }}</td>
                                    {{-- Product --}}
                                    <td><strong>{{ $item->product->name }}</strong></td>
                                    {{-- part no --}}
                                    <td>
                                        <div>
                                            {{ $item->product->sku }}@if (!$loop->last)
                                                ,
                                            @endif
                                        </div>
                                    </td>
                                    {{-- Serial --}}
                                    <td>
                                        @if ($serials->count() > 0)
                                            <div style="max-width: 350px; white-space: normal; word-break: break-word;">
                                                @foreach ($serials as $serial)
                                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                                        {{ $serial }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No Serial</span>
                                        @endif
                                        <button type="button" class="btn btn-info btn-sm serialBtn" data-bs-toggle="modal"
                                            data-bs-target="#serialModal" data-item-id="{{ $item->id }}"
                                            data-product-name="{{ $item->product->name }}"
                                            data-qty="{{ (int) $item->qty }}" data-serials='@json($serials->values()->toArray())'>
                                            <i class="fa fa-barcode me-1">
                                            </i>
                                            @if ($serials->count())
                                                Update Serial
                                            @else
                                                Add Serial
                                            @endif
                                        </button>
                                    </td>
                                    {{-- Unit --}}
                                    <td>{{ $item->product->unit }}</td>
                                    {{-- Qty --}}
                                    <td class="text-end">{{ number_format($item->qty) }}</td>
                                    {{-- Rate --}}
                                    <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                    {{-- Amount --}}
                                    <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-danger">No Product Found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Total</th>
                                    <th class="text-end">{{ number_format($totalQty) }}</th>
                                    <th></th>
                                    <th class="text-end">{{ number_format($totalAmount, 2) }}</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">Discount</th>
                                    <th class="text-end">{{ number_format($receipt->discount ?? 0, 2) }}</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">
                                        VAT
                                        ({{ $receipt->vat ?? 0 }}%)
                                    </th>
                                    <th class="text-end">
                                        {{ number_format(((($receipt->sub_total ?? 0) - ($receipt->discount ?? 0)) * ($receipt->vat ?? 0)) / 100, 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">Grand Total</th>
                                    <th class="text-end">
                                        {{ number_format($receipt->total_amount ?? 0, 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('warehouse.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i>
                            Back
                        </a>
                        @if (!$receipt->is_receive)
                            <form action="{{ route('warehouse.receive', $receipt) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm(
                                'Receive this purchase?'
                            )">
                                    <i class="fa fa-check me-2"></i>
                                    Receive Goods
                                </button>
                            </form>
                        @else
                            <button class="btn btn-success" disabled>
                                <i class="fa fa-check me-2"></i>
                                Received
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- SERIAL MODAL --}}
        <div class="modal fade" id="serialModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    {{-- HEADER --}}
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">
                                <i class="fa fa-barcode me-2"></i>
                                Serial Number
                            </h5>
                            <small class="text-muted" id="modalProductName"></small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    {{-- BODY --}}
                    <div class="modal-body">
                        {{-- Quantity Information --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Product Qty</small>
                                    <h5 class="mb-0" id="modalQty">0</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Serial Added</small>
                                    <h5 class="mb-0" id="serialCount">0</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Status</small>
                                    <h5 class="mb-0" id="serialStatus">No Serial</h5>
                                </div>
                            </div>
                        </div>
                        {{-- Add / Edit Serial --}}
                        <div class="input-group mb-3">
                            <input type="text" id="serialInput" class="form-control" placeholder="Enter Serial Number">
                            <button type="button" class="btn btn-primary" id="addSerial">
                                <i class="fa fa-plus me-1"></i>
                                <span id="serialActionText">Add</span>
                            </button>
                            {{-- Cancel Edit --}}
                            <button type="button" class="btn btn-secondary d-none" id="cancelEditSerial">
                                <i class="fa fa-times me-1"></i>
                                Cancel
                            </button>
                        </div>
                        {{-- Serial Table --}}
                        <div class="table-responsive" style="height: 300px;">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="70">SL</th>
                                        <th>Serial Number</th>
                                        <th width="150" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="serialList"></tbody>
                            </table>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <i class="fa fa-info-circle me-1"></i>
                            Serial Number Required.
                            Serial Count অবশ্যই Product Qty-এর সমান হতে হবে।
                        </div>
                    </div>
                    {{-- FOOTER --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-success" id="saveSerial">
                            <i class="fa fa-save me-1"></i>
                            Save Serial
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(function() {
                let currentItemId = null;
                let currentQty = 0;
                let serialArray = [];
                let originalSerialArray = [];
                let serialSaved = false;
                let editingIndex = null;
                $(document).on('click', '.serialBtn', function() {
                    let button = $(this);
                    currentItemId = button.data('item-id');
                    currentQty = parseInt(button.data('qty')) || 0;
                    let productName = button.data('product-name');
                    let serialData = button.attr('data-serials');
                    try {
                        serialArray = JSON.parse(serialData || '[]');
                    } catch (e) {
                        serialArray = [];
                    }
                    serialArray = serialArray
                        .map(function(serial) {
                            return String(serial).trim().toUpperCase();
                        })
                        .filter(function(serial) {
                            return serial !== '';
                        });
                    originalSerialArray = [...serialArray];
                    serialSaved = false;
                    editingIndex = null;
                    $('#modalProductName').text(productName);
                    $('#modalQty').text(currentQty);
                    resetSerialInput();
                    renderSerialList();
                    setTimeout(function() {
                        $('#serialInput').focus();
                    }, 300);
                });

                function renderSerialList() {
                    let html = '';
                    if (serialArray.length === 0) {
                        html = `
                                <tr>

                                    <td colspan="3" class="text-center text-muted">
                                        <i class="fa fa-barcode me-1"></i>No Serial Added</td>
                                </tr>
                            `;

                    } else {
                        serialArray.forEach(
                            function(serial, index) {
                                html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>
                                        <strong>${escapeHtml(serial)}</strong>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group"
                                            role="group">
                                            {{-- EDIT --}}
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm editSerial"
                                                data-index="${index}"
                                                title="Edit Serial">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            {{-- DELETE --}}
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm removeSerial"
                                                data-index="${index}"
                                                title="Delete Serial">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            }
                        );
                    }
                    $('#serialList').html(html);
                    $('#serialCount').text(serialArray.length);
                    updateSerialStatus();
                }

                function updateSerialStatus() {
                    if (serialArray.length === 0) {
                        $('#serialStatus')
                            .removeClass('text-success text-danger')
                            .addClass('text-warning')
                            .text('No Serial');
                        return;
                    }
                    if (serialArray.length === currentQty) {
                        $('#serialStatus')
                            .removeClass('text-warning text-danger')
                            .addClass('text-success')
                            .text('Complete');
                    } else {
                        $('#serialStatus')
                            .removeClass('text-success text-warning')
                            .addClass('text-danger')
                            .text('Incomplete');
                    }
                }
                $('#addSerial').on('click', function() {
                    let serial =
                        $('#serialInput').val().trim().toUpperCase();
                    if (serial === '') {
                        Swal.fire({
                            icon: 'warning',
                            position: 'top-end',
                            title: 'Serial Required',
                            text: 'Please enter serial number.'
                        });
                        $('#serialInput').focus();
                        return;
                    }
                    if (editingIndex !== null) {
                        let duplicate =
                            serialArray.some(
                                function(item, index) {
                                    return (index !== editingIndex && String(item).toUpperCase() === serial);
                                }
                            );
                        if (duplicate) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Duplicate Serial',
                                text: 'This serial number is already added.'
                            });
                            $('#serialInput').focus();
                            return;
                        }
                        serialArray[editingIndex] = serial;
                        editingIndex = null;
                        resetSerialInput();
                        renderSerialList();
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            text: 'Serial number updated successfully.',
                            timer: 1200,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                        $('#serialInput').focus();
                        return;
                    }
                    if (serialArray.length >= currentQty) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Serial Limit Reached',
                            text: 'You can add only ' + currentQty + ' serial number(s).'
                        });
                        return;
                    }
                    let duplicate = serialArray.some(function(item) {
                        return (String(item).toUpperCase() === serial);
                    });
                    if (duplicate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Duplicate Serial',
                            text: 'This serial number is already added.'
                        });
                        $('#serialInput').val('').focus();
                        return;
                    }
                    serialArray.push(serial);
                    renderSerialList();
                    $('#serialInput').val('').focus();
                });
                $(document).on('click', '.editSerial', function() {
                    let index = parseInt($(this).data('index'));
                    if (isNaN(index)) {
                        return;
                    }
                    if (!serialArray[index]) {
                        return;
                    }
                    editingIndex = index;
                    $('#serialInput')
                        .val(serialArray[index]).focus();
                    $('#serialActionText').text('Update');
                    $('#addSerial').removeClass('btn-primary').addClass('btn-warning');
                    $('#cancelEditSerial').removeClass('d-none');
                    $('#serialInput').addClass('border-warning');
                });
                $('#cancelEditSerial').on('click', function() {
                    resetSerialInput();
                    $('#serialInput').focus();
                });
                $(document).on('click', '.removeSerial', function() {
                    let index = parseInt($(this).data('index'));
                    if (isNaN(index)) {
                        return;
                    }
                    let serial = serialArray[index];
                    if (editingIndex === index) {
                        resetSerialInput();
                    }
                    Swal.fire({
                        icon: 'warning',
                        title: 'Remove Serial?',
                        html: 'Are you sure you want to remove<br>' + '<strong>' + escapeHtml(serial) +
                            '</strong>?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Remove',
                        cancelButtonText: 'Cancel'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            serialArray.splice(index, 1);
                            if (editingIndex !== null) {
                                if (editingIndex === index) {
                                    resetSerialInput();
                                } else if (
                                    editingIndex > index
                                ) {
                                    editingIndex--;
                                }
                            }
                            renderSerialList();
                        }
                    });

                });
                $('#serialInput').on('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        $('#addSerial').trigger('click');
                    }
                    if (e.key === 'Escape' && editingIndex !== null) {
                        e.preventDefault();
                        resetSerialInput();
                    }
                });
                $('#saveSerial').on('click', function() {
                    if (serialArray.length !== currentQty) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Serial Number Required',
                            text: 'Serial number is required and must be equal to the product quantity. ' +
                                'Product Qty: ' + currentQty + ', Serial: ' + serialArray.length,
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    let action =
                        '{{ route('warehouse.serial.update', ['receipt' => $receipt->id, 'receiptItem' => '__ITEM__']) }}'
                        .replace('__ITEM__', currentItemId);
                    let form = $('<form>', {
                        method: 'POST',
                        action: action
                    });
                    form.append(
                        $('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: '{{ csrf_token() }}'
                        })
                    );
                    form.append(
                        $('<input>', {
                            type: 'hidden',
                            name: 'serial_json',
                            value: JSON.stringify(
                                serialArray
                            )
                        })
                    );
                    $('body').append(form);
                    serialSaved = true;
                    form.submit();
                });
                $('#serialModal').on('hidden.bs.modal', function() {
                    serialArray = [];
                    originalSerialArray = [];
                    currentItemId = null;
                    currentQty = 0;
                    serialSaved = false;
                    editingIndex = null;
                    resetSerialInput();
                });

                function resetSerialInput() {
                    editingIndex = null;
                    $('#serialInput').val('').removeClass('border-warning');
                    $('#serialActionText').text('Add');
                    $('#addSerial').removeClass('btn-warning').addClass('btn-primary');
                    $('#cancelEditSerial').addClass('d-none');
                }

                function escapeHtml(text) {
                    return $('<div>').text(text).html();
                }
            });
        </script>
    @endpush
