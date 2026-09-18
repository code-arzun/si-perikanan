<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Role Superadmin aplikasi utama
        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

        // Role khusus Tenant
        Role::firstOrCreate(['name' => 'tenant_superadmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'tenant_admin_keuangan', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'tenant_staf_kolam', 'guard_name' => 'web']);
    }
}