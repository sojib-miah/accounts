<?php

namespace App\Exports;

use App\Models\Party;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SupplierSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{
    public function title(): string
    {
        return 'Suppliers';
    }

    public function collection(): Collection
    {
        return Party::with('receipts')
            ->whereIn('type', [
                'Supplier',
                'Both',
                'Expense'
            ])
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [

            'Supplier ID',

            'Supplier Name',

            'Phone',

            'Email',

            'Address',

            'Expense Receipts',

            'Total Expense',

            'Paid Amount',

            'Due Amount',

            'Status'

        ];
    }

    public function map($party): array
    {
        $expense = $party->receipts
            ->where('type', 'Expense');

        return [

            $party->id,

            $party->name,

            $party->phone,

            $party->email,

            $party->address,

            $expense->count(),

            $expense->sum('total_amount'),

            $expense->sum('paid_amount'),

            $expense->sum('due_amount'),

            $party->status

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1:J1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID);

        $sheet->getStyle('A1:J1')
            ->getFill()
            ->getStartColor()
            ->setARGB('DC3545');

        $sheet->getStyle('A1:J1')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        $sheet->freezePane('A2');

        return [];
    }
}
