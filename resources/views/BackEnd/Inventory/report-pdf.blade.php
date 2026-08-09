<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Stock Report</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 8mm;
            }

            * {
                box-sizing: border-box;
            }

            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 9px;
                color: #111;
                margin: 0;
                padding: 0;
            }

            .header {
                text-align: center;
                margin-bottom: 12px;
            }

            .header h2 {
                margin: 0;
                font-size: 18px;
            }

            .header p {
                margin: 3px 0;
                font-size: 10px;
            }

            .period {
                font-size: 9px;
                margin-top: 4px;
            }

            .summary {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 12px;
            }

            .summary td {
                width: 25%;
                border: 1px solid #777;
                padding: 6px;
            }

            .summary-title {
                font-size: 8px;
            }

            .summary-value {
                font-size: 12px;
                font-weight: bold;
                margin-top: 2px;
            }

            .report {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .report th,
            .report td {
                border: 1px solid #555;
                padding: 4px 3px;
                vertical-align: middle;
            }

            .report th {
                background-color: #eeeeee;
                font-weight: bold;
                text-align: center;
            }

            .report td {
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .text-end {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .footer {
                margin-top: 10px;
                font-size: 7px;
            }
        </style>
    </head>

    <body>
        {{-- HEADER --}}
        <div class="header">
            <h2>
                STOCK REPORT
            </h2>
            <p>
                Inventory Stock Statement
            </p>
            <div class="period">
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
        {{-- TABLE --}}
        <table class="report">
            <thead>
                <tr>
                    <th style="width:4%;">
                        SL
                    </th>
                    <th style="width:10%;">
                        Product Code
                    </th>
                    <th style="width:20%;">
                        Product
                    </th>
                    <th style="width:11%;">
                        Category
                    </th>
                    <th style="width:10%;">
                        Brand
                    </th>
                    <th style="width:6%;">
                        Unit
                    </th>
                    <th style="width:8%;">
                        Received Qty
                    </th>
                    <th style="width:9%;">
                        Purchase
                    </th>
                    <th style="width:9%;">
                        Sale Price
                    </th>
                    <th style="width:8%;">
                        Current Stock
                    </th>
                    <th style="width:10%;">
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
            Generated:
            {{ now()->format('d-M-Y h:i A') }}
        </div>
    </body>

</html>
