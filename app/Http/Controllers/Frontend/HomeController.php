<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Cta;
use App\Models\Faq;
use App\Models\HeroBanner;
use App\Models\Spotlight;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the homepage with dynamic admin-managed data.
     */
    public function index(): View
    {
        // Active hero banners sorted by sort_order
        $heroBanners = HeroBanner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $heroBanner = $heroBanners->first();

        // Featured Categories for homepage (active, is_featured = true, sorted)
        $categories = Category::where('status', 'active')
            ->where('is_featured', true)
            ->withCount(['books' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $totalCategoriesCount = Category::where('status', 'active')->count();

        // Dynamic Featured / Trending Books from DB
        $trendingBooks = Book::with('category')
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // If no featured books, fallback to recent active books
        if ($trendingBooks->isEmpty()) {
            $trendingBooks = Book::with('category')
                ->where('status', 'active')
                ->orderByDesc('created_at')
                ->take(8)
                ->get();
        }

        // Dynamic Spotlight / Book of the Week configured by Admin
        $spotlight = Spotlight::with(['book.category'])
            ->where('status', 'active')
            ->first();

        $spotlightBook = null;
        if ($spotlight && $spotlight->book && $spotlight->book->status === 'active') {
            $spotlightBook = $spotlight->book;
        } elseif (! $spotlight) {
            $spotlightBook = Book::with('category')
                ->where('status', 'active')
                ->where('is_featured', true)
                ->orderByDesc('created_at')
                ->first() ?? Book::with('category')
                ->where('status', 'active')
                ->orderByDesc('created_at')
                ->first();
        }

        // Active Testimonials sorted by sort_order
        $testimonials = Testimonial::where('is_active', true)
            ->with('book')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        // Active FAQs sorted by sort_order
        $faqs = Faq::where('status', 'active')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        // Active CTA (first by sort_order)
        $cta = Cta::where('status', 'active')
            ->orderBy('sort_order')
            ->first();

        return view('welcome', compact(
            'heroBanner',
            'heroBanners',
            'categories',
            'totalCategoriesCount',
            'trendingBooks',
            'spotlight',
            'spotlightBook',
            'testimonials',
            'faqs',
            'cta'
        ));
    }
}
