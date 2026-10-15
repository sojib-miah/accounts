<div class="modal fade" id="createSupplierModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-plus me-2"></i> Add Supplier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- company name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Supplier Company <span class="text-danger">*</span>
                            </label>
                            <select name="customer_company_id"
                                class="form-select select2 @error('customer_company_id', 'add') is-invalid @enderror"
                                required>
                                <option value="">Select Supplier Company</option>
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
                        <div class="col-md-6 mb-3">
                            <label>Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Supplier Name..." name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name', 'add') is-invalid @enderror" required>
                            @error('name', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" placeholder="Designation..."
                                value="{{ old('designation') }}"
                                class="form-control @error('designation', 'add') is-invalid @enderror">
                            @error('designation', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="Phone..." value="{{ old('phone') }}"
                                class="form-control @error('phone', 'add') is-invalid @enderror">
                            @error('phone', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Email..." value="{{ old('email') }}"
                                class="form-control @error('email', 'add') is-invalid @enderror">
                            @error('email', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status"
                                class="form-select select2 @error('status', 'add') is-invalid @enderror">
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="Inactive" {{ old('status', 'Active') == 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status', 'add')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Address</label>
                            <textarea name="address" placeholder="Address..." class="form-control @error('address', 'add') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Close
                    </button>
                    <button class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
