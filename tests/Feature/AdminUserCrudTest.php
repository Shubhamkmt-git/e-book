<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminUserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
        ]);
    }

    public function test_can_list_admin_users(): void
    {
        AdminUser::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.admin-users.index'));

        $response->assertOk();
        $response->assertSee('Admin Users');
    }

    public function test_can_view_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.admin-users.create'));

        $response->assertOk();
        $response->assertSee('Add Admin User');
    }

    public function test_can_store_admin_user(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.admin-users.store'), [
            'name' => 'John Admin',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
            'status' => 'active',
            'profile_image' => $file,
        ]);

        $response->assertRedirect(route('admin.admin-users.index'));
        $this->assertDatabaseHas('admin_users', [
            'name' => 'John Admin',
            'email' => 'john@example.com',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $user = AdminUser::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->profile_image);
        Storage::disk('public')->assertExists($user->profile_image);
    }

    public function test_can_show_admin_user(): void
    {
        $adminUser = AdminUser::factory()->create([
            'name' => 'Alice Admin',
            'email' => 'alice@example.com',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.admin-users.show', $adminUser));

        $response->assertOk();
        $response->assertSee('Alice Admin');
        $response->assertSee('alice@example.com');
    }

    public function test_can_view_edit_page(): void
    {
        $adminUser = AdminUser::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.admin-users.edit', $adminUser));

        $response->assertOk();
        $response->assertSee('Edit Admin User');
    }

    public function test_can_update_admin_user(): void
    {
        $adminUser = AdminUser::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'editor',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.admin-users.update', $adminUser), [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'manager',
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('admin.admin-users.index'));
        $this->assertDatabaseHas('admin_users', [
            'id' => $adminUser->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'manager',
            'status' => 'inactive',
        ]);
    }

    public function test_can_delete_admin_user(): void
    {
        $adminUser = AdminUser::factory()->create([
            'role' => 'editor',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.admin-users.destroy', $adminUser));

        $response->assertRedirect(route('admin.admin-users.index'));
        $this->assertDatabaseMissing('admin_users', [
            'id' => $adminUser->id,
        ]);
    }

    public function test_profile_image_is_rendered_in_header_and_sidebar(): void
    {
        AdminUser::factory()->create([
            'email' => $this->admin->email,
            'name' => $this->admin->name,
            'profile_image' => 'https://example.com/admin-avatar.jpg',
            'status' => 'active',
            'role' => 'super-admin',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('https://example.com/admin-avatar.jpg');
    }
}
