<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Expense Receipt - {{ $receipt->receipt_no }}</title>

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
                font-family: DejaVu Sans, sans-serif;
                font-size: 10px;
                line-height: 1.55;
                color: #273244;
            }

            .receipt {
                --primary: #b54708;
                --light: #fff4e5;
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

            .receipt-heading {
                float: right;
                width: 39%;
                text-align: right;
            }

            .receipt-heading h1 {
                margin: 0;
                color: var(--primary);
                font-size: 25px;
                line-height: 1.3;
            }

            .receipt-number {
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
                text-transform: uppercase;
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

            .details-table,
            .meta-table,
            .items-table,
            .payments-table,
            .totals-table,
            .signatures {
                width: 100%;
                border-collapse: collapse;
            }

            .details-table {
                margin-top: 19px;
            }

            .details-table td {
                width: 50%;
                padding: 12px;
                vertical-align: top;
                border: 1px solid var(--border);
            }

            .details-label {
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
                margin-top: 12px;
            }

            .meta-table td {
                width: 33.33%;
                padding: 8px;
                border: 1px solid var(--border);
                background: #f6f8fb;
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
                margin-top: 10px;
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
                background: #fafbfd;
            }

            .items-table tr,
            .payments-table tr {
                page-break-inside: avoid;
            }

            .number {
                text-align: right;
                white-space: nowrap;
            }

            .item-name {
                font-weight: bold;
            }

            .item-details {
                margin-top: 3px;
                color: var(--muted);
                font-size: 8px;
                overflow-wrap: anywhere;
            }

            .totals-wrap {
                margin-top: 18px;
            }

            .totals-spacer {
                float: left;
                width: 45%;
            }

            .totals {
                float: right;
                width: 55%;
            }

            .totals-table td {
                padding: 7px 8px;
                border-bottom: 1px solid var(--border);
            }

            .totals-table .grand-total td {
                padding: 11px 9px;
                background: var(--primary);
                color: #fff;
                font-size: 12px;
                font-weight: bold;
            }

            .totals-table .paid td {
                color: #16804a;
            }

            .totals-table .due td {
                color: #b42318;
                font-weight: bold;
            }

            .payments-table {
                margin-top: 9px;
            }

            .payments-table th,
            .payments-table td {
                padding: 7px;
                border: 1px solid var(--border);
                text-align: left;
                vertical-align: top;
                overflow-wrap: anywhere;
            }

            .payments-table th {
                background: #f2f4f8;
                font-size: 8px;
            }

            .remarks {
                margin-top: 21px;
                padding: 11px 13px;
                border-left: 3px solid var(--primary);
                background: #f6f8fb;
                overflow-wrap: anywhere;
            }

            .remarks-title {
                margin-bottom: 4px;
                font-weight: bold;
            }

            .approval-note {
                margin-top: 18px;
                padding: 12px;
                border: 1px dashed #c5ceda;
                font-size: 9px;
            }

            .signature-grid {
                width: 100%;
                margin-top: 65px;
                border-collapse: separate;
                border-spacing: 14px 0;
            }

            .signature-grid td {
                width: 33.33%;
                padding-top: 8px;
                border-top: 1px solid #687386;
                text-align: center;
                vertical-align: top;
                font-size: 9px;
                font-weight: bold;
            }

            .signature-grid small {
                display: block;
                margin-top: 4px;
                color: var(--muted);
                font-size: 8px;
                font-weight: normal;
            }

            .footer {
                margin-top: 24px;
                padding-top: 10px;
                border-top: 1px solid var(--border);
                color: var(--muted);
                font-size: 8px;
                text-align: center;
            }

            /* CLASSIC */
            .design-classic {
                --primary: #292929;
                --light: #f3f3f3;
            }

            .design-classic .top-line {
                height: 2px;
            }

            .design-classic .details-table td {
                border-color: #999;
            }

            .design-classic .meta-table td {
                background: #fff;
            }

            .design-classic .items-table th {
                background: #eee;
                color: #222;
                border-bottom: 1px solid #555;
            }

            .design-classic .items-table tbody tr:nth-child(even) {
                background: #fff;
            }

            .design-classic .totals-table .grand-total td {
                background: #eee;
                color: #222;
                border-top: 1px solid #555;
            }

            .design-classic .remarks {
                background: #fff;
                border: 1px solid #aaa;
                border-left: 3px solid #444;
            }

            /* COMPACT */
            .design-compact .top-line {
                margin-bottom: 12px;
            }

            .design-compact .company-name {
                font-size: 17px;
            }

            .design-compact .receipt-heading h1 {
                font-size: 21px;
            }

            .design-compact .details-table {
                margin-top: 12px;
            }

            .design-compact .details-table td {
                padding: 8px;
            }

            .design-compact .section {
                margin-top: 15px;
            }

            .design-compact .meta-table td {
                padding: 6px;
            }

            .design-compact .items-table th,
            .design-compact .items-table td {
                padding: 6px 5px;
            }

            .design-compact .totals-wrap {
                margin-top: 10px;
            }

            .design-compact .signature-grid {
                margin-top: 45px;
            }

            /* PAYMENT VOUCHER */
            .design-voucher .receipt-heading h1 {
                font-size: 22px;
            }

            .design-voucher .receipt-header {
                border-bottom: 5px double var(--primary);
            }

            .design-voucher .receipt-heading h1:after {
                content: "PAYMENT VOUCHER";
                display: block;
                margin-top: 4px;
                font-size: 8px;
                letter-spacing: 2px;
            }

            .design-voucher .approval-note {
                padding: 15px;
                border: 1px solid #aaa;
                background: #fcfcfc;
            }

            .design-voucher .totals-table .grand-total td {
                background: #7c3e16;
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
            $payments = $receipt->payments ?? collect();

            $companyName = $company->name ?? ($company->company_name ?? config('app.name'));

            $partyName = $party->name ?? ($party->company_name ?? 'Payee');

            $receiptNo = $receipt->receipt_no ?? 'EXP-' . $receipt->id;

            $receiptDate = $receipt->receipt_date
                ? \Illuminate\Support\Carbon::parse($receipt->receipt_date)->format('d M Y')
                : '—';

            $status = $receipt->status ?? 'Completed';

            $design = in_array($design ?? 'modern', ['modern', 'classic', 'compact', 'voucher'], true)
                ? $design
                : 'modern';

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
        @endphp

        <div class="receipt design-{{ $design }}">

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
                <div class="receipt-heading">
                    <h1>EXPENSE RECEIPT</h1>
                    <div class="receipt-number">{{ $receiptNo }}</div>
                    <div class="muted">Date: {{ $receiptDate }}</div>
                    <span
                        class="status
                {{ strtolower($status) === 'draft' ? 'status-draft' : '' }}
                {{ strtolower($status) === 'cancelled' ? 'status-cancelled' : '' }}">
                        {{ $status }}
                    </span>
                </div>
            </div>
            <hr class="divider">
            <table class="details-table">
                <tr>
                    <td>
                        <div class="details-label">Paid To / Payee</div>
                        <div class="party-name">{{ $partyName }}</div>
                        @if ($party && $party->address)
                            <div>Address: {{ $party->address }}</div>
                        @endif
                        @if ($partyPhone)
                            <div>Phone: {{ $partyPhone }}</div>
                        @endif
                        @if ($party && $party->email)
                            <div>Email: {{ $party->email }}</div>
                        @endif
                    </td>

                    <td>
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
                    </td>
                </tr>
            </table>

            <table class="meta-table">
                <tr>
                    <td>
                        <span class="meta-label">Expense Category</span>
                        <div class="meta-value">
                            @if ($items->isNotEmpty())
                                {{ $items->first()->category->name ?? ($items->first()->accountHead->name ?? 'General Expense') }}
                            @else
                                General Expense
                            @endif
                        </div>
                    </td>

                    <td>
                        <span class="meta-label">Payment Status</span>
                        <div class="meta-value">
                            {{ $receipt->payment_status ?? ($dueAmount <= 0 ? 'Paid' : 'Pending') }}
                        </div>
                    </td>

                    <td>
                        <span class="meta-label">Total Lines</span>
                        <div class="meta-value">{{ $items->count() }}</div>
                    </td>
                </tr>
            </table>

            <section class="section">
                <h3 class="section-title">Expense Details</h3>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">SN</th>
                            <th style="width: 39%;">Description</th>
                            <th style="width: 21%;">Category / Head</th>
                            <th class="number" style="width: 11%;">Qty</th>
                            <th class="number" style="width: 23%;">Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="item-name">
                                        {{ $item->details ?: $item->category->name ?? ($item->accountHead->name ?? ($item->product->name ?? 'Expense')) }}
                                    </div>
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
            </section>

            <div class="totals-wrap clearfix">
                <div class="totals-spacer"></div>

                <div class="totals">
                    <table class="totals-table">
                        <tr>
                            <td>Subtotal</td>
                            <td class="number">{{ $money($subtotal) }}</td>
                        </tr>

                        @if ($discount > 0)
                            <tr>
                                <td>Discount</td>
                                <td class="number">- {{ $money($discount) }}</td>
                            </tr>
                        @endif

                        @if ($vatPercent > 0 || $vatAmount > 0)
                            <tr>
                                <td>VAT ({{ number_format($vatPercent, 2) }}%)</td>
                                <td class="number">{{ $money($vatAmount) }}</td>
                            </tr>
                        @endif

                        <tr class="grand-total">
                            <td>Total Expense</td>
                            <td class="number">{{ $money($grandTotal) }}</td>
                        </tr>

                        <tr class="paid">
                            <td>Paid Amount</td>
                            <td class="number">{{ $money($paidAmount) }}</td>
                        </tr>

                        <tr class="due">
                            <td>Due Amount</td>
                            <td class="number">{{ $money($dueAmount) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if ($payments->isNotEmpty())
                <section class="section">
                    <h3 class="section-title">Payment History</h3>

                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Account</th>
                                <th>Processed By</th>
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
                <div class="remarks">
                    <div class="remarks-title">Remarks / Expense Purpose</div>
                    {{ $receipt->remarks }}
                </div>
            @endif

            <div class="approval-note">
                <strong>Approval / Verification</strong>
                <p>
                    This receipt documents the expense transaction listed above.
                    Please retain it for accounting, verification and audit purposes.
                </p>
            </div>

            <table class="signature-grid">
                <tr>
                    <td>
                        Prepared By
                        <small>{{ $receipt->creator->name ?? 'Name & Signature' }}</small>
                    </td>

                    <td>
                        Checked / Approved By
                        <small>Name, Signature &amp; Date</small>
                    </td>

                    <td>
                        Received By
                        <small>Name, Signature &amp; Date</small>
                    </td>
                </tr>
            </table>

            <div class="footer">
                {{ $companyName }} · Expense Receipt · {{ $receiptNo }}
            </div>

        </div>

    </body>

</html>
