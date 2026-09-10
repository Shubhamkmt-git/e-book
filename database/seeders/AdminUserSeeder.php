<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminPassword = Hash::make('password');

        // 1. Seed Main Super Admin Account
        AdminUser::updateOrCreate(
            ['email' => 'admin@ebook.com'],
            [
                'name' => 'Super Administrator',
                'password' => $superAdminPassword,
                'role' => 'super-admin',
                'status' => 'active',
                'profile_image' => null,
            ]
        );

        // Keep matching User record in sync for standard session provider
        User::updateOrCreate(
            ['email' => 'admin@ebook.com'],
            [
                'name' => 'Super Administrator',
                'password' => $superAdminPassword,
            ]
        );

        // 2. Seed Content Manager Account
        AdminUser::updateOrCreate(
            ['email' => 'manager@ebook.com'],
            [
                'name' => 'Sarah Content Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'status' => 'active',
                'profile_image' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@ebook.com'],
            [
                'name' => 'Sarah Content Manager',
                'password' => Hash::make('password'),
            ]
        );
    }
}
