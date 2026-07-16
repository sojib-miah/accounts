<?php

namespace App\Http\Controllers\BackEnd;

use App\Exports\DashboardExport;
use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Party;
use App\Models\ReceiptPayment;

class ReportController extends Controller
{
    public function pdf()
    {
        $today = Carbon::today();
        $receipt = Receipt::where('status', 'Completed');
        $totalIncome = (clone $receipt)->where('type', 'Income')->sum('total_amount');
        $totalExpense = (clone $receipt)->where('type', 'Expense')->sum('total_amount');
        $grossProfit = $totalIncome - $totalExpense;
        $todayIncome = (clone $receipt)->where('type', 'Income')->whereDate('receipt_date', $today)->sum('total_amount');
        $todayExpense = (clone $receipt)->where('type', 'Expense')->whereDate('receipt_date', $today)->sum('total_amount');
        $currentBalance = Account::sum('current_balance');
        $totalReceivable = Receipt::where('type', 'Income')->sum('due_amount');
        $totalPayable = Receipt::where('type', 'Expense')->sum('due_amount');
        $totalCustomer = Party::where('type', 'Income')->count();
        $totalSupplier = Party::where('type', 'Expense')->count();
        $totalBranch = Branch::count();
        $totalAccount = Account::count();
        $recentReceipts = Receipt::with('party')->latest()->take(20)->get();
        $recentPayments = ReceiptPayment::with(['receipt', 'paymentType'])->latest()->take(20)->get();
        $recentTransactions = AccountTransaction::with('account')->latest()->take(20)->get();
        $pdf = Pdf::loadView(
            'BackEnd.Report.pdf',
            compact(
                'todayIncome',
                'todayExpense',
                'totalIncome',
                'totalExpense',
                'grossProfit',
                'currentBalance',
                'totalReceivable',
                'totalPayable',
                'totalCustomer',
                'totalSupplier',
                'totalBranch',
                'totalAccount',
                'recentReceipts',
                'recentPayments',
                'recentTransactions'
            )
        );
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream(
            'Report_' . date('Ymd_His') . '.pdf'
        );
    }

    public function excel()
    {
        return Excel::download(
            new DashboardExport,
            'Report_' . date('Ymd_His') . '.xlsx'
        );
    }
}
