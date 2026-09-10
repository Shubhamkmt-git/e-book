<?php

namespace Tests\Feature;

use App\Models\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_can_list_admin_roles(): void
    {
        AdminRole::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.admin-roles.index'));

        $response->assertOk();
        $response->assertSee('Admin Roles');
    }

    public function test_can_view_create_role_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.admin-roles.create'));

        $response->assertOk();
        $response->assertSee('Add Admin Role');
    }

    public function test_can_store_admin_role(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.admin-roles.store'), [
            'title' => 'Finance Auditor',
            'slug' => 'finance-auditor',
            'status' => 'active',
            'description' => 'Reviews billing and payouts.',
        ]);

        $response->assertRedirect(route('admin.admin-roles.index'));
        $this->assertDatabaseHas('admin_roles', [
            'title' => 'Finance Auditor',
            'slug' => 'finance-auditor',
            'status' => 'active',
        ]);
    }

    public function test_can_show_admin_role(): void
    {
        $role = AdminRole::factory()->create([
            'title' => 'Security Analyst',
            'slug' => 'security-analyst',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.admin-roles.show', $role));

        $response->assertOk();
        $response->assertSee('Security Analyst');
        $response->assertSee('security-analyst');
    }

    public function test_can_view_edit_role_page(): void
    {
        $role = AdminRole::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.admin-roles.edit', $role));

        $response->assertOk();
        $response->assertSee('Edit Admin Role');
    }

    public function test_can_update_admin_role(): void
    {
        $role = AdminRole::factory()->create([
            'title' => 'Old Title',
            'slug' => 'old-slug',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.admin-roles.update', $role), [
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
            'status' => 'inactive',
            'description' => 'Updated role description.',
        ]);

        $response->assertRedirect(route('admin.admin-roles.index'));
        $this->assertDatabaseHas('admin_roles', [
            'id' => $role->id,
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
            'status' => 'inactive',
        ]);
    }

    public function test_can_delete_admin_role(): void
    {
        $role = AdminRole::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.admin-roles.destroy', $role));

        $response->assertRedirect(route('admin.admin-roles.index'));
        $this->assertDatabaseMissing('admin_roles', [
            'id' => $role->id,
        ]);
    }
}
