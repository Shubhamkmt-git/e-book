<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $algoBook = Book::where('slug', 'algorithms-and-elegance')->first();
        $quantumBook = Book::where('slug', 'the-art-of-quantum-computing')->first();
        $nebulaBook = Book::where('slug', 'whispers-of-the-nebula')->first();
        $founderBook = Book::where('slug', 'the-compound-founder')->first();

        $testimonials = [
            [
                'name' => 'Sophia Martinez',
                'profession' => 'Principal Software Architect',
                'book_id' => $algoBook?->id,
                'rating' => 5,
                'message' => 'The digital reading experience is buttery smooth. Being able to read technical diagrams seamlessly across both my tablet and laptop made studying on the go effortless.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Raghavan Iyer',
                'profession' => 'Theoretical Physics Professor',
                'book_id' => $quantumBook?->id,
                'rating' => 5,
                'message' => 'Instant offline access to high-quality academic publications and scientific literature has completely transformed how I review new research and prepare lectures.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Elena Rostova',
                'profession' => 'Lead Product Designer',
                'book_id' => $nebulaBook?->id,
                'rating' => 5,
                'message' => 'The typography and dark mode reading view are so easy on the eyes during late-night reading sessions. Easily the cleanest digital library platform I have used.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Marcus Bennett Jr.',
                'profession' => 'Startup Founder & Operator',
                'book_id' => $founderBook?->id,
                'rating' => 5,
                'message' => 'The actionable mental models and cheat-sheets saved our product team weeks of trial and error. The highest ROI purchase for founders this year.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Alexander Hayes',
                'profession' => 'Engineering Director',
                'book_id' => $algoBook?->id,
                'rating' => 5,
                'message' => 'An absolute masterclass in practical execution. The structured mental models and real-world case studies made it effortless to apply immediately.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Priya Sharma',
                'profession' => 'Senior Systems Engineer',
                'book_id' => null,
                'rating' => 5,
                'message' => 'Zero fluff, lightning-fast instant PDF & EPUB downloads, and outstanding platform customer service. E-Book has become my daily reading hub.',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name'], 'message' => $testimonial['message']],
                $testimonial
            );
        }
    }
}
