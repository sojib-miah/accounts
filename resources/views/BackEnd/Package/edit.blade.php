<div class="modal fade" id="editPackageModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="fa fa-edit"></i>
                        Edit Package
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Package Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                            @error('name', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Price</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="price"
                                id="edit_price">
                            @error('price', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>User Limit</label>
                            <input type="number" class="form-control" name="user_limit" id="edit_user_limit">
                            @error('user_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Company Limit</label>
                            <input type="number" class="form-control" name="company_limit" id="edit_company_limit">
                            @error('company_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Branch Limit</label>
                            <input type="number" class="form-control" name="branch_limit" id="edit_branch_limit">
                            @error('branch_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Income Limit</label>
                            <input type="number" class="form-control" name="income_limit" id="edit_income_limit">
                            @error('income_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Expense Limit</label>
                            <input type="number" class="form-control" name="expense_limit" id="edit_expense_limit">
                            @error('expense_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Challan Limit</label>
                            <input type="number" class="form-control" name="challan_limit" id="edit_challan_limit">
                            @error('challan_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Party Limit</label>
                            <input type="number" class="form-control" name="party_limit" id="edit_party_limit">
                            @error('party_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Account Limit</label>
                            <input type="number" class="form-control" name="account_limit" id="edit_account_limit">
                            @error('account_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Storage Limit (MB)</label>
                            <input type="number" class="form-control" name="storage_limit" id="edit_storage_limit">
                            @error('storage_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select class="form-select" name="is_active" id="edit_status">
                                <option value="1">
                                    Active
                                </option>
                                <option value="0">
                                    Inactive
                                </option>
                            </select>
                            @error('is_active', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea class="form-control" rows="4" name="remarks" id="edit_remarks"></textarea>
                            @error('remarks', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning">
                        <i class="fa fa-save me-2"></i>
                        Update Package
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
