@foreach ($products as $product)
    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('product.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_product_id">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- Product Code --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('product_code', 'edit') is-invalid @enderror"
                                    name="product_code"
                                    value="{{ session('edit_product_id') == $product->id && $errors->getBag('edit')->any() ? old('product_code', $product->product_code) : $product->product_code }}"
                                    placeholder="Product Code..." required>
                                @error('product_code', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Category --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Category <span class="text-danger">*</span></label>
                                <select name="category_id"
                                    class="form-select select2 @error('category_id', 'edit') is-invalid @enderror"
                                    required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option
                                            {{ (string) old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Brand --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Brand <span class="text-danger">*</span></label>
                                <select name="brand_id"
                                    class="form-select select2 @error('brand_id', 'edit') is-invalid @enderror"
                                    required>
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option
                                            {{ (string) old('brand_id', $product->brand_id) === (string) $brand->id ? 'selected' : '' }}
                                            value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Product Name --}}
                            <div class="col-md-6 mb-3">
                                <label>Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name', 'edit') is-invalid @enderror"
                                    name="name"
                                    value="{{ session('edit_product_id') == $product->id && $errors->getBag('edit')->any() ? old('name', $product->name) : $product->name }}"
                                    placeholder="Product Name..." required>
                                @error('name', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Model No --}}
                            <div class="col-md-6 mb-3">
                                <label>Model Number <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('model_no', 'edit') is-invalid @enderror" name="model_no"
                                    value="{{ session('edit_product_id') == $product->id && $errors->getBag('edit')->any() ? old('model_no', $product->model_no) : $product->model_no }}"
                                    placeholder="Model No..." required>
                                @error('model_no', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Barcode --}}
                            <div class="col-md-6 mb-3">
                                <label>Barcode</label>
                                <input type="text"
                                    class="form-control @error('barcode', 'edit') is-invalid @enderror" name="barcode"
                                    value="{{ session('edit_product_id') == $product->id && $errors->getBag('edit')->any() ? old('barcode', $product->barcode) : $product->barcode }}"
                                    placeholder="Enter Barcode...">
                                @error('barcode', 'edit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- SKU --}}
                            <div class="col-md-6 mb-3">
                                <label>SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku', 'edit') is-invalid @enderror"
                                    name="sku"
                                    value="{{ session('edit_product_id') == $product->id && $errors->getBag('edit')->any() ? old('sku', $product->sku) : $product->sku }}"
                                    placeholder="Enter SKU..." required>
                                @error('sku', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Unit --}}
                            <div class="col-md-6 mb-3">
                                <label>Unit <span class="text-danger">*</span></label>
                                <select name="unit"
                                    class="form-select select2 @error('unit', 'edit') is-invalid @enderror" required>
                                    <option value="PCS"
                                        {{ old('unit', $product->unit) == 'PCS' ? 'selected' : '' }}>PCS</option>
                                    <option value="KG" {{ old('unit', $product->unit) == 'KG' ? 'selected' : '' }}>
                                        KG</option>
                                    <option value="LTR"
                                        {{ old('unit', $product->unit) == 'LTR' ? 'selected' : '' }}>LTR</option>
                                    <option value="BOX"
                                        {{ old('unit', $product->unit) == 'BOX' ? 'selected' : '' }}>BOX</option>
                                    <option value="PACK"
                                        {{ old('unit', $product->unit) == 'PACK' ? 'selected' : '' }}>PACK</option>
                                </select>
                                @error('unit', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Minimum Stock --}}
                            <div class="col-md-6 mb-3">
                                <label>Minimum Stock <span class="text-danger">*</span></label>
                                <input type="number" step="1"
                                    class="form-control @error('minimum_stock', 'edit') is-invalid @enderror"
                                    name="minimum_stock"
                                    value="{{ session('edit_product_id') == $product->id && $errors->getBag('edit')->any() ? old('minimum_stock', $product->minimum_stock) : $product->minimum_stock }}">
                                @error('minimum_stock', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select select2 @error('status', 'edit') is-invalid @enderror" required>
                                    <option value="Active"
                                        {{ old('status', $product->status) == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $product->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Description --}}
                            <div class="col-md-12 mb-3">
                                <label>Description</label>
                                <textarea rows="4" class="form-control @error('description', 'edit') is-invalid @enderror" name="description"
                                    placeholder="Enter Product Descriptions...">{{ session('edit_product_id') == $product->id && $errors->getBag('edit')->any() ? old('description', $product->description) : $product->description }}</textarea>
                                @error('description', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i>
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
