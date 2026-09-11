<?php

namespace Tests\Feature;

use App\Mail\EbookDeliveryMail;
use App\Models\Book;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EasebuzzPaymentTest extends TestCase
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
            'description' => 'A masterclass in crafting resilient, performant, and elegant code.',
        ]);
    }

    public function test_authenticated_customer_is_redirected_to_easebuzz_test_checkout(): void
    {
        config()->set('services.easebuzz', [
            'key' => 'test-key',
            'salt' => 'test-salt',
            'environment' => 'test',
            'test_url' => 'https://test.easebuzz.example/initiate',
            'production_url' => 'https://easebuzz.example/initiate',
        ]);
        Http::fake([
            'https://test.easebuzz.example/*' => Http::response([
                'status' => 1,
                'data' => 'https://testpay.easebuzz.in/payment/checkout-token',
            ]),
        ]);

        $customer = Customer::create([
            'name' => 'Jane Reader',
            'email' => 'jane@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('payments.initiate', 'algorithms-and-elegance'));

        $response->assertRedirect('https://testpay.easebuzz.in/payment/checkout-token');
        $this->assertDatabaseHas('purchases', [
            'customer_id' => $customer->id,
            'book_identifier' => 'algorithms-and-elegance',
            'amount' => 499,
            'status' => 'pending',
        ]);
        Http::assertSent(function ($request): bool {
            $data = $request->data();

            return $request->url() === 'https://test.easebuzz.example/initiate'
                && $data['key'] === 'test-key'
                && $data['amount'] === '499.00'
                && isset($data['hash']);
        });
    }

    public function test_payment_is_rejected_when_test_credentials_are_missing(): void
    {
        config()->set('services.easebuzz.key', '');
        config()->set('services.easebuzz.salt', '');

        $customer = Customer::create([
            'name' => 'Jane Reader',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('payments.initiate', 'algorithms-and-elegance'));

        $response->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $response->assertSessionHas('payment_error');
        $this->assertDatabaseCount('purchases', 0);
    }

    public function test_valid_easebuzz_success_callback_marks_purchase_as_paid_and_sends_email(): void
    {
        Mail::fake();

        config()->set('services.easebuzz.key', 'test-key');
        config()->set('services.easebuzz.salt', 'test-salt');
        $customer = Customer::create([
            'name' => 'Jane Reader',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);
        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'algorithms-and-elegance',
            'book_title' => 'Algorithms & Elegance',
            'amount' => 499,
            'transaction_id' => 'EBTEST123',
            'status' => 'pending',
        ]);
        $payload = [
            'key' => 'test-key',
            'txnid' => $purchase->transaction_id,
            'amount' => '499.00',
            'productinfo' => $purchase->book_title,
            'firstname' => $customer->name,
            'email' => $customer->email,
            'status' => 'success',
        ];
        $payload['hash'] = hash('sha512', implode('|', [
            'test-salt', 'success', '', '', '', '', '', '', '', '', '', '',
            $payload['email'], $payload['firstname'], $payload['productinfo'], $payload['amount'],
            $payload['txnid'], $payload['key'],
        ]));

        $response = $this->post(route('payments.return', $purchase), $payload);

        $response->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $response->assertSessionHas('payment_success');
        $response->assertSessionHas('auto_download_url');
        $this->assertDatabaseHas('purchases', ['id' => $purchase->id, 'status' => 'paid']);

        Mail::assertSent(EbookDeliveryMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }

    public function test_download_purchased_ebook_route_returns_downloadable_file(): void
    {
        $customer = Customer::create([
            'name' => 'Jane Reader',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);
        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'algorithms-and-elegance',
            'book_title' => 'Algorithms & Elegance',
            'amount' => 499,
            'transaction_id' => 'EBTEST999',
            'status' => 'paid',
        ]);

        $response = $this->get(route('purchases.download', $purchase));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="algorithms-and-elegance-full-edition.html"');
        $response->assertSee('Algorithms &amp; Elegance', false);
        $response->assertSee('Official Full Edition');
    }

    public function test_mock_payment_simulation_flow_works(): void
    {
        config()->set('services.easebuzz.environment', 'mock');
        Mail::fake();

        $customer = Customer::create([
            'name' => 'Mock User',
            'email' => 'mock@example.com',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('payments.initiate', 'algorithms-and-elegance'));

        $purchase = Purchase::where('customer_id', $customer->id)->latest()->first();
        $this->assertNotNull($purchase);
        $response->assertRedirect(route('payments.mock-checkout', $purchase));

        // View simulator page
        $simResponse = $this->actingAs($customer, 'customer')->get(route('payments.mock-checkout', $purchase));
        $simResponse->assertOk();
        $simResponse->assertSee('Easebuzz Sandbox Checkout');

        // Process mock success
        $processResponse = $this->actingAs($customer, 'customer')->post(route('payments.mock-process', $purchase), [
            'action' => 'success',
        ]);

        $processResponse->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $this->assertEquals('paid', $purchase->fresh()->status);
        Mail::assertSent(EbookDeliveryMail::class);
    }

    public function test_guest_can_initiate_payment_with_email(): void
    {
        config()->set('services.easebuzz.environment', 'mock');

        $response = $this->post(route('payments.initiate', 'algorithms-and-elegance'), [
            'name' => 'Instant Guest Buyer',
            'email' => 'guestbuyer@example.com',
            'mobile' => '9876543210',
        ]);

        $customer = Customer::where('email', 'guestbuyer@example.com')->first();
        $this->assertNotNull($customer);
        $this->assertEquals('Instant Guest Buyer', $customer->name);

        $purchase = Purchase::where('customer_id', $customer->id)->first();
        $this->assertNotNull($purchase);
        $response->assertRedirect(route('payments.mock-checkout', $purchase));
    }
}
