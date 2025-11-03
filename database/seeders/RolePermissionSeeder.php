<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- PERMISSIONS ---
        $permissions = [
            // Products
            'products.create', 'products.read', 'products.update', 'products.delete',

            // Categories
            'categories.create', 'categories.read', 'categories.update', 'categories.delete',

            // Suppliers
            'suppliers.create', 'suppliers.read', 'suppliers.update', 'suppliers.delete',

            // Customers
            'customers.create', 'customers.read', 'customers.update', 'customers.delete',

            // Transactions
            'transactions.create', 'transactions.read', 'transactions.update', 'transactions.delete',

            // Users & Dashboard
            'users.manage', 'statistics.read', 'dashboard.read',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- ROLES ---
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $kasir = Role::firstOrCreate(['name' => 'kasir']);
        $manajer = Role::firstOrCreate(['name' => 'manajer']);

        // --- ROLE PERMISSIONS ---
        $admin->givePermissionTo([
            'products.create', 'products.read', 'products.update', 'products.delete',
            'categories.create', 'categories.read', 'categories.update', 'categories.delete',
            'suppliers.create', 'suppliers.read', 'suppliers.update', 'suppliers.delete',
            'customers.create', 'customers.read', 'customers.update', 'customers.delete',
            'dashboard.read', 'users.manage',
        ]);

        $kasir->givePermissionTo([
            'transactions.create', 'transactions.read', 'transactions.update', 'transactions.delete',
            'dashboard.read',
        ]);

        $manajer->givePermissionTo([
            'statistics.read', 'dashboard.read',
        ]);
    }
}
