<?php

namespace Database\Seeders;

use App\Models\AdminPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seed only permissions corresponding to current sidebar options with all CRUD/view actions combined.
     */
    public function run(): void
    {
        $permissions = [
            // Main: Dashboard
            [
                'title' => 'Dashboard',
                'slug' => 'dashboard',
                'description' => 'Full access to view and interact with the administrative dashboard overview and metrics.',
                'status' => 'active',
            ],

            // User Manage: Admin User (All actions: list, view, add, edit, delete)
            [
                'title' => 'Admin User',
                'slug' => 'admin-user',
                'description' => 'Full combined access to all admin user operations: list, view, create, edit, and delete.',
                'status' => 'active',
            ],

            // User Manage: Role (All actions: list, view, add, edit, delete)
            [
                'title' => 'Role',
                'slug' => 'role',
                'description' => 'Full combined access to all role operations: list, view, create, edit, and delete.',
                'status' => 'active',
            ],

            // User Manage: Permission (All actions: list, view, add, edit, delete)
            [
                'title' => 'Permission',
                'slug' => 'permission',
                'description' => 'Full combined access to all permission operations: list, view, create, edit, and delete.',
                'status' => 'active',
            ],

            // App Setting: Manage (All actions: view, edit, update)
            [
                'title' => 'App Setting',
                'slug' => 'app-setting',
                'description' => 'Full combined access to view and manage general app settings, logos, contact info, social links, and SEO configuration.',
                'status' => 'active',
            ],

            // Categories: Manage (All actions: list, view, add, edit, delete)
            [
                'title' => 'Category',
                'slug' => 'category',
                'description' => 'Full combined access to all category operations: list, view, create, edit, toggle status, and delete.',
                'status' => 'active',
            ],

            // E-Books: Manage (All actions: list, view, add, edit, delete)
            [
                'title' => 'E-Book',
                'slug' => 'ebook',
                'description' => 'Full combined access to all e-book operations: list, view, create, edit, toggle status, and delete.',
                'status' => 'active',
            ],
        ];

        $currentSlugs = array_column($permissions, 'slug');

        // Remove old / obsolete permissions not in current sidebar options
        $obsoleteIds = AdminPermission::whereNotIn('slug', $currentSlugs)->pluck('id');
        if ($obsoleteIds->isNotEmpty()) {
            DB::table('admin_role_permissions')->whereIn('admin_permission_id', $obsoleteIds)->delete();
            AdminPermission::whereIn('id', $obsoleteIds)->delete();
        }

        // Insert or update current sidebar permissions
        foreach ($permissions as $perm) {
            AdminPermission::updateOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }
    }
}
