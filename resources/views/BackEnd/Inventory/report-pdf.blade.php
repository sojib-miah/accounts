<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Inventory Report</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 10px;
                color: #222;
            }

            @page {
                size: A4;
                margin: 8mm;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td,
            th {

                border: 1px solid #555;

                padding: 4px;

                white-space: normal;

                word-break: break-all;

            }

            th {
                background: #f1f1f1;
                font-size: 10px;
                font-weight: bold;
            }

            td {
                font-size: 8px;
            }

            .text-center {
                text-align: center;
            }

            .text-end {
                text-align: right;
            }

            .text-start {
                text-align: left;
            }

            .border-none {
                border: none !important;
            }

            .company-name {
                font-size: 22px;
                font-weight: bold;
                color: #0d47a1;
            }

            .title {
                font-size: 18px;
                font-weight: bold;
                margin-top: 10px;
            }

            .subtitle {
                font-size: 11px;
            }

            .report-info {
                margin-top: 15px;
                margin-bottom: 15px;
            }

            .summary {
                margin-top: 20px;
            }

            .summary td {
                font-weight: bold;
            }

            .signature {
                margin-top: 80px;
            }

            .signature td {
                border: none;
                text-align: center;
            }

            .line {
                border-top: 1px solid #000;
                width: 180px;
                margin: auto;
                margin-bottom: 5px;
            }

            .sl {
                width: 5%;
            }

            .code {
                width: 12%;
            }

            .category {
                width: 15%;
            }
        </style>
    </head>

    <body>
        @php
            $totalQty = 0;
            $totalPurchaseValue = 0;
            $totalSaleValue = 0;
        @endphp
        <table class="border-none">
            <tr>
                <td class="border-none" width="90">
                    {{-- Company Logo --}}

                    {{--
            <img src="{{ public_path('logo.png') }}"
                 width="70">
            --}}
                </td>
                <td class="border-none">
                    <div class="company-name">
                        {{ config('app.name') }}
                    </div>
                    <div class="subtitle">
                        Complete IT Solution Provider
                    </div>
                    <div class="subtitle">
                        House # xx, Road # xx,
                        DOHS Mohakhali, Dhaka
                    </div>
                    <div class="subtitle">
                        Phone :
                        +8801XXXXXXXXX
                    </div>
                    <div class="subtitle">
                        Email :
                        info@company.com
                    </div>
                </td>
            </tr>
        </table>
        <hr style="margin:10px 0;">
        <div class="text-center">
            <div class="title">
                INVENTORY REPORT
            </div>
        </div>
        <table class="report-info border-none">
            <tr>
                <td class="border-none">
                    <strong>Print Date :</strong>
                    {{ now()->format('d M Y') }}
                </td>
                <td class="border-none text-end">
                    <strong>Print Time :</strong>
                    {{ now()->format('h:i A') }}
                </td>
            </tr>
        </table>
        <table>
            <thead>
                <tr>
                    <th class="sl">SL</th>
                    <th class="code">Code</th>
                    <th>Product</th>
                    <th class="category">Category</th>
                    <th width="40">Unit</th>
                    <th width="60">Purchase</th>
                    <th width="60">Sale</th>
                    <th width="60">Stock</th>
                    <th width="80">Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    @php
                        $stockValue = $product->purchase_price * $product->current_stock;
                        $saleValue = $product->sale_price * $product->current_stock;
                        $totalQty += $product->current_stock;
                        $totalPurchaseValue += $stockValue;
                        $totalSaleValue += $saleValue;
                    @endphp
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $product->product_code }}
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if ($product->barcode)
                                <br>
                                <small>
                                    Barcode :
                                    {{ $product->barcode }}
                                </small>
                            @endif
                        </td>
                        <td>
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="text-center">
                            {{ $product->unit }}
                        </td>
                        <td class="text-end">
                            {{ number_format($product->purchase_price, 2) }}
                        </td>
                        <td class="text-end">
                            {{ number_format($product->sale_price, 2) }}
                        </td>
                        <td class="text-end">
                            {{ number_format($product->current_stock, 2) }}
                        </td>
                        <td class="text-end">
                            {{ number_format($stockValue, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            No Inventory Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-end">
                        Total Stock
                    </th>
                    <th class="text-end">
                        {{ number_format($totalQty, 2) }}
                    </th>
                    <th class="text-end">
                        {{ number_format($totalPurchaseValue, 2) }}
                    </th>
                </tr>
                <tr>
                    <th colspan="8" class="text-end">
                        Estimated Sale Value
                    </th>
                    <th class="text-end">
                        {{ number_format($totalSaleValue, 2) }}
                    </th>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <table class="summary">
            <tr>
                <td width="70%">
                    Total Products
                </td>
                <td class="text-end">
                    {{ $products->count() }}
                </td>
            </tr>
            <tr>
                <td>
                    Total Quantity
                </td>
                <td class="text-end">
                    {{ number_format($totalQty, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    Purchase Stock Value
                </td>
                <td class="text-end">
                    {{ number_format($totalPurchaseValue, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    Estimated Sale Value
                </td>
                <td class="text-end">
                    {{ number_format($totalSaleValue, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    Estimated Profit
                </td>
                <td class="text-end">
                    {{ number_format($totalSaleValue - $totalPurchaseValue, 2) }}
                </td>
            </tr>
        </table>
        <table class="signature">
            <tr>
                <td>
                    <div class="line"></div>
                    Prepared By
                </td>
                <td>
                    <div class="line"></div>
                    Checked By
                </td>
                <td>
                    <div class="line"></div>
                    Approved By
                </td>
            </tr>
        </table>
        <br><br>
        <div class="text-center" style="font-size:10px;color:#777;">
            This is a computer generated Inventory Report.
        </div>
    </body>

</html>
