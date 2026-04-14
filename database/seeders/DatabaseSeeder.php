<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
// use App\Models\Permission; // Uncomment when you create the Permission model

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Clear caches before seeding to prevent permission errors
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions() ?? null;

        // 2. Seed System Settings (Migration #20)
        $this->command->info('Seeding System Settings...');
        DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'Property Finder Real Estate', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'established_date', 'value' => '2018-08-01', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'base_location', 'value' => 'Mumbai, Maharashtra', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'gst_percentage', 'value' => '18', 'group' => 'finance', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_metro_cess', 'value' => '1', 'group' => 'finance', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Roles (Migration #21)
        $this->command->info('Seeding Roles...');
        $roles = [
            ['name' => 'proprietor', 'display_name' => 'Proprietor', 'is_system_role' => true],
            ['name' => 'sales_manager', 'display_name' => 'Sales Manager', 'is_system_role' => true],
            ['name' => 'field_agent', 'display_name' => 'Field Agent', 'is_system_role' => false],
            ['name' => 'accountant', 'display_name' => 'Accountant', 'is_system_role' => true],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']], 
                [
                    'display_name' => $roleData['display_name'],
                    'is_system_role' => $roleData['is_system_role'],
                ]
            );
        }

        // 4. Seed the Primary Admin User (Proprietor)
        $this->command->info('Seeding Admin User...');
        $proprietorUser = User::firstOrCreate(
            ['email' => 'saurav@propertyfinder.com'], // Update with your actual login email if needed
            [
                'name' => 'Saurav Chowdhury',
                'password' => Hash::make('Secret@123'), // ALWAYS change this in production
                'email_verified_at' => now(),
            ]
        );

        // 5. Assign Proprietor Role to the User
        $proprietorRole = Role::where('name', 'proprietor')->first();
        if ($proprietorRole && !$proprietorUser->roles()->where('role_id', $proprietorRole->id)->exists()) {
            $proprietorUser->roles()->attach($proprietorRole->id);
            $this->command->info('Proprietor role assigned successfully.');
        }

        // 6. Optional: Call separate seeders for dummy data
        // If you create separate files like LeadSeeder.php or PropertySeeder.php later,
        // you can call them here to populate your dashboard for testing.
        /*
        $this->call([
            PermissionSeeder::class,
            PropertySeeder::class,
            LeadSeeder::class,
        ]);
        */

        $this->command->info('PFRE-Omni Database Seeded Successfully! 🚀');
    }
}