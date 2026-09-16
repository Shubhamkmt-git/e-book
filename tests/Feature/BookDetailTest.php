<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Purchase;
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
        // 1. Without sample_file uploaded, Download Sample button should not be shown
        $response = $this->get(route('books.show', 'algorithms-and-elegance'));

        $response->assertStatus(200);
        $response->assertSee('Algorithms &amp; Elegance', false);
        $response->assertSee('Prof. Julian Hayes');
        $response->assertSee('Buy Now (₹499/-)');
        $response->assertDontSee('Download Sample (PDF)');
        $response->assertSee('About The Book');
        $response->assertSee('412 Pages');
        $response->assertSee('Table of Contents');
        $response->assertSee('Write a Review');

        // 2. When sample_file is uploaded in admin, Download Sample button should be shown
        $book = Book::where('slug', 'algorithms-and-elegance')->firstOrFail();
        $book->update(['sample_file' => 'books/samples/algorithms-sample.pdf']);

        $responseWithSample = $this->get(route('books.show', 'algorithms-and-elegance'));
        $responseWithSample->assertStatus(200);
        $responseWithSample->assertSee('Download Sample (PDF)');
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

    /**
     * Test book detail page displays full download button if customer already purchased it.
     */
    public function test_book_detail_page_shows_download_full_ebook_when_customer_has_purchased(): void
    {
        $customer = Customer::create([
            'name' => 'Book Buyer',
            'email' => 'buyer@example.com',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'algorithms-and-elegance',
            'book_title' => 'Algorithms & Elegance',
            'amount' => 499,
            'transaction_id' => 'TXN-BUYER-1',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('books.show', 'algorithms-and-elegance'));

        $response->assertStatus(200);
        $response->assertSee('You Own This E-Book');
        $response->assertSee('Download Full E-Book (PDF)');
        $response->assertSee(route('purchases.download', $purchase->id));
        $response->assertDontSee('Buy Now (₹499/-)');
    }

    public function test_book_detail_page_renders_formatted_html_description(): void
    {
        $book = Book::where('slug', 'algorithms-and-elegance')->firstOrFail();
        $book->update([
            'description' => '<p>Leading <strong>algorithms</strong> guide.</p><ul><li>Graph Search</li><li>Dynamic Programming</li></ul>',
        ]);

        $response = $this->get(route('books.show', 'algorithms-and-elegance'));

        $response->assertStatus(200);
        $response->assertSee('<div class="book-description-content', false);
        $response->assertSee('<p>Leading <strong>algorithms</strong> guide.</p>', false);
        $response->assertSee('<li>Graph Search</li>', false);
    }

    public function test_book_detail_page_renders_hindi_title_with_font_bold(): void
    {
        $book = Book::where('slug', 'algorithms-and-elegance')->firstOrFail();
        $book->update([
            'title' => 'श्रीमद् भगवद्गीता - सरल हिंदी व्याख्या',
        ]);

        $response = $this->get(route('books.show', 'algorithms-and-elegance'));

        $response->assertStatus(200);
        $response->assertSee('श्रीमद् भगवद्गीता - सरल हिंदी व्याख्या');
        // Verify h1 tag has font-bold class so Hindi Devanagari text renders bold
        $response->assertSee('<h1 class="font-brand font-bold', false);
    }

    public function test_book_detail_page_renders_reference_images_and_lightbox_modal(): void
    {
        $book = Book::where('slug', 'algorithms-and-elegance')->firstOrFail();
        $book->update([
            'gallery_images' => [
                'books/gallery/test_image_1.jpg',
                'books/gallery/test_image_2.jpg',
            ],
        ]);

        $response = $this->get(route('books.show', 'algorithms-and-elegance'));

        $response->assertStatus(200);
        $response->assertSee('Reference Images');
        $response->assertSee('books/gallery/test_image_1.jpg');
        $response->assertSee('books/gallery/test_image_2.jpg');
        $response->assertSee('id="gallery-lightbox-modal"', false);
        $response->assertSee('id="lightbox-prev-btn"', false);
        $response->assertSee('id="lightbox-next-btn"', false);
        $response->assertSee('id="lightbox-counter"', false);
        $response->assertSee('openLightbox', false);
        $response->assertSee('prevLightbox', false);
        $response->assertSee('nextLightbox', false);

        // Verify Reference Images is placed BEFORE Suggested For section
        $content = $response->getContent();
        $refPos = strpos($content, 'Reference Images');
        $sugPos = strpos($content, 'Suggested For');
        $this->assertNotFalse($refPos);
        $this->assertNotFalse($sugPos);
        $this->assertLessThan($sugPos, $refPos, 'Reference Images section should be placed before Suggested For section');
    }
}
