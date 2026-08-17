<div class="modal fade" id="editSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Edit Supplier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form id="editSupplierForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_supplier_id">
                <div class="modal-body">
                    <div class="row">
                        <!-- company name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Supplier Company
                            </label>
                            <select name="customer_company_id" id="edit_customer_company_id"
                                class="form-select select2">
                                <option value="">Select Customer Company</option>
                                @foreach ($customerCompanies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_company_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Supplier Name</label>
                            <input type="text" id="edit_name" name="name" class="form-control">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Designation</label>
                            <input type="text" id="edit_designation" name="designation" class="form-control">
                            @error('designation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" id="edit_phone" name="phone" class="form-control">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select id="edit_status" name="status" class="form-select">
                                <option value="Active">
                                    Active
                                </option>
                                <option value="Inactive">
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>
                                Address
                            </label>
                            <textarea id="edit_address" name="address" class="form-control"></textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button class="btn btn-success">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
