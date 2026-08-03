<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr>
                <th width="35%">Product</th>
                <th width="10%">Unit</th>
                <th width="10%">Stock</th>
                <th width="10%">Qty</th>
                <th width="12%">Rate</th>
                <th width="13%">Amount</th>
                <th width="10%" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="purchaseBody">
            @foreach ($purchase->items as $item)
                <tr>
                    <td>
                        <select name="product_id[]" class="form-select product" required>
                            <option value="">
                                Select Product
                            </option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-unit="{{ $product->unit }}"
                                    data-stock="{{ $product->current_stock }}"
                                    data-rate="{{ $product->purchase_price }}"
                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->sku }}
                                    - {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="unit[]" class="form-control unit"
                            value="{{ $item->product->unit }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control stock" value="{{ $item->product->current_stock }}"
                            readonly>
                    </td>
                    <td>
                        <input type="number" min="1" name="qty[]" class="form-control qty"
                            value="{{ $item->qty }}" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="rate[]" class="form-control rate"
                            value="{{ $item->rate }}" required>
                    </td>
                    <td>
                        <input type="text" name="amount[]" class="form-control amount"
                            value="{{ number_format($item->amount, 2, '.', '') }}" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger removeRow">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- Row Template --}}
<script type="text/template" id="purchaseRowTemplate">
<tr>
    <td>
        <select
            name="product_id[]"
            class="form-select product"
            required>
            <option value="">
                Select Product
            </option>
            @foreach($products as $product)
                <option
                    value="{{ $product->id }}"
                    data-unit="{{ $product->unit }}"
                    data-stock="{{ $product->current_stock }}"
                    data-rate="{{ $product->purchase_price }}">
                    {{ $product->sku }} - {{ $product->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="unit[]" class="form-control unit" readonly>
    </td>
    <td>
        <input type="text" class="form-control stock" readonly>
    </td>
    <td>
        <input type="number" min="1" value="1" name="qty[]" class="form-control qty" required>
    </td>
    <td>
        <input type="number" step="0.01" min="0" name="rate[]" class="form-control rate" required>
    </td>
    <td>
        <input type="text" name="amount[]" class="form-control amount" value="0.00" readonly>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-danger removeRow">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
</script>
