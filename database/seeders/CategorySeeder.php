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
                'description' => 'Books covering modern programming languages, software engineering, cloud, and AI.',
                'icon' => 'fa-solid fa-laptop-code',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'title' => 'Science & Space',
                'description' => 'Explore the mysteries of astronomy, physics, biology, and scientific breakthroughs.',
                'icon' => 'fa-solid fa-flask',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'title' => 'Business & Finance',
                'description' => 'Guides on entrepreneurship, personal finance, investing, leadership, and marketing.',
                'icon' => 'fa-solid fa-briefcase',
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'title' => 'Literature & Fiction',
                'description' => 'Classic and contemporary literature, poetry, drama, fantasy, and mystery novels.',
                'icon' => 'fa-solid fa-book-open',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 4,
            ],
            [
                'title' => 'Health & Wellness',
                'description' => 'Insightful reads on fitness, mental health, nutrition, and holistic well-being.',
                'icon' => 'fa-solid fa-heart-pulse',
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 5,
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
