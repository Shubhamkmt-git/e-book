<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Category dataset definition
     */
    protected array $categories = [
        'sci-fi-fantasy' => [
            'name' => 'Sci-Fi & Fantasy',
            'slug' => 'sci-fi-fantasy',
            'icon' => 'fa-solid fa-rocket',
            'count' => '4,280 Titles',
            'description' => 'Explore distant galaxies, dystopian futures, quantum realms, and mythological epics penned by visionary world-builders.',
            'books' => [
                [
                    'title' => 'Whispers of the Nebula',
                    'author' => 'S. K. Hawthorne',
                    'price' => '₹349',
                    'original_price' => '₹699',
                    'rating' => '4.8',
                    'reviews' => '980',
                    'image' => 'images/books/nebula.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Quantum Frontiers: Next Century',
                    'author' => 'Dr. Evelyn Vance',
                    'price' => '₹699',
                    'original_price' => '₹1,499',
                    'rating' => '5.0',
                    'reviews' => '4,820',
                    'image' => 'images/books/spotlight.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'The Cybernetic Odyssey',
                    'author' => 'Aria Sterling',
                    'price' => '₹449',
                    'original_price' => '₹899',
                    'rating' => '4.9',
                    'reviews' => '1,120',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Echoes of the Solar Void',
                    'author' => 'Kaelen Voss',
                    'price' => '₹399',
                    'original_price' => '₹799',
                    'rating' => '4.7',
                    'reviews' => '840',
                    'image' => 'images/books/nebula.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Starlight Dominion',
                    'author' => 'Cassian Drake',
                    'price' => '₹529',
                    'original_price' => '₹999',
                    'rating' => '4.9',
                    'reviews' => '2,310',
                    'image' => 'images/books/spotlight.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Chronicles of the Silicon Age',
                    'author' => 'Mira Thorne',
                    'price' => '₹419',
                    'original_price' => '₹849',
                    'rating' => '4.8',
                    'reviews' => '1,450',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'The Andromeda Protocol',
                    'author' => 'T. R. Mercer',
                    'price' => '₹479',
                    'original_price' => '₹950',
                    'rating' => '4.9',
                    'reviews' => '1,890',
                    'image' => 'images/books/nebula.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Voidwalker: Origins',
                    'author' => 'Logan Hayes',
                    'price' => '₹389',
                    'original_price' => '₹750',
                    'rating' => '4.8',
                    'reviews' => '960',
                    'image' => 'images/books/spotlight.jpg',
                    'format' => 'EPUB & PDF',
                ],
            ],
        ],
        'tech-coding' => [
            'name' => 'Tech & Coding',
            'slug' => 'tech-coding',
            'icon' => 'fa-solid fa-laptop-code',
            'count' => '3,120 Titles',
            'description' => 'Master modern programming languages, distributed cloud architecture, clean system design, and algorithmic thinking.',
            'books' => [
                [
                    'title' => 'Algorithms & Elegance',
                    'author' => 'Prof. Julian Hayes',
                    'price' => '₹499',
                    'original_price' => '₹999',
                    'rating' => '4.9',
                    'reviews' => '1,420',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Designing Distributed Systems',
                    'author' => 'Brendan Caldwell',
                    'price' => '₹649',
                    'original_price' => '₹1,299',
                    'rating' => '5.0',
                    'reviews' => '3,110',
                    'image' => 'images/books/spotlight.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Full-Stack Architecture Patterns',
                    'author' => 'Devon Vance',
                    'price' => '₹549',
                    'original_price' => '₹1,099',
                    'rating' => '4.9',
                    'reviews' => '2,040',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'The Pragmatic Codebase',
                    'author' => 'Liam O\'Connor',
                    'price' => '₹399',
                    'original_price' => '₹799',
                    'rating' => '4.8',
                    'reviews' => '1,630',
                    'image' => 'images/books/founder.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Modern DevOps & Cloud Mastery',
                    'author' => 'Sanjay Patel',
                    'price' => '₹599',
                    'original_price' => '₹1,199',
                    'rating' => '4.9',
                    'reviews' => '2,400',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Rust for High-Scale Systems',
                    'author' => 'Astrid Lind',
                    'price' => '₹699',
                    'original_price' => '₹1,399',
                    'rating' => '5.0',
                    'reviews' => '1,890',
                    'image' => 'images/books/spotlight.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Database Internals Deep Dive',
                    'author' => 'Alex Petrov',
                    'price' => '₹579',
                    'original_price' => '₹1,150',
                    'rating' => '4.9',
                    'reviews' => '1,720',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Clean API Engineering',
                    'author' => 'Rachel Zhang',
                    'price' => '₹469',
                    'original_price' => '₹899',
                    'rating' => '4.8',
                    'reviews' => '1,280',
                    'image' => 'images/books/founder.jpg',
                    'format' => 'EPUB & PDF',
                ],
            ],
        ],
        'business-finance' => [
            'name' => 'Business & Finance',
            'slug' => 'business-finance',
            'icon' => 'fa-solid fa-chart-line',
            'count' => '2,850 Titles',
            'description' => 'Actionable insights on scaling high-growth ventures, wealth compounding, market economics, and strategic leadership.',
            'books' => [
                [
                    'title' => 'The Compound Founder',
                    'author' => 'Marcus Bennett',
                    'price' => '₹599',
                    'original_price' => '₹1,199',
                    'rating' => '5.0',
                    'reviews' => '2,110',
                    'image' => 'images/books/founder.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Zero to Market Leader',
                    'author' => 'Victoria Sterling',
                    'price' => '₹499',
                    'original_price' => '₹999',
                    'rating' => '4.9',
                    'reviews' => '1,870',
                    'image' => 'images/books/atomic.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Capital Allocation Strategies',
                    'author' => 'Jonathan Reid',
                    'price' => '₹699',
                    'original_price' => '₹1,399',
                    'rating' => '4.8',
                    'reviews' => '940',
                    'image' => 'images/books/founder.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'The Psychology of Money Mastery',
                    'author' => 'David Thorne',
                    'price' => '₹399',
                    'original_price' => '₹799',
                    'rating' => '4.9',
                    'reviews' => '3,450',
                    'image' => 'images/books/atomic.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Scale: The Playbook for 10x Growth',
                    'author' => 'Claire Davenport',
                    'price' => '₹549',
                    'original_price' => '₹1,099',
                    'rating' => '4.9',
                    'reviews' => '1,920',
                    'image' => 'images/books/founder.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Venture Math & Equity Building',
                    'author' => 'Ethan Ross',
                    'price' => '₹479',
                    'original_price' => '₹950',
                    'rating' => '4.8',
                    'reviews' => '1,340',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
            ],
        ],
        'psychology' => [
            'name' => 'Psychology',
            'slug' => 'psychology',
            'icon' => 'fa-solid fa-brain',
            'count' => '5,410 Titles',
            'description' => 'Unpack the human mind, behavioral decision-making, cognitive performance, emotional resilience, and habit loops.',
            'books' => [
                [
                    'title' => 'Atomic Focus',
                    'author' => 'Dr. Aris Thorne',
                    'price' => '₹299',
                    'original_price' => '₹599',
                    'rating' => '4.9',
                    'reviews' => '3,540',
                    'image' => 'images/books/atomic.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'The Neurochemistry of Willpower',
                    'author' => 'Dr. Samantha Cruz',
                    'price' => '₹449',
                    'original_price' => '₹899',
                    'rating' => '5.0',
                    'reviews' => '2,780',
                    'image' => 'images/books/founder.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Subconscious Drivers',
                    'author' => 'Gabriel Moreau',
                    'price' => '₹379',
                    'original_price' => '₹750',
                    'rating' => '4.8',
                    'reviews' => '1,620',
                    'image' => 'images/books/atomic.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Mastery Over Modern Anxiety',
                    'author' => 'Elena Vasquez',
                    'price' => '₹349',
                    'original_price' => '₹699',
                    'rating' => '4.9',
                    'reviews' => '2,910',
                    'image' => 'images/books/spotlight.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Cognitive Biases at Work',
                    'author' => 'Prof. Harold Jenkins',
                    'price' => '₹499',
                    'original_price' => '₹999',
                    'rating' => '4.8',
                    'reviews' => '1,450',
                    'image' => 'images/books/atomic.jpg',
                    'format' => 'EPUB & PDF',
                ],
            ],
        ],
        'design-arts' => [
            'name' => 'Design & Arts',
            'slug' => 'design-arts',
            'icon' => 'fa-solid fa-palette',
            'count' => '1,940 Titles',
            'description' => 'Timeless visual aesthetics, UI/UX interaction systems, typography masterclasses, and product branding philosophy.',
            'books' => [
                [
                    'title' => 'Visual Geometry in Modern UI',
                    'author' => 'Mateo Bianchi',
                    'price' => '₹499',
                    'original_price' => '₹999',
                    'rating' => '4.9',
                    'reviews' => '1,560',
                    'image' => 'images/books/algorithms.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'The Typography Bible',
                    'author' => 'Chloe Dubois',
                    'price' => '₹599',
                    'original_price' => '₹1,199',
                    'rating' => '5.0',
                    'reviews' => '2,430',
                    'image' => 'images/books/spotlight.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Design Systems at Global Scale',
                    'author' => 'Liam Sterling',
                    'price' => '₹549',
                    'original_price' => '₹1,099',
                    'rating' => '4.8',
                    'reviews' => '1,890',
                    'image' => 'images/books/founder.jpg',
                    'format' => 'EPUB & PDF',
                ],
                [
                    'title' => 'Color Theory for Digital Screens',
                    'author' => 'Anais Nin Rivera',
                    'price' => '₹399',
                    'original_price' => '₹799',
                    'rating' => '4.9',
                    'reviews' => '1,120',
                    'image' => 'images/books/atomic.jpg',
                    'format' => 'EPUB & PDF',
                ],
            ],
        ],
    ];

    /**
     * Show all categories directory
     */
    public function index(): View
    {
        $allCategories = [
            ['name' => 'Sci-Fi & Fantasy', 'slug' => 'sci-fi-fantasy', 'count' => '4,280 Titles', 'icon' => 'fa-solid fa-rocket'],
            ['name' => 'Tech & Coding', 'slug' => 'tech-coding', 'count' => '3,120 Titles', 'icon' => 'fa-solid fa-laptop-code'],
            ['name' => 'Business & Finance', 'slug' => 'business-finance', 'count' => '2,850 Titles', 'icon' => 'fa-solid fa-chart-line'],
            ['name' => 'Psychology', 'slug' => 'psychology', 'count' => '5,410 Titles', 'icon' => 'fa-solid fa-brain'],
            ['name' => 'Design & Arts', 'slug' => 'design-arts', 'count' => '1,940 Titles', 'icon' => 'fa-solid fa-palette'],
            ['name' => 'AI & Data Science', 'slug' => 'tech-coding', 'count' => '2,310 Titles', 'icon' => 'fa-solid fa-microchip'],
            ['name' => 'Science & Physics', 'slug' => 'sci-fi-fantasy', 'count' => '1,890 Titles', 'icon' => 'fa-solid fa-atom'],
            ['name' => 'History & Politics', 'slug' => 'business-finance', 'count' => '3,650 Titles', 'icon' => 'fa-solid fa-landmark'],
            ['name' => 'Self-Growth', 'slug' => 'psychology', 'count' => '4,780 Titles', 'icon' => 'fa-solid fa-arrow-trend-up'],
            ['name' => 'Biographies', 'slug' => 'business-finance', 'count' => '2,150 Titles', 'icon' => 'fa-solid fa-feather-pointed'],
            ['name' => 'Health & Longevity', 'slug' => 'psychology', 'count' => '1,670 Titles', 'icon' => 'fa-solid fa-heart-pulse'],
            ['name' => 'Academic Textbooks', 'slug' => 'tech-coding', 'count' => '6,200 Titles', 'icon' => 'fa-solid fa-graduation-cap'],
            ['name' => 'Mystery & Thriller', 'slug' => 'sci-fi-fantasy', 'count' => '3,490 Titles', 'icon' => 'fa-solid fa-mask'],
            ['name' => 'Marketing & Sales', 'slug' => 'business-finance', 'count' => '1,840 Titles', 'icon' => 'fa-solid fa-bullhorn'],
            ['name' => 'Philosophy', 'slug' => 'psychology', 'count' => '2,080 Titles', 'icon' => 'fa-solid fa-book-bookmark'],
            ['name' => 'Young Adult', 'slug' => 'sci-fi-fantasy', 'count' => '2,920 Titles', 'icon' => 'fa-solid fa-wand-magic-sparkles'],
            ['name' => 'Poetry & Essays', 'slug' => 'design-arts', 'count' => '1,120 Titles', 'icon' => 'fa-solid fa-pen-nib'],
            ['name' => 'Economics', 'slug' => 'business-finance', 'count' => '2,460 Titles', 'icon' => 'fa-solid fa-coins'],
            ['name' => 'Law & Ethics', 'slug' => 'business-finance', 'count' => '1,530 Titles', 'icon' => 'fa-solid fa-scale-balanced'],
            ['name' => 'Photography & Cinema', 'slug' => 'design-arts', 'count' => '1,380 Titles', 'icon' => 'fa-solid fa-camera'],
        ];

        return view('frontend.categories.index', compact('allCategories'));
    }

    /**
     * Show books under a specific category
     */
    public function show(string $slug): View
    {
        $normalizedSlug = Str::slug($slug);

        if (isset($this->categories[$normalizedSlug])) {
            $category = $this->categories[$normalizedSlug];
        } else {
            // Generate fallback category representation if arbitrary slug is requested
            $name = Str::headline($slug);
            $category = [
                'name' => $name,
                'slug' => $normalizedSlug,
                'icon' => 'fa-solid fa-book-open',
                'count' => '1,200+ Titles',
                'description' => "Explore our hand-picked collection of bestseller e-books, academic guides, and curated publications under {$name}.",
                'books' => $this->categories['tech-coding']['books'],
            ];
        }

        // Sibling categories for quick switcher
        $otherCategories = collect($this->categories)
            ->filter(fn ($c) => $c['slug'] !== $category['slug'])
            ->take(5)
            ->values()
            ->all();

        return view('frontend.categories.show', compact('category', 'otherCategories'));
    }
}
