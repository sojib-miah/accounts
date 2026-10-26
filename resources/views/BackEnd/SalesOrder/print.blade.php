<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sales Invoice - {{ $receipt->inv_no ?? $receipt->so_no }}</title>

        <style>
            :root {
                --primary: #174ea6;
                --primary-light: #eaf1ff;
                --text: #202938;
                --muted: #687386;
                --border: #dce2ea;
                --surface: #f5f7fb;
                --success: #168653;
                --danger: #c0392b;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 24px;
                background: #edf1f6;
                color: var(--text);
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                line-height: 1.5;
            }

            .meta-table {
                width: 100%;
                margin-top: 12px;
                border-collapse: collapse;
            }

            .meta-table td {
                width: 50%;
                padding: 7px 9px;
                border: 1px solid #e0e5ed;
            }

            .toolbar {
                max-width: 1000px;
                margin: 0 auto 18px;
                padding: 14px 18px;
                background: #fff;
                border: 1px solid var(--border);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
            }

            .toolbar-left,
            .toolbar-right {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .toolbar label {
                font-weight: 700;
            }

            .toolbar select,
            .toolbar button {
                min-height: 38px;
                border-radius: 6px;
                padding: 8px 13px;
                font-size: 13px;
                cursor: pointer;
            }

            .toolbar select {
                border: 1px solid #cbd3df;
                background: #fff;
                color: var(--text);
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

            .invoice {
                --invoice-primary: var(--primary);
                max-width: 1000px;
                min-height: 900px;
                margin: 0 auto;
                padding: 42px;
                background: #fff;
                box-shadow: 0 5px 28px rgba(22, 34, 51, .09);
            }

            .invoice-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 28px;
                padding-bottom: 26px;
                border-bottom: 3px solid var(--invoice-primary);
            }

            .company-block {
                display: flex;
                align-items: flex-start;
                gap: 15px;
                min-width: 0;
            }

            .company-logo {
                max-width: 100px;
                max-height: 76px;
                object-fit: contain;
            }

            .company-name {
                margin: 0 0 7px;
                color: var(--invoice-primary);
                font-size: 24px;
                line-height: 1.2;
                font-weight: 800;
                overflow-wrap: anywhere;
            }

            .company-info,
            .muted {
                color: var(--muted);
                overflow-wrap: anywhere;
            }

            .company-info div {
                margin: 2px 0;
            }

            .invoice-title {
                flex: 0 0 auto;
                text-align: right;
            }

            .invoice-title h1 {
                margin: 0;
                color: var(--invoice-primary);
                font-size: 35px;
                line-height: 1.1;
                letter-spacing: 2px;
            }

            .invoice-title .order-number {
                margin-top: 8px;
                font-size: 15px;
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            .status {
                display: inline-block;
                margin-top: 9px;
                padding: 5px 11px;
                border-radius: 20px;
                background: #e8f7ef;
                color: var(--success);
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
            }

            .status.draft {
                background: #fff4dc;
                color: #946200;
            }

            .status.cancelled {
                background: #fdebea;
                color: var(--danger);
            }

            .section {
                margin-top: 28px;
            }

            .parties {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 22px;
            }

            .party-card {
                padding: 17px 19px;
                border: 1px solid var(--border);
                border-radius: 8px;
            }

            .section-label {
                margin: 0 0 10px;
                color: var(--invoice-primary);
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 1.2px;
                text-transform: uppercase;
            }

            .party-name {
                margin-bottom: 6px;
                font-size: 16px;
                font-weight: 800;
            }

            .meta-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px 18px;
                margin-top: 22px;
            }

            .meta-item {
                padding: 10px 12px;
                background: var(--surface);
                border-radius: 6px;
            }

            .meta-item .label {
                display: block;
                margin-bottom: 3px;
                color: var(--muted);
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .6px;
            }

            .meta-item .value {
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            .items-table {
                margin-top: 12px;
            }

            .items-table thead {
                display: table-header-group;
            }

            .items-table th {
                padding: 12px 10px;
                background: var(--invoice-primary);
                color: #fff;
                font-size: 10px;
                text-align: left;
                text-transform: uppercase;
                letter-spacing: .5px;
            }

            .items-table td {
                padding: 12px 10px;
                border-bottom: 1px solid var(--border);
                vertical-align: top;
            }

            .items-table tbody tr:nth-child(even) {
                background: #fafbfd;
            }

            .items-table .num {
                text-align: right;
                white-space: nowrap;
            }

            .product-name {
                font-weight: 700;
            }

            .product-details {
                margin-top: 4px;
                color: var(--muted);
                font-size: 11px;
                overflow-wrap: anywhere;
            }

            .summary-row {
                display: flex;
                justify-content: flex-end;
                margin-top: 20px;
            }

            .summary {
                width: 340px;
                max-width: 100%;
            }

            .summary-line {
                display: flex;
                justify-content: space-between;
                gap: 15px;
                padding: 8px 3px;
                border-bottom: 1px solid var(--border);
            }

            .summary-line strong {
                text-align: right;
                white-space: nowrap;
            }

            .summary-line.grand-total {
                margin-top: 5px;
                padding: 13px 12px;
                background: var(--invoice-primary);
                color: #fff;
                border-radius: 6px;
                font-size: 16px;
                font-weight: 800;
            }

            .summary-line.balance {
                color: var(--danger);
                font-weight: 800;
            }

            .summary-line.paid {
                color: var(--success);
            }

            .payment-table {
                margin-top: 12px;
            }

            .payment-table th,
            .payment-table td {
                padding: 9px;
                border: 1px solid var(--border);
                text-align: left;
            }

            .payment-table th {
                background: var(--surface);
            }

            .remarks {
                margin-top: 25px;
                padding: 14px 16px;
                background: var(--surface);
                border-left: 3px solid var(--invoice-primary);
                overflow-wrap: anywhere;
            }

            .remarks strong {
                display: block;
                margin-bottom: 5px;
            }

            .footer {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                gap: 25px;
                margin-top: 55px;
            }

            .footer-note {
                max-width: 55%;
                color: var(--muted);
                font-size: 11px;
            }

            .signature {
                width: 190px;
                padding-top: 9px;
                border-top: 1px solid #687386;
                text-align: center;
                font-size: 11px;
            }

            .thank-you {
                margin-top: 26px;
                color: var(--invoice-primary);
                font-weight: 800;
                text-align: center;
            }

            .currency {
                white-space: nowrap;
            }

            /* Classic template */
            .invoice.template-classic {
                --invoice-primary: #252525;
            }

            .template-classic .invoice-header {
                border-bottom-width: 1px;
            }

            .template-classic .party-card,
            .template-classic .meta-item {
                border-radius: 0;
            }

            .template-classic .items-table th,
            .template-classic .summary-line.grand-total {
                background: #eeeeee;
                color: #222;
            }

            .template-classic .items-table tbody tr:nth-child(even) {
                background: #fff;
            }

            /* Compact template */
            .template-compact {
                padding: 25px;
                min-height: auto;
            }

            .template-compact .invoice-header {
                padding-bottom: 15px;
            }

            .template-compact .section {
                margin-top: 17px;
            }

            .template-compact .party-card {
                padding: 11px;
            }

            .template-compact .meta-grid {
                margin-top: 14px;
            }

            .template-compact .items-table td,
            .template-compact .items-table th {
                padding: 7px 6px;
            }

            .template-compact .footer {
                margin-top: 35px;
            }

            /* Thermal-style layout */
            .template-thermal {
                width: 80mm;
                max-width: 100%;
                min-height: auto;
                margin: 0 auto;
                padding: 5mm;
                font-size: 11px;
                box-shadow: 0 5px 28px rgba(22, 34, 51, .09);
            }

            .template-thermal .invoice-header {
                display: block;
                text-align: center;
                padding-bottom: 12px;
                border-bottom: 1px dashed #555;
            }

            .template-thermal .company-block {
                display: block;
            }

            .template-thermal .company-logo {
                display: block;
                margin: 0 auto 8px;
                max-width: 65px;
                max-height: 45px;
            }

            .template-thermal .company-name {
                font-size: 18px;
            }

            .template-thermal .invoice-title {
                margin-top: 12px;
                text-align: center;
            }

            .template-thermal .invoice-title h1 {
                font-size: 22px;
            }

            .template-thermal .parties {
                display: block;
            }

            .template-thermal .party-card {
                margin-top: 12px;
                padding: 10px;
                border: 1px dashed #888;
            }

            .template-thermal .meta-grid {
                grid-template-columns: 1fr;
                gap: 5px;
                margin-top: 12px;
            }

            .template-thermal .meta-item {
                padding: 5px;
            }

            .template-thermal .section {
                margin-top: 15px;
            }

            .template-thermal .items-table th {
                background: #fff;
                color: #111;
                border-bottom: 1px solid #111;
                padding: 6px 3px;
                font-size: 9px;
            }

            .template-thermal .items-table td {
                padding: 7px 3px;
                border-bottom: 1px dashed #aaa;
                font-size: 10px;
            }

            .template-thermal .items-table .num {
                white-space: normal;
            }

            .template-thermal .summary-row {
                margin-top: 12px;
            }

            .template-thermal .summary {
                width: 100%;
            }

            .template-thermal .summary-line {
                padding: 5px 0;
                font-size: 11px;
            }

            .template-thermal .summary-line.grand-total {
                background: #fff;
                color: #111;
                border-top: 1px solid #111;
                border-bottom: 1px solid #111;
                border-radius: 0;
            }

            .template-thermal .payment-table th,
            .template-thermal .payment-table td {
                padding: 5px 3px;
                font-size: 10px;
            }

            .template-thermal .footer {
                display: block;
                margin-top: 25px;
            }

            .template-thermal .footer-note {
                max-width: 100%;
            }

            .template-thermal .signature {
                width: 100%;
                margin-top: 35px;
            }

            .template-thermal .thank-you {
                font-size: 11px;
            }

            @page {
                size: A4;
                margin: 12mm;
            }

            @media print {
                body {
                    padding: 0;
                    margin: 0;
                    background: #fff;
                    font-size: 11px;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .toolbar {
                    display: none !important;
                }

                .invoice {
                    width: 100%;
                    max-width: none;
                    min-height: 0;
                    margin: 0;
                    padding: 0;
                    box-shadow: none;
                    border: 0;
                }

                .party-card,
                .meta-item,
                .summary-line,
                .remarks,
                .items-table tr {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }

                .section {
                    break-inside: auto;
                    page-break-inside: auto;
                }

                .items-table tr {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }

                .footer {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }

                .template-thermal {
                    width: 100%;
                    max-width: none;
                    padding: 0;
                }
            }

            @media screen and (max-width: 650px) {
                body {
                    padding: 10px;
                }

                .invoice {
                    padding: 20px;
                }

                .invoice-header {
                    flex-direction: column;
                }

                .invoice-title {
                    text-align: left;
                }

                .parties {
                    grid-template-columns: 1fr;
                }

                .meta-grid {
                    grid-template-columns: 1fr 1fr;
                }

                .items-table {
                    font-size: 11px;
                }

                .items-table th,
                .items-table td {
                    padding: 7px 5px;
                }

                .footer {
                    flex-direction: column;
                    align-items: stretch;
                }

                .footer-note {
                    max-width: 100%;
                }

                .signature {
                    align-self: flex-end;
                }
            }
        </style>
    </head>

    <body>

        @php
            $company = $receipt->company;
            $branch = $receipt->branch;
            $party = $receipt->party;

            $companyName = $company->name ?? ($company->company_name ?? config('app.name'));

            $branchName = $branch->name ?? null;
            $customerName = $party->name ?? ($party->company_name ?? 'Customer');

            $orderNumber = $receipt->inv_no ?? ($receipt->so_no ?? 'SO-' . $receipt->id);

            $receiptDate = $receipt->receipt_date
                ? \Illuminate\Support\Carbon::parse($receipt->receipt_date)->format('d M Y')
                : '—';

            $items = $receipt->items ?? collect();

            $subtotal = (float) ($receipt->sub_total ?? $items->sum('amount'));

            $discount = (float) ($receipt->discount ?? 0);
            $vatPercent = (float) ($receipt->vat ?? 0);

            $vatAmount = (float) ($receipt->vat_amount ?? (($subtotal - $discount) * $vatPercent) / 100);

            $grandTotal = (float) ($receipt->total_amount ?? 0);

            $paidAmount = (float) ($receipt->paid_amount ?? ($receipt->payments?->sum('amount') ?? 0));

            $dueAmount = (float) ($receipt->due_amount ?? max(0, $grandTotal - $paidAmount));

            $currency = $receipt->currency ?? 'BDT';

            $money = function ($amount) use ($currency) {
                return $currency . ' ' . number_format((float) $amount, 2);
            };
            $branchAddress = $branch->address ?? null;
            $customerAddress = $party->address ?? null;
            $customerPhone = $party->phone ?? ($party->phone_one ?? null);

            $status = $receipt->status ?? 'Completed';
        @endphp

        <div class="toolbar">
            <div class="toolbar-left">
                <label for="invoiceTemplate">Invoice Design</label>
                <select id="invoiceTemplate" onchange="changeTemplate(this.value)">
                    <option value="modern">Modern Professional</option>
                    <option value="classic">Classic Business</option>
                    <option value="compact">Compact A4</option>
                    <option value="thermal">Thermal Receipt (80mm)</option>
                </select>
            </div>

            <div class="toolbar-right">
                <button type="button" onclick="window.print()">
                    Print Invoice
                </button>
            </div>
        </div>

        <main id="invoice" class="invoice template-modern">

            <header class="invoice-header">
                <div class="company-block">
                    @if (optional(setting())->logo)
                        <img class="company-logo" src="{{ asset('uploads/settings/' . setting()->logo) }}"
                            alt="{{ $companyName }} logo">
                    @endif
                    <div>
                        <h2 class="company-name">{{ $companyName }}</h2>

                        <div class="company-info">
                            @if ($branchName)
                                <div><strong>{{ $branchName }}</strong></div>
                            @endif

                            @if ($branchAddress ?: $companyAddress)
                                <div>{{ $branchAddress ?: $companyAddress }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="invoice-title">
                    <h1>INVOICE</h1>
                    <div class="order-number">{{ $orderNumber }}</div>

                    <span class="status {{ strtolower($status) }}">
                        {{ $status }}
                    </span>
                </div>
            </header>

            <section class="section parties">
                <div class="party-card">
                    <h3 class="section-label">Bill To</h3>

                    <div class="party-name">{{ $customerName }}</div>

                    @if ($customerAddress)
                        <div class="muted">Address: {{ $customerAddress }}</div>
                    @endif

                    @if ($customerPhone)
                        <div class="muted">Phone: {{ $customerPhone }}</div>
                    @endif

                    @if (!empty($party->email))
                        <div class="muted">Email: {{ $party->email }}</div>
                    @endif
                </div>

                <div class="party-card">
                    <h3 class="section-label">Invoice Information</h3>

                    <div>
                        <span class="muted">Invoice No:</span>
                        <strong>{{ $orderNumber }}</strong>
                    </div>

                    <div>
                        <span class="muted">Sales Order:</span>
                        <strong>{{ $receipt->so_no ?? $orderNumber }}</strong>
                    </div>

                    <div>
                        <span class="muted">Invoice Date:</span>
                        <strong>{{ $receiptDate }}</strong>
                    </div>

                    @if (!empty($receipt->created_by))
                        <div>
                            <span class="muted">Created By:</span>
                            <strong>{{ $receipt->creator->name ?? '—' }}</strong>
                        </div>
                    @endif

                    @if (!empty($receipt->customer_company_id))
                        <div>
                            <span class="muted">Customer Company ID:</span>
                            <strong>{{ $receipt->customer_company_id }}</strong>
                        </div>
                    @endif
                </div>
            </section>

            <table class="meta-table">
                <tr>
                    <td>
                        <span class="muted">Branch</span><br>
                        <strong>{{ $branch->name ?? '—' }}</strong>
                    </td>
                    <td>
                        <span class="muted">Payment Status</span><br>
                        <strong>{{ $receipt->payment_status ?? ($dueAmount <= 0 ? 'Paid' : 'Due') }}</strong>
                    </td>
                </tr>
            </table>

            <section class="section">
                <h3 class="section-label">Order Details</h3>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 36px;">#</th>
                            <th>Product / Description</th>
                            <th class="num">Qty</th>
                            <th class="num">Unit Price</th>
                            <th class="num">Amount</th>
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
                                        <div class="product-details">
                                            {{ $item->details }}
                                        </div>
                                    @endif
                                </td>

                                <td class="num">
                                    {{ rtrim(rtrim(number_format((float) $item->qty, 2, '.', ''), '0'), '.') }}
                                </td>

                                <td class="num">
                                    {{ $money($item->rate) }}
                                </td>

                                <td class="num">
                                    {{ $money($item->amount) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;">
                                    No items found for this invoice.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            <section class="summary-row">
                <div class="summary">
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <strong>{{ $money($subtotal) }}</strong>
                    </div>

                    @if ($discount > 0)
                        <div class="summary-line">
                            <span>Discount</span>
                            <strong>- {{ $money($discount) }}</strong>
                        </div>
                    @endif

                    @if ($vatPercent > 0 || $vatAmount > 0)
                        <div class="summary-line">
                            <span>VAT ({{ number_format($vatPercent, 2) }}%)</span>
                            <strong>{{ $money($vatAmount) }}</strong>
                        </div>
                    @endif

                    <div class="summary-line grand-total">
                        <span>Grand Total</span>
                        <strong>{{ $money($grandTotal) }}</strong>
                    </div>

                    <div class="summary-line paid">
                        <span>Paid Amount</span>
                        <strong>{{ $money($paidAmount) }}</strong>
                    </div>

                    <div class="summary-line balance">
                        <span>Due Amount</span>
                        <strong>{{ $money($dueAmount) }}</strong>
                    </div>
                </div>
            </section>

            @if (($receipt->payments ?? collect())->isNotEmpty())
                <section class="section">
                    <h3 class="section-label">Payment History</h3>

                    <table class="payment-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Account</th>
                                <th>Received By</th>
                                <th style="text-align:right;">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($receipt->payments as $payment)
                                <tr>
                                    <td>
                                        {{ !empty($payment->payment_date)
                                            ? \Illuminate\Support\Carbon::parse($payment->payment_date)->format('d M Y')
                                            : '—' }}
                                    </td>

                                    <td>
                                        {{ $payment->paymentType->name ?? ($payment->payment_type ?? '—') }}
                                    </td>

                                    <td>
                                        {{ $payment->account->name ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $payment->user->name ?? '—' }}
                                    </td>

                                    <td style="text-align:right;">
                                        {{ $money($payment->amount ?? 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endif

            @if (!empty($receipt->remarks))
                <div class="remarks">
                    <strong>Remarks / Notes</strong>
                    {{ $receipt->remarks }}
                </div>
            @endif

            <footer class="footer">
                <div class="footer-note">
                    <strong>Terms &amp; Conditions</strong>
                    <p>
                        Please check the items and amounts listed above.
                        Contact us if you have any questions regarding this invoice.
                    </p>
                    <p>Thank you for your business.</p>
                </div>

                <div class="signature">
                    Authorized Signature
                </div>
            </footer>

            <div class="thank-you">
                Thank you for choosing {{ $companyName }}!
            </div>

        </main>

        <script>
            function changeTemplate(template) {
                const invoice = document.getElementById('invoice');

                invoice.classList.remove(
                    'template-modern',
                    'template-classic',
                    'template-compact',
                    'template-thermal'
                );

                invoice.classList.add('template-' + template);

                if (template === 'thermal') {
                    document.body.classList.add('thermal-print');
                } else {
                    document.body.classList.remove('thermal-print');
                }
            }
        </script>

    </body>

</html>
