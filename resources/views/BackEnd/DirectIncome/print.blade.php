<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>
            Direct Income - {{ $receipt->receipt_no }}
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 20px;
                background: #f2f2f2;
                font-family: Arial, Helvetica, sans-serif;
                color: #000;
                font-size: 13px;
            }

            .print-container {
                width: 210mm;
                min-height: 297mm;
                margin: 0 auto;
                background: #fff;
                padding: 15mm;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                border-bottom: 2px solid #000;
                padding-bottom: 12px;
                margin-bottom: 15px;
            }

            .company-info {
                width: 65%;
            }

            .company-logo img {
                max-width: 160px;
                max-height: 65px;
                object-fit: contain;
                margin-bottom: 5px;
            }

            .company-name {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .company-info p {
                margin: 2px 0;
                line-height: 1.4;
            }

            .document-info {
                width: 35%;
                text-align: right;
            }

            .document-title {
                font-size: 22px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .document-info table {
                width: 100%;
                border-collapse: collapse;
            }

            .document-info td {
                padding: 3px;
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
                font-size: 14px;
                margin-bottom: 6px;
                padding-bottom: 4px;
                border-bottom: 1px solid #999;
            }

            .info-section {
                display: flex;
                gap: 15px;
                margin-bottom: 18px;
            }

            .info-box {
                width: 50%;
                border: 1px solid #aaa;
                padding: 10px;
            }

            .info-table {
                width: 100%;
                border-collapse: collapse;
            }

            .info-table td {
                padding: 3px 0;
                vertical-align: top;
            }

            .info-table td:first-child {
                width: 110px;
                font-weight: bold;
            }

            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 8px;
            }

            .items-table th,
            .items-table td {
                border: 1px solid #000;
                padding: 7px 6px;
            }

            .items-table th {
                background: #eee;
                font-weight: bold;
                text-align: center;
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

            .totals-wrapper {
                display: flex;
                justify-content: flex-end;
                margin-top: 15px;
            }

            .totals-table {
                width: 330px;
                border-collapse: collapse;
            }

            .totals-table td {
                padding: 5px 8px;
                border-bottom: 1px solid #ccc;
            }

            .totals-table td:first-child {
                font-weight: bold;
            }

            .totals-table td:last-child {
                text-align: right;
            }

            .grand-total td {
                font-size: 15px;
                font-weight: bold;
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
            }

            .payment-section {
                margin-top: 25px;
            }

            .payment-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 8px;
            }

            .payment-table th,
            .payment-table td {
                border: 1px solid #000;
                padding: 6px;
            }

            .payment-table th {
                background: #eee;
                text-align: center;
            }

            .payment-summary {
                width: 330px;
                margin-left: auto;
                margin-top: 10px;
                border-collapse: collapse;
            }

            .payment-summary td {
                padding: 5px 8px;
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
                margin-top: 60px;
                display: flex;
                justify-content: space-between;
                gap: 50px;
            }

            .signature {
                width: 30%;
                text-align: center;
            }

            .signature-line {
                border-top: 1px solid #000;
                padding-top: 5px;
                margin-top: 45px;
            }

            .print-info {
                margin-top: 25px;
                text-align: center;
                font-size: 10px;
                color: #555;
            }

            @media print {
                body {
                    padding: 0;
                    background: #fff;
                }

                .print-container {
                    width: 100%;
                    min-height: auto;
                    padding: 10mm;
                    margin: 0;
                }

                .no-print {
                    display: none !important;
                }

                @page {
                    size: A4;
                    margin: 8mm;
                }
            }

            @media screen {
                .print-button {
                    position: fixed;
                    top: 15px;
                    right: 15px;
                    padding: 10px 18px;
                    border: 0;
                    background: #000;
                    color: #fff;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                }
            }
        </style>
    </head>

    <body>
        <button type="button" class="print-button no-print" onclick="window.print()">
            🖨 Print
        </button>
        <div class="print-container">
            <div class="header">
                <div class="company-info">
                    @if (optional(setting())->logo)
                        <div class="company-logo">
                            <img src="{{ asset('uploads/settings/' . setting()->logo) }}" alt="Logo">
                        </div>
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
                </div>
                <div class="document-info">
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
                </div>
            </div>
            <div class="info-section">
                <div class="info-box">
                    <div class="section-title">
                        Customer Company
                    </div>
                    <table class="info-table">
                        <tr>
                            <td>Name:</td>
                            <td>
                                {{ $receipt->customerCompany->name ?? '' }}
                            </td>
                        </tr>
                        @if ($receipt->customerCompany)
                            @if ($receipt->customerCompany->phone)
                                <tr>
                                    <td>Mobile:</td>
                                    <td>
                                        {{ $receipt->customerCompany->phone }}
                                    </td>
                                </tr>
                            @endif
                            @if ($receipt->customerCompany->email)
                                <tr>
                                    <td>Email:</td>
                                    <td>
                                        {{ $receipt->customerCompany->email }}
                                    </td>
                                </tr>
                            @endif
                            @if ($receipt->customerCompany->address)
                                <tr>
                                    <td>Address:</td>
                                    <td>
                                        {{ $receipt->customerCompany->address }}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
                <div class="info-box">
                    <div class="section-title">
                        Contact Person
                    </div>
                    <table class="info-table">
                        <tr>
                            <td>Name:</td>
                            <td>
                                {{ $receipt->party->name ?? '' }}
                            </td>
                        </tr>
                        @if ($receipt->party)
                            <tr>
                                <td>Designation:</td>
                                <td>
                                    {{ $receipt->party->designation ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Mobile:</td>
                                <td>
                                    {{ $receipt->party->phone ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>
                                    {{ $receipt->party->email ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>
                                    {{ $receipt->party->address ?? '' }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="section-title">
                Income Details
            </div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="45">
                            SL
                        </th>
                        <th>
                            Details
                        </th>
                        <th width="90">
                            Qty
                        </th>
                        <th width="110">
                            Rate
                        </th>
                        <th width="120">
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
            <div class="totals-wrapper">
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
                                <th width="45">
                                    SL
                                </th>
                                <th width="100">
                                    Date
                                </th>
                                <th>
                                    Payment Type
                                </th>
                                <th>
                                    Account
                                </th>
                                <th width="120">
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
                        <td>
                            {{ $receipt->payment_status }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="footer">
                <div class="signature">
                    <div class="signature-line">
                        Prepared By
                    </div>
                    <div>
                        {{ $receipt->creator->name ?? '' }}
                    </div>
                </div>
                <div class="signature">
                    <div class="signature-line">
                        Customer Signature
                    </div>
                </div>
                <div class="signature">
                    <div class="signature-line">
                        Authorized Signature
                    </div>
                </div>
            </div>
            <div class="print-info">
                Printed on:
                {{ now()->format('d-m-Y h:i A') }}
            </div>
        </div>
    </body>

</html>
