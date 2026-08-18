<div class="card shadow-sm mb-3">
    <div class="card-header">
        <h5 class="mb-0">Purchase Information</h5>
    </div>

    <div class="card-body">
        <div class="row">

            {{-- PO No --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">PO No</label>

                <input type="text" class="form-control" value="{{ $receiptNo }}" readonly>

                <input type="hidden" name="receipt_no" value="{{ $receiptNo }}">

                <input type="hidden" name="po_no" value="{{ $receiptNo }}">
            </div>

            {{-- Purchase Date --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">
                    Purchase Date
                </label>

                <input type="date" name="receipt_date"
                    class="form-control @error('receipt_date') is-invalid @enderror"
                    value="{{ old('receipt_date', date('Y-m-d')) }}" required>

                @error('receipt_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Supplier Company --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">
                    Supplier Company
                </label>

                <select name="customer_company_id" id="customer_company_id"
                    class="form-select select2 @error('customer_company_id') is-invalid @enderror" required>

                    <option value="">
                        Select Supplier Company
                    </option>

                    @foreach ($customerCompanies as $company)
                        <option value="{{ $company->id }}">
                            {{ $company->name }}
                        </option>
                    @endforeach

                </select>

                @error('customer_company_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Supplier --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">
                    Supplier Name
                </label>

                <select name="party_id" id="party_id"
                    class="form-select select2 @error('party_id') is-invalid @enderror" required>

                    <option value="">
                        Select Supplier
                    </option>

                </select>

                @error('party_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
    </div>
</div>
