<div class="modal fade" id="editBrandModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editBrandForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-edit me-2"></i>
                        Edit Brand
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Brand Name
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Description
                            </label>
                            <textarea name="description" id="edit_description" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Status
                            </label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="Active">
                                    Active
                                </option>
                                <option value="Inactive">
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>
                        Update Brand
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
