<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Technology & Programming',
                'description' => 'Books covering modern programming languages, software engineering, cloud architectures, and scalable systems.',
                'icon' => 'fa-solid fa-laptop-code',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'title' => 'Sci-Fi & Fantasy',
                'description' => 'Explore distant galaxies, dystopian futures, quantum anomalies, and mythological speculative epics.',
                'icon' => 'fa-solid fa-rocket',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'title' => 'Business & Finance',
                'description' => 'Guides on high-margin entrepreneurship, personal finance, investing, compounding leverage, and leadership.',
                'icon' => 'fa-solid fa-chart-line',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'title' => 'Psychology & Mindset',
                'description' => 'Neuroscience protocols, deep work flow states, cognitive performance, and behavioral psychology.',
                'icon' => 'fa-solid fa-brain',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 4,
            ],
            [
                'title' => 'Design & Arts',
                'description' => 'Visual systems, UI/UX architecture, creative typography, art direction, and digital illustration.',
                'icon' => 'fa-solid fa-palette',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 5,
            ],
            [
                'title' => 'AI & Data Science',
                'description' => 'Machine learning algorithms, neural network foundations, LLMs, and large-scale data engineering.',
                'icon' => 'fa-solid fa-microchip',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 6,
            ],
            [
                'title' => 'Science & Space',
                'description' => 'Astrophysics, theoretical quantum physics, cosmology, biology, and cutting-edge scientific discoveries.',
                'icon' => 'fa-solid fa-flask',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 7,
            ],
            [
                'title' => 'History & Politics',
                'description' => 'Civilization milestones, geopolitical strategy, historical biographies, and institutional development.',
                'icon' => 'fa-solid fa-landmark',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 8,
            ],
            [
                'title' => 'Self-Growth & Mastery',
                'description' => 'Habit architecture, peak productivity frameworks, emotional resilience, and lifelong learning.',
                'icon' => 'fa-solid fa-arrow-trend-up',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 9,
            ],
            [
                'title' => 'Biographies & Memoirs',
                'description' => 'First-hand memoirs and biographies of pioneering innovators, world leaders, and historical figures.',
                'icon' => 'fa-solid fa-feather-pointed',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 10,
            ],
            [
                'title' => 'Health & Longevity',
                'description' => 'Circadian optimization, nutritional science, physical conditioning, and preventative health protocols.',
                'icon' => 'fa-solid fa-heart-pulse',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 11,
            ],
            [
                'title' => 'Academic & Engineering',
                'description' => 'Comprehensive university-level textbooks, rigorous engineering blueprints, and reference guides.',
                'icon' => 'fa-solid fa-graduation-cap',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 12,
            ],
            [
                'title' => 'Mystery & Thriller',
                'description' => 'High-stakes suspense novels, investigative mysteries, psychological thrillers, and legal dramas.',
                'icon' => 'fa-solid fa-mask',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 13,
            ],
            [
                'title' => 'Marketing & Strategy',
                'description' => 'Product-led growth, customer acquisition flywheels, brand positioning, and narrative design.',
                'icon' => 'fa-solid fa-bullhorn',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 14,
            ],
            [
                'title' => 'Philosophy & Ethics',
                'description' => 'Classical philosophy, modern ethics, stoicism, existential inquiry, and mental clarity.',
                'icon' => 'fa-solid fa-book-bookmark',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 15,
            ],
            [
                'title' => 'Economics & Wealth',
                'description' => 'Macroeconomics, market mechanisms, monetary policy, and asymmetric capital allocation.',
                'icon' => 'fa-solid fa-coins',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 16,
            ],
            [
                'title' => 'Literature & Fiction',
                'description' => 'Classic literature, contemporary novels, narrative poetry, and celebrated literary works.',
                'icon' => 'fa-solid fa-book-open',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 17,
            ],
            [
                'title' => 'Photography & Cinema',
                'description' => 'Visual composition, cinematographic lighting, visual storytelling, and photographic mastery.',
                'icon' => 'fa-solid fa-camera',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 18,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['title'])],
                array_merge($category, ['slug' => Str::slug($category['title'])])
            );
        }
    }
}
