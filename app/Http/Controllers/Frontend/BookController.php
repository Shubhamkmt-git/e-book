<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Display all e-books with search & filter capabilities
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $categoryFilter = trim((string) $request->query('category', 'all'));

        $query = Book::with('category')->where('status', 'active');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($cq) use ($search) {
                        $cq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($categoryFilter !== 'all' && $categoryFilter !== '') {
            $query->whereHas('category', function ($cq) use ($categoryFilter) {
                $cq->where('slug', $categoryFilter);
            });
        }

        $dbBooks = $query->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->get();

        $books = $dbBooks->map(function ($b) {
            return $this->formatBookSummary($b);
        })->values()->all();

        $totalCount = Book::where('status', 'active')->count();

        // Active Categories for filtering
        $dbCategories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $categories = [
            ['name' => 'All Genres', 'slug' => 'all'],
        ];

        foreach ($dbCategories as $cat) {
            $categories[] = [
                'name' => $cat->title,
                'slug' => $cat->slug,
            ];
        }

        return view('frontend.books.index', [
            'books' => $books,
            'categories' => $categories,
            'activeCategory' => $categoryFilter ?: 'all',
            'searchQuery' => $search,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Find a catalogue book by its ID or slug.
     *
     * @return array<string, mixed>|null
     */
    public function findBook(string|int $identifier): ?array
    {
        $dbBook = Book::with(['category', 'testimonials' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order')->orderByDesc('created_at');
        }])
            ->where('status', 'active')
            ->where(function ($q) use ($identifier) {
                $q->where('slug', (string) $identifier)
                    ->orWhere('id', (string) $identifier);
            })
            ->first();

        if (! $dbBook) {
            return null;
        }

        return $this->formatBookDetails($dbBook);
    }

    /**
     * Display individual book detail page as a dedicated Landing Page
     */
    public function show(string|int $identifier): View
    {
        $book = $this->findBook($identifier);

        if (! $book) {
            // Fallback to first active book or 404
            $firstBook = Book::where('status', 'active')->first();
            if ($firstBook) {
                $book = $this->formatBookDetails($firstBook);
            } else {
                abort(404, 'Book not found.');
            }
        }

        $landingData = [
            'reading_time' => sprintf('~%.1f Hours', max(2.0, ($book['pages'] ?? 300) / 60)),
            'target_audience' => $book['target_audience'] ?? 'Engineers, Professionals, Researchers & Readers',
            'landing_badge' => $book['badge'] ?? 'Featured Edition',
            'author_bio' => $book['author_bio'] ?? ($book['author'].' is an acclaimed author and industry specialist in '.($book['category'] ?? 'this discipline').'.'),
            'book_quote' => '“Simplicity is prerequisite for reliability. Master the underlying computational patterns, and the scale takes care of itself.”',
            'takeaways' => [
                [
                    'icon' => 'fa-solid fa-lightbulb',
                    'title' => 'Core Mental Models & Principles',
                    'desc' => 'Understand the foundational theory and mental frameworks that separate surface-level knowledge from deep mastery.',
                ],
                [
                    'icon' => 'fa-solid fa-layer-group',
                    'title' => 'Battle-Tested Actionable Frameworks',
                    'desc' => 'Zero academic filler. Every chapter delivers concrete templates, diagrams, and blueprints you can apply immediately.',
                ],
                [
                    'icon' => 'fa-solid fa-chart-line',
                    'title' => 'Proven Case Studies & Real Scenarios',
                    'desc' => 'Deconstruct real-world challenges, trade-offs, and breakdown points with detailed step-by-step walkthroughs.',
                ],
                [
                    'icon' => 'fa-solid fa-gem',
                    'title' => 'Compounding Long-Term Leverage',
                    'desc' => 'Gain timeless principles and competitive advantages designed to compound in value across your entire career.',
                ],
            ],
            'bonuses' => [
                [
                    'icon' => 'fa-solid fa-file-pdf',
                    'title' => 'Universal Multi-Format Bundle',
                    'desc' => 'DRM-free PDF & reflowable EPUB optimized for Kindle, iPad, Kobo, and desktop readers.',
                    'value' => 'Included ($19 Value)',
                ],
                [
                    'icon' => 'fa-solid fa-file-lines',
                    'title' => 'Executive Summary Cheat-Sheet',
                    'desc' => 'Printable high-resolution reference sheet summarizing every key concept and framework.',
                    'value' => 'Free Bonus ($29 Value)',
                ],
                [
                    'icon' => 'fa-solid fa-list-check',
                    'title' => 'Interactive Study & Action Checklist',
                    'desc' => 'Step-by-step implementation guide to turn book insights into daily execution.',
                    'value' => 'Free Bonus ($19 Value)',
                ],
                [
                    'icon' => 'fa-solid fa-arrows-rotate',
                    'title' => 'Lifetime Free Edition Updates',
                    'desc' => 'All future revised editions, errata corrections, and additional chapters delivered automatically.',
                    'value' => 'Lifetime Access',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'What digital formats are provided upon purchase?',
                    'a' => 'You will receive instant, DRM-free downloads in both high-resolution PDF and reflowable EPUB format compatible with Kindle, Apple Books, Kobo, Android, and all modern e-readers.',
                ],
                [
                    'q' => 'How can I download and read the free sample preview?',
                    'a' => 'Click the "Download Free Sample (PDF)" button or "Look Inside Preview" button on this page. You will get immediate access with zero sign-up required.',
                ],
                [
                    'q' => 'How quickly will I get access to the complete e-book?',
                    'a' => 'Instantly. As soon as your checkout is confirmed, your download links are presented on-screen and sent directly to your email for permanent lifetime access.',
                ],
                [
                    'q' => 'What is your refund policy?',
                    'a' => 'We offer a 100% money-back guarantee within 30 days of purchase if you feel the e-book did not deliver immense value to your work or learning.',
                ],
            ],
        ];

        $reviews = $book['reviews_list'] ?? [];
        $reviewCount = count($reviews);
        $avgRating = $reviewCount > 0 ? number_format((float) collect($reviews)->avg('rating'), 1) : ($book['rating'] ?? '5.0');
        $suggestedFor = $book['suggested_for'] ?? [];

        if (empty($suggestedFor)) {
            $suggestedFor = [
                ['title' => 'Software Engineers & Developers', 'icon' => 'fa-solid fa-laptop-code'],
                ['title' => 'Students & Academics', 'icon' => 'fa-solid fa-graduation-cap'],
                ['title' => 'Researchers & Scientists', 'icon' => 'fa-solid fa-microscope'],
                ['title' => 'General Readers & Enthusiasts', 'icon' => 'fa-solid fa-glasses'],
            ];
        }

        return view('frontend.books.show', [
            'book' => $book,
            'landing' => $landingData,
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'avgRating' => $avgRating,
            'suggestedFor' => $suggestedFor,
        ]);
    }

    /**
     * Store a reader review/feedback for a specific book.
     */
    public function storeReview(Request $request, string|int $identifier): Response
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $dbBook = Book::where('status', 'active')
            ->where(function ($q) use ($identifier) {
                $q->where('id', (string) $identifier)
                    ->orWhere('slug', (string) $identifier);
            })
            ->first();

        $testimonial = Testimonial::create([
            'name' => $validated['name'],
            'profession' => $validated['profession'] ?? null,
            'book_id' => $dbBook?->id,
            'rating' => $validated['rating'],
            'message' => $validated['message'],
            'sort_order' => 0,
            'is_active' => true,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your review has been published.',
                'review' => [
                    'id' => 'testimonial-'.$testimonial->id,
                    'name' => $testimonial->name,
                    'avatar' => strtoupper(substr($testimonial->name, 0, 2)),
                    'rating' => (int) $testimonial->rating,
                    'date' => 'Just now',
                    'verified' => true,
                    'title' => $testimonial->profession ?: 'Verified Reader Review',
                    'comment' => $testimonial->message,
                    'helpful' => 1,
                ],
            ]);
        }

        return redirect()->to(url()->previous().'#reviews-section')
            ->with('success', 'Thank you! Your review has been published successfully.');
    }

    /**
     * Download or view book sample preview
     */
    public function downloadPreview(string|int $identifier): Response
    {
        $dbBook = Book::where('status', 'active')
            ->where(function ($q) use ($identifier) {
                $q->where('slug', (string) $identifier)
                    ->orWhere('id', (string) $identifier);
            })
            ->first();

        if (! $dbBook) {
            abort(404, 'Book preview not found.');
        }

        $book = $this->formatBookDetails($dbBook);
        $filename = ($book['slug'] ?? 'ebook').'-sample-preview.html';

        $html = view('frontend.books.preview-download', [
            'book' => $book,
        ])->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Format a Book model into a summary card array.
     *
     * @return array<string, mixed>
     */
    private function formatBookSummary(Book $book): array
    {
        $sellingPrice = (float) $book->selling_price;
        $originalPrice = (float) $book->price;
        $discount = $book->discount_percentage > 0 ? ($book->discount_percentage.'% OFF') : '';

        return [
            'id' => $book->id,
            'slug' => $book->slug,
            'title' => $book->title,
            'author' => $book->author_name,
            'category' => $book->category?->title ?? 'E-Book',
            'category_slug' => $book->category?->slug ?? 'all',
            'price' => '₹'.number_format($sellingPrice, 0),
            'original_price' => $originalPrice > $sellingPrice ? ('₹'.number_format($originalPrice, 0)) : '',
            'discount' => $discount,
            'rating' => '5.0',
            'reviews' => '120+',
            'image' => $book->cover_image ? $book->cover_image_url : asset('images/books/algorithms.jpg'),
            'pages' => $book->pages ?: 320,
            'format' => $book->format ?: 'EPUB & PDF',
            'description' => $book->description,
        ];
    }

    /**
     * Format a Book model into a rich detail array.
     *
     * @return array<string, mixed>
     */
    private function formatBookDetails(Book $book): array
    {
        $sellingPrice = (float) $book->selling_price;
        $originalPrice = (float) $book->price;
        $discount = $book->discount_percentage > 0 ? ($book->discount_percentage.'% OFF') : '';

        $reviews = [];
        if ($book->relationLoaded('testimonials') && $book->testimonials->isNotEmpty()) {
            $reviews = $book->testimonials->map(function ($t) {
                return [
                    'id' => 'testimonial-'.$t->id,
                    'name' => $t->name,
                    'avatar' => strtoupper(substr($t->name, 0, 2)),
                    'rating' => (int) $t->rating,
                    'date' => $t->created_at ? $t->created_at->diffForHumans() : 'Recent',
                    'verified' => true,
                    'title' => $t->profession ?: 'Verified Reader Review',
                    'comment' => $t->message,
                    'helpful' => 10 + ($t->rating * 3),
                ];
            })->all();
        }

        $highlights = $book->highlights_list;
        if (empty($highlights)) {
            $highlights = [
                'Comprehensive step-by-step masterclass with practical blueprints',
                'Optimized algorithms, real-world case studies, and architecture diagrams',
                'Production-grade implementation strategies and memory profiling techniques',
            ];
        }

        // Chapters parsing from table_of_contents if available
        $chapters = [];
        if (! empty($book->table_of_contents)) {
            $lines = preg_split('/\r\n|\r|\n/', (string) $book->table_of_contents);
            $chapIdx = 1;
            foreach ($lines as $line) {
                $line = trim((string) $line);
                if ($line !== '') {
                    $chapters[] = [
                        'number' => sprintf('%02d', $chapIdx++),
                        'title' => $line,
                        'pages' => 'Chapter '.$chapIdx,
                        'is_sample' => $chapIdx <= 2,
                        'summary' => 'In-depth exploration of '.$line.' with key takeaways and real-world implementation.',
                        'topics' => [$line, 'Fundamental Concepts', 'Practical Applications'],
                    ];
                }
            }
        }

        if (empty($chapters)) {
            $chapters = [
                [
                    'number' => '01',
                    'title' => 'Foundations & Core Principles',
                    'pages' => 'pp. 1–45',
                    'is_sample' => true,
                    'summary' => 'Introduction to core architecture, mental models, and fundamental theoretical principles.',
                    'topics' => ['Core Architecture', 'Mental Models', 'Foundations'],
                ],
                [
                    'number' => '02',
                    'title' => 'Advanced Implementation Patterns',
                    'pages' => 'pp. 46–120',
                    'is_sample' => false,
                    'summary' => 'Designing robust, scalable workflows and eliminating friction in execution.',
                    'topics' => ['System Design', 'Scalability', 'Performance'],
                ],
                [
                    'number' => '03',
                    'title' => 'Production Case Studies & Strategy',
                    'pages' => 'pp. 121–200',
                    'is_sample' => false,
                    'summary' => 'Deconstruction of industry case studies and high-leverage execution patterns.',
                    'topics' => ['Case Studies', 'Execution Strategy', 'Best Practices'],
                ],
            ];
        }

        return [
            'id' => $book->id,
            'slug' => $book->slug,
            'title' => $book->title,
            'subtitle' => $book->meta_description ?: 'Comprehensive Guide & Publication',
            'author' => $book->author_name,
            'author_role' => 'Author & Subject Specialist',
            'author_bio' => $book->author_name.' is an author with deep expertise in '.($book->category?->title ?? 'this field').'.',
            'author_avatar' => strtoupper(substr($book->author_name, 0, 2)),
            'category' => $book->category?->title ?? 'E-Book',
            'category_slug' => $book->category?->slug ?? 'all',
            'price' => '₹'.number_format($sellingPrice, 0),
            'original_price' => $originalPrice > $sellingPrice ? ('₹'.number_format($originalPrice, 0)) : '',
            'discount' => $discount,
            'rating' => '5.0',
            'reviews' => count($reviews) > 0 ? (string) count($reviews) : '120+',
            'rating_breakdown' => ['5' => 92, '4' => 6, '3' => 2, '2' => 0, '1' => 0],
            'image' => $book->cover_image ? $book->cover_image_url : asset('images/books/algorithms.jpg'),
            'pages' => $book->pages ?: 320,
            'language' => $book->language ?: 'English',
            'format' => $book->format ?: 'PDF & EPUB',
            'file_size' => $book->file_size ?: '15.0 MB',
            'release_date' => $book->created_at ? $book->created_at->format('F Y') : 'Recent',
            'edition' => '1st Edition',
            'isbn' => '978-0-13-'.rand(100000, 999999).'-1',
            'badge' => $book->is_featured ? 'Bestseller' : 'Featured',
            'sample_pages' => 24,
            'description' => $book->description,
            'prerequisites' => 'No special prerequisite knowledge required. Suitable for beginner and advanced readers alike.',
            'target_audience' => 'Engineers, Professionals, Researchers & Curious Minds',
            'suggested_for' => $book->suggested_for_list,
            'highlights' => $highlights,
            'chapters' => $chapters,
            'reviews_list' => $reviews,
            'sample_file' => $book->sample_file,
            'sample_file_url' => $book->sample_file_url,
            'sample_content' => [
                'chapter_title' => 'Chapter 1: Core Principles & Foundations',
                'reading_time' => '15 min read',
                'intro' => 'A masterclass in fundamental concepts, exploring how structured thinking and mechanical sympathy create intuitive leverage.',
                'sections' => [
                    [
                        'heading' => 'The Power of First Principles',
                        'content' => $book->description ?: 'Explore the core tenets that define scalable systems and timeless mental models.',
                    ],
                ],
            ],
        ];
    }
}
