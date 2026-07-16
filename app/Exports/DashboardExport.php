<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new DashboardSummarySheet(),
            new ReceiptSheet(),
            new PaymentSheet(),
            new TransactionSheet(),
            new CustomerSheet(),
            new SupplierSheet(),
        ];
    }
}
