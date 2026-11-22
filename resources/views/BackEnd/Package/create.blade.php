<div class="modal fade" id="addPackageModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('package.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-plus me-2"></i>
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
                                value="{{ old('name') }}" placeholder="Enter Package Name...">
                            @error('name', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Price
                            </label>
                            <input type="number" step="1" min="0" name="price" class="form-control"
                                value="0" placeholder="Enter Price..." required>
                            @error('price', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Company Limit</label>
                            <input type="text" name="company_limit" class="form-control"
                                placeholder="Enter Company Limit..." required>
                            @error('company_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Branch Limit</label>
                            <input type="text" name="branch_limit" class="form-control"
                                placeholder="Enter Branch Limit..." required>
                            @error('branch_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>User Limit</label>
                            <input type="text" name="user_limit" class="form-control"
                                placeholder="Enter User Limit..." required>
                            @error('user_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Supplier Limit</label>
                            <input type="text" name="supplier_limit" class="form-control"
                                placeholder="Enter Supplier Limit..." required>
                            @error('supplier_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Customer Limit</label>
                            <input type="text" name="customer_limit" class="form-control"
                                placeholder="Enter Customer Limit..." required>
                            @error('customer_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Payment Type Limit</label>
                            <input type="text" name="payment_type_limit" class="form-control"
                                placeholder="Enter Payment Type Limit..." required>
                            @error('payment_type_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Account Limit</label>
                            <input type="text" name="account_limit" class="form-control"
                                placeholder="Enter Account Limit..." required>
                            @error('account_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Product Limit</label>
                            <input type="text" name="product_limit" class="form-control"
                                placeholder="Enter Product Limit..." required>
                            @error('product_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Sales Order Limit</label>
                            <input type="text" name="sales_order_limit" class="form-control"
                                placeholder="Enter Sales Order Limit..." required>
                            @error('sales_order_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Storage Limit (MB)</label>
                            <input type="number" name="storage_limit" class="form-control" value="0"
                                placeholder="Enter Storage Limit..." required>
                            @error('storage_limit', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select name="is_active" class="form-select select2">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('is_active', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="4" class="form-control" placeholder="Enter Remarks...">{{ old('remarks') }}</textarea>
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
                        <i class="fa fa-close me-2"></i> Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
