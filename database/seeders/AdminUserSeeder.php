<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Internal SaaS Admin Pertama (tenant_id = null)
        $admin = User::firstOrCreate(
            ['email' => 'admin@saas.com'],
            [
                'tenant_id' => null,
                'name' => 'Superadmin SaaS',
                'username' => 'superadmin',
                'phone' => '081234567890',
                'password' => Hash::make('superadmin'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('saas_admin');
    }
}