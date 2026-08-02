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
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                Company Name <span class="text-danger">*</span>
                                            </label>

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
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                Branch Name <span class="text-danger">*</span>
                                            </label>
                                            <select name="branch_id" id="branch_id" class="form-select select2" required>
                                                <option value="">
                                                    Select Branch
                                                </option>
                                            </select>
                                            <div class="mt-3">
                                                <p class="mb-1"><b>Company Name :</b> <span id="company_name"></span></p>
                                                <p class="mb-1"><b>Branch Name :</b> <span id="branch_name"></span></p>
                                                <p class="mb-1"><b>Mobile :</b> <span id="branch_phone"></span></p>
                                                <p class="mb-1"><b>E-mail :</b> <span id="branch_email"></span></p>
                                                <p class="mb-1"><b>Address :</b> <span id="branch_address"></span></p>
                                            </div>
                                        </div>
                                        <!-- Party -->
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                Customer Name <span class="text-danger">*</span>
                                            </label>
                                            <select name="party_id" id="party_id" class="form-select select2" required>
                                                <option value="">
                                                    Select Customer
                                                </option>
                                                @foreach ($parties as $party)
                                                    <option value="{{ $party->id }}">
                                                        {{ $party->name }}
                                                    </option>
                                                @endforeach
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
                            {{-- ========================= --}}
                            {{-- Income Item List --}}
                            {{-- ========================= --}}
                            <div class="card shadow-sm mt-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>
                                        Products
                                    </h4>
                                    <div>
                                        <button type="button" class="btn btn-primary" id="addRow">
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
                                                    <th width="120">
                                                        Stock
                                                    </th>
                                                    <th width="120">
                                                        Qty
                                                    </th>
                                                    <th width="150">
                                                        Sale Price
                                                    </th>
                                                    <th width="150">
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
                                            <tbody id="salesBody">
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
                                    <label>
                                        Remarks
                                    </label>
                                    <textarea name="remarks" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>
                                                Total Qty
                                            </th>
                                            <td>
                                                <input id="total_qty" readonly class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Sub Total
                                            </th>
                                            <td>
                                                <input id="sub_total" readonly class="form-control text-end">
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
                                                VAT %
                                            </th>
                                            <td>
                                                <input type="number" name="vat" id="vat" value="0"
                                                    class="form-control text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Grand Total
                                            </th>
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

    <script type="text/template" id="salesRowTemplate">
        <tr>
            <td class="sl text-center">
                1
            </td>
            <td>
                <select
                    name="product_id[]"
                    class="form-select product select2">
                    <option value="">
                        Select Product
                    </option>
                    @foreach($products as $product)
                    <option
                        value="{{ $product->id }}"
                        data-stock="{{ $product->current_stock }}"
                        data-price="{{ $product->sale_price }}"
                        data-code="{{ $product->product_code }}"
                        data-unit="{{ $product->unit }}">
                        {{ $product->model_no }}
                        -
                        {{ $product->name }}
                        (Stock :
                        {{ number_format($product->current_stock,2) }})
                    </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input
                    type="text"
                    class="form-control stock text-end"
                    readonly>
            </td>
            <td>
                <input
                    type="number"
                    min="1"
                    name="qty[]"
                    class="form-control qty text-end"
                    value="1">
            </td>
            <td>
                <input
                    type="number"
                    step="0.01"
                    name="rate[]"
                    class="form-control rate text-end"
                    value="0">
            </td>
            <td>
                <input
                    type="text"
                    class="form-control total text-end"
                    readonly>
            </td>
            <td>
                <input
                    type="text"
                    name="details[]"
                    class="form-control details"
                    placeholder="Remarks">
            </td>
            <td class="text-center">
                <button
                    type="button"
                    class="btn btn-danger remove">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    </script>
@endsection

@push('scripts')
    @include('BackEnd.Script.addscript')
@endpush
