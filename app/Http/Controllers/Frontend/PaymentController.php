<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\EbookDeliveryMail;
use App\Models\AppSetting;
use App\Models\Book;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(private BookController $books) {}

    /**
     * Resolve or register customer from request before payment initiation.
     */
    private function resolveCustomer(Request $request, string $identifier): ?Customer
    {
        /** @var Customer|null $customer */
        $customer = $request->user('customer');

        if (! $customer) {
            if ($request->filled('email')) {
                $validated = $request->validate([
                    'email' => ['required', 'string', 'email', 'max:255'],
                    'name' => ['nullable', 'string', 'max:255'],
                    'mobile' => ['nullable', 'string', 'max:20'],
                ]);

                $email = strtolower(trim($validated['email']));
                $name = trim($validated['name'] ?? '') ?: 'Customer';
                $mobile = trim($validated['mobile'] ?? '') ?: null;

                $customer = Customer::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'mobile' => $mobile,
                        'password' => Hash::make(Str::random(16)),
                    ]
                );

                if ($mobile && ! $customer->mobile) {
                    $customer->update(['mobile' => $mobile]);
                }

                Auth::guard('customer')->login($customer);
            }
        }

        return $customer;
    }

    /**
     * Initiate Easebuzz payment gateway checkout for a book.
     */
    public function initiate(Request $request, string $identifier): RedirectResponse
    {
        $appSetting = AppSetting::getSettings();
        if (! $appSetting->isEasebuzzEnabled()) {
            return redirect()->route('books.show', $identifier)
                ->with('payment_error', 'Easebuzz payment method is currently disabled by administrator.');
        }

        $customer = $this->resolveCustomer($request, $identifier);
        $bookData = $this->books->findBook($identifier);

        if (! $bookData) {
            abort(404, 'E-Book not found.');
        }

        if (! $customer) {
            return redirect()->route('books.show', ['identifier' => $identifier, 'checkout' => 1])
                ->with('payment_error', 'Please enter your email to proceed directly to payment.');
        }

        $key = trim((string) config('services.easebuzz.key'));
        $salt = trim((string) config('services.easebuzz.salt'));
        $env = strtolower(trim((string) config('services.easebuzz.environment', 'test')));
        $isMock = in_array($env, ['mock', 'simulation', 'local_test'], true)
            || in_array(strtolower($key), ['mock', 'demo', 'test_mode'], true);

        if ($isMock) {
            $amount = max(1.0, $this->amountFrom($bookData['price']));
            $purchase = Purchase::create([
                'customer_id' => $customer->id,
                'book_identifier' => (string) ($bookData['slug'] ?? $bookData['id']),
                'book_title' => (string) $bookData['title'],
                'amount' => $amount,
                'payment_method' => 'easebuzz',
                'transaction_id' => 'EBMOCK'.now()->format('YmdHis').Str::upper(Str::random(4)),
                'status' => 'pending',
            ]);

            return redirect()->route('payments.mock-checkout', $purchase);
        }

        if ($key === '' || $salt === '') {
            return redirect()->route('books.show', $identifier)
                ->with('payment_error', 'Easebuzz payment gateway is not configured yet. Set EASEBUZZ_ENV=mock in .env for test simulation, or provide Easebuzz merchant credentials.');
        }

        $amount = max(1.0, $this->amountFrom($bookData['price']));
        $cleanTitle = preg_replace('/[^a-zA-Z0-9 ]/', '', (string) ($bookData['title'] ?? ''));
        $productInfo = Str::limit(trim($cleanTitle), 40, '') ?: 'EBook Publication';

        $cleanName = preg_replace('/[^a-zA-Z0-9 ]/', '', (string) ($customer->name ?? ''));
        $firstName = Str::limit(trim($cleanName), 50, '') ?: 'Customer';

        $email = trim(strtolower((string) $customer->email));

        $phone = preg_replace('/[^0-9]/', '', (string) ($customer->mobile ?? ''));
        if (strlen($phone) !== 10 || ! in_array($phone[0] ?? '', ['6', '7', '8', '9'])) {
            $phone = '9876543210';
        }

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => (string) ($bookData['slug'] ?? $bookData['id']),
            'book_title' => (string) $bookData['title'],
            'amount' => $amount,
            'payment_method' => 'easebuzz',
            'transaction_id' => 'EB'.now()->format('YmdHis').Str::upper(Str::random(6)),
            'status' => 'pending',
        ]);

        $payload = [
            'key' => $key,
            'txnid' => $purchase->transaction_id,
            'amount' => number_format($amount, 2, '.', ''),
            'productinfo' => $productInfo,
            'firstname' => $firstName,
            'email' => $email,
            'phone' => $phone,
            'surl' => route('payments.return', $purchase),
            'furl' => route('payments.return', $purchase),
            'webhook_url' => route('payments.webhook'),
            'udf1' => (string) Str::limit(preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($bookData['id'] ?? '')), 50, ''),
            'udf2' => (string) $customer->id,
            'udf3' => (string) Str::limit(preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($bookData['slug'] ?? '')), 50, ''),
            'udf4' => '',
            'udf5' => '',
            'udf6' => '',
            'udf7' => '',
            'udf8' => '',
            'udf9' => '',
            'udf10' => '',
        ];

        $payload['hash'] = $this->requestHash($payload, $salt);

        try {
            $response = Http::asForm()->post($this->initiateUrl(), $payload);
        } catch (\Throwable $e) {
            Log::error('Easebuzz Connection Error: '.$e->getMessage(), ['txnid' => $purchase->transaction_id]);

            return redirect()->route('books.show', $identifier)
                ->with('payment_error', 'Unable to connect to Easebuzz payment gateway. Please try again.');
        }

        $responseData = $response->json();
        $status = $responseData['status'] ?? 0;
        $paymentData = $responseData['data'] ?? null;
        $errorReason = $responseData['error_desc'] ?? $responseData['reason'] ?? (is_string($paymentData) ? $paymentData : null);

        Log::info('Easebuzz Initiate Response', [
            'txnid' => $purchase->transaction_id,
            'status' => $status,
            'response' => $responseData,
        ]);

        if (! $response->successful() || (int) $status !== 1 || ! is_string($paymentData) || trim($paymentData) === '') {
            $purchase->update([
                'status' => 'failed',
                'gateway_response' => is_array($responseData) ? $responseData : ['raw' => $response->body()],
            ]);

            $errorMsg = ! empty($errorReason) ? $errorReason : 'Unable to initiate payment with Easebuzz.';

            if (str_contains(strtolower($errorMsg), 'invalid merchant key') || str_contains(strtolower($errorMsg), 'invalid value for key')) {
                $currentEnv = config('services.easebuzz.environment', 'test');
                $errorMsg = "Invalid Easebuzz Merchant Key. If you are using Live/Production credentials, please set EASEBUZZ_ENV=production in your .env file. (Current environment: '{$currentEnv}').";
            }

            return redirect()->route('books.show', $identifier)->with('payment_error', $errorMsg);
        }

        // Check if data is already a full redirect URL or an access token
        $redirectUrl = str_starts_with($paymentData, 'http://') || str_starts_with($paymentData, 'https://')
            ? $paymentData
            : $this->payUrl($paymentData);

        return redirect()->away($redirectUrl);
    }

    /**
     * Handle return response callback from Easebuzz (surl / furl).
     */
    public function handleReturn(Request $request, Purchase $purchase): RedirectResponse
    {
        $salt = trim((string) config('services.easebuzz.salt'));

        if ($salt === '') {
            $purchase->update(['status' => 'failed', 'gateway_response' => $request->all()]);

            return redirect()->route('books.show', $purchase->book_identifier)
                ->with('payment_error', 'Payment configuration error.');
        }

        $payload = $request->all();
        $receivedHash = (string) ($payload['hash'] ?? '');
        $calculatedHash = $this->responseHash($payload, $salt);

        $isValid = hash_equals($calculatedHash, $receivedHash);

        if (
            ! $isValid
            || ($payload['key'] ?? null) !== config('services.easebuzz.key')
            || ($payload['txnid'] ?? null) !== $purchase->transaction_id
            || number_format((float) ($payload['amount'] ?? 0), 2, '.', '') !== number_format((float) $purchase->amount, 2, '.', '')
        ) {
            $purchase->update(['status' => 'failed', 'gateway_response' => $payload]);

            return redirect()->route('books.show', $purchase->book_identifier)
                ->with('payment_error', 'We could not verify the payment response from Easebuzz.');
        }

        $status = strtolower((string) ($payload['status'] ?? ''));
        $isSuccess = ($status === 'success');

        $purchase->update([
            'status' => $isSuccess ? 'paid' : 'failed',
            'gateway_response' => $payload,
        ]);

        if ($isSuccess) {
            $customer = $purchase->customer;
            $dbBook = Book::where('slug', $purchase->book_identifier)
                ->orWhere('id', $purchase->book_identifier)
                ->first();

            // Automatically send the E-Book PDF delivery email to customer's registered email
            if ($customer && ! empty($customer->email)) {
                try {
                    Mail::to($customer->email)->send(new EbookDeliveryMail($purchase, $dbBook, $customer));
                } catch (\Throwable $mailException) {
                    Log::error('E-Book Delivery Email Failed: '.$mailException->getMessage(), [
                        'purchase_id' => $purchase->id,
                        'customer_email' => $customer->email,
                    ]);
                }
            }

            return redirect()->route('books.show', $purchase->book_identifier)->with([
                'payment_success' => '🎉 Payment completed successfully! Your e-book is ready.',
                'auto_download_url' => route('purchases.download', $purchase),
                'purchased_book_title' => $purchase->book_title,
                'customer_email' => $customer?->email ?? '',
            ]);
        }

        return redirect()->route('books.show', $purchase->book_identifier)
            ->with('payment_error', 'Your payment was not completed or was cancelled.');
    }

    /**
     * Handle Server-to-Server Webhook Notification from Easebuzz.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();

        // 1. Audit Log: Log incoming webhook attempt securely
        Log::info('Easebuzz Webhook Notification Received', [
            'ip' => $request->ip(),
            'txnid' => $payload['txnid'] ?? null,
            'status' => $payload['status'] ?? null,
            'easepayid' => $payload['easepayid'] ?? null,
        ]);

        $salt = trim((string) config('services.easebuzz.salt'));
        $key = trim((string) config('services.easebuzz.key'));
        $webhookSecret = trim((string) config('services.easebuzz.webhook_secret'));

        // 2. Optional Webhook Secret Verification (Header or Query token)
        if ($webhookSecret !== '') {
            $providedSecret = (string) ($request->header('X-Webhook-Secret') ?: $request->query('secret', ''));
            if (! hash_equals($webhookSecret, $providedSecret)) {
                Log::warning('Payment Webhook Security Failure: Invalid webhook secret token.', [
                    'ip' => $request->ip(),
                ]);

                return response()->json(['status' => 'error', 'message' => 'Unauthorized webhook secret.'], 401);
            }
        }

        if ($salt === '' || $key === '') {
            Log::error('Payment Webhook Error: Easebuzz merchant credentials not configured.');

            return response()->json(['status' => 'error', 'message' => 'Gateway credentials missing.'], 500);
        }

        // 3. Validate essential transaction ID
        $txnid = trim((string) ($payload['txnid'] ?? ''));
        if ($txnid === '') {
            Log::warning('Payment Webhook Warning: Missing transaction ID (txnid).', ['payload' => $payload]);

            return response()->json(['status' => 'error', 'message' => 'Missing transaction ID.'], 400);
        }

        // 4. Find associated purchase record
        /** @var Purchase|null $purchase */
        $purchase = Purchase::where('transaction_id', $txnid)->first();
        if (! $purchase) {
            Log::warning('Payment Webhook Warning: Purchase record not found.', ['txnid' => $txnid]);

            return response()->json(['status' => 'error', 'message' => 'Purchase not found.'], 404);
        }

        // 5. Verify Cryptographic SHA-512 Reverse Hash Signature
        $receivedHash = (string) ($payload['hash'] ?? '');
        $calculatedHash = $this->responseHash($payload, $salt);

        if (! hash_equals($calculatedHash, $receivedHash)) {
            Log::warning('Payment Webhook Security Failure: Hash signature mismatch.', [
                'txnid' => $txnid,
                'ip' => $request->ip(),
            ]);

            return response()->json(['status' => 'error', 'message' => 'Invalid signature verification.'], 403);
        }

        // 6. Verify Merchant Key and Amount
        $merchantKey = (string) ($payload['key'] ?? '');
        $paidAmount = (float) ($payload['amount'] ?? 0);
        $expectedAmount = (float) $purchase->amount;

        if ($merchantKey !== $key) {
            Log::warning('Payment Webhook Security Failure: Merchant key mismatch.', [
                'txnid' => $txnid,
                'expected' => $key,
                'received' => $merchantKey,
            ]);

            return response()->json(['status' => 'error', 'message' => 'Invalid merchant key.'], 403);
        }

        if (number_format($paidAmount, 2, '.', '') !== number_format($expectedAmount, 2, '.', '')) {
            Log::warning('Payment Webhook Security Failure: Transaction amount mismatch.', [
                'txnid' => $txnid,
                'expected_amount' => $expectedAmount,
                'received_amount' => $paidAmount,
            ]);

            return response()->json(['status' => 'error', 'message' => 'Payment amount mismatch.'], 400);
        }

        // 7. Idempotency Check: Avoid double fulfillment if already paid
        if ($purchase->status === 'paid') {
            Log::info('Payment Webhook: Purchase already processed as paid.', ['txnid' => $txnid]);

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase was already paid and processed.',
                'purchase_id' => $purchase->id,
            ], 200);
        }

        // 8. Process Transaction Status
        $status = strtolower((string) ($payload['status'] ?? ''));
        $isSuccess = ($status === 'success');

        $purchase->update([
            'status' => $isSuccess ? 'paid' : 'failed',
            'gateway_response' => $payload,
        ]);

        // 9. Dispatch Delivery Mail on Successful Payment
        if ($isSuccess) {
            $customer = $purchase->customer;
            $dbBook = Book::where('slug', $purchase->book_identifier)
                ->orWhere('id', $purchase->book_identifier)
                ->first();

            if ($customer && ! empty($customer->email)) {
                try {
                    Mail::to($customer->email)->send(new EbookDeliveryMail($purchase, $dbBook, $customer));
                    Log::info('Payment Webhook: E-Book Delivery Email dispatched successfully.', [
                        'txnid' => $txnid,
                        'customer' => $customer->email,
                    ]);
                } catch (\Throwable $mailException) {
                    Log::error('Payment Webhook: E-Book Delivery Email Failed: '.$mailException->getMessage(), [
                        'purchase_id' => $purchase->id,
                        'customer_email' => $customer->email,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook notification processed successfully.',
            'purchase_id' => $purchase->id,
            'purchase_status' => $purchase->status,
        ], 200);
    }

    /**
     * Initiate Razorpay payment gateway checkout for a book.
     */
    public function initiateRazorpay(Request $request, string $identifier): RedirectResponse|View
    {
        $appSetting = AppSetting::getSettings();
        if (! $appSetting->isRazorpayEnabled()) {
            return redirect()->route('books.show', $identifier)
                ->with('payment_error', 'Razorpay payment method is currently disabled by administrator.');
        }

        $customer = $this->resolveCustomer($request, $identifier);
        $bookData = $this->books->findBook($identifier);

        if (! $bookData) {
            abort(404, 'E-Book not found.');
        }

        if (! $customer) {
            return redirect()->route('books.show', ['identifier' => $identifier, 'checkout' => 1])
                ->with('payment_error', 'Please enter your email to proceed directly to payment.');
        }

        $key = trim((string) config('services.razorpay.key'));
        $secret = trim((string) config('services.razorpay.secret'));
        $env = strtolower(trim((string) config('services.razorpay.environment', 'test')));
        $isMock = in_array($env, ['mock', 'simulation', 'local_test'], true)
            || in_array(strtolower($key), ['mock', 'demo', 'test_mode'], true);

        $amount = max(1.0, $this->amountFrom($bookData['price']));

        if ($isMock) {
            $purchase = Purchase::create([
                'customer_id' => $customer->id,
                'book_identifier' => (string) ($bookData['slug'] ?? $bookData['id']),
                'book_title' => (string) $bookData['title'],
                'amount' => $amount,
                'payment_method' => 'razorpay',
                'transaction_id' => 'RZMOCK'.now()->format('YmdHis').Str::upper(Str::random(4)),
                'status' => 'pending',
            ]);

            return redirect()->route('payments.razorpay.mock-checkout', $purchase);
        }

        if ($key === '' || $secret === '') {
            return redirect()->route('books.show', $identifier)
                ->with('payment_error', 'Razorpay payment gateway is not configured yet. Set RAZORPAY_ENV=mock in .env for test simulation, or provide Razorpay Key and Secret in .env.');
        }

        $txnid = 'RZ'.now()->format('YmdHis').Str::upper(Str::random(6));

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => (string) ($bookData['slug'] ?? $bookData['id']),
            'book_title' => (string) $bookData['title'],
            'amount' => $amount,
            'payment_method' => 'razorpay',
            'transaction_id' => $txnid,
            'status' => 'pending',
        ]);

        try {
            $api = new Api($key, $secret);
            $order = $api->order->create([
                'receipt' => $purchase->transaction_id,
                'amount' => (int) round($amount * 100), // Amount in paise
                'currency' => 'INR',
                'notes' => [
                    'purchase_id' => (string) $purchase->id,
                    'book_identifier' => (string) ($bookData['slug'] ?? $bookData['id']),
                    'customer_email' => (string) $customer->email,
                ],
            ]);

            $purchase->update([
                'razorpay_order_id' => $order['id'],
                'gateway_response' => $order->toArray(),
            ]);

            return view('frontend.payments.razorpay-checkout', [
                'purchase' => $purchase,
                'razorpayOrder' => $order,
                'razorpayKey' => $key,
                'appName' => $appSetting->app_name ?? config('app.name', 'E-Book CMS'),
                'appLogo' => $appSetting->logo_dark_url ?? $appSetting->logo_light_url ?? '',
            ]);
        } catch (\Throwable $e) {
            Log::error('Razorpay Order Creation Error: '.$e->getMessage(), [
                'purchase_id' => $purchase->id,
            ]);

            $purchase->update([
                'status' => 'failed',
                'gateway_response' => ['error' => $e->getMessage()],
            ]);

            return redirect()->route('books.show', $identifier)
                ->with('payment_error', 'Unable to initialize Razorpay checkout: '.$e->getMessage());
        }
    }

    /**
     * Handle Razorpay standard checkout callback (success or client fail).
     */
    public function handleRazorpayCallback(Request $request, Purchase $purchase): RedirectResponse
    {
        $key = trim((string) config('services.razorpay.key'));
        $secret = trim((string) config('services.razorpay.secret'));

        $paymentId = (string) $request->input('razorpay_payment_id', '');
        $orderId = (string) ($request->input('razorpay_order_id', '') ?: $purchase->razorpay_order_id);
        $signature = (string) $request->input('razorpay_signature', '');

        if ($paymentId === '' || $signature === '') {
            $purchase->update([
                'status' => 'failed',
                'gateway_response' => $request->all(),
            ]);

            return redirect()->route('books.show', $purchase->book_identifier)
                ->with('payment_error', 'Payment verification failed: Missing Razorpay transaction identifiers.');
        }

        try {
            $api = new Api($key, $secret);
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);

            $purchase->update([
                'status' => 'paid',
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
                'gateway_response' => $request->all(),
            ]);

            $customer = $purchase->customer;
            $dbBook = Book::where('slug', $purchase->book_identifier)
                ->orWhere('id', $purchase->book_identifier)
                ->first();

            // Automatically send the E-Book PDF delivery email to customer's registered email
            if ($customer && ! empty($customer->email)) {
                try {
                    Mail::to($customer->email)->send(new EbookDeliveryMail($purchase, $dbBook, $customer));
                } catch (\Throwable $mailException) {
                    Log::error('Razorpay E-Book Delivery Email Failed: '.$mailException->getMessage(), [
                        'purchase_id' => $purchase->id,
                        'customer_email' => $customer->email,
                    ]);
                }
            }

            return redirect()->route('books.show', $purchase->book_identifier)->with([
                'payment_success' => '🎉 Payment completed successfully via Razorpay! Your e-book is ready.',
                'auto_download_url' => route('purchases.download', $purchase),
                'purchased_book_title' => $purchase->book_title,
                'customer_email' => $customer?->email ?? '',
            ]);
        } catch (SignatureVerificationError $e) {
            Log::warning('Razorpay Signature Verification Error: '.$e->getMessage(), [
                'purchase_id' => $purchase->id,
                'payload' => $request->all(),
            ]);

            $purchase->update([
                'status' => 'failed',
                'gateway_response' => $request->all(),
            ]);

            return redirect()->route('books.show', $purchase->book_identifier)
                ->with('payment_error', 'Razorpay payment signature verification failed.');
        } catch (\Throwable $e) {
            Log::error('Razorpay Callback Error: '.$e->getMessage(), [
                'purchase_id' => $purchase->id,
            ]);

            return redirect()->route('books.show', $purchase->book_identifier)
                ->with('payment_error', 'An error occurred while confirming your Razorpay payment.');
        }
    }

    /**
     * Handle Server-to-Server Webhook Notification from Razorpay.
     */
    public function handleRazorpayWebhook(Request $request): JsonResponse
    {
        $webhookBody = $request->getContent();
        $webhookSignature = (string) $request->header('X-Razorpay-Signature', '');
        $webhookSecret = trim((string) config('services.razorpay.webhook_secret'));

        Log::info('Razorpay Webhook Notification Received', [
            'ip' => $request->ip(),
            'signature_present' => ! empty($webhookSignature),
        ]);

        // Verify signature if secret is configured
        if ($webhookSecret !== '') {
            if ($webhookSignature === '') {
                Log::warning('Razorpay Webhook: Missing X-Razorpay-Signature header.');

                return response()->json(['status' => 'error', 'message' => 'Missing webhook signature.'], 400);
            }

            try {
                $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $api->utility->verifyWebhookSignature($webhookBody, $webhookSignature, $webhookSecret);
            } catch (\Throwable $e) {
                Log::warning('Razorpay Webhook Signature Verification Failed: '.$e->getMessage());

                return response()->json(['status' => 'error', 'message' => 'Invalid webhook signature.'], 403);
            }
        }

        $payload = json_decode($webhookBody, true) ?? [];
        $event = (string) ($payload['event'] ?? '');

        Log::info('Razorpay Webhook Event: '.$event);

        $paymentEntity = $payload['payload']['payment']['entity'] ?? [];
        $orderId = $paymentEntity['order_id'] ?? ($payload['payload']['order']['entity']['id'] ?? null);
        $paymentId = $paymentEntity['id'] ?? null;
        $notes = $paymentEntity['notes'] ?? ($payload['payload']['order']['entity']['notes'] ?? []);
        $purchaseId = $notes['purchase_id'] ?? null;

        /** @var Purchase|null $purchase */
        $purchase = null;
        if ($orderId) {
            $purchase = Purchase::where('razorpay_order_id', $orderId)->first();
        }
        if (! $purchase && $purchaseId) {
            $purchase = Purchase::find($purchaseId);
        }

        if (! $purchase) {
            Log::warning('Razorpay Webhook: Matching purchase record not found.', ['order_id' => $orderId, 'purchase_id' => $purchaseId]);

            return response()->json(['status' => 'success', 'message' => 'Order not found, ignored.'], 200);
        }

        if (in_array($event, ['payment.captured', 'order.paid'], true)) {
            if ($purchase->status === 'paid') {
                return response()->json(['status' => 'success', 'message' => 'Already processed as paid.'], 200);
            }

            $purchase->update([
                'status' => 'paid',
                'razorpay_payment_id' => $paymentId ?: $purchase->razorpay_payment_id,
                'gateway_response' => $payload,
            ]);

            $customer = $purchase->customer;
            $dbBook = Book::where('slug', $purchase->book_identifier)
                ->orWhere('id', $purchase->book_identifier)
                ->first();

            if ($customer && ! empty($customer->email)) {
                try {
                    Mail::to($customer->email)->send(new EbookDeliveryMail($purchase, $dbBook, $customer));
                } catch (\Throwable $e) {
                    Log::error('Razorpay Webhook: Delivery Mail Error: '.$e->getMessage());
                }
            }
        } elseif ($event === 'payment.failed') {
            $purchase->update([
                'status' => 'failed',
                'gateway_response' => $payload,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Razorpay webhook processed successfully.',
            'purchase_id' => $purchase->id,
        ], 200);
    }

    /**
     * Display mock Razorpay simulator checkout page.
     */
    public function mockRazorpayCheckout(Request $request, Purchase $purchase): View
    {
        return view('frontend.payments.razorpay-mock-checkout', compact('purchase'));
    }

    /**
     * Process mock Razorpay payment simulation result.
     */
    public function processRazorpayMockPayment(Request $request, Purchase $purchase): RedirectResponse
    {
        $action = $request->input('action', 'success');

        if ($action === 'success') {
            $mockResponse = [
                'status' => 'captured',
                'razorpay_payment_id' => 'pay_'.Str::random(14),
                'razorpay_order_id' => 'order_'.Str::random(14),
                'amount' => (int) round($purchase->amount * 100),
                'currency' => 'INR',
                'payment_source' => 'Razorpay Sandbox Simulator',
                'method' => 'upi',
            ];

            $purchase->update([
                'status' => 'paid',
                'razorpay_payment_id' => $mockResponse['razorpay_payment_id'],
                'razorpay_order_id' => $mockResponse['razorpay_order_id'],
                'gateway_response' => $mockResponse,
            ]);

            $customer = $purchase->customer;
            $dbBook = Book::where('slug', $purchase->book_identifier)
                ->orWhere('id', $purchase->book_identifier)
                ->first();

            // Automatically send the E-Book PDF delivery email to customer's registered email
            if ($customer && ! empty($customer->email)) {
                try {
                    Mail::to($customer->email)->send(new EbookDeliveryMail($purchase, $dbBook, $customer));
                } catch (\Throwable $mailException) {
                    Log::error('E-Book Delivery Email Failed (Razorpay Mock): '.$mailException->getMessage(), [
                        'purchase_id' => $purchase->id,
                        'customer_email' => $customer->email,
                    ]);
                }
            }

            return redirect()->route('books.show', $purchase->book_identifier)->with([
                'payment_success' => '🎉 Payment completed successfully via Razorpay! Your e-book is ready.',
                'auto_download_url' => route('purchases.download', $purchase),
                'purchased_book_title' => $purchase->book_title,
                'customer_email' => $customer?->email ?? '',
            ]);
        }

        $purchase->update([
            'status' => 'failed',
            'gateway_response' => [
                'status' => 'cancelled',
                'error' => 'Transaction cancelled by user (Razorpay Simulation)',
            ],
        ]);

        return redirect()->route('books.show', $purchase->book_identifier)
            ->with('payment_error', 'Your payment was cancelled or failed.');
    }

    /**
     * Download the full purchased e-book.
     */
    public function downloadPurchasedEbook(Purchase $purchase): Response
    {
        if ($purchase->status !== 'paid') {
            abort(403, 'This purchase has not been paid or verified.');
        }

        $dbBook = Book::where('slug', $purchase->book_identifier)
            ->orWhere('id', $purchase->book_identifier)
            ->first();

        // 1. If physical full PDF exists in public disk
        if ($dbBook && $dbBook->ebook_file && Storage::disk('public')->exists($dbBook->ebook_file)) {
            $fileName = Str::slug($dbBook->title).'-complete-edition.pdf';

            return Storage::disk('public')->download($dbBook->ebook_file, $fileName);
        }

        // 2. If sample PDF exists
        if ($dbBook && $dbBook->sample_file && Storage::disk('public')->exists($dbBook->sample_file)) {
            $fileName = Str::slug($dbBook->title).'-sample-edition.pdf';

            return Storage::disk('public')->download($dbBook->sample_file, $fileName);
        }

        // 3. Otherwise, render the rich printable full edition document
        $bookData = $this->books->findBook($purchase->book_identifier);
        if (! $bookData && $dbBook) {
            $bookData = [
                'id' => $dbBook->id,
                'slug' => $dbBook->slug,
                'title' => $dbBook->title,
                'author' => $dbBook->author_name,
                'category' => $dbBook->category?->title ?? 'E-Book',
                'pages' => $dbBook->pages ?: 320,
                'format' => $dbBook->format ?: 'EPUB & PDF',
                'description' => $dbBook->description,
                'highlights' => $dbBook->highlights_list,
            ];
        }

        $filename = ($bookData['slug'] ?? 'ebook').'-full-edition.html';

        $html = view('frontend.books.ebook-full-download', [
            'book' => $bookData ?? [
                'title' => $purchase->book_title,
                'author' => 'Author',
                'category' => 'Publication',
                'pages' => 320,
                'format' => 'PDF & EPUB',
                'description' => 'Official DRM-Free Digital Publication.',
            ],
            'customer' => $purchase->customer,
            'purchase' => $purchase,
        ])->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Generate SHA512 hash for Easebuzz Payment Initiation.
     * Formula: key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10|salt
     *
     * @param  array<string, mixed>  $payload
     */
    private function requestHash(array $payload, string $salt): string
    {
        $hashSequence = [
            $payload['key'] ?? '',
            $payload['txnid'] ?? '',
            $payload['amount'] ?? '',
            $payload['productinfo'] ?? '',
            $payload['firstname'] ?? '',
            $payload['email'] ?? '',
            $payload['udf1'] ?? '',
            $payload['udf2'] ?? '',
            $payload['udf3'] ?? '',
            $payload['udf4'] ?? '',
            $payload['udf5'] ?? '',
            $payload['udf6'] ?? '',
            $payload['udf7'] ?? '',
            $payload['udf8'] ?? '',
            $payload['udf9'] ?? '',
            $payload['udf10'] ?? '',
            $salt,
        ];

        return hash('sha512', implode('|', $hashSequence));
    }

    /**
     * Generate SHA512 reverse hash for Easebuzz Return Callback verification.
     * Formula: salt|status|udf10|udf9|udf8|udf7|udf6|udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key
     *
     * @param  array<string, mixed>  $payload
     */
    private function responseHash(array $payload, string $salt): string
    {
        $hashSequence = [
            $salt,
            $payload['status'] ?? '',
            $payload['udf10'] ?? '',
            $payload['udf9'] ?? '',
            $payload['udf8'] ?? '',
            $payload['udf7'] ?? '',
            $payload['udf6'] ?? '',
            $payload['udf5'] ?? '',
            $payload['udf4'] ?? '',
            $payload['udf3'] ?? '',
            $payload['udf2'] ?? '',
            $payload['udf1'] ?? '',
            $payload['email'] ?? '',
            $payload['firstname'] ?? '',
            $payload['productinfo'] ?? '',
            $payload['amount'] ?? '',
            $payload['txnid'] ?? '',
            $payload['key'] ?? '',
        ];

        return hash('sha512', implode('|', $hashSequence));
    }

    /**
     * Extract clean float amount from string or number.
     */
    private function amountFrom(mixed $price): float
    {
        return (float) preg_replace('/[^0-9.]/', '', (string) $price);
    }

    /**
     * Get initiate link URL based on environment.
     */
    private function initiateUrl(): string
    {
        $env = strtolower(trim((string) config('services.easebuzz.environment', 'test')));

        return in_array($env, ['production', 'prod', 'live'], true)
            ? (string) config('services.easebuzz.production_url', 'https://pay.easebuzz.in/payment/initiateLink')
            : (string) config('services.easebuzz.test_url', 'https://testpay.easebuzz.in/payment/initiateLink');
    }

    /**
     * Get payment page URL for token redirection.
     */
    private function payUrl(string $accessKey): string
    {
        $env = strtolower(trim((string) config('services.easebuzz.environment', 'test')));
        $baseUrl = in_array($env, ['production', 'prod', 'live'], true)
            ? 'https://pay.easebuzz.in/pay/'
            : 'https://testpay.easebuzz.in/pay/';

        return $baseUrl.trim($accessKey);
    }

    /**
     * Display mock payment sandbox simulator checkout page.
     */
    public function mockCheckout(Request $request, Purchase $purchase): View
    {
        return view('frontend.payments.mock-checkout', compact('purchase'));
    }

    /**
     * Process mock payment simulation result.
     */
    public function processMockPayment(Request $request, Purchase $purchase): RedirectResponse
    {
        $action = $request->input('action', 'success');

        if ($action === 'success') {
            $mockResponse = [
                'status' => 'success',
                'txnid' => $purchase->transaction_id,
                'easepayid' => 'EBPAY'.now()->format('YmdHis').Str::upper(Str::random(4)),
                'amount' => number_format((float) $purchase->amount, 2, '.', ''),
                'net_amount_debit' => number_format((float) $purchase->amount, 2, '.', ''),
                'payment_source' => 'Easebuzz Sandbox Simulator',
                'mode' => 'UPI',
                'bankcode' => 'SIMULATOR',
                'name_on_card' => $purchase->customer?->name ?? 'Customer',
                'email' => $purchase->customer?->email ?? 'customer@example.com',
                'phone' => $purchase->customer?->mobile ?? '9876543210',
                'productinfo' => $purchase->book_title,
                'error' => 'APPROVED',
                'error_Message' => 'Transaction Successful (Easebuzz Test Simulator)',
            ];

            $purchase->update([
                'status' => 'paid',
                'gateway_response' => $mockResponse,
            ]);

            $customer = $purchase->customer;
            $dbBook = Book::where('slug', $purchase->book_identifier)
                ->orWhere('id', $purchase->book_identifier)
                ->first();

            // Automatically send the E-Book PDF delivery email to customer's registered email
            if ($customer && ! empty($customer->email)) {
                try {
                    Mail::to($customer->email)->send(new EbookDeliveryMail($purchase, $dbBook, $customer));
                } catch (\Throwable $mailException) {
                    Log::error('E-Book Delivery Email Failed (Mock): '.$mailException->getMessage(), [
                        'purchase_id' => $purchase->id,
                        'customer_email' => $customer->email,
                    ]);
                }
            }

            return redirect()->route('books.show', $purchase->book_identifier)->with([
                'payment_success' => '🎉 Payment completed successfully! Your e-book is ready.',
                'auto_download_url' => route('purchases.download', $purchase),
                'purchased_book_title' => $purchase->book_title,
                'customer_email' => $customer?->email ?? '',
            ]);
        }

        $purchase->update([
            'status' => 'failed',
            'gateway_response' => [
                'status' => 'userCancelled',
                'txnid' => $purchase->transaction_id,
                'error_Message' => 'Transaction cancelled by user (Simulation)',
            ],
        ]);

        return redirect()->route('books.show', $purchase->book_identifier)
            ->with('payment_error', 'Your payment was cancelled or failed.');
    }
}
