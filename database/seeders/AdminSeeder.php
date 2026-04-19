<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the Super Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // 2. Create a System Administrator User
        $admin = User::firstOrCreate(
            ['email' => 'admin@pfre.in'],
            [
                'name' => 'PFRE System Admin',
                'phone' => '9999999999',
                'password' => Hash::make('Admin@PFRE2026'), // Ensure you change this on first login
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 3. Assign Role
        $admin->assignRole($adminRole);

        $this->command->info('System Admin created: admin@pfre.in');
    }
}