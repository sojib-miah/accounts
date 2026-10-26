<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Delivery Challan - {{ $receipt->dm_no }}</title>

        <style>
            @page {
                size: A4 portrait;
                margin: 28px 30px;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 0;
                font-family: DejaVu Sans, sans-serif;
                font-size: 10px;
                line-height: 1.55;
                color: #263244;
            }

            .challan {
                --primary: #185abc;
                --primary-light: #edf3ff;
                --border: #dce3ec;
                --muted: #687386;
                width: 100%;
            }

            .clearfix:after {
                content: "";
                display: table;
                clear: both;
            }

            .muted {
                color: var(--muted);
            }

            .top-line {
                height: 5px;
                margin-bottom: 22px;
                background: var(--primary);
            }

            .company {
                float: left;
                width: 59%;
            }

            .logo {
                max-width: 85px;
                max-height: 55px;
                margin-bottom: 7px;
            }

            .company-name {
                margin: 0 0 7px;
                color: var(--primary);
                font-size: 21px;
                font-weight: bold;
                overflow-wrap: anywhere;
            }

            .company-info {
                color: var(--muted);
                font-size: 9px;
                overflow-wrap: anywhere;
            }

            .company-info div {
                margin: 2px 0;
            }

            .challan-heading {
                float: right;
                width: 39%;
                text-align: right;
            }

            .challan-heading h1 {
                margin: 0;
                color: var(--primary);
                font-size: 29px;
                letter-spacing: 1.5px;
            }

            .challan-number {
                margin-top: 7px;
                font-size: 12px;
                font-weight: bold;
                overflow-wrap: anywhere;
            }

            .status {
                display: inline-block;
                margin-top: 7px;
                padding: 4px 9px;
                background: #e7f7ee;
                color: #16804a;
                font-size: 8px;
                font-weight: bold;
            }

            .status-draft {
                background: #fff0cf;
                color: #946200;
            }

            .status-cancelled {
                background: #fde8e7;
                color: #b42318;
            }

            .divider {
                margin: 17px 0;
                border: 0;
                border-top: 2px solid var(--primary);
            }

            .section {
                margin-top: 22px;
            }

            .section-title {
                margin: 0 0 9px;
                color: var(--primary);
                font-size: 10px;
                font-weight: bold;
                letter-spacing: .8px;
                text-transform: uppercase;
            }

            .party-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 19px;
            }

            .party-table td {
                width: 50%;
                padding: 12px;
                vertical-align: top;
                border: 1px solid var(--border);
            }

            .party-label {
                margin-bottom: 7px;
                color: var(--primary);
                font-size: 9px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .party-name {
                margin-bottom: 5px;
                font-size: 12px;
                font-weight: bold;
                overflow-wrap: anywhere;
            }

            .meta-table {
                width: 100%;
                margin-top: 12px;
                border-collapse: collapse;
            }

            .meta-table td {
                width: 33.33%;
                padding: 9px;
                border: 1px solid var(--border);
                background: #f5f7fb;
                vertical-align: top;
            }

            .meta-label {
                display: block;
                margin-bottom: 3px;
                color: var(--muted);
                font-size: 8px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .meta-value {
                font-weight: bold;
                overflow-wrap: anywhere;
            }

            .items-table {
                width: 100%;
                margin-top: 10px;
                border-collapse: collapse;
            }

            .items-table thead {
                display: table-header-group;
            }

            .items-table th {
                padding: 10px 7px;
                background: var(--primary);
                color: #fff;
                font-size: 8px;
                text-align: left;
                text-transform: uppercase;
            }

            .items-table td {
                padding: 9px 7px;
                border-bottom: 1px solid var(--border);
                vertical-align: top;
                overflow-wrap: anywhere;
            }

            .items-table tbody tr:nth-child(even) {
                background: #f8faff;
            }

            .items-table tr {
                page-break-inside: avoid;
            }

            .number {
                text-align: right;
                white-space: nowrap;
            }

            .product-name {
                font-weight: bold;
            }

            .product-description {
                margin-top: 3px;
                color: var(--muted);
                font-size: 8px;
                overflow-wrap: anywhere;
            }

            .quantity-box {
                width: 240px;
                margin: 14px 0 0 auto;
                padding: 10px 12px;
                border: 1px solid #d7e4ff;
                background: var(--primary-light);
            }

            .quantity-label {
                float: left;
                font-weight: bold;
            }

            .quantity-value {
                float: right;
                color: var(--primary);
                font-weight: bold;
            }

            .remarks {
                margin-top: 22px;
                padding: 11px 13px;
                border-left: 3px solid var(--primary);
                background: #f5f7fb;
                overflow-wrap: anywhere;
            }

            .remarks-title {
                margin-bottom: 4px;
                font-weight: bold;
            }

            .declaration {
                margin-top: 20px;
                padding: 12px;
                border: 1px dashed #aeb8c7;
                font-size: 9px;
            }

            .declaration-title {
                font-weight: bold;
            }

            .signatures {
                width: 100%;
                margin-top: 66px;
                border-collapse: separate;
                border-spacing: 15px 0;
            }

            .signatures td {
                width: 33.33%;
                padding-top: 8px;
                border-top: 1px solid #657184;
                text-align: center;
                vertical-align: top;
                font-size: 9px;
                font-weight: bold;
            }

            .signatures small {
                display: block;
                margin-top: 4px;
                color: var(--muted);
                font-size: 8px;
                font-weight: normal;
            }

            .footer {
                margin-top: 25px;
                padding-top: 10px;
                border-top: 1px solid var(--border);
                color: var(--muted);
                font-size: 8px;
                text-align: center;
            }

            /* CLASSIC DESIGN */
            .design-classic {
                --primary: #252525;
                --primary-light: #ffffff;
                --border: #aaaaaa;
            }

            .design-classic .top-line {
                height: 2px;
            }

            .design-classic .party-table td {
                border-color: #999;
            }

            .design-classic .meta-table td {
                background: #fff;
            }

            .design-classic .items-table th {
                background: #eeeeee;
                color: #222;
                border-bottom: 1px solid #555;
            }

            .design-classic .items-table tbody tr:nth-child(even) {
                background: #fff;
            }

            .design-classic .quantity-box {
                border-color: #777;
                background: #fff;
            }

            .design-classic .declaration {
                border-style: solid;
            }

            /* COMPACT DESIGN */
            .design-compact .top-line {
                margin-bottom: 12px;
            }

            .design-compact .company-name {
                font-size: 17px;
            }

            .design-compact .challan-heading h1 {
                font-size: 23px;
            }

            .design-compact .party-table {
                margin-top: 12px;
            }

            .design-compact .party-table td {
                padding: 8px;
            }

            .design-compact .meta-table td {
                padding: 6px;
            }

            .design-compact .section {
                margin-top: 15px;
            }

            .design-compact .items-table th,
            .design-compact .items-table td {
                padding: 6px 5px;
            }

            .design-compact .quantity-box {
                margin-top: 8px;
                padding: 7px 9px;
            }

            .design-compact .signatures {
                margin-top: 48px;
            }

            /* DELIVERY DESIGN */
            .design-delivery .top-line {
                height: 7px;
            }

            .design-delivery .challan-heading h1 {
                font-size: 24px;
            }

            .design-delivery .challan-heading h1:after {
                content: "DELIVERY NOTE";
                display: block;
                margin-top: 4px;
                font-size: 9px;
                letter-spacing: 2px;
            }

            .design-delivery .declaration {
                padding: 15px;
                border: 1px solid #aeb8c7;
                background: #fafafa;
            }

            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }
        </style>
    </head>

    <body>

        @php
            $company = $receipt->company;
            $branch = $receipt->branch;
            $party = $receipt->party;
            $items = $receipt->items ?? collect();

            $companyName = $company->name ?? ($company->company_name ?? config('app.name'));

            $partyName = $party->name ?? ($party->company_name ?? 'Customer');

            $challanNo = $receipt->dm_no ?? ($receipt->so_no ?? 'CH-' . $receipt->id);

            $challanDate = $receipt->receipt_date
                ? \Illuminate\Support\Carbon::parse($receipt->receipt_date)->format('d M Y')
                : '—';

            $totalQty = (float) ($receipt->total_qty ?? $items->sum('qty'));

            $status = $receipt->status ?? 'Completed';

            $design = in_array($design ?? 'modern', ['modern', 'classic', 'compact', 'delivery'], true)
                ? $design
                : 'modern';

            $partyPhone = $party->phone ?? ($party->phone_one ?? null);
            $formatQty = function ($qty) {
                return rtrim(rtrim(number_format((float) $qty, 2, '.', ''), '0'), '.');
            };
        @endphp

        <div class="challan design-{{ $design }}">

            <div class="top-line"></div>

            <div class="clearfix">
                <div class="company">
                    @if (optional(setting())->logo)
                        <img class="logo" src="{{ public_path('uploads/settings/' . setting()->logo) }}" alt="Logo">
                    @endif

                    <h2 class="company-name">{{ $companyName }}</h2>

                    <div class="company-info">
                        @if ($branch && $branch->name)
                            <div><strong>{{ $branch->name }}</strong></div>
                        @endif

                        @if ($branch && $branch->address)
                            <div>{{ $branch->address }}</div>
                        @endif
                    </div>
                </div>

                <div class="challan-heading">
                    <h1>CHALLAN</h1>

                    <div class="challan-number">{{ $challanNo }}</div>

                    <div class="muted">Date: {{ $challanDate }}</div>

                    <span
                        class="status
                {{ strtolower($status) === 'draft' ? 'status-draft' : '' }}
                {{ strtolower($status) === 'cancelled' ? 'status-cancelled' : '' }}">
                        {{ $status }}
                    </span>
                </div>
            </div>

            <hr class="divider">

            <table class="party-table">
                <tr>
                    <td>
                        <div class="party-label">Delivery To / Receiver</div>

                        <div class="party-name">{{ $partyName }}</div>

                        @if ($party && $party->address)
                            <div>{{ $party->address }}</div>
                        @endif

                        @if ($partyPhone)
                            <div>Phone: {{ $partyPhone }}</div>
                        @endif

                        @if ($party && $party->email)
                            <div>Email: {{ $party->email }}</div>
                        @endif
                    </td>

                    <td>
                        <div class="party-label">Challan Information</div>

                        <div>
                            <span class="muted">Challan No:</span>
                            <strong>{{ $challanNo }}</strong>
                        </div>

                        <div>
                            <span class="muted">Date:</span>
                            <strong>{{ $challanDate }}</strong>
                        </div>

                        <div>
                            <span class="muted">Branch:</span>
                            <strong>{{ $branch->name ?? '—' }}</strong>
                        </div>

                        <div>
                            <span class="muted">Prepared By:</span>
                            <strong>{{ $receipt->creator->name ?? '—' }}</strong>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="meta-table">
                <tr>
                    <td>
                        <span class="meta-label">Reference No.</span>
                        <div class="meta-value">
                            {{ $receipt->so_no ?? ($receipt->receipt_no ?? '—') }}
                        </div>
                    </td>

                    <td>
                        <span class="meta-label">Delivery Date</span>
                        <div class="meta-value">{{ $challanDate }}</div>
                    </td>

                    <td>
                        <span class="meta-label">Product Lines</span>
                        <div class="meta-value">{{ $items->count() }}</div>
                    </td>
                </tr>
            </table>

            <section class="section">
                <h3 class="section-title">Goods / Product Details</h3>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">SL</th>
                            <th style="width: 46%;">Product Description</th>
                            <th style="width: 18%;">Product Code</th>
                            <th class="number" style="width: 12%;">Qty</th>
                            <th style="width: 18%;">Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="product-name">
                                        {{ $item->product->name ?? ($item->category->name ?? ($item->accountHead->name ?? 'Item')) }}
                                    </div>

                                    @if ($item->details)
                                        <div class="product-description">
                                            {{ $item->details }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->product->sku ?? ($item->product->product_code ?? '—') }}
                                </td>

                                <td class="number">{{ $formatQty($item->qty) }}</td>

                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;">
                                    No items found for this challan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="quantity-box clearfix">
                    <span class="quantity-label">Total Quantity</span>
                    <span class="quantity-value">{{ $formatQty($totalQty) }}</span>
                </div>
            </section>

            @if (!empty($receipt->remarks))
                <div class="remarks">
                    <div class="remarks-title">Remarks / Delivery Instructions</div>
                    {{ $receipt->remarks }}
                </div>
            @endif

            <div class="declaration">
                <div class="declaration-title">Receiver's Declaration</div>

                <p>
                    I acknowledge receipt of the goods listed above in the stated
                    quantities. Any discrepancy or damage should be recorded at the
                    time of delivery.
                </p>
            </div>

            <table class="signatures">
                <tr>
                    <td>
                        Prepared By
                        <small>{{ $receipt->creator->name ?? 'Name & Signature' }}</small>
                    </td>

                    <td>
                        Delivered By
                        <small>Name &amp; Signature</small>
                    </td>

                    <td>
                        Received By
                        <small>Name, Signature &amp; Seal</small>
                    </td>
                </tr>
            </table>

            <div class="footer">
                This is a goods delivery challan, not a tax invoice.
                <br>
                Generated by {{ $companyName }}
            </div>

        </div>

    </body>

</html>
