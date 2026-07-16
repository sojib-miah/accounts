<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>
            {{ $receipt->type }} Receipt
        </title>
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Calibri, Arial, sans-serif;
                font-size: 15px;
                background: #ececec;
            }

            .receipt {
                width: 210mm;
                min-height: 297mm;
                margin: 15px auto;
                background: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th {
                background: #e8dcff;
                color: #1b1b1b;
                font-size: 20px;
                padding: 8px;
                border: 1px solid #7d52c4;
                text-align: center;
            }

            table td {
                border: 1px solid #7d52c4;
                padding: 8px;
                vertical-align: top;
            }

            .logo {
                height: 70px;
            }

            .company {
                font-size: 34px;
                font-weight: 700;
                color: #333;
            }

            .phone {
                text-align: right;
                font-size: 18px;
                font-weight: bold;
                color: #6b3fb5;
                line-height: 30px;
            }

            .section-title {
                text-align: center;
                color: #6b3fb5;
                font-size: 30px;
                font-weight: 700;
                margin: 18px 0 10px;
            }

            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .fw-bold {
                font-weight: bold;
            }

            .print-btn {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 999;
            }

            @page {
                size: A4;
                margin: 10mm;
            }

            @media print {
                body {
                    background: #fff;
                }

                .receipt {
                    width: 100%;
                    margin: 0;
                    box-shadow: none;
                    padding: 0;
                }

                .print-btn {
                    display: none;
                }
            }
        </style>
    </head>

    <body>
        <button onclick="window.print()" class="btn btn-primary print-btn">
            <i class="fa fa-print"></i>
            Print
        </button>
        <div class="receipt">
            <table>
                <tr style="border:none;">
                    <td style="border:none;width:65%;">
                        <table style="border:none;">
                            <tr>
                                <td style="border:none;width:90px;">
                                    @if (setting() && setting()->logo)
                                        <img src="{{ asset('uploads/settings/' . setting()->logo) }}" class="logo">
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="border:none;width:35%;">
                        <div class="phone">
                            {{ setting()->phone ?? '' }}
                            <br>
                            {{ setting()->mobile ?? '' }}
                        </div>
                    </td>
                </tr>
            </table>
            <hr style="margin:12px 0;border:1px solid #7d52c4;">
            <table class="mt-2">
                <tr>
                    <th width="50%">
                        Branch Information
                    </th>
                    <th width="50%">
                        {{ $receipt->type == 'Income' ? 'Payer Information' : 'Payee Information' }}
                    </th>
                </tr>
                <tr>
                    <!-- Branch -->
                    <td>
                        <table style="border:none;width:100%;">
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>Company :</strong>
                                    {{ $receipt->branch->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>Email :</strong>
                                    {{ $receipt->branch->email ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>Phone :</strong>
                                    {{ $receipt->branch->phone_one ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>Address :</strong>
                                    {{ $receipt->branch->address ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <!-- Party -->
                    <td>
                        <table style="border:none;width:100%;">
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>Name :</strong>
                                    {{ $receipt->party->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>ID :</strong>
                                    {{ $receipt->party->id ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>Phone :</strong>
                                    {{ $receipt->party->phone ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none;padding:3px 0;">
                                    <strong>Address :</strong>
                                    {{ $receipt->party->address ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            Receipt ID :
                        </strong>
                        {{ $receipt->receipt_no }}
                    </td>
                    <td>
                        <strong>
                            Receipt Date :
                        </strong>
                        {{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') }}
                    </td>
                </tr>
            </table>
            <!-- Receipt Details Title -->
            <div class="section-title">
                Receipt Details
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">
                            #
                        </th>
                        <th width="20%">
                            {{ $receipt->type }}
                        </th>
                        <th width="20%">
                            Category
                        </th>
                        <th>
                            Details
                        </th>
                        <th width="20%">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotal = 0;
                    @endphp
                    @foreach ($receipt->items as $item)
                        @php
                            $grandTotal += $item->amount;
                        @endphp
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $item->accountHead->name ?? '' }}
                            </td>
                            <td>
                                {{ $item->category->name ?? '' }}
                            </td>
                            <td>
                                {{ $item->details }}
                            </td>
                            <td class="text-right">
                                {{ number_format($item->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right fw-bold" style="font-size:18px;">
                            Total
                        </td>
                        <td class="text-right fw-bold" style="font-size:18px;background:#e8dcff;">
                            {{ number_format($grandTotal, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Payment Information -->
            <div class="section-title">
                Payment Information
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">
                            #
                        </th>
                        <th width="18%">
                            Payment ID
                        </th>
                        <th width="20%">
                            Payment Type
                        </th>
                        <th width="20%">
                            Account
                        </th>
                        <th width="17%">
                            Amount
                        </th>
                        <th width="20%">
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipt->payments as $payment)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="text-center">
                                {{ $payment->id }}
                            </td>
                            <td>
                                {{ $payment->paymentType->name ?? '' }}
                            </td>
                            <td>
                                {{ $payment->account->account_name ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No Payment Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Closing Calculation -->
            <div class="section-title">
                Closing Calculation
            </div>
            <table>
                <tbody>
                    <tr>
                        <td width="75%">
                            <strong>Total Amount</strong>
                        </td>
                        <td class="text-right">
                            {{ number_format($receipt->total_amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Paid Amount</strong>
                        </td>
                        <td class="text-right text-success">
                            {{ number_format($receipt->paid_amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Due Amount</strong>
                        </td>
                        <td class="text-right text-danger">
                            {{ number_format($receipt->due_amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Payment Status</strong>
                        </td>
                        <td class="text-right">
                            <strong>
                                {{ $receipt->payment_status }}
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Amount In Words -->
            <div style="margin-top:20px;">
                <strong>
                    Amount In Words :
                </strong>
                {{ numberToWords($receipt->total_amount) }} Only.
            </div>
            <!-- Thanks Message -->
            <div
                style="margin-top:25px;
                    padding:12px;
                    text-align:center;
                    font-size:18px;
                    font-weight:bold;
                    color:#6b3fb5;
                    border:1px dashed #6b3fb5;">
                Payment Received With Thanks
            </div>
            <!-- Remarks -->
            @if ($receipt->remarks)
                <div style="margin-top:20px;">
                    <strong>
                        Remarks :
                    </strong>
                    {{ $receipt->remarks }}
                </div>
            @endif
            <!-- Signature Section -->
            <table style="margin-top:70px;
    border:none;">
                <tr>
                    <td style="border:none;
           width:33%;
            text-align:center;">
                        ______________________
                        <br>
                        <strong>
                            Prepared By
                        </strong>
                        <br>
                        {{ $receipt->creator->name ?? '' }}
                    </td>
                    <td style="border:none;
           width:34%;
            text-align:center;">
                        ______________________
                        <br>
                        <strong>
                            Received By
                        </strong>
                        <br>
                        {{ $receipt->party->name ?? '' }}
                    </td>
                    <td style="border:none;
           width:33%;
            text-align:center;">
                        ______________________
                        <br>
                        <strong>
                            Authorized Signature
                        </strong>
                    </td>
                </tr>
            </table>
            <!-- Footer -->
            <div
                style="margin-top:50px;
    border-top:2px solid #7d52c4;
    padding-top:10px;
    text-align:center;
    font-size:12px;
    color:#666;">
                <div>
                    This is a computer generated receipt.
                </div>
                <div>
                    Printed On :
                    {{ now()->format('d M Y h:i A') }}
                </div>
                <div>
                    Generated By :
                    {{ auth()->user()->name }}
                </div>
            </div>
        </div>
        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    </body>

</html>
