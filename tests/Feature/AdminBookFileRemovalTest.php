<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBookFileRemovalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->admin = User::factory()->create();
        $this->category = Category::create([
            'title' => 'Software Engineering',
            'slug' => 'software-engineering',
            'status' => 'active',
            'is_featured' => true,
        ]);
    }

    public function test_can_remove_ebook_file_completely_via_cross_button_flag(): void
    {
        $ebookPath = 'books/ebooks/test_ebook.pdf';
        Storage::disk('public')->put($ebookPath, 'PDF dummy content');
        Storage::disk('public')->assertExists($ebookPath);

        $book = Book::create([
            'title' => 'Laravel Mastery',
            'slug' => 'laravel-mastery',
            'author_name' => 'John Doe',
            'category_id' => $this->category->id,
            'price' => 999.00,
            'selling_price' => 499.00,
            'ebook_file' => $ebookPath,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.books.update', $book), [
            'title' => 'Laravel Mastery Updated',
            'author_name' => 'John Doe',
            'category_id' => $this->category->id,
            'price' => 999.00,
            'selling_price' => 499.00,
            'status' => 'active',
            'remove_ebook_file' => '1',
        ]);

        $response->assertRedirect(route('admin.books.index'));

        $book->refresh();
        $this->assertNull($book->ebook_file);
        Storage::disk('public')->assertMissing($ebookPath);
    }

    public function test_can_remove_sample_file_completely_via_cross_button_flag(): void
    {
        $samplePath = 'books/samples/test_sample.pdf';
        Storage::disk('public')->put($samplePath, 'Sample PDF content');
        Storage::disk('public')->assertExists($samplePath);

        $book = Book::create([
            'title' => 'Vue 3 In Action',
            'slug' => 'vue-3-in-action',
            'author_name' => 'Jane Smith',
            'category_id' => $this->category->id,
            'price' => 799.00,
            'selling_price' => 399.00,
            'sample_file' => $samplePath,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.books.update', $book), [
            'title' => 'Vue 3 In Action',
            'author_name' => 'Jane Smith',
            'category_id' => $this->category->id,
            'price' => 799.00,
            'selling_price' => 399.00,
            'status' => 'active',
            'remove_sample_file' => '1',
        ]);

        $response->assertRedirect(route('admin.books.index'));

        $book->refresh();
        $this->assertNull($book->sample_file);
        Storage::disk('public')->assertMissing($samplePath);
    }

    public function test_can_remove_cover_image_completely_via_cross_button_flag(): void
    {
        $coverPath = 'books/covers/test_cover.jpg';
        Storage::disk('public')->put($coverPath, 'Cover image content');
        Storage::disk('public')->assertExists($coverPath);

        $book = Book::create([
            'title' => 'Modern Architecture',
            'slug' => 'modern-architecture',
            'author_name' => 'Alice Walker',
            'category_id' => $this->category->id,
            'price' => 1200.00,
            'selling_price' => 600.00,
            'cover_image' => $coverPath,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.books.update', $book), [
            'title' => 'Modern Architecture',
            'author_name' => 'Alice Walker',
            'category_id' => $this->category->id,
            'price' => 1200.00,
            'selling_price' => 600.00,
            'status' => 'active',
            'remove_cover_image' => '1',
        ]);

        $response->assertRedirect(route('admin.books.index'));

        $book->refresh();
        $this->assertNull($book->cover_image);
        Storage::disk('public')->assertMissing($coverPath);
    }
}
