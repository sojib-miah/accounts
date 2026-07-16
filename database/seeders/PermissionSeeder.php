<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Dashboard
            'dashboard-view',

            // User Management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Role Management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Permission Management
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',

            // company 
            'company-create',
            'company-edit',
            'company-list',
            'company-delete',

            // company user 
            'company-user-list',
            'company-user-create',
            'company-user-edit',
            'company-user-delete',

            // branch 
            'branch-list',
            'branch-create',
            'branch-edit',
            'branch-delete',

            // payment type 
            'payment-type-list',
            'payment-type-create',
            'payment-type-edit',
            'payment-type-delete',

            // account 
            'account-list',
            'account-edit',
            'account-delete',
            'account-create',

            // payee list 
            'payee-list-list',
            'payee-list-create',
            'payee-list-edit',
            'payee-list-delete',

            // expense category 
            'expense-category-list-list',
            'expense-category-list-create',
            'expense-category-list-edit',
            'expense-category-list-delete',

            // expense list 
            'expense-list-list',
            'expense-list-create',
            'expense-list-edit',
            'expense-list-delete',

            // expense recept list 
            'expense-receipt-list',
            'expense-receipt-create',
            'expense-receipt-edit',
            'expense-receipt-delete',

            // receiver list 
            'receiver-list-list',
            'receiver-list-create',
            'receiver-list-edit',
            'receiver-list-delete',

            // income category list 
            'income-category-list-list',
            'income-category-list-create',
            'income-category-list-edit',
            'income-category-list-delete',

            // income leist 
            'income-list-list',
            'income-list-create',
            'income-list-edit',
            'income-list-delete',

            // income recept list 
            'income-receipt-list',
            'income-receipt-create',
            'income-receipt-edit',
            'income-receipt-delete',

            // contact list 
            'contact-list',
            'contact-create',
            'contact-edit',
            'contact-delete',

            // user list 
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Settings
            'general-settings-list',
            'general-settings-create',
            'general-settings-edit',
            'general-settings-delete',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}
