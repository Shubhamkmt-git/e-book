<?php

namespace App\Mail;

use App\Models\Book;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookDeliveryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Purchase $purchase,
        public ?Book $book = null,
        public ?Customer $customer = null
    ) {
        $this->customer = $customer ?? $purchase->customer;
        if (! $this->book && $purchase->book_identifier) {
            $this->book = Book::where('slug', $purchase->book_identifier)
                ->orWhere('id', $purchase->book_identifier)
                ->first();
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $bookTitle = $this->book?->title ?? $this->purchase->book_title;

        return new Envelope(
            subject: 'Your E-Book Delivery: '.$bookTitle.' (Attached PDF)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ebook-delivery',
            with: [
                'purchase' => $this->purchase,
                'book' => $this->book,
                'customer' => $this->customer,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        $bookTitle = $this->book?->title ?? $this->purchase->book_title;
        $cleanPdfName = Str::slug($bookTitle).'-complete-edition.pdf';

        // 1. If physical full PDF exists in public disk
        if ($this->book && ! empty(trim((string) $this->book->ebook_file))) {
            $filePath = Storage::disk('public')->path($this->book->ebook_file);
            if (is_file($filePath)) {
                $attachments[] = Attachment::fromPath($filePath)
                    ->as($cleanPdfName)
                    ->withMime('application/pdf');

                return $attachments;
            }
        }

        // 2. If sample PDF exists in public disk
        if ($this->book && ! empty(trim((string) $this->book->sample_file))) {
            $filePath = Storage::disk('public')->path($this->book->sample_file);
            if (is_file($filePath)) {
                $attachments[] = Attachment::fromPath($filePath)
                    ->as(Str::slug($bookTitle).'-sample-edition.pdf')
                    ->withMime('application/pdf');

                return $attachments;
            }
        }

        // 3. Fallback: attach printable complete HTML document
        $htmlContent = view('frontend.books.ebook-full-download', [
            'book' => [
                'id' => $this->book?->id ?? 1,
                'slug' => $this->book?->slug ?? Str::slug($bookTitle),
                'title' => $bookTitle,
                'author' => $this->book?->author_name ?? 'Author',
                'category' => $this->book?->category?->title ?? 'Publication',
                'pages' => $this->book?->pages ?: 320,
                'format' => $this->book?->format ?: 'EPUB & PDF',
                'description' => $this->book?->description ?? 'Official DRM-Free Digital Publication.',
                'highlights' => $this->book?->highlights_list ?? [],
            ],
            'customer' => $this->customer,
            'purchase' => $this->purchase,
        ])->render();

        $attachments[] = Attachment::fromData(fn () => $htmlContent, Str::slug($bookTitle).'-full-edition.html')
            ->withMime('text/html');

        return $attachments;
    }
}
