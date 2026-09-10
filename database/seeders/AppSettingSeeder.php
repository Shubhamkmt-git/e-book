<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::updateOrCreate(
            ['id' => 1],
            [
                'app_name' => 'E-Book CMS',
                'app_short_description' => 'A modern e-book reading, publication, and digital asset management platform.',
                'contact_email' => 'support@ebook.com',
                'contact_phone' => '+1 (555) 019-2834',
                'contact_whatsapp' => '+1 (555) 019-2834',
                'contact_address' => '742 Evergreen Terrace, Suite 100, Springfield, OR 97477',
                'facebook_url' => 'https://facebook.com/ebookcms',
                'instagram_url' => 'https://instagram.com/ebookcms',
                'twitter_url' => 'https://x.com/ebookcms',
                'linkedin_url' => 'https://linkedin.com/company/ebookcms',
                'youtube_url' => 'https://youtube.com/@ebookcms',
                'meta_title' => 'E-Book CMS - Read & Discover Digital Books',
                'meta_description' => 'Browse through curated collections of e-books, articles, and interactive publications on our platform.',
                'meta_keywords' => 'ebooks, reading, digital books, library, pdf reader',
            ]
        );
    }
}
