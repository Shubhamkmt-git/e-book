<!-- Minimal & Clean FAQ Section (1 FAQ Per Row) -->
<section id="faq" class="py-16 sm:py-20 bg-white">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Clean Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">FAQ</span>
            <h2 class="font-brand text-4xl sm:text-5xl text-slate-900 tracking-wide uppercase mt-1">Frequently Asked Questions</h2>
            <p class="text-sm text-slate-500 mt-2">Quick answers to common questions about downloads, formats, and devices.</p>
        </div>

        <!-- 1 FAQ Per Row Accordion List -->
        <div class="max-w-3xl mx-auto space-y-3 sm:space-y-3.5">
            @php
                $faqItems = $faqs ?? collect();
                $hasFaqs  = $faqItems instanceof \Illuminate\Support\Collection
                    ? $faqItems->isNotEmpty()
                    : !empty($faqItems);
            @endphp

            @if($hasFaqs)
                @foreach ($faqItems as $faq)
                    @php
                        $q = is_array($faq) ? $faq['question'] : $faq->question;
                        $a = is_array($faq) ? $faq['answer']   : $faq->answer;
                    @endphp
                    <details class="group bg-white rounded-2xl border border-slate-200/80 hover:border-brand-200 p-4.5 sm:p-5 transition-all duration-200 open:border-brand-300/80 open:shadow-xs">
                        <summary class="flex items-center justify-between gap-4 cursor-pointer list-none font-bold text-slate-900 text-[15px] sm:text-base group-open:text-brand-600 transition-colors select-none">
                            <span>{{ $q }}</span>
                            <div class="w-6 h-6 rounded-full bg-slate-100 group-open:bg-brand-50 text-slate-400 group-open:text-brand-600 flex items-center justify-center shrink-0 transition-all duration-200">
                                <i class="fa-solid fa-chevron-down text-[11px] group-open:rotate-180 transition-transform duration-200"></i>
                            </div>
                        </summary>
                        <div class="mt-3 pt-3 border-t border-slate-100 text-slate-600 text-xs sm:text-sm leading-relaxed font-normal">
                            {{ $a }}
                        </div>
                    </details>
                @endforeach
            @else
                {{-- Static fallback --}}
                @foreach ([
                    ['question' => 'What formats do I receive when I purchase an e-book?', 'answer' => 'Every e-book comes with both universal EPUB and high-resolution PDF formats. All files are clean, DRM-free for your personal devices, and formatted for maximum readability.'],
                    ['question' => 'Can I read my books on Kindle, Apple Books, or mobile devices?', 'answer' => 'Yes, absolutely. Our e-books work on all devices including Amazon Kindle, iPad & iPhone (Apple Books), Android tablets & phones, Kobo, and web readers.'],
                    ['question' => 'How does instant download and cloud access work?', 'answer' => 'Your download links are delivered instantly on-screen and to your email address right after checkout. You also receive lifetime access in your personal library to re-download anytime.'],
                    ['question' => 'Is there any monthly subscription or hidden fees?', 'answer' => 'No subscriptions or recurring charges. You only pay for the individual e-books you choose, with lifetime ownership and free future edition updates.'],
                    ['question' => 'What payment methods do you support?', 'answer' => 'We accept UPI (Google Pay, PhonePe, Paytm), Credit & Debit Cards (Visa, Mastercard, RuPay, Amex), and Net Banking with 256-bit encryption.'],
                    ['question' => 'What is your refund policy?', 'answer' => 'We offer a 7-day hassle-free refund guarantee if you experience any technical or formatting issues that our support team cannot resolve.'],
                ] as $faq)
                    <details class="group bg-white rounded-2xl border border-slate-200/80 hover:border-brand-200 p-4.5 sm:p-5 transition-all duration-200 open:border-brand-300/80 open:shadow-xs">
                        <summary class="flex items-center justify-between gap-4 cursor-pointer list-none font-bold text-slate-900 text-[15px] sm:text-base group-open:text-brand-600 transition-colors select-none">
                            <span>{{ $faq['question'] }}</span>
                            <div class="w-6 h-6 rounded-full bg-slate-100 group-open:bg-brand-50 text-slate-400 group-open:text-brand-600 flex items-center justify-center shrink-0 transition-all duration-200">
                                <i class="fa-solid fa-chevron-down text-[11px] group-open:rotate-180 transition-transform duration-200"></i>
                            </div>
                        </summary>
                        <div class="mt-3 pt-3 border-t border-slate-100 text-slate-600 text-xs sm:text-sm leading-relaxed font-normal">
                            {{ $faq['answer'] }}
                        </div>
                    </details>
                @endforeach
            @endif
        </div>

    </div>
</section>
