<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\User;
use Database\Seeders\AdminPermissionSeeder;
use Database\Seeders\AdminRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPermissionSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AdminPermissionSeeder::class);
        $this->seed(AdminRoleSeeder::class);
    }

    public function test_super_admin_has_unrestricted_access_to_all_modules(): void
    {
        $superAdmin = AdminUser::factory()->create([
            'email' => 'super@ebook.com',
            'role' => 'super-admin',
            'status' => 'active',
        ]);

        $user = User::factory()->create(['email' => $superAdmin->email]);

        $this->actingAs($user);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.books.index'))->assertOk();
        $this->get(route('admin.orders.index'))->assertOk();
        $this->get(route('admin.app-setting.index'))->assertOk();
    }

    public function test_restricted_role_can_only_access_granted_permission_routes(): void
    {
        // Create custom role with ONLY category permission
        $categoryPerm = AdminPermission::where('slug', 'category')->firstOrFail();
        $customRole = AdminRole::create([
            'title' => 'Category Specialist',
            'slug' => 'category-specialist',
            'status' => 'active',
        ]);
        $customRole->permissions()->sync([$categoryPerm->id]);

        $limitedAdmin = AdminUser::factory()->create([
            'email' => 'category.lead@ebook.com',
            'role' => 'category-specialist',
            'status' => 'active',
        ]);

        $user = User::factory()->create(['email' => $limitedAdmin->email]);

        $this->actingAs($user);

        // Category route should be accessible
        $this->get(route('admin.categories.index'))->assertOk();

        // Other modules should return 403 Forbidden
        $this->get(route('admin.books.index'))->assertForbidden();
        $this->get(route('admin.orders.index'))->assertForbidden();
        $this->get(route('admin.app-setting.index'))->assertForbidden();
        $this->get(route('admin.admin-users.index'))->assertForbidden();
    }

    public function test_inactive_admin_user_is_denied_access(): void
    {
        $inactiveAdmin = AdminUser::factory()->create([
            'email' => 'inactive@ebook.com',
            'role' => 'super-admin',
            'status' => 'inactive',
        ]);

        $user = User::factory()->create(['email' => $inactiveAdmin->email]);

        $this->actingAs($user);

        $this->get(route('admin.categories.index'))->assertForbidden();
    }

    public function test_sidebar_only_renders_links_for_authorized_permissions(): void
    {
        $categoryPerm = AdminPermission::where('slug', 'category')->firstOrFail();
        $customRole = AdminRole::create([
            'title' => 'Catalog Only',
            'slug' => 'catalog-only',
            'status' => 'active',
        ]);
        $customRole->permissions()->sync([$categoryPerm->id]);

        $adminUser = AdminUser::factory()->create([
            'email' => 'catalog@ebook.com',
            'role' => 'catalog-only',
            'status' => 'active',
        ]);

        $user = User::factory()->create(['email' => $adminUser->email]);

        $response = $this->actingAs($user)->get(route('admin.categories.index'));

        $response->assertOk();
        $response->assertSee('Categories');
        $response->assertDontSee('App Setting');
        $response->assertDontSee('Orders');
    }
}
