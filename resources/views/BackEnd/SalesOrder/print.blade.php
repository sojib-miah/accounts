<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <title>
            Invoice - {{ $receipt->inv_no }}
        </title>

        <style>
            @page {
                size: A4 portrait;
                margin: 0;
            }

            * {
                box-sizing: border-box;
            }

            /* html,
            body {
                margin: 0;
                padding: 0;
                width: 210mm;
                min-height: 297mm;
            }

            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 15px;
                color: #222;
                background: #fff;
            } */

            .page {
                width: 194mm;
                min-height: 281mm;
                margin: 20px auto;
                padding-top: 7mm;
                padding-bottom: 7mm;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            td,
            th {
                vertical-align: top;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .break {
                word-break: break-all;
                overflow-wrap: anywhere;
                word-wrap: break-word;
            }

            .header {
                width: 194mm;
                max-width: 194mm;
                table-layout: fixed;
                border-collapse: collapse;
            }

            .header td {
                border: none;
                padding: 0;
                vertical-align: top;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .logo-area {
                width: 27mm;
                text-align: left;
                vertical-align: top;
            }

            .company-area {
                width: 129mm;
                text-align: left;
                vertical-align: top;
            }

            .contact-area {
                width: 38mm;
                text-align: right;
                vertical-align: top;
            }

            .logo {
                width: 60px;
                max-width: 60px;
                height: auto;
                display: block;
            }

            .company-name {
                width: 129mm;
                max-width: 129mm;
                font-size: 25px;
                line-height: 20px;
                font-weight: bold;
                color: #4d4d4d;
                text-align: left;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .company-info {
                width: 129mm;
                max-width: 129mm;
                margin-top: 2px;
                font-size: 14px;
                line-height: 13px;
                color: #555;
                text-align: left;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .invoice-title {
                width: 194mm;
                text-align: center;
                font-size: 20px;
                font-weight: bold;
                margin-top: 8px;
            }

            .page-number {
                width: 194mm;
                text-align: right;
                font-size: 12px;
                margin-top: -8px;
                margin-bottom: 7px;
            }

            .info-table,
            .customer-table {
                width: 194mm;
                max-width: 194mm;
                table-layout: fixed;
                border-collapse: collapse;
                border-spacing: 0;
                margin: 0;
                padding: 0;
            }

            .info-table td,
            .customer-table td {
                border: none;
                padding-top: 1.5px;
                padding-bottom: 1.5px;
                padding-left: 0;
                padding-right: 0;
                vertical-align: top;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .info-label,
            .customer-label {
                width: 42mm;
                font-weight: bold;
                padding-right: 6px !important;
                text-align: left;
                white-space: nowrap;
            }

            .info-table .colon,
            .customer-table .colon {
                width: 6mm;
                min-width: 6mm;
                max-width: 6mm;
                padding-left: 0 !important;
                padding-right: 6px !important;
                text-align: left;
                white-space: nowrap;
            }

            .info-value,
            .customer-value {
                width: 146mm;
                padding-left: 0 !important;
                padding-right: 0 !important;
                text-align: left;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .info-value .break,
            .customer-value .break {
                word-break: break-all;
                overflow-wrap: anywhere;
                word-wrap: break-word;
            }

            .customer-table {
                margin-top: 2px;
            }

            .items {
                width: 194mm;
                max-width: 194mm;
                table-layout: fixed;
                margin-top: 8px;
                border-collapse: collapse;
            }

            .items th {
                border: 1px solid #666;
                padding: 4px 2px;
                height: 22px;
                font-size: 13px;
                font-weight: bold;
                text-align: center;
                vertical-align: middle;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .items td {
                border: 1px solid #666;
                padding: 4px 2px;
                font-size: 13px;
                line-height: 12px;
                vertical-align: top;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .sl-col {
                width: 10mm;
                text-align: center;
            }

            .item-col {
                width: 23mm;
            }

            .description-col {
                width: 65mm;
            }

            .qty-col {
                width: 14mm;
                text-align: center;
            }

            .uom-col {
                width: 16mm;
                text-align: center;
            }

            .price-col {
                width: 27mm;
                text-align: right;
            }

            .disc-col {
                width: 15mm;
                text-align: center;
            }

            .amount-col {
                width: 24mm;
                text-align: right;
            }

            .description {
                width: 65mm;
                line-height: 12px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .item-number {
                width: 23mm;
                word-break: break-all;
                overflow-wrap: anywhere;
            }

            .center {
                text-align: center;
            }

            .number {
                text-align: right;
                white-space: nowrap;
            }

            .total-row td {
                font-weight: bold;
                font-size: 15px;
            }

            .total-title {
                text-align: right;
                padding-right: 5px !important;
            }

            .total-value {
                width: 24mm;
                text-align: right;
                white-space: nowrap;
            }

            .amount-words {
                width: 194mm;
                max-width: 194mm;
                margin-top: 7px;
                font-size: 15px;
                line-height: 13px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .remarks {
                width: 194mm;
                max-width: 194mm;
                margin-top: 6px;
                font-size: 14px;
                line-height: 12px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .bottom-area {
                width: 194mm;
                max-width: 194mm;
                margin-top: 25px;
            }

            .signature-table {
                width: 194mm;
                max-width: 194mm;
                table-layout: fixed;
                border-collapse: collapse;
            }

            .signature-table td {
                border: none;
                vertical-align: bottom;
            }

            .signature-left {
                width: 126mm;
            }

            .signature-right {
                width: 68mm;
                text-align: right;
            }

            .signature-line {
                font-size: 15px;
                margin-top: 3px;
                white-space: nowrap;
            }

            .authorized {
                font-size: 15px;
                font-weight: bold;
                margin-top: 3px;
                white-space: nowrap;
            }

            .system-note {
                width: 194mm;
                max-width: 194mm;
                text-align: center;
                font-size: 14px;
                margin-top: 8px;
            }

            .footer-line {
                width: 194mm;
                max-width: 194mm;
                border-top: 1px solid #777;
                margin-top: 6px;
            }

            .footer-text {
                width: 194mm;
                max-width: 194mm;
                text-align: center;
                font-size: 13px;
                color: #777;
                margin-top: 4px;
                line-height: 11px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            tr {

                page-break-inside: avoid;
            }

            .print-btn {
                position: fixed;
                top: 10px;
                right: 10px;
                z-index: 99999;
                padding: 8px 15px;
                border: none;
                border-radius: 4px;
                background: #0d6efd;
                color: #fff;
                font-size: 13px;
                cursor: pointer;
            }

            @media print {
                @page {
                    size: A4 portrait;
                    margin: 0;
                }

                html,
                body {
                    width: 210mm;
                    min-height: 297mm;
                    margin: 0;
                    padding: 0;
                }

                body {
                    background: #fff;
                }

                .page {
                    width: 194mm;
                    min-height: 281mm;
                    margin-left: 8mm;
                    margin-right: 8mm;
                    padding-top: 7mm;
                    padding-bottom: 7mm;
                    overflow: visible;
                }

                .print-btn {
                    display: none !important;
                }
            }
        </style>
    </head>

    <body>
        <button type="button" class="print-btn" onclick="window.print()">
            Print
        </button>
        <div class="page">
            <table class="header">
                <tr>
                    <td class="logo-area">
                        @if (setting() && setting()->logo)
                            <img src="{{ asset('uploads/settings/' . setting()->logo) }}" class="logo">
                        @endif
                    </td>
                    <td class="company-area">
                        <div class="company-name">
                            {{ $receipt->company->name ?? config('app.name') }}
                        </div>
                        <div class="company-info">
                            @if ($receipt->company->address ?? null)
                                {{ $receipt->company->address }}
                            @elseif ($receipt->branch->address ?? null)
                                {{ $receipt->branch->address }}
                            @endif
                            @if ($receipt->branch->phone_one ?? null)
                                <br>
                                Telephone :
                                {{ $receipt->branch->phone_one }}
                            @endif
                            @if ($receipt->branch->phone_two ?? null)
                                ,
                                {{ $receipt->branch->phone_two }}
                            @endif
                            @if ($receipt->branch->email ?? null)
                                <br>
                                Email :
                                {{ $receipt->branch->email }}
                            @endif
                        </div>
                    </td>
                    <td class="contact-area">
                    </td>
                </tr>
            </table>
            <div class="invoice-title">
                INVOICE
            </div>
            <div class="page-number">
                Page No.: 1/1
            </div>
            <table class="info-table">
                <tr>
                    <td class="info-label">
                        Date
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="info-value">
                        {{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d/m/Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="info-label">
                        Invoice No.
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="info-value">
                        <span class="break">
                            {{ $receipt->inv_no }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">
                        Delivery Note No.
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="info-value">
                        <span class="break">
                            {{ $receipt->challan_no ?? ($receipt->delivery_no ?? '') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">
                        Ref. No.
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="info-value">
                        <span class="break">
                            {{ $receipt->so_no ?? '' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">
                        Ref. Date
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="info-value">
                        @if ($receipt->ref_date ?? null)
                            {{ \Carbon\Carbon::parse($receipt->ref_date)->format('d/m/Y') }}
                        @endif
                    </td>
                </tr>
            </table>
            <table class="customer-table">
                <tr>
                    <td class="customer-label">
                        Customer Code
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="customer-value">
                        <span class="break">
                            {{ $receipt->party->customer_code ??
                                ($receipt->party->code ?? 'CUST-' . str_pad($receipt->party->id ?? 0, 8, '0', STR_PAD_LEFT)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="customer-label">
                        Customer Name
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="customer-value">
                        {{ $receipt->party->name ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="customer-label">
                        Customer Address
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="customer-value">
                        {{ $receipt->party->address ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="customer-label">
                        Contact Name
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="customer-value">
                        {{ $receipt->party->contact_name ?? ($receipt->party->name ?? '') }}
                    </td>
                </tr>
                <tr>
                    <td class="customer-label">
                        Phone No.
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="customer-value">
                        <span class="break">
                            {{ $receipt->party->phone ?? '' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="customer-label">
                        Payment Terms
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="customer-value">
                        {{ $receipt->party->payment_terms ?? ($receipt->payment_terms ?? '') }}
                    </td>
                </tr>
            </table>
            <table class="items">
                <thead>
                    <tr>
                        <th class="sl-col">
                            Sl. No.
                        </th>
                        <th class="item-col">
                            Item No
                        </th>
                        <th class="description-col">
                            Description
                        </th>
                        <th class="qty-col">
                            Qty
                        </th>
                        <th class="uom-col">
                            UOM
                        </th>
                        <th class="price-col">
                            Unit price
                        </th>
                        <th class="disc-col">
                            Disc %
                        </th>
                        <th class="amount-col">
                            Amount
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipt->items as $item)
                        @php
                            $product = $item->product;
                            $itemCode = $product->sku ?? ($product->product_code ?? '');
                            $description = $product->name ?? '';
                            $uom = $product->unit ?? 'Number';
                            $qty = (float) $item->qty;
                            $rate = (float) $item->rate;
                            $amount = (float) $item->amount;
                        @endphp
                        <tr>
                            {{-- SL --}}
                            <td class="sl-col center">
                                {{ $loop->iteration }}
                            </td>
                            {{-- ITEM CODE --}}
                            <td class="item-col">
                                <span class="break">
                                    {{ $itemCode }}
                                </span>
                            </td>
                            {{-- DESCRIPTION --}}
                            <td class="description-col description">
                                {{ $description }}
                                @if ($item->details ?? null)
                                    <br>
                                    {{ $item->details }}
                                @endif
                            </td>
                            {{-- QTY --}}
                            <td class="qty-col center">
                                {{ rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.') }}
                            </td>
                            {{-- UOM --}}
                            <td class="uom-col center">
                                {{ $uom }}
                            </td>
                            {{-- RATE --}}
                            <td class="price-col number">
                                {{ number_format($rate, 2) }}
                            </td>
                            {{-- DISCOUNT --}}
                            <td class="disc-col center">
                                0
                            </td>
                            {{-- AMOUNT --}}
                            <td class="amount-col number">
                                {{ number_format($amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="center">
                                No Item Found
                            </td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="7" class="total-title">
                            Total BDT
                        </td>
                        <td class="amount-col total-value">
                            {{ number_format($receipt->total_amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="amount-words">
                <strong>
                    Amount BDT (In Words)
                </strong>
                :
                @if (function_exists('numberToWords'))
                    {{ numberToWords($receipt->total_amount) }}
                @else
                    {{ number_format($receipt->total_amount, 2) }}
                @endif
                ONLY
            </div>
            @if ($receipt->remarks)
                <div class="remarks">
                    <strong>
                        Remarks :
                    </strong>
                    {{ $receipt->remarks }}
                </div>
            @endif
            <div class="bottom-area">
                <table class="signature-table">
                    <tr>
                        <td class="signature-left">
                        </td>
                        <td class="signature-right">
                            <br>
                            <br>
                            <br>
                            <div class="signature-line">
                                _________________________
                            </div>
                            <div class="authorized">
                                Authorized Signature
                            </div>
                        </td>
                    </tr>
                </table>
                {{-- SYSTEM NOTE --}}
                <div class="system-note">
                    This is a System Generated Invoice.
                </div>
                {{-- FOOTER LINE --}}
                <div class="footer-line">
                </div>
                {{-- FOOTER --}}
                <div class="footer-text">
                    Printed On :
                    {{ now()->format('d/m/Y h:i A') }}
                    &nbsp;&nbsp;&nbsp;
                    Generated By :
                    {{ $receipt->creator->name ?? '' }}
                </div>
            </div>
        </div>
        <script>
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 300);
            };
        </script>
    </body>

</html>
