@foreach ($suppliers as $supplier)
    <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-edit me-2"></i> Edit Supplier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <!-- company name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Supplier Company <span class="text-danger">*</span>
                                </label>
                                <select name="customer_company_id"
                                    class="form-select select2 @error('customer_company_id', 'edit') is-invalid @enderror"
                                    required>
                                    <option value="">Select Customer Company</option>
                                    @foreach ($customerCompanies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ (string) old('customer_company_id', $supplier->customer_company_id) === (string) $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_company_id', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    value="{{ session('edit_supplier_id') == $supplier->id && $errors->getBag('edit')->any() ? old('name', $supplier->name) : $supplier->name }}"
                                    placeholder="Supplier Name..."
                                    class="form-control @error('name', 'edit') is-invalid @enderror" required>
                                @error('name', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Designation</label>
                                <input type="text" name="designation"
                                    value="{{ session('edit_supplier_id') == $supplier->id && $errors->getBag('edit')->any() ? old('designation', $supplier->designation) : $supplier->designation }}"
                                    placeholder="Designation..."
                                    class="form-control @error('designation', 'edit') is-invalid @enderror">
                                @error('designation', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone"
                                    value="{{ session('edit_supplier_id') == $supplier->id && $errors->getBag('edit')->any() ? old('phone', $supplier->phone) : $supplier->phone }}"
                                    placeholder="Phone..."
                                    class="form-control @error('phone', 'edit') is-invalid @enderror">
                                @error('phone', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email"
                                    value="{{ session('edit_supplier_id') == $supplier->id && $errors->getBag('edit')->any() ? old('email', $supplier->email) : $supplier->email }}"
                                    placeholder="Email..."
                                    class="form-control @error('email', 'edit') is-invalid @enderror">
                                @error('email', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select name="status"
                                    class="form-select select2 @error('status', 'edit') is-invalid @enderror">
                                    <option value="Active"
                                        {{ old('status', $supplier->status) == 'Active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $supplier->status) == 'Inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <textarea name="address" placeholder="Address..." class="form-control @error('address', 'edit') is-invalid @enderror">{{ session('edit_supplier_id') == $supplier->id && $errors->getBag('edit')->any() ? old('address', $supplier->address) : $supplier->address }}</textarea>
                                @error('address', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button class="btn btn-success">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
