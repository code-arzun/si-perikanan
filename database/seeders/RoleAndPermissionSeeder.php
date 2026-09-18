<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Permissions Dasar
        $permissions = [
            // SaaS Admin Permissions
            ['name' => 'manage-tenants', 'group' => 'saas'],
            ['name' => 'manage-master-data', 'group' => 'saas'],
            
            // Tenant Operational Permissions
            ['name' => 'view-dashboard', 'group' => 'tenant'],
            ['name' => 'manage-ponds', 'group' => 'tenant'],
            ['name' => 'manage-batches', 'group' => 'tenant'],
            ['name' => 'log-daily-activity', 'group' => 'tenant'],
            ['name' => 'log-harvest', 'group' => 'tenant'],
            ['name' => 'manage-finance', 'group' => 'tenant'],
            ['name' => 'manage-staff', 'group' => 'tenant'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['group' => $permission['group']]
            );
        }

        // 2. Buat Default Roles Level SaaS (tenant_id = null)
        $saasAdmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
            'tenant_id' => null]);
        $saasAdmin->givePermissionTo(Permission::all());

        Role::firstOrCreate([
            'name' => 'saas_sales',
            'guard_name' => 'web',
            'tenant_id' => null
        ]);

        // 3. Buat Template Roles Level Tenant (tenant_id = null sebagai cetakan)
        $tenantSuperadmin = Role::firstOrCreate([
            'name' => 'tenant_superadmin',
            'guard_name' => 'web',
            'tenant_id' => null
        ]);
        $tenantSuperadmin->givePermissionTo([
            'view-dashboard', 'manage-ponds', 'manage-batches', 
            'log-daily-activity', 'log-harvest', 'manage-finance', 'manage-staff'
        ]);

        $tenantOperator = Role::firstOrCreate(['name' => 'tenant_operator', 'guard_name' => 'web', 'tenant_id' => null]);
        $tenantOperator->givePermissionTo([
            'view-dashboard', 'manage-ponds', 'manage-batches', 'log-daily-activity'
        ]);

        $tenantFinance = Role::firstOrCreate(['name' => 'tenant_finance', 'guard_name' => 'web', 'tenant_id' => null]);
        $tenantFinance->givePermissionTo([
            'view-dashboard', 'log-harvest', 'manage-finance'
        ]);
    }
}