<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DirectPurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function createTestBook(string $slug, string $title, float $price = 299): Book
    {
        $category = Category::create([
            'title' => 'Software Engineering',
            'slug' => 'software-engineering-'.uniqid(),
            'status' => 'active',
        ]);

        return Book::create([
            'category_id' => $category->id,
            'title' => $title,
            'slug' => $slug,
            'author_name' => 'John Author',
            'price' => $price,
            'selling_price' => $price,
            'description' => 'Test book description',
            'status' => 'active',
        ]);
    }

    public function test_authenticated_customer_can_purchase_book_directly(): void
    {
        $book = $this->createTestBook('test-ebook-slug', 'Test E-Book', 299);

        $customer = Customer::create([
            'email' => 'reader@example.com',
            'name' => 'Reader Name',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('payments.initiate', $book->slug));

        $response->assertRedirect(route('books.show', $book->slug));
        $response->assertSessionHas('payment_success');

        $this->assertDatabaseHas('purchases', [
            'customer_id' => $customer->id,
            'book_identifier' => 'test-ebook-slug',
            'amount' => 299,
            'status' => 'paid',
            'payment_method' => 'direct',
        ]);
    }

    public function test_guest_with_email_and_name_can_purchase_and_auto_login(): void
    {
        $book = $this->createTestBook('clean-code-architecture', 'Clean Code Architecture', 499);

        $response = $this->post(route('payments.initiate', $book->slug), [
            'name' => 'John Direct',
            'email' => 'johndirect@example.com',
            'mobile' => '9876543210',
        ]);

        $response->assertRedirect(route('books.show', $book->slug));
        $response->assertSessionHas('payment_success');

        $this->assertDatabaseHas('customers', [
            'email' => 'johndirect@example.com',
            'name' => 'John Direct',
            'mobile' => '9876543210',
        ]);

        $this->assertTrue(Auth::guard('customer')->check());
    }

    public function test_customer_can_download_paid_book(): void
    {
        $book = $this->createTestBook('downloadable-book', 'Downloadable Book');

        $customer = Customer::create([
            'email' => 'downloader@example.com',
            'name' => 'Downloader',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => $book->slug,
            'book_title' => $book->title,
            'amount' => 199,
            'payment_method' => 'direct',
            'transaction_id' => 'ORDTEST123',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('purchases.download', $purchase));

        $response->assertOk();
    }
}
