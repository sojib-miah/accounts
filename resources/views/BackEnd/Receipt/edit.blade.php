@extends('BackEnd.Layouts.layout')

@section('title', 'Modify Expense Receipt')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            <form action="{{ route('receipt.update', $receipt->id) }}" method="POST" id="receiptForm">
                @csrf
                @method('PUT')
                {{-- Hidden Fields --}}
                <input type="hidden" name="type" value="{{ $receipt->type }}">
                <input type="hidden" name="company_id" id="hidden_company_id" value="{{ $receipt->company_id }}">
                <input type="hidden" name="items" id="items_json">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    {{-- LOGO --}}
                                    <div class="col-md-6">
                                        <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : asset('default-favicon.ico') }}"
                                            height="55">
                                    </div>
                                    {{-- RECEIPT DETAILS --}}
                                    <div class="col-md-6">
                                        <div class="row">
                                            {{-- RECEIPT NO --}}
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <div class="col-4 text-end">
                                                    <b>Receipt No :</b>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $receipt->receipt_no }}">
                                                </div>
                                            </div>
                                            {{-- DATE --}}
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <div class="col-4 text-end mt-2">
                                                    <b>Date :</b>
                                                </div>
                                                <div class="col-8 mt-2">
                                                    <input type="date" name="receipt_date" class="form-control"
                                                        value="{{ $receipt->receipt_date }}" required>
                                                </div>
                                            </div>
                                            {{-- CREATED BY --}}
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

                                {{-- COMPANY / BRANCH / PARTY --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Company Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="company_id" id="company_id" class="form-select select2" required>
                                            <option value="">Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}" data-name="{{ $company->name }}"
                                                    {{ (int) $receipt->company_id === (int) $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-3">
                                            <p>
                                                <b>Company Name :</b>
                                                <span id="name">{{ $receipt->company->name ?? '' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Branch Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="branch_id" id="branch_id" class="form-select select2" required>
                                            <option value="">Loading branches...</option>
                                        </select>
                                        {{-- BRANCH INFORMATION --}}
                                        <div class="mt-3">
                                            <p class="mb-1">
                                                <b>Company Name :</b>
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
                                                <span id="branch_phone">{{ $receipt->branch->phone_one ?? '' }}</span>
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
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Customer Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="party_id" id="party_id" class="form-select select2" required>
                                            <option value="">Select Customer</option>
                                            @foreach ($parties as $party)
                                                <option value="{{ $party->id }}" data-name="{{ $party->name }}"
                                                    data-designation="{{ $party->designation }}"
                                                    data-phone="{{ $party->phone }}" data-email="{{ $party->email }}"
                                                    data-address="{{ $party->address }}"
                                                    {{ (int) $receipt->party_id === (int) $party->id ? 'selected' : '' }}>
                                                    {{ $party->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- PARTY INFORMATION --}}
                                        <div class="mt-3">
                                            <p class="mb-1">
                                                <b>Name :</b>
                                                <span id="party_name">{{ $receipt->party->name ?? '' }}</span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Designation :</b>
                                                <span
                                                    id="party_designation">{{ $receipt->party->designation ?? '' }}</span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Mobile :</b>
                                                <span id="party_phone">{{ $receipt->party->phone ?? '' }}</span>
                                            </p>
                                            <p class="mb-1">
                                                <b>E-mail :</b>
                                                <span id="party_email">{{ $receipt->party->email ?? '' }}</span>
                                            </p>
                                            <p class="mb-1">
                                                <b>Address :</b>
                                                <span id="party_address">{{ $receipt->party->address ?? '' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- EXPENSE ITEMS --}}
                        <div class="card shadow-sm mt-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Expense Items</h4>
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
                                                <th width="40">SN</th>
                                                <th width="220">Category</th>
                                                <th width="250">Expense</th>
                                                <th width="150">Qty</th>
                                                <th width="200">Unit Price</th>
                                                <th width="200">Total Price</th>
                                                <th>Remarks</th>
                                                <th width="70">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="expenseBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- TOTAL SECTION --}}
                        <div class="row mt-4">
                            <div class="col-md-8">
                                <div>
                                    <label class="form-label">Receipt Notes</label>
                                    <textarea name="remarks" rows="6" class="form-control">{{ $receipt->remarks }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="180">Total Qty</th>
                                            <td>
                                                <input type="text" id="total_qty" class="form-control text-end"
                                                    readonly value="{{ $receipt->total_qty }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sub Total</th>
                                            <td>
                                                <input type="text" id="sub_total" class="form-control text-end"
                                                    readonly value="{{ number_format($receipt->sub_total, 2, '.', '') }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td>
                                                <input type="number" name="discount" id="discount"
                                                    value="{{ $receipt->discount ?? 0 }}" class="form-control text-end"
                                                    min="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="d-flex align-items-center gap-2">
                                                <span>VAT</span>
                                                <i class="fa-solid fa-circle-info mt-1" title="VAT Percentage"></i>
                                            </th>
                                            <td>
                                                <input type="number" name="vat" id="vat"
                                                    value="{{ $receipt->vat ?? 0 }}" class="form-control text-end"
                                                    min="0">
                                            </td>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>Grand Total</th>
                                            <td>
                                                <input type="text" id="grand_total"
                                                    class="form-control text-end fw-bold" readonly
                                                    value="{{ number_format($receipt->total_amount, 2, '.', '') }}">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="fa fa-save me-2"></i>
                            Update Receipt
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DATA FOR JAVASCRIPT --}}
    <script>
        window.expenseEditData = {
            receiptItems: @json($receiptItems),
            categories: @json($categories),
            receiptBranchId: @json($receipt->branch_id),
            receiptCompanyId: @json($receipt->company_id),
            receiptPartyId: @json($receipt->party_id)
        };
    </script>
@endsection

@push('scripts')
    @include('BackEnd.Receipt.partials.edit-script')
@endpush
