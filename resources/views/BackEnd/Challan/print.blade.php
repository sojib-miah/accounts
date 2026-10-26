<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delivery Challan - {{ $receipt->dm_no }}</title>

        <style>
            :root {
                --primary: #185abc;
                --primary-light: #edf3ff;
                --text: #263244;
                --muted: #687386;
                --border: #dce3ec;
                --light: #f5f7fb;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 25px;
                background: #edf1f6;
                color: var(--text);
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                line-height: 1.5;
            }

            .toolbar {
                max-width: 1000px;
                margin: 0 auto 18px;
                padding: 14px 18px;
                background: #fff;
                border: 1px solid var(--border);
                border-radius: 9px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 12px;
            }

            .toolbar-group {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
            }

            .toolbar label {
                font-weight: 700;
            }

            .toolbar select,
            .toolbar button {
                min-height: 38px;
                padding: 8px 13px;
                border-radius: 6px;
                font-size: 13px;
                cursor: pointer;
            }

            .toolbar select {
                border: 1px solid #cbd3df;
                background: #fff;
            }

            .toolbar button {
                border: 0;
                background: var(--primary);
                color: #fff;
                font-weight: 700;
            }

            .toolbar .secondary {
                background: #e8edf5;
                color: #273449;
            }

            .challan {
                --challan-primary: #185abc;
                max-width: 1000px;
                min-height: 850px;
                margin: 0 auto;
                padding: 40px;
                background: #fff;
                box-shadow: 0 5px 28px rgba(22, 34, 51, .09);
            }

            .challan-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 25px;
                padding-bottom: 22px;
                border-bottom: 3px solid var(--challan-primary);
            }

            .company-block {
                display: flex;
                align-items: flex-start;
                gap: 15px;
                flex: 1;
                min-width: 0;
            }

            .company-logo {
                max-width: 95px;
                max-height: 75px;
                object-fit: contain;
            }

            .company-name {
                margin: 0 0 7px;
                font-size: 23px;
                font-weight: 800;
                color: var(--challan-primary);
                overflow-wrap: anywhere;
            }

            .company-info {
                color: var(--muted);
                font-size: 11px;
                overflow-wrap: anywhere;
            }

            .company-info div {
                margin: 2px 0;
            }

            .challan-heading {
                flex: 0 0 auto;
                text-align: right;
            }

            .challan-heading h1 {
                margin: 0;
                font-size: 30px;
                line-height: 1.2;
                letter-spacing: 1.5px;
                color: var(--challan-primary);
            }

            .challan-number {
                margin-top: 8px;
                font-size: 14px;
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            .status {
                display: inline-block;
                margin-top: 8px;
                padding: 4px 10px;
                border-radius: 20px;
                background: #e7f7ee;
                color: #16804a;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
            }

            .status.draft {
                background: #fff0cf;
                color: #946200;
            }

            .status.cancelled {
                background: #fde8e7;
                color: #b42318;
            }

            .section {
                margin-top: 25px;
            }

            .section-title {
                margin: 0 0 10px;
                color: var(--challan-primary);
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .party-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 18px;
            }

            .party-card {
                padding: 16px;
                border: 1px solid var(--border);
                border-radius: 7px;
            }

            .party-label {
                margin-bottom: 8px;
                color: var(--challan-primary);
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: .8px;
            }

            .party-name {
                margin-bottom: 5px;
                font-size: 15px;
                font-weight: 800;
                overflow-wrap: anywhere;
            }

            .muted {
                color: var(--muted);
            }

            .meta-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                margin-top: 17px;
            }

            .meta-item {
                padding: 11px 12px;
                background: var(--light);
                border-radius: 6px;
                min-width: 0;
            }

            .meta-label {
                display: block;
                margin-bottom: 4px;
                color: var(--muted);
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .meta-value {
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            .items-table {
                width: 100%;
                margin-top: 12px;
                border-collapse: collapse;
            }

            .items-table thead {
                display: table-header-group;
            }

            .items-table th {
                padding: 12px 9px;
                background: var(--challan-primary);
                color: #fff;
                text-align: left;
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: .4px;
            }

            .items-table td {
                padding: 12px 9px;
                border-bottom: 1px solid var(--border);
                vertical-align: top;
            }

            .items-table tbody tr:nth-child(even) {
                background: #f8faff;
            }

            .items-table .number {
                text-align: right;
                white-space: nowrap;
            }

            .product-name {
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            .product-description {
                margin-top: 4px;
                font-size: 11px;
                color: var(--muted);
                overflow-wrap: anywhere;
            }

            .quantity-summary {
                display: flex;
                justify-content: flex-end;
                margin-top: 15px;
            }

            .quantity-box {
                min-width: 230px;
                padding: 12px 15px;
                background: var(--primary-light);
                border: 1px solid #d7e4ff;
                border-radius: 7px;
                display: flex;
                justify-content: space-between;
                gap: 20px;
            }

            .quantity-box strong {
                color: var(--challan-primary);
            }

            .remarks {
                margin-top: 25px;
                padding: 14px 16px;
                border-left: 3px solid var(--challan-primary);
                background: var(--light);
                overflow-wrap: anywhere;
            }

            .remarks-title {
                margin-bottom: 5px;
                font-weight: 800;
            }

            .declaration {
                margin-top: 24px;
                padding: 12px 14px;
                border: 1px dashed #c5ceda;
                color: #4b5563;
                font-size: 11px;
            }

            .signature-grid {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 25px;
                margin-top: 75px;
            }

            .signature {
                padding-top: 9px;
                border-top: 1px solid #657184;
                text-align: center;
                font-size: 11px;
                font-weight: 700;
            }

            .signature small {
                display: block;
                margin-top: 4px;
                font-weight: 400;
                color: var(--muted);
            }

            .footer {
                margin-top: 30px;
                padding-top: 12px;
                border-top: 1px solid var(--border);
                text-align: center;
                font-size: 10px;
                color: var(--muted);
            }

            /* Classic design */
            .design-classic {
                --challan-primary: #252525;
            }

            .design-classic .challan-header {
                border-bottom-width: 1px;
            }

            .design-classic .party-card {
                border-radius: 0;
                border-color: #aaa;
            }

            .design-classic .meta-item {
                border-radius: 0;
                background: #fff;
                border: 1px solid #ddd;
            }

            .design-classic .items-table th {
                background: #eee;
                color: #222;
                border-bottom: 1px solid #555;
            }

            .design-classic .items-table tbody tr:nth-child(even) {
                background: #fff;
            }

            .design-classic .quantity-box {
                background: #fff;
                border: 1px solid #777;
                border-radius: 0;
            }

            .design-classic .declaration {
                border-style: solid;
            }

            /* Compact design */
            .design-compact {
                min-height: auto;
                padding: 25px;
            }

            .design-compact .challan-header {
                padding-bottom: 14px;
            }

            .design-compact .company-name {
                font-size: 19px;
            }

            .design-compact .challan-heading h1 {
                font-size: 25px;
            }

            .design-compact .section {
                margin-top: 16px;
            }

            .design-compact .party-card {
                padding: 10px;
            }

            .design-compact .meta-grid {
                margin-top: 10px;
            }

            .design-compact .meta-item {
                padding: 7px;
            }

            .design-compact .items-table th,
            .design-compact .items-table td {
                padding: 7px 6px;
            }

            .design-compact .signature-grid {
                margin-top: 55px;
            }

            /* Delivery-focused design */
            .design-delivery .challan-header {
                border-bottom: 5px solid var(--challan-primary);
            }

            .design-delivery .challan-heading h1 {
                font-size: 25px;
                letter-spacing: 1px;
            }

            .design-delivery .challan-heading h1:after {
                content: "DELIVERY";
                display: block;
                margin-top: 5px;
                font-size: 11px;
                letter-spacing: 3px;
            }

            .design-delivery .declaration {
                background: #fafafa;
                border: 1px solid #b9c2cf;
                padding: 17px;
            }

            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            @media print {
                body {
                    margin: 0;
                    padding: 0;
                    background: #fff;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .toolbar {
                    display: none !important;
                }

                .challan {
                    width: 100%;
                    max-width: none;
                    min-height: 0;
                    margin: 0;
                    padding: 0;
                    box-shadow: none;
                }

                .party-card,
                .meta-item,
                .quantity-box,
                .remarks,
                .declaration,
                .signature-grid {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }

                .items-table tr {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }
            }

            @media screen and (max-width: 650px) {
                body {
                    padding: 10px;
                }

                .challan {
                    padding: 20px;
                }

                .challan-header {
                    flex-direction: column;
                }

                .challan-heading {
                    text-align: left;
                }

                .party-grid {
                    grid-template-columns: 1fr;
                }

                .meta-grid {
                    grid-template-columns: 1fr 1fr;
                }

                .signature-grid {
                    grid-template-columns: 1fr;
                    gap: 40px;
                }

                .items-table th,
                .items-table td {
                    padding: 6px 4px;
                    font-size: 10px;
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

            $challanNo = $receipt->dm_no ?? ($receipt->receipt_no ?? 'CH-' . $receipt->id);

            $challanDate = $receipt->receipt_date
                ? \Illuminate\Support\Carbon::parse($receipt->receipt_date)->format('d M Y')
                : '—';

            $totalQty = (float) ($receipt->total_qty ?? $items->sum('qty'));

            $status = $receipt->status ?? 'Completed';
            $design = in_array(request()->query('design', 'modern'), ['modern', 'classic', 'compact', 'delivery'], true)
                ? request()->query('design', 'modern')
                : 'modern';
            $branchAddress = $branch->address ?? null;
            $partyPhone = $party->phone ?? ($party->phone_one ?? null);
            $formatQty = function ($qty) {
                return rtrim(rtrim(number_format((float) $qty, 2, '.', ''), '0'), '.');
            };
        @endphp

        <div class="toolbar">
            <div class="toolbar-group">
                <label for="designSelect">Challan Design</label>

                <select id="designSelect" onchange="changeDesign(this.value)">
                    <option value="modern" {{ $design === 'modern' ? 'selected' : '' }}>
                        Modern Challan
                    </option>
                    <option value="classic" {{ $design === 'classic' ? 'selected' : '' }}>
                        Classic Challan
                    </option>
                    <option value="compact" {{ $design === 'compact' ? 'selected' : '' }}>
                        Compact Challan
                    </option>
                    <option value="delivery" {{ $design === 'delivery' ? 'selected' : '' }}>
                        Delivery Challan
                    </option>
                </select>
            </div>

            <div class="toolbar-group">
                <button type="button" onclick="window.print()">
                    <span>Print Challan</span>
                </button>
            </div>
        </div>

        <main id="challan" class="challan design-{{ $design }}">
            <header class="challan-header">
                <div class="company-block">
                    @if (optional(setting())->logo)
                        <div class="company-logo">
                            <img src="{{ asset('uploads/settings/' . setting()->logo) }}" alt="Logo">
                        </div>
                    @endif
                    <div>
                        <h2 class="company-name">{{ $companyName }}</h2>
                        <div class="company-info">
                            @if ($branch && $branch->name)
                                <div><strong>{{ $branch->name }}</strong></div>
                            @endif
                            @if ($branchAddress)
                                <div>{{ $branchAddress }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="challan-heading">
                    <h1>CHALLAN</h1>
                    <div class="challan-number">{{ $challanNo }}</div>
                    <div class="muted">Date: {{ $challanDate }}</div>
                    <span
                        class="status
                {{ strtolower($status) === 'draft' ? 'draft' : '' }}
                {{ strtolower($status) === 'cancelled' ? 'cancelled' : '' }}">
                        {{ $status }}
                    </span>
                </div>
            </header>

            <section class="section party-grid">
                <div class="party-card">
                    <div class="party-label">Delivery To / Receiver</div>
                    <div class="party-name">{{ $partyName }}</div>
                    @if ($party && $party->address)
                        <div class="muted">Address: {{ $party->address }}</div>
                    @endif
                    @if ($partyPhone)
                        <div class="muted">Phone: {{ $partyPhone }}</div>
                    @endif
                    @if ($party && $party->email)
                        <div class="muted">Email: {{ $party->email }}</div>
                    @endif
                </div>
                <div class="party-card">
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
                </div>
            </section>

            <section class="section">
                <div class="meta-grid">
                    <div class="meta-item">
                        <span class="meta-label">Reference No.</span>
                        <div class="meta-value">
                            {{ $receipt->so_no ?? ($receipt->receipt_no ?? '—') }}
                        </div>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Delivery Date</span>
                        <div class="meta-value">{{ $challanDate }}</div>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Total Items</span>
                        <div class="meta-value">{{ $items->count() }}</div>
                    </div>
                </div>
            </section>

            <section class="section">
                <h3 class="section-title">Goods / Product Details</h3>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">SL</th>
                            <th style="width: 48%;">Product Description</th>
                            <th style="width: 16%;">Reference</th>
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

                                    @if (!empty($item->details))
                                        <div class="product-description">
                                            {{ $item->details }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->product->sku ?? ($item->product->product_code ?? '—') }}
                                </td>

                                <td class="number">
                                    {{ $formatQty($item->qty) }}
                                </td>

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

                <div class="quantity-summary">
                    <div class="quantity-box">
                        <span>Total Quantity</span>
                        <strong>{{ $formatQty($totalQty) }}</strong>
                    </div>
                </div>
            </section>

            @if (!empty($receipt->remarks))
                <section class="section remarks">
                    <div class="remarks-title">Remarks / Delivery Instructions</div>
                    {{ $receipt->remarks }}
                </section>
            @endif

            <section class="section declaration">
                <strong>Receiver's Declaration</strong>

                <p>
                    I acknowledge receipt of the goods listed above in the stated
                    quantities. Any discrepancy or damage should be recorded at the
                    time of delivery.
                </p>
            </section>

            <section class="signature-grid">
                <div class="signature">
                    Prepared By
                    <small>{{ $receipt->creator->name ?? '' }}</small>
                </div>

                <div class="signature">
                    Delivered By
                    <small>Name &amp; Signature</small>
                </div>

                <div class="signature">
                    Received By
                    <small>Name, Signature &amp; Seal</small>
                </div>
            </section>

            <footer class="footer">
                This is a goods delivery challan. It is not a tax invoice.
                <br>
                Generated by {{ $companyName }}
            </footer>

        </main>

        <script>
            function changeDesign(design) {
                const allowed = ['modern', 'classic', 'compact', 'delivery'];

                if (!allowed.includes(design)) {
                    design = 'modern';
                }

                const url = new URL(window.location.href);
                url.searchParams.set('design', design);

                window.location.href = url.toString();
            }
        </script>

    </body>

</html>
