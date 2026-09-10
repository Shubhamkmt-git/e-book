<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\AppSetting;
use Database\Seeders\AdminPermissionSeeder;
use Database\Seeders\AdminRoleSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\AppSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppSettingTest extends TestCase
{
    use RefreshDatabase;

    protected AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AdminPermissionSeeder::class,
            AdminRoleSeeder::class,
            AdminUserSeeder::class,
            AppSettingSeeder::class,
        ]);

        $this->admin = AdminUser::where('email', 'admin@ebook.com')->firstOrFail();
    }

    public function test_admin_can_view_app_setting_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.app-setting.index'));

        $response->assertOk();
        $response->assertSee('App Settings');
        $response->assertSee('General Information');
        $response->assertSee('Brand Logos & Favicon', false);
        $response->assertSee('Contact Information');
        $response->assertSee('Social Links');
        $response->assertSee('App SEO Content');
    }

    public function test_guest_cannot_view_app_setting_page(): void
    {
        $response = $this->get(route('admin.app-setting.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_update_app_settings_text_fields(): void
    {
        $payload = [
            'app_name' => 'Acme E-Books',
            'app_short_description' => 'World class reading experience on any device.',
            'contact_email' => 'hello@acmebooks.com',
            'contact_phone' => '+1 (555) 123-4567',
            'contact_whatsapp' => '+1 (555) 987-6543',
            'contact_address' => '123 Fiction Lane, Book City, CA 90210',
            'facebook_url' => 'https://facebook.com/acmebooks',
            'instagram_url' => 'https://instagram.com/acmebooks',
            'twitter_url' => 'https://x.com/acmebooks',
            'linkedin_url' => 'https://linkedin.com/company/acmebooks',
            'youtube_url' => 'https://youtube.com/@acmebooks',
            'meta_title' => 'Acme E-Books - Read Everywhere',
            'meta_description' => 'Discover thousands of digital publications and bestsellers.',
            'meta_keywords' => 'acme, ebooks, reader, library',
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.app-setting.update'), $payload);

        $response->assertRedirect(route('admin.app-setting.index'));
        $response->assertSessionHas('success', 'App settings updated successfully.');

        $this->assertDatabaseHas('app_settings', [
            'id' => 1,
            'app_name' => 'Acme E-Books',
            'contact_email' => 'hello@acmebooks.com',
            'contact_whatsapp' => '+1 (555) 987-6543',
            'twitter_url' => 'https://x.com/acmebooks',
            'meta_title' => 'Acme E-Books - Read Everywhere',
        ]);
    }

    public function test_admin_can_upload_logos_and_favicon(): void
    {
        Storage::fake('public');

        $logoDark = UploadedFile::fake()->image('dark_logo.png', 400, 150);
        $logoLight = UploadedFile::fake()->image('light_logo.png', 400, 150);
        $favicon = UploadedFile::fake()->image('favicon.png', 64, 64);

        $payload = [
            'app_name' => 'E-Book CMS Pro',
            'logo_dark' => $logoDark,
            'logo_light' => $logoLight,
            'favicon' => $favicon,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.app-setting.update'), $payload);

        $response->assertRedirect(route('admin.app-setting.index'));
        $response->assertSessionHas('success');

        $setting = AppSetting::first();
        $this->assertNotNull($setting->logo_dark);
        $this->assertNotNull($setting->logo_light);
        $this->assertNotNull($setting->favicon);

        Storage::disk('public')->assertExists($setting->logo_dark);
        Storage::disk('public')->assertExists($setting->logo_light);
        Storage::disk('public')->assertExists($setting->favicon);
    }

    public function test_admin_can_remove_uploaded_logos(): void
    {
        Storage::fake('public');

        $setting = AppSetting::getSettings();
        $fakePath = 'settings/logos/test_logo.png';
        Storage::disk('public')->put($fakePath, 'fake-content');
        $setting->update(['logo_dark' => $fakePath]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.app-setting.update'), [
                'app_name' => 'E-Book CMS',
                'remove_logo_dark' => 1,
            ]);

        $response->assertRedirect(route('admin.app-setting.index'));

        $setting->refresh();
        $this->assertNull($setting->logo_dark);
        Storage::disk('public')->assertMissing($fakePath);
    }

    public function test_app_setting_url_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.app-setting.update'), [
                'facebook_url' => 'not-a-valid-url',
                'contact_email' => 'not-an-email',
            ]);

        $response->assertSessionHasErrors(['facebook_url', 'contact_email']);
    }
}
