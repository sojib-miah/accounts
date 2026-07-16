<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Dashboard Report</title>
        <style>
            @page {
                margin: 15mm;
                size: A4 portrait;
            }

            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
                color: #333;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            .header {
                border-bottom: 3px solid #5b4fcf;
                padding-bottom: 12px;
                margin-bottom: 20px;
            }

            .logo {
                width: 80px;
            }

            .company {
                font-size: 26px;
                color: #5b4fcf;
                font-weight: bold;
            }

            .title {
                text-align: center;
                font-size: 22px;
                margin: 20px 0;
                color: #5b4fcf;
                font-weight: bold;
            }

            .section {
                background: #5b4fcf;
                color: #fff;
                padding: 8px;
                margin-top: 20px;
                font-size: 15px;
                font-weight: bold;
            }

            .summary td {
                border: 1px solid #ddd;
                padding: 10px;
            }

            .summary-title {
                background: #f8f8ff;
                font-weight: bold;
            }

            .text-right {
                text-align: right;
            }

            .income {
                color: #28a745;
                font-weight: bold;
            }

            .expense {
                color: #dc3545;
                font-weight: bold;
            }

            .profit {
                color: #0d6efd;
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <!-- ================================= -->
        <table class="header">
            <tr>
                <td>
                    <div class="company">
                        ComitsBD
                    </div>
                    <div>
                        Corporate Office
                    </div>
                    <div>
                        Dhaka, Bangladesh
                    </div>
                    <div>
                        Phone : +8801571276348
                    </div>
                </td>
                <td align="right">
                    <b>
                        Dashboard Report
                    </b>
                    <br>
                    Generated :
                    {{ now()->format('d M Y h:i A') }}
                </td>
            </tr>
        </table>
        <!-- ================================= -->
        <div class="title">
            FINANCIAL DASHBOARD SUMMARY
        </div>
        <!-- ================================= -->
        <div class="section">
            Financial Summary
        </div>
        <table class="summary">
            <tr>
                <td class="summary-title">
                    Today's Income
                </td>
                <td class="income text-right">
                    {{ number_format($todayIncome, 2) }}
                </td>
                <td class="summary-title">
                    Today's Expense
                </td>
                <td class="expense text-right">
                    {{ number_format($todayExpense, 2) }}
                </td>
            </tr>
            <tr>
                <td class="summary-title">
                    Total Income
                </td>
                <td class="income text-right">
                    {{ number_format($totalIncome, 2) }}
                </td>
                <td class="summary-title">
                    Total Expense
                </td>
                <td class="expense text-right">
                    {{ number_format($totalExpense, 2) }}
                </td>
            </tr>
            <tr>
                <td class="summary-title">
                    Gross Profit
                </td>
                <td class="profit text-right">
                    {{ number_format($grossProfit, 2) }}
                </td>
                <td class="summary-title">
                    Current Balance
                </td>
                <td class="profit text-right">
                    {{ number_format($currentBalance, 2) }}
                </td>
            </tr>
            <tr>
                <td class="summary-title">
                    Receivable
                </td>
                <td class="text-right">
                    {{ number_format($totalReceivable, 2) }}
                </td>
                <td class="summary-title">
                    Payable
                </td>
                <td class="text-right">
                    {{ number_format($totalPayable, 2) }}
                </td>
            </tr>
        </table>
        <!-- ================================= -->
        <div class="section">
            System Statistics
        </div>
        <table class="summary">
            <tr>
                <td class="summary-title">
                    Customers
                </td>
                <td class="text-right">
                    {{ $totalCustomer }}
                </td>
                <td class="summary-title">
                    Suppliers
                </td>
                <td class="text-right">
                    {{ $totalSupplier }}
                </td>
            </tr>
            <tr>
                <td class="summary-title">
                    Branches
                </td>
                <td class="text-right">
                    {{ $totalBranch }}
                </td>
                <td class="summary-title">
                    Accounts
                </td>
                <td class="text-right">
                    {{ $totalAccount }}
                </td>
            </tr>
        </table>
        <!-- ================================= -->
        <!-- Recent Receipts -->
        <!-- ================================= -->
        <div class="section">
            Recent Receipts
        </div>
        <table class="summary">
            <thead>
                <tr style="background:#f3f4f8;">
                    <th width="15%">Receipt No</th>
                    <th width="30%">Party</th>
                    <th width="15%">Type</th>
                    <th width="20%">Date</th>
                    <th width="20%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentReceipts as $receipt)
                    <tr>
                        <td>{{ $receipt->receipt_no }}</td>
                        <td>{{ $receipt->party->name ?? '-' }}</td>
                        <td>{{ $receipt->type }}</td>
                        <td>{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') }}</td>
                        <td class="text-right">
                            {{ number_format($receipt->total_amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" align="center">
                            No Receipt Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- ================================= -->
        <!-- Recent Payments -->
        <!-- ================================= -->
        <div class="section">
            Recent Payments
        </div>
        <table class="summary">
            <thead>
                <tr style="background:#f3f4f8;">
                    <th width="15%">Receipt</th>
                    <th width="25%">Payment Type</th>
                    <th width="20%">Date</th>
                    <th width="20%">Account</th>
                    <th width="20%" class="text-right">
                        Amount
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                    <tr>
                        <td>
                            {{ $payment->receipt->receipt_no ?? '-' }}
                        </td>
                        <td>
                            {{ $payment->paymentType->name ?? '-' }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
                        </td>
                        <td>
                            {{ $payment->account->account_name ?? '-' }}
                        </td>
                        <td class="text-right">
                            {{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" align="center">
                            No Payment Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- ================================= -->
        <!-- Recent Account Transactions -->
        <!-- ================================= -->
        <div class="section">
            Recent Account Transactions
        </div>
        <table class="summary">
            <thead>
                <tr style="background:#f3f4f8;">
                    <th width="18%">Voucher</th>
                    <th width="15%">Type</th>
                    <th width="22%">Date</th>
                    <th width="20%">Account</th>
                    <th width="12%" class="text-right">
                        Credit
                    </th>
                    <th width="13%" class="text-right">
                        Debit
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $tran)
                    <tr>
                        <td>
                            {{ $tran->voucher_no }}
                        </td>
                        <td>
                            {{ $tran->transaction_type }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($tran->transaction_date)->format('d-m-Y') }}
                        </td>
                        <td>
                            {{ $tran->account->account_name ?? '-' }}
                        </td>
                        <td class="text-right">
                            {{ number_format($tran->credit, 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format($tran->debit, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" align="center">
                            No Transaction Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- ================================= -->
        <!-- Report Highlights -->
        <!-- ================================= -->
        <div class="section">
            Report Highlights
        </div>
        <table class="summary">
            <tr>
                <td class="summary-title">
                    Total Receipts
                </td>
                <td class="text-right">
                    {{ $recentReceipts->count() }}
                </td>
                <td class="summary-title">
                    Total Payments
                </td>
                <td class="text-right">
                    {{ $recentPayments->count() }}
                </td>
            </tr>
            <tr>
                <td class="summary-title">
                    Total Receivable
                </td>
                <td class="text-right">
                    {{ number_format($totalReceivable, 2) }}
                </td>
                <td class="summary-title">
                    Total Payable
                </td>
                <td class="text-right">
                    {{ number_format($totalPayable, 2) }}
                </td>
            </tr>
            <tr>
                <td class="summary-title">
                    Net Balance
                </td>
                <td colspan="3" class="text-right profit">
                    {{ number_format($currentBalance, 2) }}
                </td>
            </tr>
        </table>
        <!-- ================================= -->
        <!-- Business Summary -->
        <!-- ================================= -->
        <div class="section">
            Business Summary
        </div>
        <table class="summary">
            <tr>
                <td>
                    ✔ Total Income
                </td>
                <td class="income text-right">
                    {{ number_format($totalIncome, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    ✔ Total Expense
                </td>
                <td class="expense text-right">
                    {{ number_format($totalExpense, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    ✔ Gross Profit
                </td>
                <td class="profit text-right">
                    {{ number_format($grossProfit, 2) }}
                </td>
            </tr>
        </table>
        <!-- ================================= -->
        <!-- Notes -->
        <!-- ================================= -->
        <div class="section">
            Remarks
        </div>
        <table class="summary">
            <tr>
                <td>
                    This report is automatically generated from the ERP Accounting
                    System and summarizes the latest financial information available
                    at the time of printing.
                </td>
            </tr>
        </table>

        <!-- ================================= -->
        <!-- Signature -->
        <!-- ================================= -->
        <table style="margin-top:60px;width:100%;border:none;">
            <tr>
                <td align="center" style="border:none;width:33%;">
                    ______________________
                    <br><br>
                    Prepared By
                </td>
                <td align="center" style="border:none;width:34%;">
                    ______________________
                    <br><br>
                    Checked By
                </td>
                <td align="center" style="border:none;width:33%;">
                    ______________________
                    <br><br>
                    Approved By
                </td>
            </tr>
        </table>
        <!-- ================================= -->
        <!-- Footer -->
        <!-- ================================= -->
        <hr style="margin-top:40px;">
        <table style="width:100%;border:none;font-size:10px;">
            <tr>
                <td style="border:none;">
                    ERP Financial Dashboard Report
                </td>
                <td align="center" style="border:none;">
                    Generated :
                    {{ now()->format('d M Y h:i A') }}
                </td>
                <td align="right" style="border:none;">
                    Powered By AKHI PRINTS ERP
                </td>
            </tr>
        </table>
    </body>

</html>
