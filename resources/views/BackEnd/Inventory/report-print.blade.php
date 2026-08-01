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
        @endphp
        <table>
            <thead>
                <tr>
                    <th width="40">SL</th>
                    <th>PO No</th>
                    <th>Receive Date</th>
                    <th>Supplier</th>
                    <th>Code</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th width="60">Unit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $receipt)

                    @foreach ($receipt->items as $item)
                        @php

                            $stockValue = $item->product->current_stock * $item->product->purchase_price;

                            $totalQty += $item->product->current_stock;

                            $purchaseValue += $stockValue;

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

                <tr class="summary">

                    <td colspan="10" class="text-end">

                        Total Stock

                    </td>

                    <td class="text-end">

                        {{ number_format($totalQty, 2) }}

                    </td>

                    <td class="text-end">

                        {{ number_format($purchaseValue, 2) }}

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
