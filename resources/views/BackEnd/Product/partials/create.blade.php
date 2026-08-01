<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('product.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add Product
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
                                name="product_code" required value="{{ old('product_code') }}">
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
                            <select name="category_id"
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
                            <select name="brand_id" class="form-select select2  @error('brand_id') is-invalid @enderror"
                                required>
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
                                name="name" required value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Model --}}
                        <div class="col-md-6 mb-3">
                            <label>
                                Model Number
                            </label>
                            <input type="text" class="form-control  @error('model_no') is-invalid @enderror"
                                name="model_no" value="{{ old('model_no') }}" required>
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
                                name="barcode" value="{{ old('barcode') }}">
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
                                name="sku" value="{{ old('sku') }}">
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
                            <select name="unit" class="form-select select2  @error('unit') is-invalid @enderror">
                                <option value="PCS">
                                    PCS
                                </option>
                                <option value="KG">
                                    KG
                                </option>
                                <option value="LTR">
                                    LTR
                                </option>
                                <option value="BOX">
                                    BOX
                                </option>
                                <option value="PACK">
                                    PACK
                                </option>
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
                                class="form-control  @error('minimum_stock') is-invalid @enderror" name="minimum_stock"
                                value="0">
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
                            <select name="status" class="form-select  @error('status') is-invalid @enderror">
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
                            <textarea rows="4" class="form-control  @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
