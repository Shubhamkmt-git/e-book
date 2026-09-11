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
            // 1. Main: Dashboard
            [
                'title' => 'Dashboard',
                'slug' => 'dashboard',
                'description' => 'Full access to view and interact with the administrative dashboard overview and metrics.',
                'status' => 'active',
            ],

            // 2. User Manage: Admin User
            [
                'title' => 'Admin User',
                'slug' => 'admin-user',
                'description' => 'Full combined access to all admin user operations: list, view, create, edit, toggle status, and delete.',
                'status' => 'active',
            ],

            // 3. User Manage: Role
            [
                'title' => 'Role',
                'slug' => 'role',
                'description' => 'Full combined access to all role operations: list, view, create, edit, toggle status, and delete.',
                'status' => 'active',
            ],

            // 4. User Manage: Permission
            [
                'title' => 'Permission',
                'slug' => 'permission',
                'description' => 'Full combined access to all permission operations: list, view, create, edit, toggle status, and delete.',
                'status' => 'active',
            ],

            // 5. App Setting: Manage
            [
                'title' => 'App Setting',
                'slug' => 'app-setting',
                'description' => 'Full combined access to view and manage general app settings, logos, contact info, social links, and SEO configuration.',
                'status' => 'active',
            ],

            // 6. Categories: Manage
            [
                'title' => 'Category',
                'slug' => 'category',
                'description' => 'Full combined access to all category operations: list, view, create, edit, toggle status, and delete.',
                'status' => 'active',
            ],

            // 7. E-Books: Manage
            [
                'title' => 'E-Book',
                'slug' => 'ebook',
                'description' => 'Full combined access to all e-book operations: list, view, create, edit, toggle status, and delete.',
                'status' => 'active',
            ],

            // 8. Orders & Purchases
            [
                'title' => 'Order',
                'slug' => 'order',
                'description' => 'Full combined access to view orders, update order payment/fulfillment statuses, and delete records.',
                'status' => 'active',
            ],

            // 9. Customers Management
            [
                'title' => 'Customer',
                'slug' => 'customer',
                'description' => 'Full combined access to view customer accounts, manage customer statuses, and customer records.',
                'status' => 'active',
            ],

            // 10. Hero Banners
            [
                'title' => 'Hero Banner',
                'slug' => 'hero-banner',
                'description' => 'Full combined access to list, create, edit, reorder, toggle status, and delete homepage hero banners.',
                'status' => 'active',
            ],

            // 11. Book of the Week / Spotlight
            [
                'title' => 'Spotlight',
                'slug' => 'spotlight',
                'description' => 'Full combined access to configure the Book of the Week spotlight promotion and callouts.',
                'status' => 'active',
            ],

            // 12. FAQs Management
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'description' => 'Full combined access to list, create, edit, toggle status, and delete frequently asked questions.',
                'status' => 'active',
            ],

            // 13. Call-To-Action (CTA) Management
            [
                'title' => 'CTA',
                'slug' => 'cta',
                'description' => 'Full combined access to manage community and newsletter call-to-action sections.',
                'status' => 'active',
            ],

            // 14. Testimonials Management
            [
                'title' => 'Testimonial',
                'slug' => 'testimonial',
                'description' => 'Full combined access to list, create, edit, toggle status, and delete customer reviews and testimonials.',
                'status' => 'active',
            ],

            // 15. Legal Pages (Privacy Policy, Terms of Service)
            [
                'title' => 'Legal Page',
                'slug' => 'legal-page',
                'description' => 'Full combined access to edit Privacy Policy, Terms of Service, and compliance documents.',
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
