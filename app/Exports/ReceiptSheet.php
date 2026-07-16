<?php

namespace App\Exports;

use App\Models\Receipt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Collection;

class ReceiptSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    ShouldAutoSize,
    WithStyles
{
    public function title(): string
    {
        return 'Receipts';
    }

    public function collection(): Collection
    {
        return Receipt::with(['branch', 'party', 'creator'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Receipt No',
            'Type',
            'Date',
            'Party',
            'Branch',
            'Total Qty',
            'Sub Total',
            'Discount',
            'VAT',
            'Total Amount',
            'Paid',
            'Due',
            'Payment Status',
            'Receipt Status',
            'Created By',
            'Created At'
        ];
    }

    public function map($receipt): array
    {
        return [
            $receipt->receipt_no,
            $receipt->type,
            optional($receipt->receipt_date)->format('d-m-Y'),
            $receipt->party->name ?? '',
            $receipt->branch->name ?? '',
            $receipt->total_qty,
            $receipt->sub_total,
            $receipt->discount,
            $receipt->vat,
            $receipt->total_amount,
            $receipt->paid_amount,
            $receipt->due_amount,
            $receipt->payment_status,
            $receipt->status,
            $receipt->creator->name ?? '',
            optional($receipt->created_at)->format('d-m-Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);
        $sheet->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4F46E5');
        $sheet->getStyle('A1:P1')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->freezePane('A2');
        return [];
    }
}
