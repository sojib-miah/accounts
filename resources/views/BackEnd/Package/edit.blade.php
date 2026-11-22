<div class="modal fade" id="editPackageModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="fa fa-edit me-2"></i>
                        Edit Package
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Package Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required
                                placeholder="Enter Package Name...">
                            @error('name', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Price</label>
                            <input type="number" step="1" min="0" class="form-control" name="price"
                                id="edit_price" placeholder="Enter Price..." required>
                            @error('price', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Company Limit</label>
                            <input type="text" class="form-control" name="company_limit" id="edit_company_limit"
                                required placeholder="Enter Company Limit...">
                            @error('company_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Branch Limit</label>
                            <input type="text" class="form-control" name="branch_limit" id="edit_branch_limit"
                                required placeholder="Enter Branch Limit...">
                            @error('branch_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>User Limit</label>
                            <input type="text" class="form-control" name="user_limit" id="edit_user_limit" required
                                placeholder="Enter User Limit...">
                            @error('user_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Supplier Limit</label>
                            <input type="text" class="form-control" name="supplier_limit" id="edit_supplier_limit"
                                required placeholder="Enter Supplier Limit...">
                            @error('supplier_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Customer Limit</label>
                            <input type="text" class="form-control" name="customer_limit" id="edit_customer_limit"
                                required placeholder="Enter Customer Limit...">
                            @error('customer_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Payment Type Limit</label>
                            <input type="text" name="payment_type_limit" class="form-control"
                                id="edit_payment_type_limit" required placeholder="Enter Payment Type Limit...">
                            @error('payment_type_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Account Limit</label>
                            <input type="text" class="form-control" name="account_limit" id="edit_account_limit"
                                required placeholder="Enter Account Limit...">
                            @error('account_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Product Limit</label>
                            <input type="text" name="product_limit" class="form-control" id="edit_product_limit"
                                required placeholder="Enter Product Limit...">
                            @error('product_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Sales Order Limit</label>
                            <input type="text" name="sales_order_limit" class="form-control"
                                id="edit_sales_order_limit" required placeholder="Enter Sales Order Limit...">
                            @error('sales_order_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Storage Limit (MB)</label>
                            <input type="number" class="form-control" name="storage_limit" id="edit_storage_limit"
                                value="0" required placeholder="Enter Storage Limit...">
                            @error('storage_limit', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select class="form-select" name="is_active" id="edit_status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('is_active', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea class="form-control" rows="4" name="remarks" id="edit_remarks" placeholder="Enter Remarks..."></textarea>
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
                        <i class="fa fa-close me-2"></i> Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
