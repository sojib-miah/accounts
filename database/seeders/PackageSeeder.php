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
                'company_limit' => 1,
                'branch_limit' => 1,
                'user_limit' => 1,
                'supplier_limit' => 20,
                'customer_limit' => 20,
                'payment_type_limit' => 5,
                'account_limit' => 5,
                'product_limit' => 20,
                'sales_order_limit' => 100,
                'storage_limit' => 1024,
                'remarks' => 'Perfect for trying the system with basic features for 14 days.',
                'is_active' => true,
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Basic'],
            [
                'price' => 899,
                'company_limit' => 1,
                'branch_limit' => 1,
                'user_limit' => 1,
                'supplier_limit' => 50,
                'customer_limit' => 50,
                'payment_type_limit' => 10,
                'account_limit' => 10,
                'product_limit' => 'Unlimited',
                'sales_order_limit' => 300,
                'storage_limit' => 5120,
                'remarks' => 'Great for small and growing businesses with essential business management features.',
                'is_active' => true,
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Professional'],
            [
                'price' => 1499,
                'company_limit' => 2,
                'branch_limit' => 2,
                'user_limit' => 2,
                'supplier_limit' => 100,
                'customer_limit' => 100,
                'payment_type_limit' => 10,
                'account_limit' => 10,
                'product_limit' => 'Unlimited',
                'sales_order_limit' => 500,
                'storage_limit' => 20480,
                'remarks' => 'Ideal for established businesses requiring higher limits and advanced business operations.',
                'is_active' => true,
            ]
        );

        // Unlimited
        Package::updateOrCreate(
            ['name' => 'Enterprise'],
            [
                'price' => 1999,
                'company_limit' => 4,
                'branch_limit' => 4,
                'user_limit' => 4,
                'supplier_limit' => 'Unlimited',
                'customer_limit' => 'Unlimited',
                'payment_type_limit' => 20,
                'account_limit' => 20,
                'product_limit' => 'Unlimited',
                'sales_order_limit' => 1000,
                'storage_limit' => 102400,
                'remarks' => 'Designed for large organizations with unlimited business management capabilities.',
                'is_active' => true,
            ]
        );
    }
}
