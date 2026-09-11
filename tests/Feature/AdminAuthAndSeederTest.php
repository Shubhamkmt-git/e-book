<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use Database\Seeders\AdminPermissionSeeder;
use Database\Seeders\AdminRoleSeeder;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthAndSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_permissions_and_super_admin_role_with_all_permissions(): void
    {
        $this->seed(AdminPermissionSeeder::class);
        $this->seed(AdminRoleSeeder::class);
        $this->seed(AdminUserSeeder::class);

        $totalPermissions = AdminPermission::count();
        $this->assertEquals(15, $totalPermissions);

        $superAdmin = AdminRole::where('slug', 'super-admin')->firstOrFail();
        $this->assertEquals(15, $superAdmin->permissions()->count());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'dashboard')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'admin-user')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'role')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'permission')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'app-setting')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'category')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'ebook')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'order')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'customer')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'hero-banner')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'spotlight')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'faq')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'cta')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'testimonial')->exists());
        $this->assertTrue($superAdmin->permissions()->where('slug', 'legal-page')->exists());

        $adminUser = AdminUser::where('email', 'admin@ebook.com')->firstOrFail();
        $this->assertEquals('super-admin', $adminUser->role);
        $this->assertEquals('active', $adminUser->status);
    }

    public function test_can_dynamically_login_as_admin_user(): void
    {
        AdminUser::create([
            'name' => 'John Super',
            'email' => 'john@admin.com',
            'password' => Hash::make('secret123'),
            'role' => 'super-admin',
            'status' => 'active',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'john@admin.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_invalid_password(): void
    {
        AdminUser::create([
            'name' => 'John Super',
            'email' => 'john@admin.com',
            'password' => Hash::make('secret123'),
            'role' => 'super-admin',
            'status' => 'active',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'john@admin.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_deactivated_admin_user_cannot_login(): void
    {
        AdminUser::create([
            'name' => 'Inactive Admin',
            'email' => 'inactive@admin.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'inactive@admin.com',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors(['email' => 'Your administrative account has been deactivated. Please contact support.']);
        $this->assertGuest();
    }

    public function test_admin_can_logout(): void
    {
        $admin = AdminUser::create([
            'name' => 'Active Admin',
            'email' => 'active@admin.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->post(route('admin.login.submit'), [
            'email' => 'active@admin.com',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticated();

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_can_toggle_admin_user_status(): void
    {
        $admin = AdminUser::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->patchJson(route('admin.admin-users.toggle-status', $admin));

        $response->assertOk();
        $response->assertJson(['success' => true, 'status' => 'inactive']);
        $this->assertEquals('inactive', $admin->fresh()->status);
    }

    public function test_super_admin_user_cannot_be_deactivated(): void
    {
        $superAdmin = AdminUser::factory()->create([
            'role' => 'super-admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($superAdmin)->patchJson(route('admin.admin-users.toggle-status', $superAdmin));

        $response->assertStatus(422);
        $this->assertEquals('active', $superAdmin->fresh()->status);

        $updateResponse = $this->actingAs($superAdmin)->put(route('admin.admin-users.update', $superAdmin), [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'role' => 'super-admin',
            'status' => 'inactive',
        ]);

        $updateResponse->assertSessionHasErrors(['status']);
        $this->assertEquals('active', $superAdmin->fresh()->status);
    }

    public function test_super_admin_user_cannot_be_deleted(): void
    {
        $superAdmin = AdminUser::factory()->create([
            'role' => 'super-admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($superAdmin)->delete(route('admin.admin-users.destroy', $superAdmin));

        $response->assertRedirect(route('admin.admin-users.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('admin_users', ['id' => $superAdmin->id]);
    }
}
