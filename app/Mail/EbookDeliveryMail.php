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
            subject: 'Your E-Book Delivery: '.$bookTitle.' (Instant PDF & Access)',
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
                'downloadUrl' => route('purchases.download', $this->purchase),
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

        if ($this->book) {
            $pdfPath = null;
            if ($this->book->ebook_file && Storage::disk('public')->exists($this->book->ebook_file)) {
                $pdfPath = Storage::disk('public')->path($this->book->ebook_file);
            } elseif ($this->book->sample_file && Storage::disk('public')->exists($this->book->sample_file)) {
                $pdfPath = Storage::disk('public')->path($this->book->sample_file);
            }

            if ($pdfPath && file_exists($pdfPath)) {
                $fileName = Str::slug($this->book->title).'-ebook.pdf';
                $attachments[] = Attachment::fromPath($pdfPath)
                    ->as($fileName)
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}
