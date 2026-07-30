<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Inventory Report</title>
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 13px;
                color: #000;
                width: 1200px;
                margin: auto;
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

            .mb-5 {
                margin-bottom: 5px;
            }

            .mb-10 {
                margin-bottom: 10px;
            }

            .mb-20 {
                margin-bottom: 20px;
            }

            h2,
            h3,
            h4,
            p {
                margin: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th,
            table td {
                border: 1px solid #000;
                padding: 6px;
            }

            table th {
                background: #f2f2f2;
            }

            .summary td {
                font-weight: bold;
                background: #fafafa;
            }

            .footer {
                margin-top: 70px;
            }

            .footer table td {
                border: none;
                text-align: center;
                width: 33%;
            }

            .line {
                border-top: 1px solid #000;
                margin: auto;
                width: 180px;
                margin-bottom: 5px;
            }

            @media print {
                .no-print {
                    display: none;
                }

                @page {
                    size: A4 portrait;
                    margin: 12mm;
                }
            }
        </style>
    </head>

    <body>
        <div class="mb-20">
            {{-- Company Logo --}}
            {{-- <img src="{{ asset('logo.png') }}" height="70"> --}}
            <h2>{{ config('app.name') }}</h2>
            <p>Complete IT Solution Provider</p>
            <p>Dhaka, Bangladesh</p>
            <p>Phone : +8801XXXXXXXXX</p>
            <br>
            <h3>INVENTORY REPORT</h3>
            <p>
                Print Date :
                {{ now()->format('d M Y h:i A') }}
            </p>
        </div>
        @php
            $totalQty = 0;
            $purchaseValue = 0;
            $saleValue = 0;
        @endphp
        <table>
            <thead>
                <tr>
                    <th width="40">SL</th>
                    <th>Code</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th width="60">Unit</th>
                    <th width="80" class="text-end">Purchase</th>
                    <th width="80" class="text-end">Sale</th>
                    <th width="80" class="text-end">Stock</th>
                    <th width="110" class="text-end">Stock Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    @php
                        $stockValue = $product->purchase_price * $product->current_stock;
                        $saleStockValue = $product->sale_price * $product->current_stock;
                        $totalQty += $product->current_stock;
                        $purchaseValue += $stockValue;
                        $saleValue += $saleStockValue;
                    @endphp
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $product->product_code }}
                        </td>
                        <td>
                            {{ $product->name }}
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
                <tr class="summary">
                    <td colspan="7" class="text-end">
                        TOTAL
                    </td>
                    <td class="text-end">
                        {{ number_format($totalQty, 2) }}
                    </td>
                    <td class="text-end">
                        {{ number_format($purchaseValue, 2) }}
                    </td>
                </tr>
                <tr class="summary">
                    <td colspan="8" class="text-end">
                        Estimated Sale Value
                    </td>
                    <td class="text-end">
                        {{ number_format($saleValue, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="footer">
            <table>
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
        </div>
        <div class="text-center no-print" style="margin-top:30px;">
            <button onclick="window.print()" style="padding:10px 25px;cursor:pointer;">
                Print
            </button>
        </div>
    </body>

</html>
