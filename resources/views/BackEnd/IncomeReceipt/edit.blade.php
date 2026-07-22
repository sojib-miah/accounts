@extends('BackEnd.Layouts.layout')

@section('title', 'Modify Income Receipt')

@section('content')
    <div class="py-4">
        <div class="mx-5">
            <form action="{{ route('income.receipt.update', $receipt->id) }}" method="POST" id="receiptForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $receipt->type }}">
                {{-- <input type="hidden" name="company_id" value="{{ $receipt->company_id }}"> --}}
                <input type="hidden" name="items" id="items_json">
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    Invoice Items
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
                                                <th width="40">
                                                    SN
                                                </th>
                                                <th width="180">
                                                    Category
                                                </th>
                                                <th width="220">
                                                    Invoice
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
                                                    Remarks
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
                        {{-- ========================================= --}}
                        {{-- Total --}}
                        {{-- ========================================= --}}
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
                                                class="form-control text-end" min="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="d-flex align-items-center gap-2">
                                            <span>VAT</span>
                                            <i class="fa-solid fa-circle-info mt-1" title="Vat Count Percentege."></i>
                                        </th>
                                        <td>
                                            <input type="number" name="vat" id="vat" value="0"
                                                class="form-control text-end" min="0">
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
                    {{-- ========================================= --}}
                    {{-- Right Sidebar --}}
                    {{-- ========================================= --}}
                    <div class="col-lg-3">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <strong>
                                    Invoice Notes
                                </strong>
                            </div>
                            <div class="card-body">
                                <textarea name="remarks" rows="6" class="form-control">{{ $receipt->remarks }}</textarea>
                                <button type="submit" class="btn btn-success w-100 mt-3">
                                    <i class="fa fa-save me-2"></i>
                                    Update Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('BackEnd.Script.editscript')
@endpush
