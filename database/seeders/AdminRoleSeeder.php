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

        // 3. Manager Role (Operations, Users, Catalog, Orders, Marketing, Content)
        $managerPermissions = AdminPermission::whereIn('slug', [
            'dashboard',
            'admin-user',
            'category',
            'ebook',
            'order',
            'customer',
            'hero-banner',
            'spotlight',
            'faq',
            'cta',
            'testimonial',
            'legal-page',
        ])->pluck('id')->all();

        $manager = AdminRole::updateOrCreate(
            ['slug' => 'manager'],
            [
                'title' => 'Manager',
                'status' => 'active',
                'description' => 'Comprehensive access to store operations, e-books, categories, orders, customers, and marketing modules.',
            ]
        );
        $manager->permissions()->sync($managerPermissions);

        // 4. Editor Role (Catalog, Content, CMS)
        $editorPermissions = AdminPermission::whereIn('slug', [
            'dashboard',
            'category',
            'ebook',
            'hero-banner',
            'spotlight',
            'faq',
            'cta',
            'testimonial',
            'legal-page',
        ])->pluck('id')->all();

        $editor = AdminRole::updateOrCreate(
            ['slug' => 'editor'],
            [
                'title' => 'Editor',
                'status' => 'active',
                'description' => 'Content and catalog editor access to books, categories, legal pages, banners, and FAQs.',
            ]
        );
        $editor->permissions()->sync($editorPermissions);
    }
}
