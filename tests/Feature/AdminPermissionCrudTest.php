<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPermissionCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_can_list_admin_permissions(): void
    {
        AdminPermission::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.admin-permissions.index'));

        $response->assertOk();
        $response->assertSee('Admin Permissions');
    }

    public function test_can_view_create_permission_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.admin-permissions.create'));

        $response->assertOk();
        $response->assertSee('Add Admin Permission');
    }

    public function test_can_store_admin_permission(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.admin-permissions.store'), [
            'title' => 'Manage Books',
            'slug' => 'manage-books',
            'status' => 'active',
            'description' => 'Can view, create, edit and delete books.',
        ]);

        $response->assertRedirect(route('admin.admin-permissions.index'));
        $this->assertDatabaseHas('admin_permissions', [
            'title' => 'Manage Books',
            'slug' => 'manage-books',
            'status' => 'active',
        ]);
    }

    public function test_can_show_admin_permission(): void
    {
        $permission = AdminPermission::factory()->create([
            'title' => 'Edit Settings',
            'slug' => 'edit-settings',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.admin-permissions.show', $permission));

        $response->assertOk();
        $response->assertSee('Edit Settings');
        $response->assertSee('edit-settings');
    }

    public function test_can_view_edit_permission_page(): void
    {
        $permission = AdminPermission::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.admin-permissions.edit', $permission));

        $response->assertOk();
        $response->assertSee('Edit Admin Permission');
    }

    public function test_can_update_admin_permission(): void
    {
        $permission = AdminPermission::factory()->create([
            'title' => 'Old Title',
            'slug' => 'old-slug',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.admin-permissions.update', $permission), [
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
            'status' => 'inactive',
            'description' => 'Updated description.',
        ]);

        $response->assertRedirect(route('admin.admin-permissions.index'));
        $this->assertDatabaseHas('admin_permissions', [
            'id' => $permission->id,
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
            'status' => 'inactive',
        ]);
    }

    public function test_can_delete_admin_permission(): void
    {
        $permission = AdminPermission::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.admin-permissions.destroy', $permission));

        $response->assertRedirect(route('admin.admin-permissions.index'));
        $this->assertDatabaseMissing('admin_permissions', [
            'id' => $permission->id,
        ]);
    }

    public function test_can_assign_permissions_to_role_and_sync_pivot(): void
    {
        $perm1 = AdminPermission::factory()->create(['title' => 'Create Book', 'slug' => 'create-book']);
        $perm2 = AdminPermission::factory()->create(['title' => 'Delete Book', 'slug' => 'delete-book']);

        // Create role with permissions
        $response = $this->actingAs($this->admin)->post(route('admin.admin-roles.store'), [
            'title' => 'Editor Role',
            'slug' => 'editor-role',
            'status' => 'active',
            'permissions' => [$perm1->id, $perm2->id],
        ]);

        $response->assertRedirect(route('admin.admin-roles.index'));
        $role = AdminRole::where('slug', 'editor-role')->firstOrFail();

        $this->assertDatabaseHas('admin_role_permissions', [
            'admin_role_id' => $role->id,
            'admin_permission_id' => $perm1->id,
        ]);
        $this->assertDatabaseHas('admin_role_permissions', [
            'admin_role_id' => $role->id,
            'admin_permission_id' => $perm2->id,
        ]);

        // Update role to have only perm1
        $this->actingAs($this->admin)->put(route('admin.admin-roles.update', $role), [
            'title' => 'Editor Role',
            'slug' => 'editor-role',
            'status' => 'active',
            'permissions' => [$perm1->id],
        ]);

        $this->assertDatabaseHas('admin_role_permissions', [
            'admin_role_id' => $role->id,
            'admin_permission_id' => $perm1->id,
        ]);
        $this->assertDatabaseMissing('admin_role_permissions', [
            'admin_role_id' => $role->id,
            'admin_permission_id' => $perm2->id,
        ]);
    }

    public function test_can_toggle_permission_status(): void
    {
        $permission = AdminPermission::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)->patchJson(route('admin.admin-permissions.toggle-status', $permission));

        $response->assertOk();
        $response->assertJson(['success' => true, 'status' => 'inactive']);
        $this->assertEquals('inactive', $permission->fresh()->status);
    }

    public function test_can_toggle_role_status(): void
    {
        $role = AdminRole::factory()->create([
            'slug' => 'editor-role',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin)->patchJson(route('admin.admin-roles.toggle-status', $role));

        $response->assertOk();
        $response->assertJson(['success' => true, 'status' => 'active']);
        $this->assertEquals('active', $role->fresh()->status);
    }

    public function test_super_admin_role_cannot_be_deactivated(): void
    {
        $role = AdminRole::factory()->create([
            'slug' => 'super-admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->patchJson(route('admin.admin-roles.toggle-status', $role));

        $response->assertStatus(422);
        $this->assertEquals('active', $role->fresh()->status);

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.admin-roles.update', $role), [
            'title' => 'Super Admin',
            'slug' => 'super-admin',
            'status' => 'inactive',
        ]);

        $updateResponse->assertSessionHasErrors(['status']);
        $this->assertEquals('active', $role->fresh()->status);
    }

    public function test_super_admin_role_cannot_be_deleted(): void
    {
        $role = AdminRole::factory()->create([
            'slug' => 'super-admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.admin-roles.destroy', $role));

        $response->assertRedirect(route('admin.admin-roles.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('admin_roles', ['id' => $role->id]);
    }
}
