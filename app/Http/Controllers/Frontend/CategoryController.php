<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Show all categories directory
     */
    public function index(): View
    {
        $dbCategories = Category::where('status', 'active')
            ->withCount(['books' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $allCategories = $dbCategories->map(function ($cat) {
            return [
                'name' => $cat->title,
                'slug' => $cat->slug,
                'count' => ($cat->books_count ?? 0).' '.Str::plural('Book', $cat->books_count ?? 0),
                'icon' => $cat->icon ?: 'fa-solid fa-book-open',
                'description' => $cat->description,
            ];
        })->all();

        $totalCount = count($allCategories);

        return view('frontend.categories.index', compact('allCategories', 'totalCount'));
    }

    /**
     * Show books under a specific category
     */
    public function show(string $slug): View
    {
        $normalizedSlug = Str::slug($slug);

        $dbCategory = Category::where('status', 'active')
            ->where(function ($q) use ($normalizedSlug, $slug) {
                $q->where('slug', $normalizedSlug)
                    ->orWhere('slug', $slug)
                    ->orWhere('id', $slug);
            })
            ->with(['books' => function ($bq) {
                $bq->where('status', 'active');
            }])
            ->first();

        if ($dbCategory) {
            $categoryBooks = $dbCategory->books->map(function ($b) {
                return [
                    'id' => $b->id,
                    'title' => $b->title,
                    'slug' => $b->slug,
                    'author' => $b->author_name,
                    'price' => '₹'.number_format((float) $b->selling_price, 0),
                    'original_price' => $b->price > $b->selling_price ? ('₹'.number_format((float) $b->price, 0)) : null,
                    'rating' => '5.0',
                    'reviews' => '120',
                    'image' => $b->cover_image ? $b->cover_image_url : asset('images/books/algorithms.jpg'),
                    'format' => $b->format ?: 'EPUB & PDF',
                ];
            })->all();

            $category = [
                'name' => $dbCategory->title,
                'slug' => $dbCategory->slug,
                'icon' => $dbCategory->icon ?: 'fa-solid fa-book-open',
                'count' => ($dbCategory->books->count()).' '.Str::plural('Book', $dbCategory->books->count()),
                'description' => $dbCategory->description ?: "Explore our collection of bestseller e-books and publications under {$dbCategory->title}.",
                'books' => $categoryBooks,
            ];
        } else {
            $name = Str::headline($slug);
            $category = [
                'name' => $name,
                'slug' => $normalizedSlug,
                'icon' => 'fa-solid fa-book-open',
                'count' => '0 Titles',
                'description' => "Explore our hand-picked collection of bestseller e-books, academic guides, and curated publications under {$name}.",
                'books' => [],
            ];
        }

        // Sibling categories for quick switcher
        $dbSiblings = Category::where('status', 'active')
            ->where('slug', '!=', $category['slug'])
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        $otherCategories = $dbSiblings->map(function ($c) {
            return [
                'name' => $c->title,
                'slug' => $c->slug,
                'icon' => $c->icon ?: 'fa-solid fa-book-open',
            ];
        })->all();

        return view('frontend.categories.show', compact('category', 'otherCategories'));
    }
}
