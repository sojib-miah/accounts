<div class="modal fade" id="addBrandModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('brand.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-plus-circle me-2"></i>
                        Add Brand
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
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Enter Brand Name" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Description
                            </label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Brand Description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>
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
                        <i class="fa fa-times me-2"></i>
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save me-2"></i>
                        Save Brand
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
