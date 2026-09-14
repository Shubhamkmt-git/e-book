<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\EbookDeliveryMail;
use App\Models\AppSetting;
use App\Models\Book;
use App\Models\Customer;
use App\Models\Purchase;
use App\Services\CashfreeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(
        private BookController $books,
        private CashfreeService $cashfree
    ) {}

    /**
     * Resolve or register customer from request before checkout.
     */
    private function resolveCustomer(Request $request): ?Customer
    {
        /** @var Customer|null $customer */
        $customer = $request->user('customer');

        if (! $customer) {
            if ($request->filled('email') || $request->filled('mobile')) {
                $validated = $request->validate([
                    'email' => ['nullable', 'string', 'email', 'max:255'],
                    'name' => ['nullable', 'string', 'max:255'],
                    'mobile' => ['nullable', 'string', 'max:20'],
                ]);

                $email = ! empty($validated['email']) ? strtolower(trim($validated['email'])) : null;
                $name = trim($validated['name'] ?? '') ?: 'Customer';
                $mobile = trim($validated['mobile'] ?? '') ?: null;

                if ($email) {
                    $customer = Customer::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $name,
                            'mobile' => $mobile,
                        ]
                    );
                } elseif ($mobile) {
                    $customer = Customer::firstOrCreate(
                        ['mobile' => $mobile],
                        [
                            'name' => $name,
                        ]
                    );
                }

                if ($customer) {
                    if ($mobile && ! $customer->mobile) {
                        $customer->update(['mobile' => $mobile]);
                    }
                    Auth::guard('customer')->login($customer);
                }
            }
        }

        return $customer;
    }

    /**
     * Initiate payment (Cashfree order creation or direct purchase fallback).
     */
    public function initiatePayment(Request $request, string $identifier): JsonResponse|RedirectResponse
    {
        $customer = $this->resolveCustomer($request);

        $dbBook = Book::where('slug', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        if (! $dbBook) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'E-Book not found.'], 404);
            }
            abort(404, 'E-Book not found.');
        }

        if (! $customer) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please provide your email or mobile number to continue.'], 422);
            }

            return redirect()->route('books.show', ['identifier' => $identifier, 'checkout' => 1])
                ->with('payment_error', 'Please enter your details to proceed.');
        }

        $amount = (float) ($dbBook->selling_price ?: $dbBook->price);
        $transactionId = 'ORD'.now()->format('YmdHis').Str::upper(Str::random(6));

        // Create pending purchase record
        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => (string) ($dbBook->slug ?: $dbBook->id),
            'book_title' => (string) $dbBook->title,
            'amount' => $amount,
            'payment_method' => 'cashfree',
            'transaction_id' => $transactionId,
            'status' => 'pending',
        ]);

        $settings = AppSetting::getSettings();

        // 1. If Cashfree credentials are configured
        if ($settings->isCashfreeEnabled() && $this->cashfree->isConfigured()) {
            try {
                $returnUrl = route('payments.cashfree.callback').'?order_id='.$purchase->transaction_id;
                $notifyUrl = route('payments.cashfree.webhook');

                $cfOrder = $this->cashfree->createOrder($purchase, $customer, $dbBook, $returnUrl, $notifyUrl);

                $purchase->update([
                    'cashfree_order_id' => $cfOrder['cf_order_id'] ?? $purchase->transaction_id,
                    'gateway_response' => $cfOrder['raw'] ?? null,
                ]);

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'gateway' => 'cashfree',
                        'environment' => $this->cashfree->getEnvironment(),
                        'order_id' => $purchase->transaction_id,
                        'cf_order_id' => $cfOrder['cf_order_id'] ?? null,
                        'payment_session_id' => $cfOrder['payment_session_id'] ?? null,
                        'return_url' => $returnUrl,
                    ]);
                }

                return redirect()->route('payments.cashfree.callback', ['order_id' => $purchase->transaction_id]);
            } catch (\Throwable $e) {
                Log::error('Cashfree PG Initialization Error: '.$e->getMessage(), [
                    'purchase_id' => $purchase->id,
                ]);

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to initiate Cashfree payment: '.$e->getMessage(),
                    ], 500);
                }

                return redirect()->route('books.show', ['identifier' => $identifier, 'checkout' => 1])
                    ->with('payment_error', 'Payment initialization failed. Please try again.');
            }
        }

        // 2. Direct fallback mode if Cashfree keys not provided or running in mock testing
        $purchase->update([
            'payment_method' => 'direct',
            'status' => 'paid',
        ]);

        $this->finalizePaidPurchase($purchase, $customer, $dbBook);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'gateway' => 'direct',
                'redirect_url' => route('books.show', $purchase->book_identifier),
            ]);
        }

        return redirect()->route('books.show', $purchase->book_identifier)->with([
            'payment_success' => '🎉 Success! Your e-book is ready for download.',
            'auto_download_url' => URL::signedRoute('purchases.download', ['purchase' => $purchase->id], now()->addHours(24)),
            'purchased_book_title' => $purchase->book_title,
            'customer_email' => $customer?->email ?? '',
        ]);
    }

    /**
     * Handle customer return callback after completing Cashfree checkout.
     */
    public function cashfreeCallback(Request $request): RedirectResponse
    {
        $orderId = $request->query('order_id') ?? $request->input('order_id');

        if (empty($orderId)) {
            return redirect()->route('home')->with('payment_error', 'No order ID provided in payment return.');
        }

        $purchase = Purchase::where('transaction_id', $orderId)
            ->orWhere('cashfree_order_id', $orderId)
            ->first();

        if (! $purchase) {
            return redirect()->route('home')->with('payment_error', 'Order transaction not found.');
        }

        // If already verified as paid
        if ($purchase->status === 'paid') {
            session(['recent_purchase_id' => $purchase->id]);

            return redirect()->route('books.show', $purchase->book_identifier)->with([
                'payment_success' => '🎉 Payment confirmed! Your e-book is ready.',
                'auto_download_url' => URL::signedRoute('purchases.download', ['purchase' => $purchase->id], now()->addHours(24)),
                'purchased_book_title' => $purchase->book_title,
            ]);
        }

        $dbBook = Book::where('slug', $purchase->book_identifier)
            ->orWhere('id', $purchase->book_identifier)
            ->first();

        /** @var Customer|null $customer */
        $customer = $purchase->customer;

        // Verify with Cashfree API
        if ($this->cashfree->isConfigured()) {
            $cfOrder = $this->cashfree->fetchOrder($purchase->transaction_id);
            $orderStatus = strtoupper((string) ($cfOrder['order_status'] ?? ''));

            if ($orderStatus === 'PAID') {
                $purchase->update([
                    'status' => 'paid',
                    'gateway_response' => $cfOrder,
                ]);

                $this->finalizePaidPurchase($purchase, $customer, $dbBook);

                session(['recent_purchase_id' => $purchase->id]);

                return redirect()->route('books.show', $purchase->book_identifier)->with([
                    'payment_success' => '🎉 Payment successful! Your e-book is ready for download.',
                    'auto_download_url' => URL::signedRoute('purchases.download', ['purchase' => $purchase->id], now()->addHours(24)),
                    'purchased_book_title' => $purchase->book_title,
                    'customer_email' => $customer?->email ?? '',
                ]);
            }

            if (in_array($orderStatus, ['EXPIRED', 'TERMINATED', 'CANCELLED', 'FAILED'])) {
                $purchase->update([
                    'status' => 'failed',
                    'gateway_response' => $cfOrder,
                ]);

                return redirect()->route('books.show', ['identifier' => $purchase->book_identifier, 'checkout' => 1])
                    ->with('payment_error', 'Payment was not completed (Status: '.$orderStatus.'). Please try again.');
            }
        }

        // In local/test environment or if already confirmed
        if (app()->environment('testing', 'local') && $request->has('mock_success')) {
            $purchase->update(['status' => 'paid']);
            $this->finalizePaidPurchase($purchase, $customer, $dbBook);
            session(['recent_purchase_id' => $purchase->id]);

            return redirect()->route('books.show', $purchase->book_identifier)->with([
                'payment_success' => '🎉 Payment verified! Your e-book is ready.',
                'auto_download_url' => URL::signedRoute('purchases.download', ['purchase' => $purchase->id], now()->addHours(24)),
                'purchased_book_title' => $purchase->book_title,
            ]);
        }

        return redirect()->route('books.show', ['identifier' => $purchase->book_identifier, 'checkout' => 1])
            ->with('payment_error', 'Payment verification is pending or incomplete. Please check your payment status.');
    }

    /**
     * Cashfree Webhook Listener (asynchronous event verification).
     */
    public function cashfreeWebhook(Request $request): JsonResponse
    {
        $rawBody = $request->getContent();
        $signature = $request->header('x-webhook-signature');
        $timestamp = $request->header('x-webhook-timestamp');

        // Cryptographic HMAC-SHA256 signature verification
        if ($this->cashfree->isConfigured()) {
            if (! $this->cashfree->verifyWebhookSignature($rawBody, $signature, $timestamp)) {
                Log::warning('Cashfree Webhook: Invalid Signature', [
                    'signature' => $signature,
                    'timestamp' => $timestamp,
                ]);

                return response()->json(['error' => 'Invalid webhook signature'], 400);
            }
        }

        $data = json_decode($rawBody, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        }

        $type = $data['type'] ?? '';
        $orderData = $data['data']['order'] ?? [];
        $paymentData = $data['data']['payment'] ?? [];

        $orderId = $orderData['order_id'] ?? $data['data']['order_id'] ?? null;

        if ($orderId) {
            $purchase = Purchase::where('transaction_id', $orderId)
                ->orWhere('cashfree_order_id', $orderId)
                ->first();

            if ($purchase && $purchase->status !== 'paid') {
                $paymentStatus = strtoupper((string) ($paymentData['payment_status'] ?? $orderData['order_status'] ?? ''));

                if ($paymentStatus === 'SUCCESS' || $paymentStatus === 'PAID' || $type === 'PAYMENT_SUCCESS_WEBHOOK') {
                    $purchase->update([
                        'status' => 'paid',
                        'cashfree_payment_id' => (string) ($paymentData['cf_payment_id'] ?? ''),
                        'gateway_response' => $data,
                    ]);

                    $dbBook = Book::where('slug', $purchase->book_identifier)
                        ->orWhere('id', $purchase->book_identifier)
                        ->first();

                    $this->finalizePaidPurchase($purchase, $purchase->customer, $dbBook);
                } elseif (in_array($paymentStatus, ['FAILED', 'USER_DROPPED', 'CANCELLED'])) {
                    $purchase->update([
                        'status' => 'failed',
                        'gateway_response' => $data,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'OK']);
    }

    /**
     * Finalize paid purchase: send email & log.
     */
    private function finalizePaidPurchase(Purchase $purchase, ?Customer $customer, ?Book $dbBook): void
    {
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
    }

    /**
     * Download the full purchased e-book (authorized customers, signed URLs, or admins only).
     */
    public function downloadPurchasedEbook(Request $request, Purchase $purchase): Response|RedirectResponse
    {
        if ($purchase->status !== 'paid') {
            abort(403, 'This purchase has not been verified.');
        }

        // Authorization check:
        $isSigned = $request->hasValidSignature();

        $isCustomerOwner = Auth::guard('customer')->check() && (
            Auth::guard('customer')->id() === $purchase->customer_id ||
            (! empty($purchase->customer?->email) && strtolower((string) Auth::guard('customer')->user()->email) === strtolower((string) $purchase->customer?->email))
        );

        $isAdmin = Auth::guard('web')->check();
        $isSessionPurchase = (int) session('recent_purchase_id') === (int) $purchase->id;

        if (! $isSigned && ! $isCustomerOwner && ! $isAdmin && ! $isSessionPurchase) {
            if (! Auth::guard('customer')->check()) {
                return redirect()->route('home')->with([
                    'error' => 'Please sign in with your customer account to download your purchased e-book.',
                    'open_auth_drawer' => true,
                ]);
            }

            abort(403, 'You are not authorized to download this e-book.');
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

        // 3. Otherwise, render printable full edition
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
}
