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
                        <div class="col-md-6 mb-3">
                            <label>Company Name</label>
                            <input type="text" id="edit_company_name" name="company_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" id="edit_name" name="name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Designation</label>
                            <input type="text" id="edit_designation" name="designation" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" id="edit_phone" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control">
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
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>
                                Address
                            </label>
                            <textarea id="edit_address" name="address" class="form-control"></textarea>
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
