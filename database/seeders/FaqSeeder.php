<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What formats do I receive when I purchase an e-book?',
                'answer' => 'Every e-book comes with both universal EPUB and high-resolution PDF formats. All files are clean, DRM-free for your personal devices, and formatted for maximum readability.',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'question' => 'Can I read my books on Kindle, Apple Books, or mobile devices?',
                'answer' => 'Yes, absolutely. Our e-books work on all devices including Amazon Kindle (via "Send to Kindle" or USB transfer), iPad & iPhone (Apple Books), Android tablets & phones, Kobo, and web readers.',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'question' => 'How does instant download and cloud access work?',
                'answer' => 'Your download links are delivered instantly on-screen and to your email address right after checkout. You also receive lifetime access in your personal library to re-download anytime.',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'question' => 'Is there any monthly subscription or hidden fees?',
                'answer' => 'No subscriptions or recurring charges. You only pay for the individual e-books you choose, with lifetime ownership and free future edition updates.',
                'status' => 'active',
                'sort_order' => 4,
            ],
            [
                'question' => 'What payment methods do you support?',
                'answer' => 'We accept all major payment methods including UPI (Google Pay, PhonePe, Paytm), Credit & Debit Cards (Visa, Mastercard, RuPay, Amex), and Net Banking with 256-bit encryption.',
                'status' => 'active',
                'sort_order' => 5,
            ],
            [
                'question' => 'What is your refund policy?',
                'answer' => 'We offer a 7-day hassle-free refund guarantee if you experience any technical or formatting issues that our support team cannot resolve.',
                'status' => 'active',
                'sort_order' => 6,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
