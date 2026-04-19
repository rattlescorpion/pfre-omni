<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Business Specific Roles
        $proprietorRole = Role::firstOrCreate(['name' => 'Proprietor', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'Sales Manager', 'guard_name' => 'web']);
        $agentRole = Role::firstOrCreate(['name' => 'Real Estate Agent', 'guard_name' => 'web']);

        // 2. Create your Proprietor account (Saurav Chowdhury)
        $proprietor = User::firstOrCreate(
            ['email' => 'saurav.chowdhury@pfre.in'], // Update with your preferred business email
            [
                'name' => 'Saurav Chowdhury',
                'phone' => '9830000000', 
                'password' => Hash::make('Saurav@PFRE2026'),
                'status' => 'active',
                'email_verified_at' => now(),
                'language' => 'en',
                'timezone' => 'Asia/Kolkata',
                'default_dashboard' => 'proprietor_v1',
            ]
        );

        // 3. Assign Proprietor Role
        $proprietor->assignRole($proprietorRole);

        $this->command->info('Proprietor account created for Saurav Chowdhury');
        
        // 4. Create a Sample Agent for testing
        $agent = User::firstOrCreate(
            ['email' => 'agent.test@pfre.in'],
            [
                'name' => 'Mumbai Agent One',
                'phone' => '9830012345',
                'password' => Hash::make('Agent@123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        $agent->assignRole($agentRole);
    }
}