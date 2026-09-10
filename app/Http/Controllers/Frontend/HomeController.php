<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Cta;
use App\Models\Faq;
use App\Models\HeroBanner;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the homepage with dynamic admin-managed data.
     */
    public function index(): View
    {
        // Active hero banner (first by sort_order)
        $heroBanner = HeroBanner::where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        // Categories (active, sorted)
        $categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->take(5)
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

        return view('welcome', compact('heroBanner', 'categories', 'faqs', 'cta'));
    }
}
