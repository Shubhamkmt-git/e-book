<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Book $book;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();

        $category = Category::create([
            'title' => 'Software Engineering',
            'slug' => 'software-engineering',
            'status' => 'active',
        ]);

        $this->book = Book::create([
            'title' => 'Algorithms & Elegance',
            'slug' => 'algorithms-and-elegance',
            'author_name' => 'Prof. Julian Hayes',
            'category_id' => $category->id,
            'price' => 999.00,
            'selling_price' => 499.00,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_view_testimonials_list(): void
    {
        Testimonial::create([
            'name' => 'Alice Johnson',
            'profession' => 'Staff Engineer',
            'book_id' => $this->book->id,
            'rating' => 5,
            'message' => 'Life changing read on distributed algorithms.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.testimonials.index'));

        $response->assertOk()
            ->assertSee('Alice Johnson')
            ->assertSee('Staff Engineer')
            ->assertSee('Algorithms &amp; Elegance', false);
    }

    public function test_admin_can_create_testimonial_with_related_book(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'name' => 'Robert Smith',
            'profession' => 'Tech Lead',
            'book_id' => $this->book->id,
            'rating' => 5,
            'message' => 'Phenomenal clarity and practical insights.',
            'sort_order' => 2,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.testimonials.index'));

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Robert Smith',
            'profession' => 'Tech Lead',
            'book_id' => $this->book->id,
            'rating' => 5,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_testimonial(): void
    {
        $testimonial = Testimonial::create([
            'name' => 'Sara Lee',
            'profession' => 'Developer',
            'book_id' => null,
            'rating' => 4,
            'message' => 'Initial review text.',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.testimonials.update', $testimonial), [
            'name' => 'Sara Lee Updated',
            'profession' => 'Senior Developer',
            'book_id' => $this->book->id,
            'rating' => 5,
            'message' => 'Updated thorough review text.',
            'sort_order' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.testimonials.index'));

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'name' => 'Sara Lee Updated',
            'profession' => 'Senior Developer',
            'book_id' => $this->book->id,
            'rating' => 5,
        ]);
    }

    public function test_admin_can_toggle_testimonial_status(): void
    {
        $testimonial = Testimonial::create([
            'name' => 'Tom Davis',
            'rating' => 5,
            'message' => 'Great experience.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.testimonials.toggle-status', $testimonial));

        $response->assertRedirect();
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'is_active' => false,
        ]);
    }

    public function test_homepage_displays_dynamic_testimonials(): void
    {
        Testimonial::create([
            'name' => 'Elena Rostova (Custom)',
            'profession' => 'Lead Architect',
            'book_id' => $this->book->id,
            'rating' => 5,
            'message' => 'The absolute finest resource on scalable architectures.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Elena Rostova (Custom)')
            ->assertSee('The absolute finest resource on scalable architectures.')
            ->assertSee('Algorithms &amp; Elegance', false);
    }

    public function test_book_show_page_displays_book_specific_testimonials(): void
    {
        Testimonial::create([
            'name' => 'Markus Vance (Reader)',
            'profession' => 'Principal Engineer',
            'book_id' => $this->book->id,
            'rating' => 5,
            'message' => 'Unbelievable depth on CPU cache locality and zero-allocation techniques.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('books.show', $this->book->slug));

        $response->assertOk()
            ->assertSee('Markus Vance (Reader)')
            ->assertSee('Unbelievable depth on CPU cache locality and zero-allocation techniques.');
    }

    public function test_reader_can_submit_review_for_book(): void
    {
        $response = $this->postJson(route('books.reviews.store', $this->book->slug), [
            'name' => 'John Reader',
            'profession' => 'Senior Developer',
            'rating' => 5,
            'message' => 'Brilliant guide, highly recommend to every software engineer.',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Thank you! Your review has been published.',
            ]);

        $this->assertDatabaseHas('testimonials', [
            'name' => 'John Reader',
            'profession' => 'Senior Developer',
            'book_id' => $this->book->id,
            'rating' => 5,
            'is_active' => true,
        ]);
    }
}
