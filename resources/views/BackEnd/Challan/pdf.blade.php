<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>
            Challan - {{ $receipt->dm_no }}
        </title>
        <style>
            @page {
                size: A4 portrait;
                margin: 0;
            }

            * {
                box-sizing: border-box;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                width: 210mm;
                min-height: 297mm;
            }

            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 12px;
                color: #222;
                background: #fff;
            }

            .page {
                width: 194mm;
                min-height: 281mm;
                margin-left: 8mm;
                margin-right: 8mm;
                padding-top: 8mm;
                padding-bottom: 8mm;
                position: relative;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            td,
            th,
            div,
            span {
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .break {
                word-break: break-all;
                overflow-wrap: anywhere;
                word-wrap: break-word;
            }

            /* .header {
                width: 194mm;
                max-width: 194mm;
                table-layout: fixed;
            }

            .header td {
                border: none;
                padding: 0;
                vertical-align: top;
                word-wrap: break-word;
                overflow-wrap: break-word;
                word-break: break-word;
            } */

            .logo-area {
                width: 27mm;
                text-align: left;
            }

            .company-area {
                width: 129mm;
                text-align: left;
            }

            .contact-area {
                width: 38mm;
                text-align: right;
            }

            .logo {
                width: 60px;
                max-width: 60px;
                height: auto;
            }

            .company-name {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 20px;
                line-height: 19px;
                font-weight: bold;
                color: #4d4d4d;
                width: 129mm;
                max-width: 129mm;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .company-info {
                font-size: 12px;
                line-height: 10px;
                color: #555;
                width: 129mm;
                max-width: 129mm;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .contact-info {
                width: 38mm;
                max-width: 38mm;
                font-size: 12px;
                line-height: 10px;
                color: #555;
                text-align: right;
                word-break: break-all;
                overflow-wrap: anywhere;
            }

            .invoice-title {
                width: 194mm;
                text-align: center;
                font-size: 20px;
                font-weight: bold;
                margin-top: 10px;
            }

            .page-number {
                width: 194mm;
                text-align: right;
                font-size: 12px;
                margin-top: -10px;
                margin-bottom: 8px;
            }

            .info-table,
            .customer-table {
                border-collapse: collapse;
                table-layout: auto;
                width: auto;
                max-width: 194mm;
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
                font-weight: bold;
                white-space: nowrap;
                padding-right: 6px !important;
            }

            .info-table .colon,
            .customer-table .colon {
                width: 5px;

                min-width: 5px;

                padding-left: 0 !important;

                /* Same gap after colon */
                padding-right: 6px !important;

                text-align: left;

                white-space: nowrap;
            }

            .info-value,
            .customer-value {
                padding-left: 0 !important;
                padding-right: 0 !important;

                word-break: break-word;
                overflow-wrap: break-word;
            }

            .info-value .break,
            .customer-value .break,
            .break {
                word-break: break-all;
                overflow-wrap: anywhere;
                word-wrap: break-word;
            }

            .items {
                width: 194mm;
                max-width: 194mm;
                table-layout: fixed;
                margin-top: 8px;
            }

            .items th {
                border: 1px solid #666;
                padding: 3px 2px;
                height: 22px;
                font-size: 12px;
                font-weight: bold;
                text-align: center;
                vertical-align: middle;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .items td {
                border: 1px solid #666;
                padding: 3px 2px;
                font-size: 12px;
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
                line-height: 10px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .item-number {
                width: 23mm;
                word-break: break-all;
                overflow-wrap: anywhere;
            }

            .number {
                text-align: right;
                white-space: nowrap;
            }

            .center {
                text-align: center;
            }

            .total-row td {
                font-weight: bold;
            }

            .total-title {
                text-align: right;
                font-size: 8px;
            }

            .total-value {
                width: 24mm;
                text-align: right;
                font-size: 8px;
                white-space: nowrap;
            }

            .amount-words {
                width: 194mm;
                margin-top: 7px;
                font-size: 12px;
                line-height: 12px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .remarks {
                width: 194mm;
                margin-top: 6px;
                font-size: 8px;
                line-height: 11px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .bottom-area {
                width: 194mm;
                margin-top: 30px;
            }

            .signature-table {
                width: 194mm;
                max-width: 194mm;
                table-layout: fixed;
                margin-top: 8px;
            }

            .signature-table td {
                border: none;
                vertical-align: bottom;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .signature-left {
                width: 126mm;
            }

            .signature-right {
                width: 68mm;
                text-align: right;
            }

            .signature-line {
                font-size: 12px;
                margin-top: 3px;
                white-space: nowrap;
            }

            .authorized {
                font-size: 12px;
                font-weight: bold;
                margin-top: 3px;
                white-space: nowrap;
            }

            .system-note {
                width: 194mm;
                text-align: center;
                font-size: 12px;
                margin-top: 8px;
            }

            .footer-line {
                width: 194mm;
                border-top: 1px solid #777;
                margin-top: 6px;
            }

            .footer-text {
                width: 194mm;
                text-align: center;
                font-size: 12px;
                color: #777;
                margin-top: 4px;
                line-height: 10px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            tr {
                page-break-inside: avoid;
            }

            td,
            th {
                max-width: 100%;
            }
        </style>
    </head>

    <body>
        <div class="page">
            <table class="header">
                <tr>
                    {{-- LOGO --}}
                    <td class="logo-area">
                        @if (function_exists('setting') && setting() && setting()->logo)
                            @php
                                $logoPath = public_path('uploads/settings/' . setting()->logo);
                            @endphp
                            @if (file_exists($logoPath))
                                <img src="{{ $logoPath }}" class="logo">
                            @endif
                        @endif
                    </td>
                    {{-- COMPANY --}}
                    <td class="company-area">
                        <div class="company-name">
                            {{ $receipt->company->name ??
                                ($receipt->branch->name ??
                                    ((function_exists('setting') && setting() ? setting()->company_name : null) ?? config('app.name'))) }}
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
                </tr>
            </table>
            <div class="invoice-title">
                CHALLAN
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
                        Challan No.
                    </td>
                    <td class="colon">
                        :
                    </td>
                    <td class="info-value">
                        <span class="break">
                            {{ $receipt->dm_no }}
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
                        {{-- <th class="price-col">
                            Unit price
                        </th>
                        <th class="disc-col">
                            Disc %
                        </th>
                        <th class="amount-col">
                            Amount
                        </th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipt->items as $item)
                        @php
                            $product = $item->product;
                            $itemCode = $product->sku ?? '';
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
                            {{-- ITEM NO --}}
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
                            {{-- UNIT PRICE --}}
                            {{-- <td class="price-col number">
                                {{ number_format($rate, 2) }}
                            </td> --}}
                            {{-- DISCOUNT --}}
                            {{-- <td class="disc-col center">
                                0
                            </td> --}}
                            {{-- AMOUNT --}}
                            {{-- <td class="amount-col number">
                                {{ number_format($amount, 2) }}
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="center">
                                No Item Found
                            </td>
                        </tr>
                    @endforelse
                    {{-- TOTAL --}}
                    {{-- <tr class="total-row">
                        <td colspan="7" class="total-title">
                            Total BDT
                        </td>
                        <td class="amount-col total-value">
                            {{ number_format($receipt->total_amount, 2) }}
                        </td>
                    </tr> --}}
                </tbody>
            </table>
            {{-- <div class="amount-words">
                <strong>
                    Amount BDT (In Words)
                </strong>
                :
                {{ numberToWords($receipt->total_amount) }}
                ONLY
            </div> --}}
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
                <div class="system-note">
                    This is a System Generated Invoice.
                </div>
                <div class="footer-line">
                </div>
                <div class="footer-text">
                    <div>
                        Printed On :
                        {{ now()->format('d/m/Y h:i A') }}
                    </div>
                    <div>
                        Generated By :
                        {{ auth()->user()->name ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
