<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Purchase;
use App\Services\CashfreeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CashfreePaymentTest extends TestCase
{
    use RefreshDatabase;

    private Book $book;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $category = Category::create([
            'title' => 'Technology & Coding',
            'slug' => 'tech-coding',
            'status' => 'active',
            'icon' => 'fa-solid fa-code',
        ]);

        $this->book = Book::create([
            'title' => 'Mastering Modern Full-Stack Development',
            'slug' => 'mastering-modern-full-stack',
            'category_id' => $category->id,
            'author_name' => 'Dr. Jane Doe',
            'price' => 799.00,
            'selling_price' => 499.00,
            'status' => 'active',
            'pages' => 380,
            'format' => 'PDF & EPUB',
            'highlights' => ['Clean architecture', 'Microservices', 'Production setup'],
        ]);

        $this->customer = Customer::create([
            'name' => 'Alice Developer',
            'email' => 'alice@example.com',
            'mobile' => '+919876543210',
        ]);

        Config::set('services.cashfree.app_id', 'CF_TEST_APP_ID_123');
        Config::set('services.cashfree.secret_key', 'CF_TEST_SECRET_KEY_456');
        Config::set('services.cashfree.env', 'SANDBOX');
        Config::set('services.cashfree.api_version', '2026-01-01');
    }

    public function test_guest_without_details_cannot_initiate_payment(): void
    {
        $response = $this->postJson(route('payments.initiate', $this->book->slug));

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_guest_providing_email_and_mobile_creates_customer_and_initiates_cashfree_order(): void
    {
        Http::fake([
            'https://sandbox.cashfree.com/pg/orders' => Http::response([
                'order_id' => 'ORD_TEST_123',
                'cf_order_id' => '123456789',
                'payment_session_id' => 'session_mock_guest_session_token_123',
                'order_status' => 'ACTIVE',
            ], 200),
        ]);

        $response = $this->postJson(route('payments.initiate', $this->book->slug), [
            'name' => 'New Customer',
            'email' => 'newcustomer@example.com',
            'mobile' => '9876500000',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'gateway' => 'cashfree',
                'payment_session_id' => 'session_mock_guest_session_token_123',
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'newcustomer@example.com',
            'mobile' => '9876500000',
        ]);

        $this->assertDatabaseHas('purchases', [
            'book_identifier' => $this->book->slug,
            'amount' => 499.00,
            'payment_method' => 'cashfree',
            'status' => 'pending',
        ]);
    }

    public function test_authenticated_customer_initiates_cashfree_payment_session(): void
    {
        Http::fake([
            'https://sandbox.cashfree.com/pg/orders' => Http::response([
                'order_id' => 'ORD_AUTH_TEST_456',
                'cf_order_id' => '987654321',
                'payment_session_id' => 'session_mock_auth_token_999',
                'order_status' => 'ACTIVE',
            ], 200),
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->postJson(route('payments.initiate', $this->book->slug));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'gateway' => 'cashfree',
                'payment_session_id' => 'session_mock_auth_token_999',
            ]);

        $this->assertDatabaseHas('purchases', [
            'customer_id' => $this->customer->id,
            'book_identifier' => $this->book->slug,
            'amount' => 499.00,
            'payment_method' => 'cashfree',
            'status' => 'pending',
        ]);
    }

    public function test_cashfree_return_callback_verifies_order_as_paid_and_delivers_ebook(): void
    {
        $purchase = Purchase::create([
            'customer_id' => $this->customer->id,
            'book_identifier' => $this->book->slug,
            'book_title' => $this->book->title,
            'amount' => 499.00,
            'transaction_id' => 'ORD_RETURN_TEST_789',
            'cashfree_order_id' => 'CF_ORDER_789',
            'payment_method' => 'cashfree',
            'status' => 'pending',
        ]);

        Http::fake([
            'https://sandbox.cashfree.com/pg/orders/ORD_RETURN_TEST_789' => Http::response([
                'order_id' => 'ORD_RETURN_TEST_789',
                'cf_order_id' => 'CF_ORDER_789',
                'order_amount' => 499.00,
                'order_status' => 'PAID',
            ], 200),
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('payments.cashfree.callback', ['order_id' => 'ORD_RETURN_TEST_789']));

        $response->assertRedirect(route('books.show', $this->book->slug));
        $response->assertSessionHas('payment_success');

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'paid',
        ]);
    }

    public function test_cashfree_webhook_with_valid_hmac_signature_marks_purchase_as_paid(): void
    {
        $purchase = Purchase::create([
            'customer_id' => $this->customer->id,
            'book_identifier' => $this->book->slug,
            'book_title' => $this->book->title,
            'amount' => 499.00,
            'transaction_id' => 'ORD_WEBHOOK_TEST_101',
            'payment_method' => 'cashfree',
            'status' => 'pending',
        ]);

        $payload = [
            'data' => [
                'order' => [
                    'order_id' => 'ORD_WEBHOOK_TEST_101',
                    'order_amount' => 499.00,
                    'order_currency' => 'INR',
                ],
                'payment' => [
                    'cf_payment_id' => 'CF_PAY_998877',
                    'payment_status' => 'SUCCESS',
                    'payment_amount' => 499.00,
                ],
            ],
            'type' => 'PAYMENT_SUCCESS_WEBHOOK',
        ];

        $rawBody = json_encode($payload);
        $timestamp = (string) time();
        $secretKey = 'CF_TEST_SECRET_KEY_456';
        $signature = base64_encode(hash_hmac('sha256', $timestamp.$rawBody, $secretKey, true));

        $response = $this->call(
            'POST',
            route('payments.cashfree.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_x-webhook-signature' => $signature,
                'HTTP_x-webhook-timestamp' => $timestamp,
            ],
            $rawBody
        );

        $response->assertOk()
            ->assertJson(['status' => 'OK']);

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'paid',
            'cashfree_payment_id' => 'CF_PAY_998877',
        ]);
    }

    public function test_cashfree_webhook_with_invalid_signature_is_rejected(): void
    {
        $rawBody = json_encode(['data' => ['order' => ['order_id' => 'ORD_FAKE_999']]]);
        $timestamp = (string) time();

        $response = $this->call(
            'POST',
            route('payments.cashfree.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_x-webhook-signature' => 'invalid_forged_signature_xyz',
                'HTTP_x-webhook-timestamp' => $timestamp,
            ],
            $rawBody
        );

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid webhook signature']);
    }

    public function test_customer_can_download_paid_purchased_ebook(): void
    {
        $purchase = Purchase::create([
            'customer_id' => $this->customer->id,
            'book_identifier' => $this->book->slug,
            'book_title' => $this->book->title,
            'amount' => 499.00,
            'transaction_id' => 'ORD_DOWNLOAD_AUTH_1',
            'payment_method' => 'cashfree',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('purchases.download', $purchase->id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public function test_unauthorized_user_cannot_download_another_customer_ebook(): void
    {
        $otherCustomer = Customer::create([
            'name' => 'Stranger',
            'email' => 'stranger@example.com',
            'mobile' => '+919999999999',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $this->customer->id,
            'book_identifier' => $this->book->slug,
            'book_title' => $this->book->title,
            'amount' => 499.00,
            'transaction_id' => 'ORD_DOWNLOAD_AUTH_2',
            'payment_method' => 'cashfree',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($otherCustomer, 'customer')
            ->get(route('purchases.download', $purchase->id));

        $response->assertStatus(403);
    }

    public function test_cashfree_order_creation_in_production_environment_targets_production_api(): void
    {
        Config::set('services.cashfree.env', 'PRODUCTION');

        Http::fake([
            'https://api.cashfree.com/pg/orders' => Http::response([
                'order_id' => 'ORD_PROD_123',
                'cf_order_id' => '99998888',
                'payment_session_id' => 'session_prod_token_123',
                'order_status' => 'ACTIVE',
            ], 200),
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->postJson(route('payments.initiate', $this->book->slug));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'gateway' => 'cashfree',
                'environment' => 'PRODUCTION',
                'payment_session_id' => 'session_prod_token_123',
            ]);
    }

    public function test_cashfree_webhook_with_raw_payload_fallback_signature_marks_purchase_as_paid(): void
    {
        $purchase = Purchase::create([
            'customer_id' => $this->customer->id,
            'book_identifier' => $this->book->slug,
            'book_title' => $this->book->title,
            'amount' => 499.00,
            'transaction_id' => 'ORD_RAW_SIG_TEST_202',
            'payment_method' => 'cashfree',
            'status' => 'pending',
        ]);

        $payload = [
            'data' => [
                'order' => [
                    'order_id' => 'ORD_RAW_SIG_TEST_202',
                    'order_amount' => 499.00,
                ],
                'payment' => [
                    'cf_payment_id' => 'CF_PAY_FALLBACK_123',
                    'payment_status' => 'SUCCESS',
                ],
            ],
            'type' => 'PAYMENT_SUCCESS_WEBHOOK',
        ];

        $rawBody = json_encode($payload);
        $secretKey = 'CF_TEST_SECRET_KEY_456';
        // Test fallback without timestamp
        $signature = base64_encode(hash_hmac('sha256', $rawBody, $secretKey, true));

        $response = $this->call(
            'POST',
            route('payments.cashfree.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_x-webhook-signature' => $signature,
            ],
            $rawBody
        );

        $response->assertOk()
            ->assertJson(['status' => 'OK']);

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'paid',
            'cashfree_payment_id' => 'CF_PAY_FALLBACK_123',
        ]);
    }

    public function test_cashfree_webhook_payment_failed_event_marks_purchase_as_failed(): void
    {
        $purchase = Purchase::create([
            'customer_id' => $this->customer->id,
            'book_identifier' => $this->book->slug,
            'book_title' => $this->book->title,
            'amount' => 499.00,
            'transaction_id' => 'ORD_FAILED_TEST_303',
            'payment_method' => 'cashfree',
            'status' => 'pending',
        ]);

        $payload = [
            'data' => [
                'order' => [
                    'order_id' => 'ORD_FAILED_TEST_303',
                ],
                'payment' => [
                    'payment_status' => 'FAILED',
                ],
            ],
            'type' => 'PAYMENT_FAILED_WEBHOOK',
        ];

        $rawBody = json_encode($payload);
        $timestamp = (string) time();
        $secretKey = 'CF_TEST_SECRET_KEY_456';
        $signature = base64_encode(hash_hmac('sha256', $timestamp.$rawBody, $secretKey, true));

        $response = $this->call(
            'POST',
            route('payments.cashfree.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_x-webhook-signature' => $signature,
                'HTTP_x-webhook-timestamp' => $timestamp,
            ],
            $rawBody
        );

        $response->assertOk()
            ->assertJson(['status' => 'OK']);

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'failed',
        ]);
    }

    public function test_cashfree_service_test_connection(): void
    {
        Http::fake([
            'https://sandbox.cashfree.com/pg/orders/HEALTH_CHECK_TEST' => Http::response([
                'code' => 'order_not_found',
                'message' => 'Order Reference Id does not exist',
            ], 404),
        ]);

        /** @var CashfreeService $service */
        $service = app(CashfreeService::class);
        $result = $service->testConnection();

        $this->assertTrue($result['success']);
        $this->assertSame(404, $result['status_code']);
    }
}
