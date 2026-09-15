<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_category_custom_slug_and_auto_generated_slug_when_blank(): void
    {
        // 1. Create with custom slug
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'title' => 'Web Development',
            'slug' => 'custom-web-dev',
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.categories.index'));
        $cat1 = Category::where('slug', 'custom-web-dev')->firstOrFail();
        $this->assertEquals('custom-web-dev', $cat1->slug);

        // 2. Create with blank slug - should auto-generate from title
        $blankResponse = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'title' => 'Machine Learning & AI',
            'slug' => '',
            'status' => 'active',
        ]);
        $blankResponse->assertRedirect(route('admin.categories.index'));
        $cat2 = Category::where('title', 'Machine Learning & AI')->firstOrFail();
        $this->assertEquals('machine-learning-ai', $cat2->slug);

        // 3. Create another category with same title and blank slug - should auto-generate unique slug
        $dupResponse = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'title' => 'Machine Learning & AI',
            'slug' => '',
            'status' => 'active',
        ]);
        $dupResponse->assertRedirect(route('admin.categories.index'));
        $cat3 = Category::where('slug', 'machine-learning-ai-1')->firstOrFail();
        $this->assertNotNull($cat3);

        // 4. Update with custom slug
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.categories.update', $cat1), [
            'title' => 'Web Development Masterclass',
            'slug' => 'updated-web-master',
            'status' => 'active',
        ]);
        $updateResponse->assertRedirect(route('admin.categories.index'));
        $this->assertEquals('updated-web-master', $cat1->fresh()->slug);

        // 5. Update with blank slug - should auto-generate from title
        $updateBlankResponse = $this->actingAs($this->admin)->put(route('admin.categories.update', $cat1), [
            'title' => 'Modern Frontend Architecture',
            'slug' => '',
            'status' => 'active',
        ]);
        $updateBlankResponse->assertRedirect(route('admin.categories.index'));
        $this->assertEquals('modern-frontend-architecture', $cat1->fresh()->slug);
    }
}
