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
        Package::updateOrCreate(
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

                // 1 GB = 1024 MB
                'storage_limit' => 1024,

                'end_date' => now()->addDays(30),

                'remarks' => 'Perfect for trying the system with basic features for 30 days.',

                'is_active' => true,
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Basic'],
            [
                'price' => 999,

                'user_limit' => 5,
                'company_limit' => 2,
                'branch_limit' => 5,

                'income_limit' => 2000,
                'expense_limit' => 2000,
                'challan_limit' => 1000,

                'party_limit' => 1000,
                'account_limit' => 200,

                'payment_type_limit' => 25,
                'category_limit' => 200,
                'item_list_limit' => 1000,
                'sales_order_limit' => 1000,

                // 5 GB = 5120 MB
                'storage_limit' => 5120,

                'end_date' => null,

                'remarks' => 'Great for small and growing businesses with essential business management features.',

                'is_active' => true,
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Professional'],
            [
                'price' => 1999,

                'user_limit' => 10,
                'company_limit' => 5,
                'branch_limit' => 10,

                'income_limit' => 10000,
                'expense_limit' => 10000,
                'challan_limit' => 5000,

                'party_limit' => 5000,
                'account_limit' => 500,

                'payment_type_limit' => 50,
                'category_limit' => 500,
                'item_list_limit' => 5000,
                'sales_order_limit' => 5000,

                // 20 GB = 20480 MB
                'storage_limit' => 20480,

                'end_date' => null,

                'remarks' => 'Ideal for established businesses requiring higher limits and advanced business operations.',

                'is_active' => true,
            ]
        );

        // Unlimited
        Package::updateOrCreate(
            ['name' => 'Enterprise'],
            [
                'price' => 4999,
                'user_limit' => 0,
                'company_limit' => 0,
                'branch_limit' => 0,

                'income_limit' => 0,
                'expense_limit' => 0,
                'challan_limit' => 0,

                'party_limit' => 0,
                'account_limit' => 0,

                'payment_type_limit' => 0,
                'category_limit' => 0,
                'item_list_limit' => 0,
                'sales_order_limit' => 0,

                // 100 GB = 102400 MB
                'storage_limit' => 102400,

                'end_date' => null,

                'remarks' => 'Designed for large organizations with unlimited business management capabilities.',

                'is_active' => true,
            ]
        );
    }
}
