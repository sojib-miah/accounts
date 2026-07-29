<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Product Code
        </label>
        <input type="text" class="form-control" readonly value="{{ $product->product_code ?? 'Auto Generate' }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Category
        </label>
        <select name="category_id" class="form-select select2 @error('category_id') is-invalid @enderror">
            <option value="">
                Select Category
            </option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
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
    <div class="col-md-6 mb-3">
        <label>
            Product Name
        </label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $product->name ?? '') }}">
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>
            Barcode
        </label>
        <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode ?? '') }}">
        @error('barcode')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>
            SKU
        </label>
        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}">
        @error('sku')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>
            Unit
        </label>
        <select name="unit" class="form-select">
            <option value="PCS" {{ old('unit', $product->unit ?? 'PCS') == 'PCS' ? 'selected' : '' }}>
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
    <div class="col-md-6 mb-3">
        <label>
            Purchase Price
        </label>
        <input type="number" step="0.01" name="purchase_price" class="form-control"
            value="{{ old('purchase_price', $product->purchase_price ?? 0) }}">
        @error('purchase_price')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>
            Sale Price
        </label>
        <input type="number" step="0.01" name="sale_price" class="form-control"
            value="{{ old('sale_price', $product->sale_price ?? 0) }}">
        @error('sale_price')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>
            Minimum Stock
        </label>
        <input type="number" step="0.01" name="minimum_stock" class="form-control"
            value="{{ old('minimum_stock', $product->minimum_stock ?? 0) }}">
        @error('minimum_stock')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>
            Status
        </label>
        <select name="status" class="form-select">
            <option value="Active" {{ old('status', $product->status ?? 'Active') == 'Active' ? 'selected' : '' }}>
                Active
            </option>
            <option value="Inactive" {{ old('status', $product->status ?? '') == 'Inactive' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-12">
        <label>
            Description
        </label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
