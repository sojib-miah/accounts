<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Stock Report</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            * {
                box-sizing: border-box;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                color: #000;
                width: 1000px;
                margin: 20px auto;
                padding: 0;
            }

            .header {
                text-align: center;
                margin-bottom: 15px;
            }

            .header h2 {
                margin: 0 0 5px;
                font-size: 20px;
            }

            .header p {
                margin: 2px 0;
            }

            .date {
                text-align: center;
                margin-bottom: 12px;
            }

            .summary {
                width: 100%;
                margin-bottom: 15px;
            }

            .summary td {
                width: 25%;
                border: 1px solid #999;
                padding: 8px;
            }

            .summary-title {
                font-size: 10px;
                color: #555;
            }

            .summary-value {
                font-size: 15px;
                font-weight: bold;
                margin-top: 3px;
            }

            table.report {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            table.report th,
            table.report td {
                border: 1px solid #555;
                padding: 5px 4px;
                vertical-align: middle;
            }

            table.report th {
                background: #eeeeee;
                text-align: center;
                font-weight: bold;
            }

            .text-end {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .footer {
                margin-top: 15px;
                font-size: 9px;
            }

            @media print {
                .no-print {
                    display: none !important;
                }
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h2>
                STOCK REPORT
            </h2>
            <p>
                Inventory Stock Statement
            </p>
            <div class="date">
                @if (request('from_date') || request('to_date'))
                    Period:
                    {{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d-M-Y') : 'Beginning' }}
                    -
                    {{ request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('d-M-Y') : 'Present' }}
                @else
                    All Received Stock
                @endif
            </div>
        </div>
        {{-- SUMMARY --}}
        <table class="summary">
            <tr>
                <td>
                    <div class="summary-title">
                        Total Products
                    </div>
                    <div class="summary-value">
                        {{ number_format($totalProducts) }}
                    </div>
                </td>
                <td>
                    <div class="summary-title">
                        Received Qty
                    </div>
                    <div class="summary-value">
                        {{ number_format($totalReceivedQty, 2) }}
                    </div>
                </td>
                <td>
                    <div class="summary-title">
                        Current Stock
                    </div>
                    <div class="summary-value">
                        {{ number_format($totalCurrentStock, 2) }}
                    </div>
                </td>
                <td>
                    <div class="summary-title">
                        Stock Value
                    </div>
                    <div class="summary-value">
                        {{ number_format($totalStockValue, 2) }}
                    </div>
                </td>
            </tr>
        </table>
        {{-- REPORT TABLE --}}
        <table class="report">
            <thead>
                <tr>
                    <th width="4%">
                        SL
                    </th>
                    <th width="10%">
                        Product Code
                    </th>
                    <th width="20%">
                        Product
                    </th>
                    <th width="11%">
                        Category
                    </th>
                    <th width="10%">
                        Brand
                    </th>
                    <th width="6%">
                        Unit
                    </th>
                    <th width="8%">
                        Received Qty
                    </th>
                    <th width="9%">
                        Purchase
                    </th>
                    <th width="9%">
                        Sale Price
                    </th>
                    <th width="8%">
                        Current Stock
                    </th>
                    <th width="10%">
                        Stock Value
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php
                        $receivedQty = $product->receiptItems
                            ->filter(function ($item) {
                                return $item->receipt &&
                                    $item->receipt->type === 'Purchase-Order' &&
                                    $item->receipt->is_receive == true;
                            })
                            ->sum('qty');
                        $stockValue = $product->current_stock * $product->purchase_price;
                    @endphp
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $product->product_code ?? ($product->sku ?? '-') }}
                        </td>
                        <td>
                            {{ $product->name }}
                        </td>
                        <td>
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td>
                            {{ $product->brand->name ?? '-' }}
                        </td>
                        <td class="text-center">
                            {{ $product->unit ?? '-' }}
                        </td>
                        <td class="text-end">
                            {{ number_format($receivedQty, 2) }}
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
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" class="text-end">
                        Total
                    </th>
                    <th class="text-end">
                        {{ number_format(
                            $products->sum(function ($product) {
                                return $product->receiptItems->filter(function ($item) {
                                        return $item->receipt &&
                                            $item->receipt->type === 'Purchase-Order' &&
                                            $item->receipt->is_receive == true;
                                    })->sum('qty');
                            }),
                            2,
                        ) }}
                    </th>
                    <th colspan="2"></th>
                    <th class="text-end">
                        {{ number_format($products->sum('current_stock'), 2) }}
                    </th>
                    <th class="text-end">
                        {{ number_format(
                            $products->sum(function ($product) {
                                return $product->current_stock * $product->purchase_price;
                            }),
                            2,
                        ) }}
                    </th>
                </tr>
            </tfoot>
        </table>
        <div class="footer">
            Printed:
            {{ now()->format('d-M-Y h:i A') }}
        </div>
        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    </body>

</html>
