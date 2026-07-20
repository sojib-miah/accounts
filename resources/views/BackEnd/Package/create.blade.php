<div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('package.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="fa fa-package"></i>
                        Add Package
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Package Name
                            </label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ old('name') }}">
                            @error('name', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Price
                            </label>
                            <input type="number" step="0.01" min="0" name="price" class="form-control"
                                value="0">
                            @error('price', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>User Limit</label>
                            <input type="number" name="user_limit" class="form-control" value="0">
                            @error('user_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Company Limit</label>
                            <input type="number" name="company_limit" class="form-control" value="0">
                            @error('company_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Branch Limit</label>
                            <input type="number" name="branch_limit" class="form-control" value="0">
                            @error('branch_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Income Limit</label>
                            <input type="number" name="income_limit" class="form-control" value="0">
                            @error('income_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Expense Limit</label>
                            <input type="number" name="expense_limit" class="form-control" value="0">
                            @error('expense_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Challan Limit</label>
                            <input type="number" name="challan_limit" class="form-control" value="0">
                            @error('challan_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Party Limit</label>
                            <input type="number" name="party_limit" class="form-control" value="0">
                            @error('party_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Account Limit</label>
                            <input type="number" name="account_limit" class="form-control" value="0">
                            @error('account_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Storage Limit (MB)</label>
                            <input type="number" name="storage_limit" class="form-control" value="0">
                            @error('storage_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">
                                    Active
                                </option>
                                <option value="0">
                                    Inactive
                                </option>
                            </select>
                            @error('status', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="4" class="form-control">{{ old('remarks') }}</textarea>
                            @error('remarks', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>
                        Save Package
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
