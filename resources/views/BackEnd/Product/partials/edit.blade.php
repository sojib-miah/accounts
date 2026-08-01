<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_product_id">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Product Code --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Product Code
                            </label>
                            <input type="text" class="form-control  @error('product_code') is-invalid @enderror"
                                id="edit_product_code" name="product_code">
                            @error('product_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Category --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Category
                            </label>
                            <select id="edit_category_id" name="category_id"
                                class="form-select select2  @error('category_id') is-invalid @enderror" required>
                                <option value="">
                                    Select Category
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Brand --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Brand
                            </label>
                            <select id="edit_brand_id" name="brand_id"
                                class="form-select select2  @error('brand_id') is-invalid @enderror" required>
                                <option value="">
                                    Select Brand
                                </option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Product Name --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                Product Name
                            </label>
                            <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                id="edit_name" name="name" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Model No --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                Model Number
                            </label>
                            <input type="text" class="form-control  @error('model_no') is-invalid @enderror"
                                id="edit_model_no" name="model_no">
                            @error('model_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Barcode --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                Barcode
                            </label>
                            <input type="text" class="form-control  @error('barcode') is-invalid @enderror"
                                id="edit_barcode" name="barcode">
                            @error('barcode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- SKU --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                SKU
                            </label>
                            <input type="text" class="form-control  @error('sku') is-invalid @enderror"
                                id="edit_sku" name="sku">
                            @error('sku')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Unit --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                Unit
                            </label>
                            <select id="edit_unit" name="unit"
                                class="form-select select2  @error('unit') is-invalid @enderror">
                                <option value="PCS">PCS</option>
                                <option value="KG">KG</option>
                                <option value="LTR">LTR</option>
                                <option value="BOX">BOX</option>
                                <option value="PACK">PACK</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Minimum Stock --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                Minimum Stock
                            </label>
                            <input type="number" step="0.01"
                                class="form-control  @error('minimum_stock') is-invalid @enderror"
                                id="edit_minimum_stock" name="minimum_stock">
                            @error('minimum_stock')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                Status
                            </label>
                            <select id="edit_status" name="status"
                                class="form-select  @error('status') is-invalid @enderror">
                                <option value="Active">
                                    Active
                                </option>
                                <option value="Inactive">
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Description --}}
                        <div class="col-md-12 mb-3">
                            <label>
                                Description
                            </label>
                            <textarea rows="4" class="form-control  @error('description') is-invalid @enderror" id="edit_description"
                                name="description"></textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-2"></i>
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
