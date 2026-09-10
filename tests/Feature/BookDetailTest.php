<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookDetailTest extends TestCase
{
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
