<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr>
                <th width="10%">Product</th>
                <th width="15%">Desctiption</th>
                <th width="10%">Serial No</th>
                <th width="10%">Unit</th>
                <th width="10%">Stock</th>
                <th width="10%">Qty</th>
                <th width="12%">Unit Price</th>
                <th width="13%">Total Price</th>
                <th width="10%" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="purchaseBody">
            <tr>
                <td>
                    <select name="product_id[]" class="form-select product select2" required>
                        <option value="">
                            Select Product
                        </option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-unit="{{ $product->unit }}"
                                data-stock="{{ $product->current_stock }}" data-rate="{{ $product->purchase_price }}"
                                data-description="{{ $product->description }}">
                                {{ $product->sku }}
                                - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="description[]" class="form-control description" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-info serialBtn">
                        <i class="fa fa-barcode"></i>
                    </button>
                    <input type="hidden" name="serial_json[]" class="serial_json" value="[]">
                </td>
                <td>
                    <input type="text" name="unit[]" class="form-control unit" readonly>
                </td>
                <td>
                    <input type="text" class="form-control stock" readonly>
                </td>
                <td>
                    <input type="number" min="1" value="0" name="qty[]" class="form-control qty">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" name="rate[]" class="form-control rate"
                        required>
                </td>
                <td>
                    <input type="text" name="amount[]" class="form-control amount" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger removeRow">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
{{-- Row Template --}}
<script type="text/template" id="purchaseRowTemplate">
<tr>
<td>
<select name="product_id[]" class="form-select product select2" required>
    <option value="">Select Product</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}" data-unit="{{ $product->unit }}" data-stock="{{ $product->current_stock }}" data-rate="{{ $product->purchase_price }}">
           {{ $product->sku }} - {{ $product->name }}
        </option>
    @endforeach
</select>
</td>
<td>
   <button type="button" class="btn btn-info serialBtn">
        <i class="fa fa-barcode"></i>
    </button>
    <input type="hidden" name="serial_json[]" class="serial_json" value="[]">
</td>
<td>
    <input type="text" name="unit[]" class="form-control unit" readonly>
</td>
<td>
    <input type="text" class="form-control stock" readonly>
</td>
<td>
    <input type="number" min="1" value="0" name="qty[]" class="form-control qty">
</td>
<td>
    <input type="number" step="0.01" min="0" name="rate[]" class="form-control rate" required>
</td>
<td>
    <input type="text" name="amount[]" class="form-control amount" readonly>
</td>
<td class="text-center">
    <button type="button" class="btn btn-danger removeRow">
        <i class="fa fa-trash"></i>
    </button>
</td>
</tr>
</script>
