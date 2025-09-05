<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        AdminUser::create([
            'name' => 'Super Admin',
            'email' => 'admin@cafeteria.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'active' => true,
        ]);

        // Create Manager
        AdminUser::create([
            'name' => 'Manager User',
            'email' => 'manager@cafeteria.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'active' => true,
        ]);

        // Create another Manager (inactive for testing)
        AdminUser::create([
            'name' => 'Inactive Manager',
            'email' => 'inactive@cafeteria.com',
            'password' => Hash::make('inactive123'),
            'role' => 'manager',
            'active' => false,
        ]);
    }
}
