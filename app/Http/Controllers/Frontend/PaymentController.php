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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(private BookController $books) {}

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
                            'password' => Hash::make(Str::random(16)),
                        ]
                    );
                } elseif ($mobile) {
                    $customer = Customer::firstOrCreate(
                        ['mobile' => $mobile],
                        [
                            'name' => $name,
                            'password' => Hash::make(Str::random(16)),
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
     * Direct checkout / purchase for an e-book.
     */
    public function directPurchase(Request $request, string $identifier): RedirectResponse
    {
        $customer = $this->resolveCustomer($request);

        $dbBook = Book::where('slug', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        if (! $dbBook) {
            abort(404, 'E-Book not found.');
        }

        if (! $customer) {
            return redirect()->route('books.show', ['identifier' => $identifier, 'checkout' => 1])
                ->with('payment_error', 'Please enter your details to proceed.');
        }

        $amount = (float) ($dbBook->selling_price ?: $dbBook->price);

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => (string) ($dbBook->slug ?: $dbBook->id),
            'book_title' => (string) $dbBook->title,
            'amount' => $amount,
            'payment_method' => 'direct',
            'transaction_id' => 'ORD'.now()->format('YmdHis').Str::upper(Str::random(4)),
            'status' => 'paid',
        ]);

        $dbBook = Book::where('slug', $purchase->book_identifier)
            ->orWhere('id', $purchase->book_identifier)
            ->first();

        // Send delivery email if email is provided
        if (! empty($customer->email)) {
            try {
                Mail::to($customer->email)->send(new EbookDeliveryMail($purchase, $dbBook, $customer));
            } catch (\Throwable $mailException) {
                Log::error('E-Book Delivery Email Failed: '.$mailException->getMessage(), [
                    'purchase_id' => $purchase->id,
                    'customer_email' => $customer->email,
                ]);
            }
        }

        session(['recent_purchase_id' => $purchase->id]);

        return redirect()->route('books.show', $purchase->book_identifier)->with([
            'payment_success' => '🎉 Success! Your e-book is ready for download.',
            'auto_download_url' => URL::signedRoute('purchases.download', ['purchase' => $purchase->id], now()->addHours(24)),
            'purchased_book_title' => $purchase->book_title,
            'customer_email' => $customer?->email ?? '',
        ]);
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
