<?php

namespace App\Exports;

use App\Models\ReceiptPayment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Database\Eloquent\Collection;

class PaymentSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    ShouldAutoSize,
    WithStyles
{

    public function title(): string
    {
        return 'Receipt Payments';
    }

    public function collection(): Collection
    {
        return ReceiptPayment::with([
            'receipt',
            'party',
            'paymentType',
            'account',
        ])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [

            'Payment ID',

            'Receipt No',

            'Type',

            'Party',

            'Payment Type',

            'Account',

            'Payment Date',

            'Amount',

            'Note',

            'Created By',

            'Created At'

        ];
    }

    public function map($payment): array
    {
        return [

            $payment->id,

            $payment->receipt->receipt_no ?? '',

            $payment->receipt->type ?? '',

            $payment->receipt->party->name ?? '',

            $payment->paymentType->name ?? '',

            $payment->account->account_name ?? '',

            Carbon::parse($payment->payment_date)
                ->format('d-m-Y'),

            $payment->amount,

            $payment->note,

            $payment->creator->name ?? '',

            Carbon::parse($payment->created_at)
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
            ->setARGB('198754');

        $sheet->getStyle('A1:K1')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        $sheet->freezePane('A2');

        return [];
    }
}
