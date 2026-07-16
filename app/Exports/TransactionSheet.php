<?php

namespace App\Exports;

use App\Models\AccountTransaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Collection;

class TransactionSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{
    public function title(): string
    {
        return 'Transactions';
    }

    public function collection(): Collection
    {
        return AccountTransaction::with([
            'account',
            'receipt',
        ])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [

            'Voucher No',

            'Transaction Date',

            'Account',

            'Receipt',

            'Type',

            'Purpose',

            'Credit',

            'Debit',

            'Balance',

            'Created By',

            'Created At'

        ];
    }

    public function map($transaction): array
    {
        return [

            $transaction->voucher_no,

            Carbon::parse($transaction->transaction_date)
                ->format('d-m-Y'),

            $transaction->account->account_name ?? '',

            $transaction->receipt->receipt_no ?? '',

            $transaction->transaction_type,

            $transaction->purpose,

            $transaction->credit,

            $transaction->debit,

            $transaction->balance,

            $transaction->creator->name ?? '',

            Carbon::parse($transaction->created_at)
                ->format('d-m-Y H:i')

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1:K1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID);

        $sheet->getStyle('A1:K1')
            ->getFill()
            ->getStartColor()
            ->setARGB('0D6EFD');

        $sheet->getStyle('A1:K1')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        $sheet->freezePane('A2');

        return [];
    }
}
