<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CashfreeService
{
    private string $appId;

    private string $secretKey;

    private string $environment;

    private string $apiVersion;

    private string $baseUrl;

    public function __construct()
    {
        $this->appId = (string) config('services.cashfree.app_id', '');
        $this->secretKey = (string) config('services.cashfree.secret_key', '');
        $this->environment = strtoupper((string) config('services.cashfree.env', 'SANDBOX'));
        $this->apiVersion = (string) config('services.cashfree.api_version', '2023-08-01');

        $this->baseUrl = $this->environment === 'PRODUCTION'
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';
    }

    /**
     * Check if Cashfree credentials are configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->appId) && ! empty($this->secretKey);
    }

    /**
     * Get environment name (SANDBOX or PRODUCTION).
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Format a phone number for Cashfree requirements (must be at least 10 digits).
     */
    public function sanitizePhone(?string $phone): string
    {
        if (empty($phone)) {
            return '9999999999';
        }

        $digits = preg_replace('/[^0-9]/', '', $phone) ?? '';

        // If with country code prefix (e.g. 919876543210), take last 10 digits if >= 10
        if (strlen($digits) >= 10) {
            return substr($digits, -10);
        }

        return str_pad($digits, 10, '0', STR_PAD_RIGHT);
    }

    /**
     * Create a Cashfree PG Order.
     *
     * @return array{order_id: string, payment_session_id: ?string, order_status: string, raw: array}
     */
    public function createOrder(Purchase $purchase, Customer $customer, Book $book, string $returnUrl, ?string $notifyUrl = null): array
    {
        $customerPhone = $this->sanitizePhone($customer->mobile);
        $customerEmail = ! empty($customer->email) ? $customer->email : 'customer_'.$customer->id.'@bookstore.local';
        $customerName = ! empty($customer->name) ? $customer->name : 'Customer';

        $payload = [
            'order_id' => (string) $purchase->transaction_id,
            'order_amount' => (float) number_format((float) $purchase->amount, 2, '.', ''),
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'cust_'.$customer->id,
                'customer_name' => Str::limit($customerName, 90, ''),
                'customer_email' => Str::limit($customerEmail, 90, ''),
                'customer_phone' => $customerPhone,
            ],
            'order_meta' => array_filter([
                'return_url' => $returnUrl,
                'notify_url' => $notifyUrl,
                'payment_methods' => 'cc,dc,upi,nb,app,paylater',
            ]),
            'order_note' => Str::limit('Purchase: '.$book->title, 100, ''),
        ];

        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->appId,
                'x-client-secret' => $this->secretKey,
                'x-api-version' => $this->apiVersion,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post($this->baseUrl.'/orders', $payload);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'order_id' => $data['order_id'] ?? $purchase->transaction_id,
                    'cf_order_id' => $data['cf_order_id'] ?? null,
                    'payment_session_id' => $data['payment_session_id'] ?? null,
                    'order_status' => $data['order_status'] ?? 'ACTIVE',
                    'raw' => $data,
                ];
            }

            Log::error('Cashfree Create Order Error', [
                'status' => $response->status(),
                'response' => $response->json() ?? $response->body(),
                'payload' => $payload,
            ]);

            throw new \RuntimeException($response->json('message') ?? 'Failed to create Cashfree order (HTTP '.$response->status().')');
        } catch (\Throwable $e) {
            Log::error('Cashfree Create Order Exception: '.$e->getMessage(), [
                'order_id' => $purchase->transaction_id,
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Fetch order details directly from Cashfree API.
     *
     * @return array<string, mixed>
     */
    public function fetchOrder(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->appId,
                'x-client-secret' => $this->secretKey,
                'x-api-version' => $this->apiVersion,
                'Accept' => 'application/json',
            ])->timeout(15)->get($this->baseUrl.'/orders/'.$orderId);

            if ($response->successful()) {
                return (array) $response->json();
            }

            Log::error('Cashfree Fetch Order Failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Throwable $e) {
            Log::error('Cashfree Fetch Order Exception: '.$e->getMessage(), [
                'order_id' => $orderId,
            ]);

            return [];
        }
    }

    /**
     * Fetch payments list for a given order ID.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchOrderPayments(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->appId,
                'x-client-secret' => $this->secretKey,
                'x-api-version' => $this->apiVersion,
                'Accept' => 'application/json',
            ])->timeout(15)->get($this->baseUrl.'/orders/'.$orderId.'/payments');

            if ($response->successful()) {
                return (array) $response->json();
            }

            return [];
        } catch (\Throwable $e) {
            Log::error('Cashfree Fetch Payments Exception: '.$e->getMessage(), [
                'order_id' => $orderId,
            ]);

            return [];
        }
    }

    /**
     * Cryptographically verify Cashfree webhook signature using HMAC-SHA256.
     *
     * Header x-webhook-signature and x-webhook-timestamp are required.
     * Signature = base64_encode(hash_hmac('sha256', timestamp + rawBody, secretKey, true))
     */
    public function verifyWebhookSignature(string $rawBody, ?string $signature, ?string $timestamp): bool
    {
        if (empty($signature) || empty($timestamp) || empty($this->secretKey)) {
            return false;
        }

        $signedPayload = $timestamp.$rawBody;
        $expectedSignature = base64_encode(hash_hmac('sha256', $signedPayload, $this->secretKey, true));

        return hash_equals($expectedSignature, $signature);
    }
}
