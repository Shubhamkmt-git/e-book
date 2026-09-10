<?php

namespace Database\Seeders;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissionIds = AdminPermission::pluck('id')->all();

        // 1. Super Admin Role (All Permissions)
        $superAdmin = AdminRole::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'title' => 'Super Administrator',
                'status' => 'active',
                'description' => 'Unrestricted master authority across all console modules and system permissions.',
            ]
        );
        $superAdmin->permissions()->sync($allPermissionIds);

        // 2. Admin Role (All permissions)
        $admin = AdminRole::updateOrCreate(
            ['slug' => 'admin'],
            [
                'title' => 'Administrator',
                'status' => 'active',
                'description' => 'Full administrative access to all console features.',
            ]
        );
        $admin->permissions()->sync($allPermissionIds);

        // 3. Manager Role (Dashboard & Admin User)
        $managerPermissions = AdminPermission::whereIn('slug', ['dashboard', 'admin-user'])
            ->pluck('id')
            ->all();

        $manager = AdminRole::updateOrCreate(
            ['slug' => 'manager'],
            [
                'title' => 'Manager',
                'status' => 'active',
                'description' => 'Access to dashboard overview and admin user management.',
            ]
        );
        $manager->permissions()->sync($managerPermissions);

        // 4. Editor Role (Dashboard only)
        $editorPermissions = AdminPermission::whereIn('slug', ['dashboard'])
            ->pluck('id')
            ->all();

        $editor = AdminRole::updateOrCreate(
            ['slug' => 'editor'],
            [
                'title' => 'Editor',
                'status' => 'active',
                'description' => 'Access to dashboard view.',
            ]
        );
        $editor->permissions()->sync($editorPermissions);
    }
}
