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

class CustomerSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{

    public function title(): string
    {
        return 'Customers';
    }

    public function collection(): Collection
    {
        return Party::with([
            'receipts'
        ])
            ->whereIn('type', [
                'Customer',
                'Both',
                'Income'
            ])
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [

            'Customer ID',

            'Customer Name',

            'Phone',

            'Email',

            'Address',

            'Receipt',

            'Income',

            'Paid',

            'Due',

            'Status'

        ];
    }

    public function map($party): array
    {

        $income = $party->receipts
            ->where('type', 'Income');

        return [

            $party->id,

            $party->name,

            $party->phone,

            $party->email,

            $party->address,

            $income->count(),

            $income->sum('total_amount'),

            $income->sum('paid_amount'),

            $income->sum('due_amount'),

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
            ->setARGB('198754');

        $sheet->getStyle('A1:J1')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        $sheet->freezePane('A2');

        return [];
    }
}
