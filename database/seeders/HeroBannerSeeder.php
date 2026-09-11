<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use Illuminate\Database\Seeder;

class HeroBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Discover, Read & Collect',
                'title_l2' => 'Your Favorite E-Books.',
                'description' => 'Your premier digital library for bestselling novels, academic textbooks, technology guides, and independent literature. Read seamlessly across all your devices anytime, anywhere.',
                'primary_button' => 'Explore Library',
                'primary_button_link' => '/ebooks',
                'secondary_button' => 'Browse Genres',
                'secondary_button_link' => '/categories',
                'is_active' => true,
                'sort_order' => 1,
                'banner_image' => null,
            ],
            [
                'title' => 'Unlock Limitless Knowledge',
                'title_l2' => 'Read Anywhere, Anytime.',
                'description' => 'Dive into thousands of curated digital titles across technology, business, science, fiction, and self-growth with instant downloads and lifetime access.',
                'primary_button' => 'Browse New Releases',
                'primary_button_link' => '/ebooks',
                'secondary_button' => 'View Best Sellers',
                'secondary_button_link' => '/#browse',
                'is_active' => true,
                'sort_order' => 2,
                'banner_image' => null,
            ],
        ];

        foreach ($banners as $banner) {
            HeroBanner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
