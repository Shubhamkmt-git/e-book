<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Jane Admin',
            'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Welcome back, Jane Admin!');
        $response->assertSee('Total Orders');
        $response->assertSee('Dashboard');
    }
}
