<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Category Name -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Status
                            </label>
                            <select name="status" id="edit_status" class="form-select select2">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            @error('status')
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
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
