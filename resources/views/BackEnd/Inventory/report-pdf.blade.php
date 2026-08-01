<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Inventory Report</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 12mm;
            }

            body {
                font-family: DejaVu Sans;
                font-size: 10px;
                color: #000;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 5px;
            }

            th {
                background: #efefef;
            }

            .text-center {
                text-align: center;
            }

            .text-end {
                text-align: right;
            }

            h2,
            h3,
            p {
                margin: 2px;
            }
        </style>
    </head>

    <body>
        <div class="text-center">
            <h2>{{ config('app.name') }}</h2>
            <p>Inventory Report</p>
            <p>
                Print Date :
                {{ now()->format('d M Y h:i A') }}
            </p>
        </div>
        <br>
        @php
            $totalQty = 0;
            $totalValue = 0;
        @endphp
        <table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>PO No</th>
                    <th>Receive Date</th>
                    <th>Supplier</th>
                    <th>Code</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Purchase</th>
                    <th>Sale</th>
                    <th>Stock</th>
                    <th>Stock Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $receipt)
                    @foreach ($receipt->items as $item)
                        @php
                            $stockValue = $item->product->current_stock * $item->product->purchase_price;
                            $totalQty += $item->product->current_stock;
                            $totalValue += $stockValue;
                        @endphp
                        <tr>
                            <td>
                                {{ $loop->parent->iteration }}
                            </td>
                            <td>
                                {{ $receipt->po_no }}
                            </td>
                            <td>
                                {{ date('d-M-Y', strtotime($receipt->received_date)) }}
                            </td>
                            <td>
                                {{ $receipt->supplier->name }}
                            </td>
                            <td>
                                {{ $item->product->product_code }}
                            </td>
                            <td>
                                {{ $item->product->name }}
                            </td>
                            <td>
                                {{ $item->product->category->name ?? '-' }}
                            </td>
                            <td>
                                {{ $item->product->unit }}
                            </td>
                            <td class="text-end">
                                {{ number_format($item->product->purchase_price, 2) }}
                            </td>
                            <td class="text-end">
                                {{ number_format($item->product->sale_price, 2) }}
                            </td>
                            <td class="text-end">
                                {{ number_format($item->product->current_stock, 2) }}
                            </td>
                            <td class="text-end">
                                {{ number_format($stockValue, 2) }}
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="12" class="text-center">
                            No Inventory Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="10" class="text-end">
                        Total
                    </th>
                    <th class="text-end">
                        {{ number_format($totalQty, 2) }}
                    </th>
                    <th class="text-end">
                        {{ number_format($totalValue, 2) }}
                    </th>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <table style="border:none">
            <tr style="border:none">
                <td style="border:none;text-align:center">
                    ____________________
                    <br>
                    Prepared By
                </td>
                <td style="border:none;text-align:center">
                    ____________________
                    <br>
                    Checked By
                </td>
                <td style="border:none;text-align:center">
                    ____________________
                    <br>
                    Approved By
                </td>
            </tr>
        </table>
    </body>

</html>
