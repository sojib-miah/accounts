<!-- Edit Receiver Modal -->
<div class="modal fade" id="editReceiverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editReceiverForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- company name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Customer Company
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
                            @error('customer_company_id', 'edit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Receiver Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control">
                            @error('name', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- designation -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" id="edit_designation" class="form-control">
                            @error('designation', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                            @error('phone', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                            @error('email', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select select2">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            @error('status', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="edit_address" rows="3" class="form-control"></textarea>
                            @error('address', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save me-1"></i>
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
