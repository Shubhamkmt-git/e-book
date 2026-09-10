<?php

namespace Database\Seeders;

use App\Models\Cta;
use Illuminate\Database\Seeder;

class CtaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ctas = [
            [
                'label' => 'Exclusive Reader Gift',
                'title' => 'Get 3 Free Bestseller E-Books Today',
                'subtitle' => 'Join over 120,000+ passionate readers. Receive hand-picked book summaries, author releases, and special reader discounts directly in your inbox.',
                'bg_image' => null,
                'status' => 'active',
                'sort_order' => 1,
            ],
        ];

        foreach ($ctas as $cta) {
            Cta::updateOrCreate(
                ['title' => $cta['title']],
                $cta
            );
        }
    }
}
