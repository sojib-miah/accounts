<!-- Edit Income Modal -->
<div class="modal fade" id="editAccountHeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editAccountHeadForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Income
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Category -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="edit_category_id" class="form-select select2" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Income Name -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Income Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save me-1"></i>
                        Update Income
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
