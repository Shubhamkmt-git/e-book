<?php

namespace Tests\Feature;

use App\Models\LegalPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_privacy_policy_page(): void
    {
        $response = $this->get(route('privacy-policy'));

        $response->assertOk();
        $response->assertSee('Privacy Policy');
        $response->assertSee('Information We Collect');
    }

    public function test_guest_can_view_terms_of_service_page(): void
    {
        $response = $this->get(route('terms'));

        $response->assertOk();
        $response->assertSee('Terms of Service');
        $response->assertSee('Acceptance of Terms');
    }

    public function test_inactive_legal_page_returns_404(): void
    {
        $page = LegalPage::getBySlug('privacy-policy');
        $page->update(['status' => 'inactive']);

        $response = $this->get(route('privacy-policy'));
        $response->assertNotFound();
    }

    public function test_guest_cannot_access_admin_legal_pages(): void
    {
        $response = $this->get(route('admin.legal-pages.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_legal_pages_index(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.legal-pages.index'));

        $response->assertOk();
        $response->assertSee('Legal Pages & Policies');
        $response->assertSee('Privacy Policy');
        $response->assertSee('Terms of Service');
    }

    public function test_admin_can_view_edit_page(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.legal-pages.edit', 'privacy-policy'));

        $response->assertOk();
        $response->assertSee('Edit Privacy Policy');
    }

    public function test_admin_can_update_legal_page(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('admin.legal-pages.update', 'privacy-policy'), [
            'title' => 'Custom Privacy & Security Policy',
            'subtitle' => 'Custom subtitle description.',
            'content' => '<h2>1. Updated Clause</h2><p>Custom updated content for privacy.</p>',
            'last_updated_date' => '2026-09-11',
            'meta_title' => 'Custom Privacy Meta Title',
            'meta_description' => 'Custom Privacy Meta Description',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.legal-pages.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('legal_pages', [
            'slug' => 'privacy-policy',
            'title' => 'Custom Privacy & Security Policy',
            'meta_title' => 'Custom Privacy Meta Title',
        ]);

        // Verify storefront renders updated content
        $frontResponse = $this->get(route('privacy-policy'));
        $frontResponse->assertOk();
        $frontResponse->assertSee('Custom Privacy & Security Policy');
        $frontResponse->assertSee('Custom updated content for privacy.');
    }

    public function test_admin_can_toggle_legal_page_status(): void
    {
        $admin = User::factory()->create();
        $page = LegalPage::getBySlug('privacy-policy');

        $response = $this->actingAs($admin)
            ->patchJson(route('admin.legal-pages.toggle-status', 'privacy-policy'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'inactive',
        ]);

        $this->assertEquals('inactive', $page->fresh()->status);
    }
}
