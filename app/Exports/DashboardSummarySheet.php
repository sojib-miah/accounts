<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Party;
use App\Models\Receipt;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardSummarySheet implements
    FromArray,
    WithTitle,
    ShouldAutoSize,
    WithStyles
{
    public function title(): string
    {
        return 'Dashboard Summary';
    }

    public function array(): array
    {
        $today = Carbon::today();
        $todayIncome = Receipt::where('status', 'Completed')->where('type', 'Income')->whereDate('receipt_date', $today)->sum('total_amount');
        $todayExpense = Receipt::where('status', 'Completed')->where('type', 'Expense')->whereDate('receipt_date', $today)->sum('total_amount');
        $totalIncome = Receipt::where('status', 'Completed')->where('type', 'Income')->sum('total_amount');
        $totalExpense = Receipt::where('status', 'Completed')->where('type', 'Expense')->sum('total_amount');
        $grossProfit = $totalIncome - $totalExpense;
        return [
            ['ComitsBD'],
            ['Financial Dashboard Report'],
            ['Generated', now()->format('d M Y h:i A')],
            [],
            ['Financial Summary'],
            ['Today Income', $todayIncome],
            ['Today Expense', $todayExpense],
            ['Gross Profit', $grossProfit],
            ['Current Balance', Account::sum('current_balance')],
            ['Customer Due', Receipt::where('type', 'Income')->sum('due_amount')],
            ['Supplier Due', Receipt::where('type', 'Expense')->sum('due_amount')],
            [],
            ['Statistics'],
            ['Income', Party::where('type', 'Income')->count()],
            ['Expense', Party::where('type', 'Expense')->count()],
            ['Branches', Branch::count()],
            ['Receipts', Receipt::count()],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:B5')->getFont()->setBold(true);
        $sheet->getStyle('A15:B15')->getFont()->setBold(true);
        return [];
    }
}
