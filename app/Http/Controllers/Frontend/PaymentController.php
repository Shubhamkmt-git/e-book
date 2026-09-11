<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\EbookDeliveryMail;
use App\Models\Book;
use App\Models\Customer;
use App\Models\Purchase;
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
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(private BookController $books) {}

    /**
     * Initiate Easebuzz payment gateway checkout for a book.
     */
    public function initiate(Request $request, string $identifier): RedirectResponse
    {
        /** @var Customer|null $customer */
        $customer = $request->user('customer');
        $bookData = $this->books->findBook($identifier);

        if (! $bookData) {
            abort(404, 'E-Book not found.');
        }

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
            } else {
                return redirect()->route('books.show', ['identifier' => $identifier, 'checkout' => 1])
                    ->with('payment_error', 'Please enter your email to proceed directly to payment.');
            }
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
                'transaction_id' => 'EBMOCK'.now()->format('YmdHis').Str::upper(Str::random(4)),
                'status' => 'pending',
            ]);

            return redirect()->route('payments.mock-checkout', $purchase);
        }

        if ($key === '' || $salt === '') {
            return redirect()->route('books.show', $identifier)
                ->with('payment_error', 'Payment gateway is not configured yet. Set EASEBUZZ_ENV=mock in .env for test simulation, or provide Easebuzz merchant credentials.');
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
