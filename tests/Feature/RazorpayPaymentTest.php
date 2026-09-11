<?php

namespace Tests\Feature;

use App\Mail\EbookDeliveryMail;
use App\Models\AppSetting;
use App\Models\Book;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RazorpayPaymentTest extends TestCase
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

    public function test_payment_fails_when_razorpay_gateway_is_disabled_in_settings(): void
    {
        $setting = AppSetting::getSettings();
        $setting->update([
            'easebuzz_enabled' => true,
            'razorpay_enabled' => false,
        ]);

        $customer = Customer::create([
            'name' => 'John Reader',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('payments.razorpay.initiate', 'algorithms-and-elegance'));

        $response->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $response->assertSessionHas('payment_error');
    }

    public function test_payment_fails_when_easebuzz_gateway_is_disabled_in_settings(): void
    {
        $setting = AppSetting::getSettings();
        $setting->update([
            'easebuzz_enabled' => false,
            'razorpay_enabled' => true,
        ]);

        $customer = Customer::create([
            'name' => 'John Reader',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('payments.initiate', 'algorithms-and-elegance'));

        $response->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $response->assertSessionHas('payment_error');
    }

    public function test_razorpay_mock_initiation_and_successful_simulation(): void
    {
        Mail::fake();

        config()->set('services.razorpay', [
            'key' => 'mock',
            'secret' => 'mock_secret',
            'environment' => 'mock',
        ]);

        $customer = Customer::create([
            'name' => 'Alice Mock',
            'email' => 'alice@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
        ]);

        $initiateResponse = $this->actingAs($customer, 'customer')
            ->post(route('payments.razorpay.initiate', 'algorithms-and-elegance'));

        $purchase = Purchase::where('customer_id', $customer->id)->firstOrFail();
        $this->assertEquals('razorpay', $purchase->payment_method);
        $this->assertEquals('pending', $purchase->status);

        $initiateResponse->assertRedirect(route('payments.razorpay.mock-checkout', $purchase));

        // Process successful mock payment
        $processResponse = $this->actingAs($customer, 'customer')
            ->post(route('payments.razorpay.mock-process', $purchase), [
                'action' => 'success',
            ]);

        $purchase->refresh();
        $this->assertEquals('paid', $purchase->status);
        $this->assertNotNull($purchase->razorpay_payment_id);

        $processResponse->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $processResponse->assertSessionHas('payment_success');

        Mail::assertSent(EbookDeliveryMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }

    public function test_razorpay_mock_simulation_failure(): void
    {
        config()->set('services.razorpay', [
            'key' => 'mock',
            'secret' => 'mock_secret',
            'environment' => 'mock',
        ]);

        $customer = Customer::create([
            'name' => 'Alice Mock',
            'email' => 'alice@example.com',
            'password' => 'password123',
        ]);

        $this->actingAs($customer, 'customer')
            ->post(route('payments.razorpay.initiate', 'algorithms-and-elegance'));

        $purchase = Purchase::where('customer_id', $customer->id)->firstOrFail();

        $processResponse = $this->actingAs($customer, 'customer')
            ->post(route('payments.razorpay.mock-process', $purchase), [
                'action' => 'failure',
            ]);

        $purchase->refresh();
        $this->assertEquals('failed', $purchase->status);

        $processResponse->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $processResponse->assertSessionHas('payment_error');
    }

    public function test_razorpay_callback_verifies_signature_and_fulfills_order(): void
    {
        Mail::fake();

        $key = 'rzp_test_key123';
        $secret = 'rzp_test_secret_abc123';
        config()->set('services.razorpay.key', $key);
        config()->set('services.razorpay.secret', $secret);

        $customer = Customer::create([
            'name' => 'Bob Buyer',
            'email' => 'bob@example.com',
            'password' => 'password123',
        ]);

        $orderId = 'order_mock123456';
        $paymentId = 'pay_mock7891011';

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'algorithms-and-elegance',
            'book_title' => 'Algorithms & Elegance',
            'amount' => 499.00,
            'payment_method' => 'razorpay',
            'transaction_id' => 'RZ123456',
            'razorpay_order_id' => $orderId,
            'status' => 'pending',
        ]);

        // Generate valid HMAC SHA256 signature: hash_hmac('sha256', order_id . "|" . payment_id, secret)
        $expectedSignature = hash_hmac('sha256', $orderId.'|'.$paymentId, $secret);

        $response = $this->post(route('payments.razorpay.callback', $purchase), [
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $expectedSignature,
        ]);

        $purchase->refresh();
        $this->assertEquals('paid', $purchase->status);
        $this->assertEquals($paymentId, $purchase->razorpay_payment_id);
        $this->assertEquals($expectedSignature, $purchase->razorpay_signature);

        $response->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $response->assertSessionHas('payment_success');

        Mail::assertSent(EbookDeliveryMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }

    public function test_razorpay_callback_rejects_invalid_signature(): void
    {
        $key = 'rzp_test_key123';
        $secret = 'rzp_test_secret_abc123';
        config()->set('services.razorpay.key', $key);
        config()->set('services.razorpay.secret', $secret);

        $customer = Customer::create([
            'name' => 'Bob Buyer',
            'email' => 'bob@example.com',
            'password' => 'password123',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'algorithms-and-elegance',
            'book_title' => 'Algorithms & Elegance',
            'amount' => 499.00,
            'payment_method' => 'razorpay',
            'transaction_id' => 'RZ123456',
            'razorpay_order_id' => 'order_mock123456',
            'status' => 'pending',
        ]);

        $response = $this->post(route('payments.razorpay.callback', $purchase), [
            'razorpay_order_id' => 'order_mock123456',
            'razorpay_payment_id' => 'pay_mock7891011',
            'razorpay_signature' => 'invalid_forged_signature',
        ]);

        $purchase->refresh();
        $this->assertEquals('failed', $purchase->status);

        $response->assertRedirect(route('books.show', 'algorithms-and-elegance'));
        $response->assertSessionHas('payment_error');
    }

    public function test_razorpay_webhook_handles_payment_captured_event(): void
    {
        Mail::fake();

        $webhookSecret = 'rzp_webhook_secret_xyz';
        config()->set('services.razorpay.key', 'rzp_test_key');
        config()->set('services.razorpay.secret', 'rzp_test_secret');
        config()->set('services.razorpay.webhook_secret', $webhookSecret);

        $customer = Customer::create([
            'name' => 'Bob Webhook',
            'email' => 'bobwh@example.com',
            'password' => 'password123',
        ]);

        $orderId = 'order_webhook_123';
        $paymentId = 'pay_webhook_789';

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'algorithms-and-elegance',
            'book_title' => 'Algorithms & Elegance',
            'amount' => 499.00,
            'payment_method' => 'razorpay',
            'transaction_id' => 'RZWH12345',
            'razorpay_order_id' => $orderId,
            'status' => 'pending',
        ]);

        $payload = [
            'entity' => 'event',
            'account_id' => 'acc_test123',
            'event' => 'payment.captured',
            'contains' => ['payment'],
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => $paymentId,
                        'entity' => 'payment',
                        'amount' => 49900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'order_id' => $orderId,
                        'notes' => [
                            'purchase_id' => (string) $purchase->id,
                        ],
                    ],
                ],
            ],
        ];

        $jsonPayload = json_encode($payload);
        $signature = hash_hmac('sha256', $jsonPayload, $webhookSecret);

        $response = $this->call(
            'POST',
            route('payments.razorpay.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
            ],
            $jsonPayload
        );

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $purchase->refresh();
        $this->assertEquals('paid', $purchase->status);
        $this->assertEquals($paymentId, $purchase->razorpay_payment_id);

        Mail::assertSent(EbookDeliveryMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }
}
