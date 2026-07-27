<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::firstOrCreate(
            ['name' => 'Trial'],
            [
                'price' => 0,
                'user_limit' => 1,
                'company_limit' => 1,
                'branch_limit' => 1,
                'income_limit' => 100,
                'expense_limit' => 100,
                'challan_limit' => 100,
                'party_limit' => 100,
                'account_limit' => 100,
                'payment_type_limit' => 10,
                'category_limit' => 20,
                'item_list_limit' => 100,
                'sales_order_limit' => 100,
                'end_date' => now()->addDays(30),
                'storage_limit' => 1024,
                'remarks' => 'Default trial package',
                'is_active' => true,
            ]
        );
    }
}
