<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>
            Direct Income - {{ $receipt->receipt_no }}
        </title>
        <style>
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 0;
                font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
                color: #000;
                font-size: 11px;
            }

            .header {
                width: 100%;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 15px;
            }

            .header-table {
                width: 100%;
                border-collapse: collapse;
            }

            .header-table td {
                vertical-align: top;
            }

            .company-info {
                width: 62%;
            }

            .document-info {
                width: 38%;
                text-align: right;
            }

            .logo {
                max-width: 150px;
                max-height: 60px;
                margin-bottom: 5px;
            }

            .company-name {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 4px;
            }

            .company-info p {
                margin: 2px 0;
            }

            .document-title {
                font-size: 19px;
                font-weight: bold;
                margin-bottom: 8px;
            }

            .document-info table {
                width: 100%;
                border-collapse: collapse;
            }

            .document-info td {
                padding: 2px;
            }

            .document-info td:first-child {
                font-weight: bold;
                text-align: left;
            }

            .document-info td:last-child {
                text-align: right;
            }

            .section-title {
                font-weight: bold;
                font-size: 12px;
                border-bottom: 1px solid #777;
                padding-bottom: 4px;
                margin-bottom: 6px;
            }

            .info-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 15px;
            }

            .info-table td {
                width: 50%;
                vertical-align: top;
                border: 1px solid #aaa;
                padding: 8px;
            }

            .inner-table {
                width: 100%;
                border-collapse: collapse;
            }

            .inner-table td {
                border: 0;
                padding: 2px 0;
                width: auto;
            }

            .inner-table td:first-child {
                width: 85px;
                font-weight: bold;
            }

            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 5px;
            }

            .items-table th,
            .items-table td {
                border: 1px solid #000;
                padding: 6px;
            }

            .items-table th {
                background-color: #eeeeee;
                text-align: center;
                font-weight: bold;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .text-left {
                text-align: left;
            }

            .totals-container {
                width: 100%;
                margin-top: 12px;
            }

            .totals-table {
                width: 310px;
                margin-left: auto;
                border-collapse: collapse;
            }

            .totals-table td {
                padding: 5px 7px;
                border-bottom: 1px solid #ccc;
            }

            .totals-table td:first-child {
                font-weight: bold;
            }

            .totals-table td:last-child {
                text-align: right;
            }

            .grand-total td {
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
                font-size: 13px;
                font-weight: bold;
            }

            .payment-section {
                margin-top: 18px;
            }

            .payment-table {
                width: 100%;
                border-collapse: collapse;
            }

            .payment-table th,
            .payment-table td {
                border: 1px solid #000;
                padding: 5px;
            }

            .payment-table th {
                background-color: #eeeeee;
                text-align: center;
            }

            .payment-summary {
                width: 310px;
                margin-left: auto;
                margin-top: 8px;
                border-collapse: collapse;
            }

            .payment-summary td {
                padding: 5px 7px;
                border-bottom: 1px solid #ccc;
            }

            .payment-summary td:first-child {
                font-weight: bold;
            }

            .payment-summary td:last-child {
                text-align: right;
            }

            .status {
                font-weight: bold;
            }

            .footer {
                width: 100%;
                margin-top: 55px;
            }

            .signature-table {
                width: 100%;
                border-collapse: collapse;
            }

            .signature-table td {
                width: 33.33%;
                text-align: center;
                vertical-align: bottom;
                padding: 0 15px;
            }

            .signature-line {
                border-top: 1px solid #000;
                padding-top: 5px;
                margin-top: 35px;
            }

            .printed {
                text-align: center;
                font-size: 8px;
                color: #555;
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="company-info">
                        @if (optional(setting())->logo)
                            <img class="logo" src="{{ public_path('uploads/settings/' . setting()->logo) }}"
                                alt="Logo">
                        @endif
                        <div class="company-name">
                            {{ $receipt->company->name ?? '' }}
                        </div>
                        @if ($receipt->branch)
                            <p>
                                <strong>Branch:</strong>
                                {{ $receipt->branch->name }}
                            </p>
                            @if ($receipt->branch->address)
                                <p>
                                    <strong>Address:</strong>
                                    {{ $receipt->branch->address }}
                                </p>
                            @endif
                            @if ($receipt->branch->phone_one)
                                <p>
                                    <strong>Phone:</strong>
                                    {{ $receipt->branch->phone_one }}
                                </p>
                            @endif
                            @if ($receipt->branch->email)
                                <p>
                                    <strong>Email:</strong>
                                    {{ $receipt->branch->email }}
                                </p>
                            @endif
                        @endif
                    </td>
                    <td class="document-info">
                        <div class="document-title">
                            DIRECT INCOME
                        </div>
                        <table>
                            <tr>
                                <td>Receipt No:</td>
                                <td>
                                    {{ $receipt->receipt_no }}
                                </td>
                            </tr>
                            <tr>
                                <td>Date:</td>
                                <td>
                                    {{ $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td>
                                    {{ $receipt->status }}
                                </td>
                            </tr>
                            <tr>
                                <td>Payment:</td>
                                <td class="status">
                                    {{ $receipt->payment_status }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section-title">
            Customer Information
        </div>
        <table class="info-table">
            <tr>
                <td>
                    <table class="inner-table">
                        <tr>
                            <td>Customer:</td>
                            <td>
                                {{ $receipt->customerCompany->name ?? '' }}
                            </td>
                        </tr>
                        @if ($receipt->customerCompany?->phone)
                            <tr>
                                <td>Mobile:</td>
                                <td>
                                    {{ $receipt->customerCompany->phone }}
                                </td>
                            </tr>
                        @endif
                        @if ($receipt->customerCompany?->email)
                            <tr>
                                <td>Email:</td>
                                <td>
                                    {{ $receipt->customerCompany->email }}
                                </td>
                            </tr>
                        @endif
                        @if ($receipt->customerCompany?->address)
                            <tr>
                                <td>Address:</td>
                                <td>
                                    {{ $receipt->customerCompany->address }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </td>
                <td>
                    <table class="inner-table">
                        <tr>
                            <td>Contact:</td>
                            <td>
                                {{ $receipt->party->name ?? '' }}
                            </td>
                        </tr>
                        @if ($receipt->party?->designation)
                            <tr>
                                <td>Designation:</td>
                                <td>
                                    {{ $receipt->party->designation }}
                                </td>
                            </tr>
                        @endif
                        @if ($receipt->party?->phone)
                            <tr>
                                <td>Mobile:</td>
                                <td>
                                    {{ $receipt->party->phone }}
                                </td>
                            </tr>
                        @endif
                        @if ($receipt->party?->email)
                            <tr>
                                <td>Email:</td>
                                <td>
                                    {{ $receipt->party->email }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
        <div class="section-title">
            Income Details
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th width="40">
                        SL
                    </th>
                    <th>
                        Details
                    </th>
                    <th width="70">
                        Qty
                    </th>
                    <th width="90">
                        Rate
                    </th>
                    <th width="100">
                        Amount
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($receipt->items as $index => $item)
                    <tr>
                        <td class="text-center">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            {{ $item->details ?? '' }}
                        </td>
                        <td class="text-center">
                            {{ number_format($item->qty, 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format($item->rate, 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format($item->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No income items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="totals-container">
            <table class="totals-table">
                <tr>
                    <td>
                        Total Qty
                    </td>
                    <td>
                        {{ number_format($receipt->items->sum('qty'), 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Sub Total
                    </td>
                    <td>
                        {{ number_format($receipt->sub_total, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Discount
                    </td>
                    <td>
                        {{ number_format($receipt->discount, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        VAT ({{ number_format($receipt->vat, 2) }}%)
                    </td>
                    <td>
                        {{ number_format($receipt->vat_amount, 2) }}
                    </td>
                </tr>
                <tr class="grand-total">
                    <td>
                        Grand Total
                    </td>
                    <td>
                        {{ number_format($receipt->total_amount, 2) }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="payment-section">
            <div class="section-title">
                Payment History
            </div>
            @if ($receipt->payments->count())
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th width="35">
                                SL
                            </th>
                            <th width="75">
                                Date
                            </th>
                            <th>
                                Payment Type
                            </th>
                            <th>
                                Account
                            </th>
                            <th width="90">
                                Amount
                            </th>
                            <th>
                                Note
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receipt->payments as $index => $payment)
                            <tr>
                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>
                                <td class="text-center">
                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '' }}
                                </td>
                                <td>
                                    {{ $payment->paymentType->name ?? '' }}
                                </td>
                                <td>
                                    {{ $payment->account->account_name ?? '' }}
                                    @if ($payment->account?->account_number)
                                        <br>
                                        <small>
                                            A/C:
                                            {{ $payment->account->account_number }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-right">
                                    {{ number_format($payment->amount, 2) }}
                                </td>
                                <td>
                                    {{ $payment->note ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>
                    No payment has been received yet.
                </p>
            @endif
            {{-- Payment Summary --}}
            <table class="payment-summary">
                <tr>
                    <td>
                        Grand Total
                    </td>
                    <td>
                        {{ number_format($receipt->total_amount, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Paid Amount
                    </td>
                    <td>
                        {{ number_format($receipt->paid_amount, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Due Amount
                    </td>
                    <td>
                        {{ number_format($receipt->due_amount, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Payment Status
                    </td>
                    <td class="status">
                        {{ $receipt->payment_status }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-line">
                            Prepared By
                        </div>
                        {{ $receipt->creator->name ?? '' }}
                    </td>
                    <td>
                        <div class="signature-line">
                            Customer Signature
                        </div>
                    </td>
                    <td>
                        <div class="signature-line">
                            Authorized Signature
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="printed">
            Printed on:
            {{ now()->format('d-m-Y h:i A') }}
        </div>
    </body>

</html>
