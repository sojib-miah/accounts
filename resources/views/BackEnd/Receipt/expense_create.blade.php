@extends('BackEnd.Layouts.layout')

@section('title', 'Create Expense Receipt')

@section('content')
    <div class="p-5">
        <form action="{{ route('receipt.store') }}" method="POST" id="receiptForm">
            @csrf
            <input type="hidden" name="type" value="Expense">
            {{-- <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}"> --}}
            <input type="hidden" name="items" id="items_json">
            <div>
                <div class="row">
                    <!-- LEFT -->
                    <div class="col-lg-9">
                        <div class="card shadow-sm mt-3">
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

                                    {{-- =========================================================
         COMPANY
    ========================================================== --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Company Name
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select name="company_id" id="company_id" class="form-select select2" required>
                                            <option value="">Select Company</option>

                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}" data-name="{{ $company->name }}"
                                                    data-phone="{{ $company->phone ?? '' }}"
                                                    data-email="{{ $company->email ?? '' }}"
                                                    data-address="{{ $company->address ?? '' }}">
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        {{-- COMPANY INFORMATION --}}
                                        <div class="mt-3">

                                            <p class="mb-1">
                                                <b>Company Name :</b>
                                                <span id="company_name">-</span>
                                            </p>

                                        </div>

                                    </div>


                                    {{-- =========================================================
         BRANCH
    ========================================================== --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Branch Name
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select name="branch_id" id="branch_id" class="form-select select2" required>

                                            <option value="">
                                                Select Branch
                                            </option>

                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    data-company-id="{{ $branch->company_id }}"
                                                    data-company-name="{{ $branch->company->name ?? '' }}"
                                                    data-name="{{ $branch->name }}"
                                                    data-phone="{{ $branch->phone_one ?? ($branch->phone ?? '') }}"
                                                    data-email="{{ $branch->email ?? '' }}"
                                                    data-address="{{ $branch->address ?? '' }}">

                                                    {{ $branch->name }}

                                                </option>
                                            @endforeach

                                        </select>


                                        {{-- BRANCH INFORMATION --}}
                                        <div class="mt-3">

                                            <p class="mb-1">
                                                <b>Company Name :</b>
                                                <span id="branch_company_name">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>Branch Name :</b>
                                                <span id="branch_name">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>Mobile :</b>
                                                <span id="branch_phone">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>E-mail :</b>
                                                <span id="branch_email">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>Address :</b>
                                                <span id="branch_address">-</span>
                                            </p>

                                        </div>

                                    </div>


                                    {{-- =========================================================
         PARTY
    ========================================================== --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Party Name
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select name="party_id" id="party_id" class="form-select select2" required>

                                            <option value="">
                                                Select Party
                                            </option>

                                            @foreach ($parties as $party)
                                                <option value="{{ $party->id }}" data-name="{{ $party->name }}"
                                                    data-designation="{{ $party->designation ?? '' }}"
                                                    data-phone="{{ $party->phone ?? '' }}"
                                                    data-email="{{ $party->email ?? '' }}"
                                                    data-address="{{ $party->address ?? '' }}">

                                                    {{ $party->name }}

                                                </option>
                                            @endforeach

                                        </select>


                                        {{-- PARTY INFORMATION --}}
                                        <div class="mt-3">

                                            <p class="mb-1">
                                                <b>Name :</b>
                                                <span id="party_name">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>Designation :</b>
                                                <span id="party_designation">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>Mobile :</b>
                                                <span id="party_phone">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>E-mail :</b>
                                                <span id="party_email">-</span>
                                            </p>

                                            <p class="mb-1">
                                                <b>Address :</b>
                                                <span id="party_address">-</span>
                                            </p>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- Expense Item List --}}
                        <div class="card shadow-sm mt-3">

                            <div class="card-header d-flex justify-content-between align-items-center">

                                <h4 class="mb-0">
                                    Expense Items
                                </h4>

                                <button type="button" id="addRow" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus me-2"></i>
                                    Add Row
                                </button>

                            </div>


                            <div class="card-body p-0">

                                <div class="table-responsive">

                                    <table class="table table-bordered align-middle mb-0">

                                        <thead>

                                            <tr>

                                                <th width="50">
                                                    SN
                                                </th>

                                                <th width="200">
                                                    Category
                                                </th>

                                                <th width="250">
                                                    Expense
                                                </th>

                                                <th width="120">
                                                    Qty
                                                </th>

                                                <th width="140">
                                                    Unit Price
                                                </th>

                                                <th width="140">
                                                    Total
                                                </th>

                                                <th>
                                                    Remarks
                                                </th>

                                                <th width="80">
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

                        {{-- Total --}}
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
                                        <th class="d-flex align-items-center gap-2">
                                            <span>VAT</span>
                                            <i class="fa-solid fa-circle-info mt-1" title="Vat Count Percentege."></i>
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
                                            <input type="text" id="grand_total" class="form-control text-end fw-bold"
                                                readonly value="0.00">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Right Sidebar --}}
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

    <script type="text/template" id="expenseRowTemplate">

<tr class="expense-row">

    {{-- =====================================================
         SN
    ====================================================== --}}
    <td class="serial-no text-center">
        1
    </td>


    {{-- =====================================================
         CATEGORY
    ====================================================== --}}
    <td>

        <select
            name="category_id[]"
            class="form-select category select2-expense"
            required
        >

            <option value="">
                Select Category
            </option>

            @foreach ($categories as $category)

                <option
                    value="{{ $category->id }}"
                >
                    {{ $category->name }}
                </option>

            @endforeach

        </select>

    </td>


    {{-- =====================================================
         EXPENSE / ACCOUNT HEAD
    ====================================================== --}}
    <td>

        <select
            name="account_head_id[]"
            class="form-select account-head select2-expense"
            required
            disabled
        >

            <option value="">
                Select Expense
            </option>

        </select>

    </td>


    {{-- =====================================================
         QTY
    ====================================================== --}}
    <td>

        <input
            type="number"
            name="qty[]"
            class="form-control qty text-end"
            min="0.01"
            step="0.01"
            value="1"
            required
        >

    </td>


    {{-- =====================================================
         RATE
    ====================================================== --}}
    <td>

        <input
            type="number"
            name="rate[]"
            class="form-control rate text-end"
            min="0"
            step="0.01"
            value="0"
            required
        >

    </td>


    {{-- =====================================================
         TOTAL
    ====================================================== --}}
    <td>

        <input
            type="text"
            class="form-control total text-end"
            value="0.00"
            readonly
        >

    </td>


    {{-- =====================================================
         REMARKS
    ====================================================== --}}
    <td>

        <input
            type="text"
            name="details[]"
            class="form-control details"
            placeholder="Remarks"
        >

    </td>


    {{-- =====================================================
         DELETE
    ====================================================== --}}
    <td class="text-center">

        <button
            type="button"
            class="btn btn-danger btn-sm removeRow"
        >

            <i class="fa fa-trash"></i>

        </button>

    </td>

</tr>

</script>
@endsection

@push('scripts')
    @include('BackEnd.Receipt.partials.script')
@endpush
