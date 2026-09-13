<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'Super-Admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
        ]);

        $userRole->syncPermissions([
            'dashboard-view',

            'account-create',
            'account-delete',
            'account-edit',
            'account-list',

            'company-create',
            'company-edit',
            'company-list',
            'company-delete',

            'branch-list',
            'branch-create',
            'branch-edit',
            'branch-delete',

            'payment-type-list',
            'payment-type-create',
            'payment-type-edit',
            'payment-type-delete',

            'account-list',
            'account-edit',
            'account-delete',
            'account-create',

            'payee-list-list',
            'payee-list-create',
            'payee-list-edit',
            'payee-list-delete',

            'expense-category-list-list',
            'expense-category-list-create',
            'expense-category-list-edit',
            'expense-category-list-delete',

            'expense-list-list',
            'expense-list-create',
            'expense-list-edit',
            'expense-list-delete',
            'expense-receipt-list',
            'expense-receipt-create',
            'expense-receipt-edit',
            'expense-receipt-delete',

            'receiver-list-list',
            'receiver-list-create',
            'receiver-list-edit',
            'receiver-list-delete',

            'income-category-list-list',
            'income-category-list-create',
            'income-category-list-edit',
            'income-category-list-delete',

            'income-list-list',
            'income-list-create',
            'income-list-edit',
            'income-list-delete',
            'income-receipt-list',
            'income-receipt-create',
            'income-receipt-edit',
            'income-receipt-delete',

            'income-challan-list',
            'income-challan-edit',
            'income-challan-delete',
            'income-challan-create',

            'menu-account-list',
            'menu-company-list',
            'menu-sales-list',
            'menu-expense-list',
            'menu-purchase-list',
            'menu-inventory-list',
            'menu-product-list',
            'menu-warehouse-list',

            'expense-details-list',
            'income-details-list',
            'income-salesorder-list',

            'purchase-delete',
            'purchase-list',
            'purchase-edit',
            'purchase-create',

            'supplier-list',
            'supplier-delete',
            'supplier-create',
            'supplier-edit',

            'product-edit',
            'product-create',
            'product-list',
            'product-delete',

            'product-category-create',
            'product-category-edit',
            'product-category-list',
            'product-category-delete',

            'inventory-list',
            'inventory-lowstock-list',
            'inventory-report-list',

            'brand-list',

            'supplier-company-list',
            'make-payment-list',
            'warehouse-list',
            'receiver-company-list',
            'payee-company-list',
        ]);
    }
}
