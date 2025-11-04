<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus cache permission agar sinkron
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- DAFTAR SEMUA PERMISSION ---
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

        // --- BUAT ROLE ---
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);
        $manajerRole = Role::firstOrCreate(['name' => 'manajer']);

        // --- HUBUNGKAN ROLE DENGAN PERMISSION ---
        $adminRole->syncPermissions([
            'products.create', 'products.read', 'products.update', 'products.delete',
            'categories.create', 'categories.read', 'categories.update', 'categories.delete',
            'suppliers.create', 'suppliers.read', 'suppliers.update', 'suppliers.delete',
            'customers.create', 'customers.read', 'customers.update', 'customers.delete',
            'dashboard.read', 'users.manage'
        ]); 

        $kasirRole->syncPermissions([
            'transactions.create', 'transactions.read', 'transactions.update', 'transactions.delete',
            'dashboard.read'
        ]);

        $manajerRole->syncPermissions([
            'statistics.read', 'dashboard.read'
        ]);

        // --- ASSIGN ROLE BERDASARKAN FIELD 'role' DI TABEL USERS ---
        $users = User::whereIn('role', ['admin', 'kasir', 'manajer'])->get();

        foreach ($users as $user) {
            $roleName = $user->role; 
            if (in_array($roleName, ['admin', 'kasir', 'manajer'])) {
                $user->assignRole($roleName);
            }
        }

        // Clear permission cache lagi agar langsung aktif
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('✅ Role dan permission berhasil dibuat & disesuaikan dengan field role di tabel users!');
    }
}
