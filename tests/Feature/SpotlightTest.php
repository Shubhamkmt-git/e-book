<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Spotlight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpotlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_spotlight_admin(): void
    {
        $response = $this->get(route('admin.spotlight.manage'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_spotlight_manage_page(): void
    {
        $admin = User::factory()->create();
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'status' => 'active',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Algorithms & Elegance',
            'slug' => 'algorithms-elegance',
            'author_name' => 'Dr. Jane Smith',
            'price' => 799,
            'selling_price' => 499,
            'description' => 'A masterclass in modern algorithms.',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.spotlight.manage'));

        $response->assertOk();
        $response->assertSee('Book of the Week');
        $response->assertSee('Algorithms & Elegance');
    }

    public function test_admin_can_update_spotlight_settings(): void
    {
        $admin = User::factory()->create();
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'status' => 'active',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Algorithms & Elegance',
            'slug' => 'algorithms-elegance',
            'author_name' => 'Dr. Jane Smith',
            'price' => 799,
            'selling_price' => 499,
            'description' => 'A masterclass in modern algorithms.',
            'status' => 'active',
        ]);

        $postData = [
            'book_id' => $book->id,
            'badge_text' => 'Editor Choice of the Week',
            'custom_title' => 'Algorithms & Elegance: Special Edition',
            'custom_description' => 'Supercharge your coding skills with this exclusive edition.',
            'feature_tags' => "Instant PDF & ePub\nLifetime Free Updates\nSource Code Included",
            'button_text' => 'Claim Your Copy',
            'status' => 'active',
        ];

        $response = $this->actingAs($admin)->put(route('admin.spotlight.update'), $postData);

        $response->assertRedirect(route('admin.spotlight.manage'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('spotlights', [
            'book_id' => $book->id,
            'badge_text' => 'Editor Choice of the Week',
            'custom_title' => 'Algorithms & Elegance: Special Edition',
            'button_text' => 'Claim Your Copy',
            'status' => 'active',
        ]);

        $spotlight = Spotlight::first();
        $this->assertCount(3, $spotlight->tags_list);
        $this->assertEquals('Instant PDF & ePub', $spotlight->tags_list[0]);
    }

    public function test_admin_can_toggle_spotlight_status(): void
    {
        $admin = User::factory()->create();
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'status' => 'active',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Algorithms & Elegance',
            'slug' => 'algorithms-elegance',
            'author_name' => 'Dr. Jane Smith',
            'price' => 799,
            'selling_price' => 499,
            'description' => 'A masterclass in modern algorithms.',
            'status' => 'active',
        ]);

        $spotlight = Spotlight::create([
            'book_id' => $book->id,
            'badge_text' => 'Book of the Week',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.spotlight.manage'))
            ->patch(route('admin.spotlight.toggle-status'));

        $response->assertRedirect(route('admin.spotlight.manage'));
        $this->assertDatabaseHas('spotlights', [
            'id' => $spotlight->id,
            'status' => 'inactive',
        ]);

        // Toggle via JSON
        $jsonResponse = $this->actingAs($admin)
            ->patchJson(route('admin.spotlight.toggle-status'));

        $jsonResponse->assertOk();
        $jsonResponse->assertJson([
            'success' => true,
            'status' => 'active',
        ]);
    }

    public function test_spotlight_renders_on_homepage(): void
    {
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'status' => 'active',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Algorithms & Elegance',
            'slug' => 'algorithms-elegance',
            'author_name' => 'Dr. Jane Smith',
            'price' => 799,
            'selling_price' => 499,
            'description' => 'A masterclass in modern algorithms.',
            'status' => 'active',
        ]);

        Spotlight::create([
            'book_id' => $book->id,
            'badge_text' => 'Exclusive Pick',
            'custom_title' => 'Dynamic Spotlight Title',
            'custom_description' => 'Dynamic Spotlight Description.',
            'feature_tags' => ['Quick Download', '24/7 Support'],
            'button_text' => 'Get Access Now',
            'status' => 'active',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Exclusive Pick');
        $response->assertSee('Dynamic Spotlight Title');
        $response->assertSee('Dynamic Spotlight Description.');
        $response->assertSee('Get Access Now');
    }
}
