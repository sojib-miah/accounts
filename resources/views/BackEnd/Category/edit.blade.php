<!-- Edit Category Modal -->
@foreach ($categories as $category)
    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('category.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Category Name -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    value="{{ session('edit_category_id') == $category->id && $errors->getBag('edit')->any() ? old('edit_category_id', $category->name) : $category->name }}"
                                    class="form-control @error('name', 'edit') is-invalid @enderror"
                                    placeholder="Enter Category Name..." required>
                                @error('name', 'edit')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Status -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select select2 @error('status', 'edit') is-invalid @enderror">
                                    <option value="Active"
                                        {{ old('status', $category->status) == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $category->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status', 'edit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Close
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
@endforeach
