<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('product.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus me-2"></i> Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Product Code --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Code <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('product_code', 'add') is-invalid @enderror"
                                name="product_code" required value="{{ old('product_code') }}"
                                placeholder="Product Code...">
                            @error('product_code', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Category --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Category <span class="text-danger">*</span></label>
                            <select name="category_id"
                                class="form-select select2 @error('category_id', 'add') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id', 'add')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Brand --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Brand <span class="text-danger">*</span></label>
                            <select name="brand_id"
                                class="form-select select2 @error('brand_id', 'add') is-invalid @enderror" required>
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id', 'add')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Product Name --}}
                        <div class="col-md-6 mb-3">
                            <label>Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name', 'add') is-invalid @enderror"
                                name="name" required value="{{ old('name') }}" placeholder="Product Name...">
                            @error('name', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Model --}}
                        <div class="col-md-6 mb-3">
                            <label>Model Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('model_no', 'add') is-invalid @enderror"
                                name="model_no" value="{{ old('model_no') }}" required placeholder="Model No...">
                            @error('model_no', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Barcode --}}
                        <div class="col-md-6 mb-3">
                            <label>Barcode</label>
                            <input type="text" class="form-control @error('barcode', 'add') is-invalid @enderror"
                                name="barcode" value="{{ old('barcode') }}" placeholder="Barcode...">
                            @error('barcode', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- SKU --}}
                        <div class="col-md-6 mb-3">
                            <label>SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku', 'add') is-invalid @enderror"
                                name="sku" value="{{ old('sku') }}" placeholder="SKU..." required>
                            @error('sku', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Unit --}}
                        <div class="col-md-6 mb-3">
                            <label>Unit <span class="text-danger">*</span></label>
                            <select name="unit"
                                class="form-select select2 @error('unit', 'add') is-invalid @enderror" required>
                                <option value="PCS" {{ old('unit', 'PCS') == 'PCS' ? 'selected' : '' }}>PCS</option>
                                <option value="KG" {{ old('unit', 'PCS') == 'KG' ? 'selected' : '' }}>KG</option>
                                <option value="LTR" {{ old('unit', 'PCS') == 'LTR' ? 'selected' : '' }}>LTR</option>
                                <option value="BOX" {{ old('unit', 'PCS') == 'BOX' ? 'selected' : '' }}>BOX</option>
                                <option value="PACK" {{ old('unit', 'PCS') == 'PACK' ? 'selected' : '' }}>PACK
                                </option>
                            </select>
                            @error('unit', 'add')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Minimum Stock --}}
                        <div class="col-md-6 mb-3">
                            <label>Minimum Stock <span class="text-danger">*</span></label>
                            <input type="number" step="1"
                                class="form-control @error('minimum_stock', 'add') is-invalid @enderror"
                                name="minimum_stock" value="0" required>
                            @error('minimum_stock', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status"
                                class="form-select select2 @error('status', 'add') is-invalid @enderror">
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="Inactive" {{ old('status', 'Active') == 'Inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                            @error('status', 'add')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Description --}}
                        <div class="col-md-12 mb-3">
                            <label>Description</label>
                            <textarea rows="4" class="form-control @error('description', 'add') is-invalid @enderror" name="description"
                                placeholder="Enter Product Descriptions...">{{ old('description') }}</textarea>
                            @error('description', 'add')
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
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
