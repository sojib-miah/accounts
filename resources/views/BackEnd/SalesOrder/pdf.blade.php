<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">

        <title>Sales Invoice - {{ $receipt->inv_no ?? $receipt->so_no }}</title>

        <style>
            @page {
                size: A4 portrait;
                margin: 25px 30px;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: DejaVu Sans, sans-serif;
                font-size: 10px;
                line-height: 1.55;
                color: #263244;
            }

            .invoice {
                width: 100%;
            }

            .clearfix:after {
                content: "";
                display: table;
                clear: both;
            }

            .muted {
                color: #6b7280;
            }

            .small {
                font-size: 9px;
            }

            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .bold {
                font-weight: bold;
            }

            .top-line {
                height: 5px;
                margin-bottom: 23px;
                background: #185abc;
            }

            .company {
                float: left;
                width: 58%;
            }

            .brand-name {
                margin: 0 0 7px;
                font-size: 23px;
                font-weight: bold;
                color: #185abc;
            }

            .logo {
                max-width: 90px;
                max-height: 58px;
                margin-bottom: 8px;
            }

            .invoice-heading {
                float: right;
                width: 40%;
                text-align: right;
            }

            .invoice-heading h1 {
                margin: 0;
                font-size: 29px;
                letter-spacing: 2px;
                color: #185abc;
            }

            .invoice-number {
                margin-top: 8px;
                font-size: 12px;
                font-weight: bold;
                overflow-wrap: anywhere;
            }

            .status {
                display: inline-block;
                margin-top: 7px;
                padding: 4px 9px;
                border-radius: 10px;
                background: #e6f6ed;
                color: #16804a;
                font-size: 8px;
                font-weight: bold;
            }

            .status-draft {
                background: #fff0ce;
                color: #946200;
            }

            .status-cancelled {
                background: #fde8e7;
                color: #b42318;
            }

            .divider {
                border: 0;
                border-top: 2px solid #185abc;
                margin: 18px 0;
            }

            .section-title {
                margin: 0 0 10px;
                color: #185abc;
                font-size: 10px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .details-table,
            .items-table,
            .payments-table,
            .summary-table {
                width: 100%;
                border-collapse: collapse;
            }

            .details-table {
                margin-top: 20px;
            }

            .details-table td {
                width: 50%;
                padding: 13px;
                vertical-align: top;
                border: 1px solid #dfe5ee;
            }

            .detail-heading {
                margin-bottom: 7px;
                color: #185abc;
                font-size: 9px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .customer-name {
                margin-bottom: 5px;
                font-size: 13px;
                font-weight: bold;
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

            .items-table {
                margin-top: 10px;
            }

            .items-table thead {
                display: table-header-group;
            }

            .items-table th {
                padding: 10px 7px;
                background: #185abc;
                color: #fff;
                font-size: 8px;
                text-align: left;
                text-transform: uppercase;
            }

            .items-table td {
                padding: 9px 7px;
                border-bottom: 1px solid #dfe5ee;
                vertical-align: top;
            }

            .items-table tbody tr:nth-child(even) {
                background: #f7f9fc;
            }

            .number {
                text-align: right;
                white-space: nowrap;
            }

            .item-name {
                font-weight: bold;
            }

            .item-description {
                margin-top: 3px;
                font-size: 8px;
                color: #6b7280;
            }

            .totals-wrap {
                margin-top: 18px;
            }

            .totals-spacer {
                float: left;
                width: 49%;
            }

            .totals {
                float: right;
                width: 49%;
            }

            .summary-table td {
                padding: 7px 8px;
                border-bottom: 1px solid #e1e6ed;
            }

            .summary-table .grand-total td {
                padding: 11px 9px;
                background: #185abc;
                color: #fff;
                font-size: 13px;
                font-weight: bold;
            }

            .summary-table .paid td {
                color: #16804a;
            }

            .summary-table .due td {
                color: #b42318;
                font-weight: bold;
            }

            .payments-section {
                margin-top: 25px;
            }

            .payments-table {
                margin-top: 9px;
            }

            .payments-table th,
            .payments-table td {
                padding: 7px;
                border: 1px solid #dfe5ee;
                text-align: left;
            }

            .payments-table th {
                background: #f0f3f8;
                font-size: 8px;
            }

            .remarks {
                margin-top: 22px;
                padding: 11px 13px;
                border-left: 3px solid #185abc;
                background: #f5f7fb;
            }

            .footer {
                margin-top: 45px;
            }

            .footer-left {
                float: left;
                width: 58%;
                color: #6b7280;
                font-size: 9px;
            }

            .signature {
                float: right;
                width: 35%;
                padding-top: 9px;
                border-top: 1px solid #687386;
                text-align: center;
                font-size: 9px;
            }

            .thank-you {
                margin-top: 25px;
                padding-top: 10px;
                border-top: 1px solid #e0e5ed;
                text-align: center;
                font-size: 10px;
                font-weight: bold;
                color: #185abc;
            }

            /* Classic design */
            .design-classic .top-line {
                height: 2px;
                background: #333;
            }

            .design-classic .brand-name,
            .design-classic .invoice-heading h1,
            .design-classic .section-title,
            .design-classic .detail-heading,
            .design-classic .thank-you {
                color: #222;
            }

            .design-classic .divider {
                border-top: 1px solid #444;
            }

            .design-classic .items-table th {
                background: #eeeeee;
                color: #222;
                border-bottom: 1px solid #444;
            }

            .design-classic .items-table tbody tr:nth-child(even) {
                background: #fff;
            }

            .design-classic .summary-table .grand-total td {
                background: #eeeeee;
                color: #222;
                border-top: 1px solid #444;
                border-bottom: 1px solid #444;
            }

            .design-classic .details-table td {
                border-color: #999;
            }

            /* Compact design */
            .design-compact .top-line {
                margin-bottom: 12px;
            }

            .design-compact .brand-name {
                font-size: 18px;
            }

            .design-compact .invoice-heading h1 {
                font-size: 24px;
            }

            .design-compact .details-table {
                margin-top: 12px;
            }

            .design-compact .details-table td {
                padding: 8px;
            }

            .design-compact .meta-table {
                margin-top: 7px;
            }

            .design-compact .meta-table td {
                padding: 5px 7px;
            }

            .design-compact .items-table th {
                padding: 7px 5px;
            }

            .design-compact .items-table td {
                padding: 6px 5px;
            }

            .design-compact .totals-wrap {
                margin-top: 10px;
            }

            .design-compact .payments-section {
                margin-top: 15px;
            }

            .design-compact .footer {
                margin-top: 25px;
            }

            .design-compact .thank-you {
                margin-top: 15px;
            }

            /* Keep important rows together where supported */
            tr {
                page-break-inside: avoid;
            }

            .details-table,
            .meta-table,
            .summary-table {
                page-break-inside: avoid;
            }
        </style>
    </head>

    <body>
        @php
            $company = $receipt->company;
            $branch = $receipt->branch;
            $party = $receipt->party;

            $companyName = $company->name ?? ($company->company_name ?? config('app.name'));

            $customerName = $party->name ?? ($party->company_name ?? 'Customer');

            $orderNumber = $receipt->inv_no ?? ($receipt->so_no ?? 'SO-' . $receipt->id);

            $receiptDate = $receipt->receipt_date
                ? \Illuminate\Support\Carbon::parse($receipt->receipt_date)->format('d M Y')
                : '—';

            $items = $receipt->items ?? collect();
            $payments = $receipt->payments ?? collect();

            $subtotal = (float) ($receipt->sub_total ?? $items->sum('amount'));
            $discount = (float) ($receipt->discount ?? 0);
            $vatPercent = (float) ($receipt->vat ?? 0);

            $vatAmount = (float) ($receipt->vat_amount ?? (($subtotal - $discount) * $vatPercent) / 100);

            $grandTotal = (float) ($receipt->total_amount ?? 0);
            $paidAmount = (float) ($receipt->paid_amount ?? $payments->sum('amount'));
            $dueAmount = (float) ($receipt->due_amount ?? max(0, $grandTotal - $paidAmount));

            $currency = $receipt->currency ?? 'BDT';

            $money = function ($amount) use ($currency) {
                return $currency . ' ' . number_format((float) $amount, 2);
            };

            $status = $receipt->status ?? 'Completed';

            $design = in_array($design ?? 'modern', ['modern', 'classic', 'compact'], true) ? $design : 'modern';
        @endphp

        <div class="invoice design-{{ $design }}">
            <div class="top-line"></div>
            <div class="clearfix">
                <div class="company">
                    @if (optional(setting())->logo)
                        <img class="logo" src="{{ public_path('uploads/settings/' . setting()->logo) }}" alt="Logo">
                    @endif
                    <h2 class="brand-name">{{ $companyName }}</h2>
                    @if ($branch && $branch->name)
                        <div class="bold">{{ $branch->name }}</div>
                    @endif
                    @if ($branch && $branch->address)
                        <div>{{ $branch->address }}</div>
                    @endif
                </div>
                <div class="invoice-heading">
                    <h1>INVOICE</h1>
                    <div class="invoice-number">{{ $orderNumber }}</div>
                    <div class="muted">Date: {{ $receiptDate }}</div>
                    <div>
                        <span
                            class="status
                    {{ strtolower($status) === 'draft' ? 'status-draft' : '' }}
                    {{ strtolower($status) === 'cancelled' ? 'status-cancelled' : '' }}">
                            {{ $status }}
                        </span>
                    </div>
                </div>
            </div>

            <hr class="divider">

            <table class="details-table">
                <tr>
                    <td>
                        <div class="detail-heading">Bill To</div>

                        <div class="customer-name">{{ $customerName }}</div>

                        @if ($party && $party->address)
                            <div>Address: {{ $party->address }}</div>
                        @endif

                        @if ($party && ($party->phone ?? ($party->phone_one ?? null)))
                            <div>Phone: {{ $party->phone ?? $party->phone_one }}</div>
                        @endif

                        @if ($party && $party->email)
                            <div>Email: {{ $party->email }}</div>
                        @endif
                    </td>

                    <td>
                        <div class="detail-heading">Invoice Details</div>

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
                        <span class="muted">Branch</span><br>
                        <strong>{{ $branch->name ?? '—' }}</strong>
                    </td>
                    <td>
                        <span class="muted">Payment Status</span><br>
                        <strong>{{ $receipt->payment_status ?? ($dueAmount <= 0 ? 'Paid' : 'Due') }}</strong>
                    </td>
                </tr>
            </table>

            <h3 class="section-title" style="margin-top:22px;">Invoice Items</h3>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:5%;">#</th>
                        <th style="width:43%;">Product / Description</th>
                        <th class="number" style="width:12%;">Qty</th>
                        <th class="number" style="width:19%;">Unit Price</th>
                        <th class="number" style="width:21%;">Amount</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <div class="item-name">
                                    {{ $item->product->name ?? ($item->category->name ?? ($item->accountHead->name ?? 'Item')) }}
                                </div>

                                @if ($item->details)
                                    <div class="item-description">{{ $item->details }}</div>
                                @endif
                            </td>

                            <td class="number">
                                {{ rtrim(rtrim(number_format((float) $item->qty, 2, '.', ''), '0'), '.') }}
                            </td>

                            <td class="number">{{ $money($item->rate) }}</td>

                            <td class="number">{{ $money($item->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="totals-wrap clearfix">
                <div class="totals-spacer"></div>

                <div class="totals">
                    <table class="summary-table">
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
                            <td>Grand Total</td>
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
                <div class="payments-section">
                    <h3 class="section-title">Payment History</h3>

                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Account</th>
                                <th>Received By</th>
                                <th class="text-right">Amount</th>
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

                                    <td class="number">{{ $money($payment->amount ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($receipt->remarks)
                <div class="remarks">
                    <strong>Remarks / Notes</strong><br>
                    {{ $receipt->remarks }}
                </div>
            @endif

            <div class="footer clearfix">
                <div class="footer-left">
                    <strong>Terms &amp; Conditions</strong>
                    <p>Please verify the invoice details and contact us if you have any questions.</p>
                    <p>Thank you for your business.</p>
                </div>

                <div class="signature">
                    Authorized Signature
                </div>
            </div>

            <div class="thank-you">
                Thank you for choosing {{ $companyName }}!
            </div>

        </div>
    </body>

</html>
