<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techCat = Category::where('slug', 'technology-programming')->orWhere('slug', 'tech-coding')->first();
        $scienceCat = Category::where('slug', 'science-space')->first();
        $bizCat = Category::where('slug', 'business-finance')->first();
        $scifiCat = Category::where('slug', 'sci-fi-fantasy')->first() ?? $scienceCat;
        $psychCat = Category::where('slug', 'psychology-mindset')->orWhere('slug', 'psychology')->first();
        $designCat = Category::where('slug', 'design-arts')->first();

        $books = [
            [
                'title' => 'Algorithms & Elegance',
                'author_name' => 'Prof. Julian Hayes',
                'category_id' => $techCat?->id,
                'price' => 999.00,
                'selling_price' => 499.00,
                'status' => 'active',
                'is_featured' => true,
                'pages' => 412,
                'language' => 'English',
                'format' => 'PDF, EPUB & MOBI',
                'file_size' => '18.4 MB',
                'cover_image' => 'images/books/algorithms.jpg',
                'description' => 'A masterclass in crafting resilient, performant, and elegant code. Delve into advanced data structures, algorithmic design patterns, computational complexity, and modern software paradigms designed to scale from zero to millions of operations per second.',
                'key_highlights' => "Master dynamic programming, graph theory, and algorithmic time-space complexity trade-offs\nProduction-grade implementation blueprints in Python, Go, and TypeScript with memory profiling\nDesign robust distributed caching and cache invalidation strategies with zero stale reads\nDeep dive into memory layout, CPU cache locality, branch prediction, and parallel execution",
                'suggested_for' => ['Software Engineers & Developers', 'Students & Academics', 'Researchers & Scientists'],
            ],
            [
                'title' => 'Quantum Frontiers: Next Century',
                'author_name' => 'Dr. Evelyn Vance',
                'category_id' => $scienceCat?->id,
                'price' => 1499.00,
                'selling_price' => 699.00,
                'status' => 'active',
                'is_featured' => true,
                'pages' => 384,
                'language' => 'English',
                'format' => 'EPUB & PDF',
                'file_size' => '24.2 MB',
                'cover_image' => 'images/books/spotlight.jpg',
                'description' => 'An illuminating journey through modern theoretical physics, unraveling quantum entanglement, wormholes, and multi-dimensional spacetime for specialists and curious minds alike.',
                'key_highlights' => "Comprehensive quantum mechanics and quantum information theory\nMathematical foundations of superposition, entanglement, and decoherence\nPractical guide to NISQ computers and fault-tolerant quantum algorithms\nArchitectures for topological quantum computation and error correction",
                'suggested_for' => ['Researchers & Scientists', 'Students & Academics', 'General Readers & Enthusiasts'],
            ],
            [
                'title' => 'Whispers of the Nebula',
                'author_name' => 'S. K. Hawthorne',
                'category_id' => $scifiCat?->id,
                'price' => 699.00,
                'selling_price' => 349.00,
                'status' => 'active',
                'is_featured' => true,
                'pages' => 368,
                'language' => 'English',
                'format' => 'EPUB & PDF',
                'file_size' => '12.8 MB',
                'cover_image' => 'images/books/nebula.jpg',
                'description' => 'An interstellar odyssey across time fractures, quantum anomalies, and lost galactic civilizations that will challenge the destiny of humanity.',
                'key_highlights' => "Expansive hard science fiction world-building grounded in theoretical relativistic physics\nMulti-generational narrative arc across colonized star systems\nComplex characters navigating interplanetary politics and existential dilemmas",
                'suggested_for' => ['General Readers & Enthusiasts', 'Self-Learners & Hobbyists', 'Beginners & Starters'],
            ],
            [
                'title' => 'The Compound Founder',
                'author_name' => 'Marcus Bennett',
                'category_id' => $bizCat?->id,
                'price' => 1199.00,
                'selling_price' => 599.00,
                'status' => 'active',
                'is_featured' => true,
                'pages' => 320,
                'language' => 'English',
                'format' => 'PDF & EPUB',
                'file_size' => '15.1 MB',
                'cover_image' => 'images/books/founder.jpg',
                'description' => 'The strategic playbook for building high-margin, self-sustaining ventures with compounding customer flywheels and unfair competitive advantages.',
                'key_highlights' => "Frameworks for identifying uncrowded market niches with organic pricing power\nUnit economics, negative churn loops, and compounding lifetime value models\nTerm sheet negotiation blueprints and capital allocation principles",
                'suggested_for' => ['Entrepreneurs & Business Leaders', 'Self-Learners & Hobbyists', 'Beginners & Starters'],
            ],
            [
                'title' => 'Atomic Focus',
                'author_name' => 'Dr. Aris Thorne',
                'category_id' => $psychCat?->id,
                'price' => 599.00,
                'selling_price' => 299.00,
                'status' => 'active',
                'is_featured' => true,
                'pages' => 280,
                'language' => 'English',
                'format' => 'EPUB & PDF',
                'file_size' => '9.5 MB',
                'cover_image' => 'images/books/atomic.jpg',
                'description' => 'Unpack the neuroscience of attention, cognitive stamina, and state-of-flow design to conquer digital distractions and achieve deep mastery.',
                'key_highlights' => "Neurobiology of dopamine regulation and attention gating mechanisms\nDesigning zero-friction deep work environments with ritualized cadence\nDefeating procrastination loops with micro-commitments and habit stacking",
                'suggested_for' => ['Software Engineers & Developers', 'Students & Academics', 'Entrepreneurs & Business Leaders'],
            ],
            [
                'title' => 'The Art of Quantum Computing',
                'author_name' => 'Dr. Maya Patel',
                'category_id' => $techCat?->id,
                'price' => 1999.00,
                'selling_price' => 1199.00,
                'status' => 'active',
                'is_featured' => false,
                'pages' => 450,
                'language' => 'English',
                'format' => 'PDF & EPUB',
                'file_size' => '28.0 MB',
                'cover_image' => 'images/books/spotlight.jpg',
                'description' => 'An in‑depth guide that bridges quantum theory and practical algorithm design. Explore qubit architectures, quantum error correction, and real‑world applications.',
                'key_highlights' => "Comprehensive quantum mechanics refresher\nStep‑by‑step implementation of quantum algorithms\nPractical guidance on using cloud‑based quantum processors",
                'suggested_for' => ['Researchers & Scientists', 'Software Engineers & Developers', 'Students & Academics'],
            ],
        ];

        foreach ($books as $book) {
            $slug = Str::slug($book['title']);
            Book::updateOrCreate(
                ['slug' => $slug],
                array_merge($book, ['slug' => $slug])
            );
        }
    }
}
