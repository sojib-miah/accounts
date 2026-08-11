@extends('BackEnd.Layouts.layout')

@section('title', 'Modify Sales Order')

@section('content')
    <div class="py-4">
        <div class="mx-5">
            <form action="{{ route('sales.order.update', $receipt->id) }}" method="POST" id="receiptForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $receipt->type }}">
                {{-- <input type="hidden" name="company_id" value="{{ $receipt->company_id }}"> --}}
                {{-- <input type="hidden" name="items" id="items_json"> --}}
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
                                                    <b>Receipt No :</b>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $receipt->receipt_no }}">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <div class="col-4 text-end mt-2">
                                                    <b>Date :</b>
                                                </div>
                                                <div class="col-8 mt-2">
                                                    <input type="date" name="receipt_date" class="form-control"
                                                        value="{{ $receipt->receipt_date }}">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <div class="col-4 text-end mt-2">
                                                    <b>By :</b>
                                                </div>
                                                <div class="col-8 mt-2">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $receipt->creator->name ?? auth()->user()->name }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    {{-- company --}}
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Company Name <span class="text-danger">*</span>
                                        </label>
                                        <select name="company_id" id="company_id" class="form-select select2" required>
                                            <option value="">Select Company</option>

                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ $receipt->company_id == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-3">
                                            <p><b>Company Name :</b> <span
                                                    id="name">{{ $receipt->company->name ?? '' }}</span></p>
                                        </div>
                                    </div>
                                    <!-- Branch -->
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Branch Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="branch_id" id="branch_id" class="form-select select2" required>
                                            <option value="">Select Branch</option>

                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ $receipt->branch_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-3">
                                            <p class="mb-1"><b>Company Name :</b>
                                                <span id="company_name">
                                                    {{ $receipt->branch->company->name ?? '' }}
                                                </span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Branch Name :</b>
                                                <span id="branch_name">
                                                    {{ $receipt->branch->name ?? '' }}
                                                </span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Mobile :</b>
                                                <span id="branch_phone">
                                                    {{ $receipt->branch->phone_one ?? '' }}
                                                </span>
                                            </p>
                                            <p class="mb-1">
                                                <b>E-mail :</b>
                                                <span id="branch_email">
                                                    {{ $receipt->branch->email ?? '' }}
                                                </span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Address :</b>
                                                <span id="branch_address">
                                                    {{ $receipt->branch->address ?? '' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Party -->
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Customer Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="party_id" id="party_id" class="form-select select2" required>
                                            @foreach ($parties as $party)
                                                <option value="{{ $party->id }}"
                                                    {{ $receipt->party_id == $party->id ? 'selected' : '' }}>
                                                    {{ $party->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-3">
                                            <p class="mb-1">
                                                <b>Name :</b>
                                                <span id="party_name">
                                                    {{ $receipt->party->name ?? '' }}
                                                </span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Designation :</b>
                                                <span id="party_designation">
                                                    {{ $receipt->party->designation ?? '' }}
                                                </span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Mobile :</b>
                                                <span id="party_phone">
                                                    {{ $receipt->party->phone ?? '' }}
                                                </span>
                                            </p>
                                            <p class="mb-1"><b>E-mail :</b> <span
                                                    id="party_email">{{ $receipt->party->email ?? '' }}</span></p>
                                            <p class="mb-1">
                                                <b>Address :</b>
                                                <span id="party_address">
                                                    {{ $receipt->party->address ?? '' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ========================================= --}}
                        {{-- Income Item List --}}
                        {{-- ========================================= --}}
                        <div class="card shadow-sm mt-3">
                            <div class="card-header d-flex justify-content-between">
                                <h4>
                                    Products
                                </h4>
                                <div>
                                    <button type="button" id="addRow" class="btn btn-primary">
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
                                                <th width="50">
                                                    SL
                                                </th>
                                                <th width="300">
                                                    Product
                                                </th>
                                                <th>
                                                    Serial No
                                                </th>
                                                <th width="120">
                                                    Stock
                                                </th>
                                                <th width="120">
                                                    Qty
                                                </th>
                                                <th width="140">
                                                    Sale Price
                                                </th>
                                                <th width="140">
                                                    Amount
                                                </th>
                                                <th>
                                                    Remarks
                                                </th>
                                                <th width="60">
                                                    Action
                                                </th>
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
                                <label>
                                    Remarks
                                </label>
                                <textarea name="remarks" rows="5" class="form-control">{{ $receipt->remarks }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <div class="border">
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
                                                <input type="number" id="discount" name="discount"
                                                    value="{{ $receipt->discount }}" class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>VAT %</th>
                                            <td>
                                                <input type="number" id="vat" name="vat"
                                                    value="{{ $receipt->vat }}" class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>Grand Total</th>
                                            <td>
                                                <input id="grand_total" readonly class="form-control text-end fw-bold">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="fa fa-save me-2"></i>
                            Update Sales Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="serialModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">


                {{-- HEADER --}}

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fa fa-barcode me-2"></i>

                        Select Serial Numbers

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                {{-- BODY --}}

                <div class="modal-body">


                    <div class="row mb-3">

                        <div class="col-md-6">

                            <label class="fw-bold">
                                Product
                            </label>

                            <input type="text" id="serialProductName" class="form-control" readonly>

                        </div>


                        <div class="col-md-2">

                            <label class="fw-bold">
                                Qty
                            </label>

                            <input type="text" id="serialQty" class="form-control text-end" readonly>

                        </div>


                        <div class="col-md-2">

                            <label class="fw-bold">
                                Selected
                            </label>

                            <input type="text" id="serialSelected" class="form-control text-end" value="0"
                                readonly>

                        </div>


                        <div class="col-md-2">

                            <label class="fw-bold">
                                Available
                            </label>

                            <input type="text" id="serialAvailable" class="form-control text-end" value="0"
                                readonly>

                        </div>

                    </div>


                    {{-- SEARCH --}}

                    <div class="mb-3">

                        <input type="text" id="serialSearch" class="form-control"
                            placeholder="Search serial number...">

                    </div>


                    {{-- SERIAL LIST --}}

                    <div id="serialList" class="border rounded p-3" style="max-height:350px; overflow-y:auto;">

                        <div class="text-center text-muted py-4">

                            <i class="fa fa-spinner fa-spin"></i>

                            Loading...

                        </div>

                    </div>


                    <div id="serialEmpty" class="text-center text-muted py-4 d-none">

                        <i class="fa fa-barcode fa-2x mb-2"></i>

                        <br>

                        No Serial Number Available

                    </div>


                </div>


                {{-- FOOTER --}}

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button type="button" class="btn btn-primary" id="saveSerialBtn">

                        <i class="fa fa-check me-1"></i>

                        Select Serial

                    </button>

                </div>


            </div>

        </div>

    </div>

    <script type="text/template" id="salesRowTemplate">

<tr>

    {{-- SL --}}
    <td class="sl text-center"></td>


    {{-- PRODUCT --}}
    <td>

        <select
            name="product_id[]"
            class="form-select product select2"
            required>

            <option value="">
                Select Product
            </option>

            @foreach($products as $product)

                <option
                    value="{{ $product->id }}"

                    data-stock="{{ $product->current_stock }}"

                    data-price="{{ $product->sale_price }}"

                    data-unit="{{ $product->unit }}"

                    data-code="{{ $product->product_code }}">

                    {{ $product->product_code }}
                    -
                    {{ $product->name }}

                    (Stock:
                    {{ number_format($product->current_stock, 2) }})

                </option>

            @endforeach

        </select>


        

    </td>

    <td>
        {{-- SERIAL BUTTON --}}

        <div class="mt-2 d-flex align-items-center gap-2">

            <button
                type="button"
                class="btn btn-info btn-sm serialBtn">

                <i class="fa fa-barcode me-1"></i>

                Serial

                <span class="serialCount badge bg-light text-dark ms-1">
                    0
                </span>

            </button>


            {{-- IMPORTANT --}}
            <input
                type="hidden"
                name="serial_json[]"
                class="serial_json"
                value="[]">


            <small class="text-muted serialStatus">
                No serial selected
            </small>

        </div>
    </td>

    {{-- STOCK --}}
    <td>

        <input
            type="text"
            class="form-control stock text-end"
            readonly>

    </td>


    {{-- QTY --}}
    <td>

        <input
            type="number"
            min="1"
            name="qty[]"
            value="1"
            class="form-control qty text-end"
            required>

    </td>


    {{-- RATE --}}
    <td>

        <input
            type="number"
            step="0.01"
            min="0"
            name="rate[]"
            class="form-control rate text-end"
            required>

    </td>


    {{-- TOTAL --}}
    <td>

        <input
            type="text"
            class="form-control total text-end"
            value="0.00"
            readonly>

    </td>


    {{-- DETAILS --}}
    <td>

        <input
            type="text"
            name="details[]"
            class="form-control details">

    </td>


    {{-- ACTION --}}
    <td class="text-center">

        <button
            type="button"
            class="btn btn-danger remove">

            <i class="fa fa-trash"></i>

        </button>

    </td>

</tr>

</script>
    <style>
        .select2 {
            width: 250px !important;
        }
    </style>
@endsection

<script>
    const oldItems = @json($oldItems);
</script>

@push('scripts')
    @include('BackEnd.Script.editscript')
@endpush
