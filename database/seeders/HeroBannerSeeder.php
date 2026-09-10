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
        ];

        foreach ($banners as $banner) {
            HeroBanner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
