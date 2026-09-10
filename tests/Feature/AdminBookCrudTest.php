<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBookCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->category = Category::create([
            'title' => 'Software Engineering',
            'slug' => 'software-engineering',
            'status' => 'active',
            'is_featured' => true,
        ]);
    }

    public function test_can_list_books_in_admin(): void
    {
        Book::create([
            'title' => 'Clean Code Principles',
            'slug' => 'clean-code-principles',
            'author_name' => 'Robert Martin',
            'category_id' => $this->category->id,
            'price' => 799.00,
            'selling_price' => 399.00,
            'status' => 'active',
            'is_featured' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.books.index'));

        $response->assertOk();
        $response->assertSee('Clean Code Principles');
        $response->assertSee('Robert Martin');
        $response->assertSee('Software Engineering');
    }

    public function test_can_view_create_book_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.books.create'));

        $response->assertOk();
        $response->assertSee('Add E-Book');
        $response->assertSee('Software Engineering');
    }

    public function test_can_store_book_with_all_inputs(): void
    {
        Storage::fake('public');

        $cover = UploadedFile::fake()->image('cover.jpg', 600, 800);
        $samplePdf = UploadedFile::fake()->create('sample-preview.pdf', 1024, 'application/pdf');
        $ebookPdf = UploadedFile::fake()->create('complete-ebook.pdf', 5120, 'application/pdf');

        $payload = [
            'title' => 'System Design at Scale',
            'author_name' => 'Alex Xu',
            'category_id' => $this->category->id,
            'price' => 1499.00,
            'selling_price' => 899.00,
            'status' => 'active',
            'is_featured' => '1',
            'description' => 'Comprehensive deep dive into distributed systems architectures.',
            'key_highlights' => "Scale to millions of concurrent users\nDesign resilient microservices\nCache invalidation techniques",
            'table_of_contents' => "Chapter 1: Scale From Zero\nChapter 2: Back-of-the-envelope estimation\nChapter 3: Framework for interviews",
            'suggested_for' => ['Software Engineers & Developers', 'Students & Academics'],
            'pages' => 368,
            'language' => 'English',
            'format' => 'PDF',
            'file_size' => '14.1 MB',
            'meta_title' => 'System Design at Scale Book - Alex Xu',
            'meta_description' => 'Master scalable system design with practical real-world architectures.',
            'meta_keywords' => 'system design, architecture, microservices, alex xu',
            'cover_image' => $cover,
            'sample_file' => $samplePdf,
            'ebook_file' => $ebookPdf,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), $payload);

        $response->assertRedirect(route('admin.books.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('books', [
            'title' => 'System Design at Scale',
            'slug' => 'system-design-at-scale',
            'author_name' => 'Alex Xu',
            'category_id' => $this->category->id,
            'price' => 1499.00,
            'selling_price' => 899.00,
            'status' => 'active',
            'is_featured' => true,
            'pages' => 368,
            'language' => 'English',
            'format' => 'PDF',
            'file_size' => '14.1 MB',
            'meta_title' => 'System Design at Scale Book - Alex Xu',
        ]);

        $book = Book::where('slug', 'system-design-at-scale')->firstOrFail();
        $this->assertCount(3, $book->highlights_list);
        $this->assertEquals(['Software Engineers & Developers', 'Students & Academics'], $book->suggested_for);
        $this->assertNotNull($book->cover_image);
        $this->assertNotNull($book->sample_file);
        $this->assertNotNull($book->ebook_file);
        Storage::disk('public')->assertExists($book->cover_image);
        Storage::disk('public')->assertExists($book->sample_file);
        Storage::disk('public')->assertExists($book->ebook_file);
    }

    public function test_can_view_book_show_page(): void
    {
        $book = Book::create([
            'title' => 'The Pragmatic Programmer',
            'slug' => 'the-pragmatic-programmer',
            'author_name' => 'Andy Hunt & Dave Thomas',
            'category_id' => $this->category->id,
            'price' => 999.00,
            'selling_price' => 599.00,
            'status' => 'active',
            'is_featured' => true,
            'pages' => 352,
            'language' => 'English',
            'format' => 'PDF, EPUB',
            'file_size' => '12.4 MB',
            'meta_title' => 'The Pragmatic Programmer 20th Anniversary Edition',
            'meta_description' => 'Your journey to mastery.',
            'meta_keywords' => 'pragmatic, programmer, software craftsmanship',
            'description' => 'A classic book on pragmatic software craft.',
            'key_highlights' => "Care about your craft\nProvide options, don't make excuses",
            'table_of_contents' => "Chapter 1: A Pragmatic Philosophy\nChapter 2: A Pragmatic Approach",
            'suggested_for' => ['Software Engineers & Developers'],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.books.show', $book));

        $response->assertOk();
        $response->assertSee('The Pragmatic Programmer');
        $response->assertSee('Andy Hunt &amp; Dave Thomas', false);
        $response->assertSee('Care about your craft');
        $response->assertSee('352 Pages');
        $response->assertSee('English');
        $response->assertSee('PDF, EPUB');
        $response->assertSee('12.4 MB');
        $response->assertSee('The Pragmatic Programmer 20th Anniversary Edition');
    }

    public function test_can_update_book(): void
    {
        $book = Book::create([
            'title' => 'Original Book Title',
            'slug' => 'original-book-title',
            'author_name' => 'Author A',
            'category_id' => $this->category->id,
            'price' => 500.00,
            'selling_price' => 300.00,
            'status' => 'active',
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.books.update', $book), [
            'title' => 'Updated Book Title',
            'author_name' => 'Author B',
            'category_id' => $this->category->id,
            'price' => 600.00,
            'selling_price' => 450.00,
            'status' => 'inactive',
            'is_featured' => '1',
            'pages' => 420,
            'language' => 'Spanish',
            'format' => 'EPUB',
            'file_size' => '8.5 MB',
            'meta_title' => 'Updated Title SEO',
            'meta_description' => 'Updated Description SEO',
            'meta_keywords' => 'tag1, tag2',
            'description' => 'Updated description.',
            'key_highlights' => "New highlight 1\nNew highlight 2",
            'table_of_contents' => 'New Section 1',
            'suggested_for' => ['Beginners & Starters'],
        ]);

        $response->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Book Title',
            'slug' => 'updated-book-title',
            'author_name' => 'Author B',
            'status' => 'inactive',
            'is_featured' => true,
            'pages' => 420,
            'language' => 'Spanish',
            'format' => 'EPUB',
            'file_size' => '8.5 MB',
            'meta_title' => 'Updated Title SEO',
        ]);
    }

    public function test_can_toggle_book_status(): void
    {
        $book = Book::create([
            'title' => 'Toggle Status Book',
            'slug' => 'toggle-status-book',
            'author_name' => 'Author X',
            'category_id' => $this->category->id,
            'price' => 100.00,
            'selling_price' => 50.00,
            'status' => 'active',
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->admin)->patchJson(route('admin.books.toggle-status', $book));

        $response->assertOk();
        $response->assertJson(['success' => true, 'status' => 'inactive']);
        $this->assertEquals('inactive', $book->fresh()->status);
    }

    public function test_can_delete_book(): void
    {
        Storage::fake('public');

        $cover = UploadedFile::fake()->image('cover.jpg');
        $sample = UploadedFile::fake()->create('sample.pdf', 100);
        $ebook = UploadedFile::fake()->create('full.pdf', 500);

        $coverPath = $cover->store('books/covers', 'public');
        $samplePath = $sample->store('books/samples', 'public');
        $ebookPath = $ebook->store('books/ebooks', 'public');

        $book = Book::create([
            'title' => 'Book To Delete',
            'slug' => 'book-to-delete',
            'author_name' => 'Author Del',
            'category_id' => $this->category->id,
            'price' => 100.00,
            'selling_price' => 50.00,
            'status' => 'active',
            'is_featured' => false,
            'cover_image' => $coverPath,
            'sample_file' => $samplePath,
            'ebook_file' => $ebookPath,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $book));

        $response->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
        Storage::disk('public')->assertMissing($coverPath);
        Storage::disk('public')->assertMissing($samplePath);
        Storage::disk('public')->assertMissing($ebookPath);
    }

    public function test_can_store_and_update_book_with_custom_suggested_audience(): void
    {
        $payload = [
            'title' => 'Custom Audience Book Guide',
            'author_name' => 'Expert Author',
            'category_id' => $this->category->id,
            'price' => 599.00,
            'selling_price' => 399.00,
            'status' => 'active',
            'suggested_for' => ['Beginners & Starters', 'UPSC Aspirants', 'Product Managers & Owners'],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), $payload);
        $response->assertRedirect(route('admin.books.index'));

        $book = Book::where('slug', 'custom-audience-book-guide')->firstOrFail();
        $this->assertEquals(['Beginners & Starters', 'UPSC Aspirants', 'Product Managers & Owners'], $book->suggested_for);

        // Test viewing edit page renders the custom audience
        $editResponse = $this->actingAs($this->admin)->get(route('admin.books.edit', $book));
        $editResponse->assertOk();
        $editResponse->assertSee('UPSC Aspirants');
        $editResponse->assertSee('Product Managers &amp; Owners', false);

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.books.update', $book), [
            'title' => 'Custom Audience Book Guide',
            'author_name' => 'Expert Author',
            'category_id' => $this->category->id,
            'price' => 599.00,
            'selling_price' => 399.00,
            'status' => 'active',
            'suggested_for' => ['High School Teachers:::fa-solid fa-graduation-cap', 'UPSC Aspirants:::fa-solid fa-building-columns'],
        ]);

        $updateResponse->assertRedirect(route('admin.books.index'));
        $this->assertEquals(['High School Teachers:::fa-solid fa-graduation-cap', 'UPSC Aspirants:::fa-solid fa-building-columns'], $book->fresh()->suggested_for);

        $showResponse = $this->actingAs($this->admin)->get(route('admin.books.show', $book));
        $showResponse->assertOk();
        $showResponse->assertSee('High School Teachers');
        $showResponse->assertSee('fa-solid fa-graduation-cap');
        $showResponse->assertSee('UPSC Aspirants');
        $showResponse->assertSee('fa-solid fa-building-columns');
    }
}
