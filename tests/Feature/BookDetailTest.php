<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'title' => 'Tech & Coding',
            'slug' => 'tech-coding',
            'status' => 'active',
            'icon' => 'fa-solid fa-laptop-code',
        ]);

        Book::create([
            'title' => 'Algorithms & Elegance',
            'slug' => 'algorithms-and-elegance',
            'author_name' => 'Prof. Julian Hayes',
            'category_id' => $category->id,
            'price' => 999.00,
            'selling_price' => 499.00,
            'status' => 'active',
            'is_featured' => true,
            'pages' => 412,
            'description' => 'A masterclass in crafting resilient, performant, and elegant code.',
            'key_highlights' => "Master dynamic programming, graph theory, and algorithmic complexity trade-offs\nProduction-grade implementation blueprints",
            'table_of_contents' => "Computational Complexity & Algorithmic Thinking\nAdvanced Trees, Heaps, and Spatial Indexing",
            'suggested_for' => ['Software Engineers & Developers', 'Students & Academics'],
        ]);
    }

    /**
     * Test book detail page renders successfully with minimal layout.
     */
    public function test_book_detail_page_loads_with_rich_content(): void
    {
        $response = $this->get(route('books.show', 'algorithms-and-elegance'));

        $response->assertStatus(200);
        $response->assertSee('Algorithms &amp; Elegance', false);
        $response->assertSee('Prof. Julian Hayes');
        $response->assertSee('Buy Now (₹499/-)');
        $response->assertSee('Download Sample (PDF)');
        $response->assertSee('About The Book');
        $response->assertSee('412 Pages');
        $response->assertSee('Table of Contents');
        $response->assertSee('Write a Review');
    }

    /**
     * Test download preview sample route works and returns downloadable document.
     */
    public function test_book_preview_sample_download(): void
    {
        $response = $this->get(route('books.preview', 'algorithms-and-elegance'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="algorithms-and-elegance-sample-preview.html"');
        $response->assertSee('Official Free Sample Preview');
        $response->assertSee('Algorithms &amp; Elegance', false);
        $response->assertSee('Table of Contents (Full Book Overview)');
    }
}
