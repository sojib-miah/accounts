@extends('BackEnd.Layouts.layout')

@section('title', 'Create Sales Order')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            <form action="{{ route('sales.order.store') }}" method="POST" id="receiptForm">
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
                                                Customer Company <span class="text-danger">*</span>
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
                                                Customer Name <span class="text-danger">*</span>
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
                            <div class="card shadow-sm mt-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>Products</h4>
                                    <div>
                                        <button type="button" class="btn btn-primary" id="addRow">
                                            <i class="fa fa-plus me-2"></i>
                                            Add Product
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="50">SL</th>
                                                    <th width="300">sku-Product name</th>
                                                    <th>Description</th>
                                                    <th width="150">Serial No</th>
                                                    <th width="120">Stock</th>
                                                    <th width="120">Qty</th>
                                                    <th width="150">Unit Price</th>
                                                    <th width="150">Total Price</th>
                                                    <th>Remarks</th>
                                                    <th width="60">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="salesBody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- Total --}}
                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <label>Remarks</label>
                                    <textarea name="remarks" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="col-md-4 border">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Total Qty</th>
                                            <td>
                                                <input id="total_qty" readonly class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sub Total</th>
                                            <td>
                                                <input id="sub_total" readonly class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td>
                                                <input type="number" name="discount" id="discount" value="0"
                                                    class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>VAT %</th>
                                            <td>
                                                <input type="number" name="vat" id="vat" value="0"
                                                    class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <td>
                                                <input id="grand_total" readonly class="form-control text-end fw-bold">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 mt-3">
                                Save Sales Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SERIAL NUMBER MODAL --}}
    <div class="modal fade" id="serialModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                {{-- HEADER --}}
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-barcode me-2"></i>
                        Select Serial Numbers
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                {{-- BODY --}}
                <div class="modal-body">
                    {{-- Product information --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Product</label>
                            <input type="text" id="serialProductName" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Required Qty</label>
                            <input type="text" id="serialRequiredQty" class="form-control text-end" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Selected</label>
                            <input type="text" id="serialSelectedQty" class="form-control text-end" readonly>
                        </div>
                    </div>
                    {{-- Search --}}
                    <div class="mb-3">
                        <input type="text" id="serialSearch" class="form-control"
                            placeholder="Search serial number...">
                    </div>
                    {{-- Select all --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <label class="form-check-label">
                                <input type="checkbox" id="selectAllSerial" class="form-check-input me-1">
                                Select All
                            </label>
                        </div>
                        <div>
                            <span id="serialAvailableText" class="badge bg-secondary">
                                0 Available
                            </span>
                        </div>
                    </div>
                    {{-- Serial list --}}
                    <div id="serialList" class="border rounded p-2" style="max-height: 350px; overflow-y: auto;">
                        <div class="text-center text-muted py-4">
                            Select a product first.
                        </div>
                    </div>

                    <div id="serialModalError" class="alert alert-danger mt-3 d-none">
                    </div>
                </div>
                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="saveSerialSelection">
                        <i class="fa fa-check me-1"></i>
                        Add Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/template" id="salesRowTemplate">
        <tr>
            <td class="sl text-center">1</td>
            <td>
                <select name="product_id[]" class="form-select product select2" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->current_stock }}" data-price="{{ $product->sale_price }}" data-code="{{ $product->product_code }}" data-unit="{{ $product->unit }}"  data-description="{{ $product->description }}">
                            {{ $product->sku }}-{{ $product->name }}(Stock:{{ number_format($product->current_stock) }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="description[]" class="form-control description" placeholder="Description"></td>
            <td class="text-center">
                <button type="button" class="btn btn-info btn-sm serialBtn" disabled>
                    <i class="fa fa-barcode me-1"></i>
                    Serial
                </button>
                <input type="hidden" name="serial_json[]" class="serial_json" value="[]">
                <div class="serialCountText mt-1">
                    <small class="text-muted">
                        No Serial
                    </small>
                </div>
            </td>
            <td>
                <input type="text" class="form-control stock text-end" readonly>
            </td>
            <td>
                <input type="number" min="1" name="qty[]" class="form-control qty text-end" value="1">
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="rate[]" class="form-control rate text-end" value="0">
            </td>
            <td>
                <input type="text" class="form-control total text-end" readonly>
            </td>
            <td>
                <input type="text" name="details[]" class="form-control details" placeholder="Remarks">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger remove">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    </script>
@endsection

@push('scripts')
    @include('BackEnd.Script.addscript')
@endpush
