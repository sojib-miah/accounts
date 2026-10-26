<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Expense Receipt - {{ $receipt->receipt_no }}</title>

        <style>
            :root {
                --primary: #b54708;
                --primary-light: #fff4e5;
                --text: #273244;
                --muted: #687386;
                --border: #dce3ec;
                --light: #f6f8fb;
                --danger: #b42318;
                --success: #16804a;
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
                line-height: 1.55;
            }

            .toolbar {
                max-width: 1000px;
                margin: 0 auto 18px;
                padding: 14px 18px;
                background: #fff;
                border: 1px solid var(--border);
                border-radius: 9px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
            }

            .toolbar-group {
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
                padding: 8px 13px;
                border-radius: 6px;
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

            .receipt {
                --receipt-primary: #b54708;
                --receipt-light: #fff4e5;
                max-width: 1000px;
                min-height: 850px;
                margin: 0 auto;
                padding: 40px;
                background: #fff;
                box-shadow: 0 5px 28px rgba(22, 34, 51, .09);
            }

            .receipt-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 24px;
                padding-bottom: 23px;
                border-bottom: 3px solid var(--receipt-primary);
            }

            .company-block {
                display: flex;
                align-items: flex-start;
                gap: 14px;
                flex: 1;
                min-width: 0;
            }

            .company-logo {
                max-width: 95px;
                max-height: 72px;
                object-fit: contain;
            }

            .company-name {
                margin: 0 0 7px;
                color: var(--receipt-primary);
                font-size: 23px;
                font-weight: 800;
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

            .receipt-heading {
                flex: 0 0 auto;
                text-align: right;
            }

            .receipt-heading h1 {
                margin: 0;
                color: var(--receipt-primary);
                font-size: 29px;
                line-height: 1.2;
                letter-spacing: 1.2px;
            }

            .receipt-number {
                margin-top: 8px;
                font-size: 14px;
                font-weight: 800;
                overflow-wrap: anywhere;
            }

            .status {
                display: inline-block;
                margin-top: 8px;
                padding: 4px 10px;
                border-radius: 20px;
                background: #e7f7ee;
                color: var(--success);
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
                color: var(--danger);
            }

            .section {
                margin-top: 25px;
            }

            .section-title {
                margin: 0 0 11px;
                color: var(--receipt-primary);
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            .details-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 18px;
            }

            .details-card {
                padding: 16px;
                border: 1px solid var(--border);
                border-radius: 7px;
                min-width: 0;
            }

            .details-label {
                margin-bottom: 8px;
                color: var(--receipt-primary);
                font-size: 10px;
                font-weight: 800;
                letter-spacing: .7px;
                text-transform: uppercase;
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
                grid-template-columns: repeat(3, minmax(0, 1fr));
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

            .items-table,
            .payments-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 11px;
            }

            .items-table thead {
                display: table-header-group;
            }

            .items-table th {
                padding: 11px 8px;
                background: var(--receipt-primary);
                color: #fff;
                font-size: 10px;
                text-align: left;
                text-transform: uppercase;
            }

            .items-table td {
                padding: 11px 8px;
                border-bottom: 1px solid var(--border);
                vertical-align: top;
                overflow-wrap: anywhere;
            }

            .items-table tbody tr:nth-child(even) {
                background: #fafbfd;
            }

            .number {
                text-align: right;
                white-space: nowrap;
            }

            .item-name {
                font-weight: 700;
            }

            .item-details {
                margin-top: 4px;
                color: var(--muted);
                font-size: 11px;
                overflow-wrap: anywhere;
            }

            .amount-in-words {
                margin-top: 20px;
                padding: 13px 15px;
                background: var(--receipt-light);
                border-left: 3px solid var(--receipt-primary);
            }

            .amount-in-words strong {
                display: block;
                margin-bottom: 4px;
                color: var(--receipt-primary);
            }

            .totals-wrap {
                display: flex;
                justify-content: flex-end;
                margin-top: 20px;
            }

            .totals {
                width: 360px;
                max-width: 100%;
            }

            .total-line {
                display: flex;
                justify-content: space-between;
                gap: 16px;
                padding: 8px 3px;
                border-bottom: 1px solid var(--border);
            }

            .total-line strong {
                text-align: right;
                white-space: nowrap;
            }

            .total-line.grand-total {
                margin-top: 5px;
                padding: 13px 12px;
                background: var(--receipt-primary);
                color: #fff;
                border-radius: 6px;
                font-size: 15px;
                font-weight: 800;
            }

            .total-line.paid {
                color: var(--success);
            }

            .total-line.due {
                color: var(--danger);
                font-weight: 800;
            }

            .payments-table th,
            .payments-table td {
                padding: 9px;
                border: 1px solid var(--border);
                text-align: left;
                vertical-align: top;
            }

            .payments-table th {
                background: var(--light);
                font-size: 10px;
            }

            .remarks {
                margin-top: 23px;
                padding: 14px 16px;
                background: var(--light);
                border-left: 3px solid var(--receipt-primary);
                overflow-wrap: anywhere;
            }

            .remarks-title {
                margin-bottom: 5px;
                font-weight: 800;
            }

            .approval-note {
                margin-top: 20px;
                padding: 12px 14px;
                border: 1px dashed #c5ceda;
                color: #4b5563;
                font-size: 11px;
            }

            .signature-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 25px;
                margin-top: 75px;
            }

            .signature {
                padding-top: 9px;
                border-top: 1px solid #687386;
                text-align: center;
                font-size: 11px;
                font-weight: 700;
            }

            .signature small {
                display: block;
                margin-top: 4px;
                color: var(--muted);
                font-size: 10px;
                font-weight: 400;
            }

            .footer {
                margin-top: 30px;
                padding-top: 12px;
                border-top: 1px solid var(--border);
                color: var(--muted);
                font-size: 10px;
                text-align: center;
            }

            /* CLASSIC */
            .design-classic {
                --receipt-primary: #292929;
                --receipt-light: #f4f4f4;
            }

            .design-classic .receipt-header {
                border-bottom-width: 1px;
            }

            .design-classic .details-card {
                border-radius: 0;
                border-color: #999;
            }

            .design-classic .meta-item {
                border: 1px solid #ddd;
                border-radius: 0;
                background: #fff;
            }

            .design-classic .items-table th {
                background: #eee;
                color: #222;
                border-bottom: 1px solid #444;
            }

            .design-classic .items-table tbody tr:nth-child(even) {
                background: #fff;
            }

            .design-classic .total-line.grand-total {
                background: #eee;
                color: #222;
                border-top: 1px solid #555;
                border-bottom: 1px solid #555;
                border-radius: 0;
            }

            .design-classic .remarks {
                background: #fff;
                border: 1px solid #aaa;
                border-left: 3px solid #444;
            }

            /* COMPACT */
            .design-compact {
                min-height: auto;
                padding: 25px;
            }

            .design-compact .receipt-header {
                padding-bottom: 14px;
            }

            .design-compact .company-name {
                font-size: 19px;
            }

            .design-compact .receipt-heading h1 {
                font-size: 24px;
            }

            .design-compact .section {
                margin-top: 16px;
            }

            .design-compact .details-card {
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
                padding: 7px 5px;
            }

            .design-compact .signature-grid {
                margin-top: 52px;
            }

            /* PAYMENT VOUCHER */
            .design-voucher .receipt-header {
                border-bottom: 5px double var(--receipt-primary);
            }

            .design-voucher .receipt-heading h1 {
                font-size: 25px;
            }

            .design-voucher .receipt-heading h1:after {
                content: "PAYMENT VOUCHER";
                display: block;
                margin-top: 5px;
                font-size: 9px;
                letter-spacing: 2px;
            }

            .design-voucher .amount-in-words {
                border: 1px solid #d6a875;
                border-left: 4px solid var(--receipt-primary);
            }

            .design-voucher .approval-note {
                padding: 17px;
                border: 1px solid #aaa;
                background: #fcfcfc;
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

                .receipt {
                    width: 100%;
                    max-width: none;
                    min-height: 0;
                    margin: 0;
                    padding: 0;
                    box-shadow: none;
                }

                .details-card,
                .meta-item,
                .amount-in-words,
                .total-line,
                .remarks,
                .approval-note,
                .signature-grid {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }

                .items-table tr,
                .payments-table tr {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }
            }

            @media screen and (max-width: 650px) {
                body {
                    padding: 10px;
                }

                .receipt {
                    padding: 20px;
                }

                .receipt-header {
                    flex-direction: column;
                }

                .receipt-heading {
                    text-align: left;
                }

                .details-grid {
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
            $payments = $receipt->payments ?? collect();

            $companyName = $company->name ?? ($company->company_name ?? config('app.name'));

            $partyName = $party->name ?? ($party->company_name ?? 'Payee');

            $receiptNo = $receipt->receipt_no ?? 'EXP-' . $receipt->id;

            $receiptDate = $receipt->receipt_date
                ? \Illuminate\Support\Carbon::parse($receipt->receipt_date)->format('d M Y')
                : '—';

            $status = $receipt->status ?? 'Completed';

            $design = request()->query('design', 'modern');

            if (!in_array($design, ['modern', 'classic', 'compact', 'voucher'], true)) {
                $design = 'modern';
            }

            $subtotal = (float) ($receipt->sub_total ?? $items->sum('amount'));

            $discount = (float) ($receipt->discount ?? 0);
            $vatPercent = (float) ($receipt->vat ?? 0);

            $vatAmount = (float) ($receipt->vat_amount ?? (($subtotal - $discount) * $vatPercent) / 100);

            $grandTotal = (float) ($receipt->total_amount ?? $items->sum('amount'));

            $paidAmount = (float) ($receipt->paid_amount ?? $payments->sum('amount'));

            $dueAmount = (float) ($receipt->due_amount ?? max(0, $grandTotal - $paidAmount));

            $currency = $receipt->currency ?? 'BDT';

            $money = function ($amount) use ($currency) {
                return $currency . ' ' . number_format((float) $amount, 2);
            };

            $formatQty = function ($qty) {
                return rtrim(rtrim(number_format((float) $qty, 2, '.', ''), '0'), '.');
            };
            $partyPhone = $party->phone ?? ($party->phone_one ?? null);
            $totalQty = (float) ($receipt->total_qty ?? $items->sum('qty'));
        @endphp

        <div class="toolbar">
            <div class="toolbar-group">
                <label for="receiptDesign">Receipt Design</label>

                <select id="receiptDesign" onchange="changeDesign(this.value)">
                    <option value="modern" {{ $design === 'modern' ? 'selected' : '' }}>
                        Modern Expense Receipt
                    </option>
                    <option value="classic" {{ $design === 'classic' ? 'selected' : '' }}>
                        Classic Receipt
                    </option>
                    <option value="compact" {{ $design === 'compact' ? 'selected' : '' }}>
                        Compact Receipt
                    </option>
                    <option value="voucher" {{ $design === 'voucher' ? 'selected' : '' }}>
                        Payment Voucher
                    </option>
                </select>
            </div>

            <div class="toolbar-group">
                <button type="button" onclick="window.print()">
                    Print Receipt
                </button>
            </div>
        </div>

        <main id="expenseReceipt" class="receipt design-{{ $design }}">

            <header class="receipt-header">
                <div class="company-block">
                    @if (optional(setting())->logo)
                        <div class="company-logo">
                            <img class="company-logo" src="{{ asset('uploads/settings/' . setting()->logo) }}"
                                alt="Logo">
                        </div>
                    @endif
                    <div>
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
                </div>

                <div class="receipt-heading">
                    <h1>EXPENSE RECEIPT</h1>

                    <div class="receipt-number">{{ $receiptNo }}</div>

                    <div class="muted">Date: {{ $receiptDate }}</div>

                    <span
                        class="status
                {{ strtolower($status) === 'draft' ? 'draft' : '' }}
                {{ strtolower($status) === 'cancelled' ? 'cancelled' : '' }}">
                        {{ $status }}
                    </span>
                </div>
            </header>

            <section class="section details-grid">
                <div class="details-card">
                    <div class="details-label">Paid To / Payee</div>

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

                <div class="details-card">
                    <div class="details-label">Receipt Information</div>

                    <div>
                        <span class="muted">Receipt No:</span>
                        <strong>{{ $receiptNo }}</strong>
                    </div>

                    <div>
                        <span class="muted">Expense Date:</span>
                        <strong>{{ $receiptDate }}</strong>
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
                        <span class="meta-label">Expense Category</span>
                        <div class="meta-value">
                            @if ($items->isNotEmpty())
                                {{ $items->first()->category->name ?? ($items->first()->accountHead->name ?? 'General Expense') }}
                            @else
                                General Expense
                            @endif
                        </div>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Payment Status</span>
                        <div class="meta-value">
                            {{ $receipt->payment_status ?? ($dueAmount <= 0 ? 'Paid' : 'Pending') }}
                        </div>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Total Items</span>
                        <div class="meta-value">{{ $items->count() }}</div>
                    </div>
                </div>
            </section>

            <section class="section">
                <h3 class="section-title">Expense Details</h3>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">SL</th>
                            <th style="width: 38%;">Expense Description</th>
                            <th style="width: 20%;">Category / Head</th>
                            <th class="number" style="width: 12%;">Qty</th>
                            <th class="number" style="width: 24%;">Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="item-name">
                                        {{ $item->details ?: $item->category->name ?? ($item->accountHead->name ?? 'Expense') }}
                                    </div>

                                    @if ($item->details && $item->category)
                                        <div class="item-details">
                                            Category: {{ $item->category->name }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->category->name ?? ($item->accountHead->name ?? 'General') }}
                                </td>

                                <td class="number">{{ $formatQty($item->qty) }}</td>

                                <td class="number">{{ $money($item->amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;">
                                    No expense items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="totals-wrap">
                    <div class="totals">
                        <div class="total-line">
                            <span>Subtotal</span>
                            <strong>{{ $money($subtotal) }}</strong>
                        </div>

                        @if ($discount > 0)
                            <div class="total-line">
                                <span>Discount</span>
                                <strong>- {{ $money($discount) }}</strong>
                            </div>
                        @endif

                        @if ($vatPercent > 0 || $vatAmount > 0)
                            <div class="total-line">
                                <span>VAT ({{ number_format($vatPercent, 2) }}%)</span>
                                <strong>{{ $money($vatAmount) }}</strong>
                            </div>
                        @endif

                        <div class="total-line grand-total">
                            <span>Total Expense</span>
                            <strong>{{ $money($grandTotal) }}</strong>
                        </div>

                        <div class="total-line paid">
                            <span>Paid Amount</span>
                            <strong>{{ $money($paidAmount) }}</strong>
                        </div>

                        <div class="total-line due">
                            <span>Due Amount</span>
                            <strong>{{ $money($dueAmount) }}</strong>
                        </div>
                    </div>
                </div>
            </section>

            @if ($payments->isNotEmpty())
                <section class="section">
                    <h3 class="section-title">Payment Details</h3>

                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Account</th>
                                <th>Received / Processed By</th>
                                <th class="number">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>
                                        {{ $payment->payment_date ? \Illuminate\Support\Carbon::parse($payment->payment_date)->format('d M Y') : '—' }}
                                    </td>

                                    <td>
                                        {{ $payment->paymentType->name ?? ($payment->payment_type ?? '—') }}
                                    </td>

                                    <td>{{ $payment->account->name ?? '—' }}</td>

                                    <td>{{ $payment->user->name ?? '—' }}</td>

                                    <td class="number">
                                        {{ $money($payment->amount ?? 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endif

            @if (!empty($receipt->remarks))
                <section class="section remarks">
                    <div class="remarks-title">Remarks / Expense Purpose</div>
                    {{ $receipt->remarks }}
                </section>
            @endif

            <div class="amount-in-words">
                <strong>Expense Acknowledgement</strong>
                This receipt records the expense transaction shown above.
                Please retain it for accounting and audit purposes.
            </div>

            <div class="approval-note">
                <strong>Approval / Verification</strong>
                <p>
                    I confirm that the expense details stated in this receipt have
                    been submitted for verification and approval according to the
                    company's internal procedures.
                </p>
            </div>

            <section class="signature-grid">
                <div class="signature">
                    Prepared By
                    <small>{{ $receipt->creator->name ?? 'Name & Signature' }}</small>
                </div>

                <div class="signature">
                    Checked / Approved By
                    <small>Name, Signature &amp; Date</small>
                </div>

                <div class="signature">
                    Received By
                    <small>Name, Signature &amp; Date</small>
                </div>
            </section>

            <footer class="footer">
                {{ $companyName }} · Expense Receipt · {{ $receiptNo }}
            </footer>

        </main>

        <script>
            function changeDesign(design) {
                const allowed = ['modern', 'classic', 'compact', 'voucher'];

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
