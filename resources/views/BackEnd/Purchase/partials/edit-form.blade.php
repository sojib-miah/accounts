<div class="card shadow-sm mb-3">
    <div class="card-header">
        <h5 class="mb-0">Purchase Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">
                    PO No
                </label>
                <input type="text" class="form-control" value="{{ $purchase->receipt_no }}" readonly>
                <input type="hidden" name="receipt_no" value="{{ $purchase->receipt_no }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">
                    Purchase Date
                </label>
                <input type="date" name="receipt_date"
                    class="form-control @error('receipt_date') is-invalid @enderror"
                    value="{{ old('receipt_date', $purchase->receipt_date) }}" required>
                @error('receipt_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Supplier
                </label>
                <select name="party_id" class="form-select @error('party_id') is-invalid @enderror" required>
                    <option value="">
                        Select Supplier
                    </option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            {{ old('party_id', $purchase->party_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                            @if ($supplier->phone)
                                ({{ $supplier->phone }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('party_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">
                    Remarks
                </label>
                <textarea name="remarks" rows="3" class="form-control" placeholder="Write remarks if necessary...">{{ old('remarks', $purchase->remarks) }}</textarea>
            </div>
        </div>
    </div>
</div>
