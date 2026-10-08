<?php

namespace App\Console\Commands;

use App\Models\CompanyPackage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-company-packages')]
#[Description('Command description')]
class ExpireCompanyPackages extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'company-packages:expire';

    protected $description = 'Expire company packages whose expiry date has passed';

    public function handle()
    {
        CompanyPackage::where('status', 'Active')
            ->whereNotNull('expire_date')
            ->whereDate('expire_date', '<=', now()->toDateString())
            ->update([
                'status' => 'Expired',
            ]);

        $this->info('Expired packages updated successfully.');

        return self::SUCCESS;
    }
}
