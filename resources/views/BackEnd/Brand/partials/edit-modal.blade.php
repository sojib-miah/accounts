@foreach ($brands as $brand)
    <div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('brand.update', $brand->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Edit Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    value="{{ session('edit_brand_id') == $brand->id && $errors->getBag('edit')->any() ? old('name', $brand->name) : $brand->name }}"
                                    class="form-control @error('name', 'edit') is-invalid @enderror"
                                    placeholder="Enter Brand Name..." required>
                                @error('name', 'edit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="4" class="form-control @error('description', 'edit') is-invalid @enderror"
                                    placeholder="Enter Brand Description...">{{ session('edit_brand_id') == $brand->id && $errors->getBag('edit')->any() ? old('description', $brand->description) : $brand->description }}</textarea>
                                @error('description', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select select2  @error('status', 'edit') is-invalid @enderror">
                                    <option value="Active"
                                        {{ old('status', $brand->status) == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $brand->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            Update Brand
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
