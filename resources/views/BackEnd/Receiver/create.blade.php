<!-- Add Party Modal -->
<div class="modal fade" id="addReceiverModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('receiver.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus me-2"></i> Add Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- company name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Company <span class="text-danger">*</span></label>
                            <select name="customer_company_id"
                                class="form-select select2 @error('customer_company_id', 'add') is-invalid @enderror"
                                required>
                                <option value="">Select Customer Company</option>
                                @foreach ($customerCompanies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ old('customer_company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_company_id', 'add')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Receiver Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name', 'add') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Enter Customer Name..." required>
                            @error('name', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Designation -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation"
                                class="form-control @error('designation', 'add') is-invalid @enderror"
                                placeholder="Enter designation..." value="{{ old('designation') }}">
                            @error('designation', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone"
                                class="form-control @error('phone', 'add') is-invalid @enderror"
                                placeholder="Enter Phone Number..." value="{{ old('phone') }}">
                            @error('phone', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                class="form-control @error('email', 'add') is-invalid @enderror"
                                placeholder="Enter Email..." value="{{ old('email') }}">
                            @error('email', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status"
                                class="form-select select2 @error('status', 'add') is-invalid @enderror">
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="Inactive" {{ old('status', 'Active') == 'Inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                            @error('status', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control @error('address', 'add') is-invalid @enderror" rows="3"
                                placeholder="Enter Address...">{{ old('address') }}</textarea>
                            @error('address', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>
                        Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
