@php
    $sampleTestimonials = [
        [
            'name' => 'Sophia Martinez',
            'role' => 'Principal Software Architect',
            'rating' => 5,
            'book_read' => 'Algorithms & Elegance',
            'book_slug' => 'algorithms-and-elegance',
            'quote' => 'The digital reading experience is buttery smooth. Being able to read technical diagrams seamlessly across both my tablet and laptop made studying on the go effortless.',
        ],
        [
            'name' => 'Dr. Raghavan Iyer',
            'role' => 'Theoretical Physics Professor',
            'rating' => 5,
            'book_read' => 'Quantum Frontiers',
            'book_slug' => 'the-art-of-quantum-computing',
            'quote' => 'Instant offline access to high-quality academic publications and scientific literature has completely transformed how I review new research and prepare lectures.',
        ],
        [
            'name' => 'Elena Rostova',
            'role' => 'Lead Product Designer',
            'rating' => 5,
            'book_read' => 'Whispers of the Nebula',
            'book_slug' => 'whispers-of-the-nebula',
            'quote' => 'The typography and dark mode reading view are so easy on the eyes during late-night reading sessions. Easily the cleanest digital library platform I have used.',
        ],
        [
            'name' => 'Marcus Bennett Jr.',
            'role' => 'Startup Founder & Operator',
            'rating' => 5,
            'book_read' => 'The Compound Founder',
            'book_slug' => 'the-compound-founder',
            'quote' => 'The actionable mental models and cheat-sheets saved our product team weeks of trial and error. The highest ROI purchase for founders this year.',
        ],
        [
            'name' => 'Alexander Hayes',
            'role' => 'Engineering Director',
            'rating' => 5,
            'book_read' => 'Algorithms & Elegance',
            'book_slug' => 'algorithms-and-elegance',
            'quote' => 'An absolute masterclass in practical execution. The structured mental models and real-world case studies made it effortless to apply immediately.',
        ],
        [
            'name' => 'Priya Sharma',
            'role' => 'Senior Systems Engineer',
            'rating' => 5,
            'book_read' => null,
            'book_slug' => null,
            'quote' => 'Zero fluff, lightning-fast instant PDF & EPUB downloads, and outstanding customer service. E-Book has become my daily reading hub.',
        ],
    ];

    $hasDynamic = isset($testimonials) && (
        ($testimonials instanceof \Illuminate\Support\Collection && $testimonials->isNotEmpty()) ||
        (is_array($testimonials) && count($testimonials) > 0)
    );

    $rawList = $hasDynamic ? $testimonials : $sampleTestimonials;
    
    // Ensure we have at least 4 items for a smooth continuous scrolling track
    $items = collect($rawList);
    if ($items->count() < 4) {
        $items = $items->concat($items);
    }
@endphp

<style>
    @keyframes homeReviewsContinuousMarquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .home-reviews-continuous-track {
        display: flex;
        width: max-content;
        gap: 1.25rem;
        animation: homeReviewsContinuousMarquee 35s linear infinite;
        will-change: transform;
    }
    .home-reviews-continuous-track:hover,
    .home-reviews-continuous-track:active {
        animation-play-state: paused;
    }
    .home-reviews-carousel-viewport {
        overflow: hidden;
        mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
        -webkit-mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
    }
</style>

<!-- Reader Reviews & Feedback Section (Continuous Marquee Carousel) -->
<section id="testimonials" class="py-16 sm:py-20 bg-slate-50 border-t border-slate-200/80">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 space-y-8">

        <!-- Centered Section Header -->
        <div class="text-center space-y-1.5 max-w-2xl mx-auto px-4">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Reader Reviews &amp; Feedback</span>
            <h2 class="font-brand text-3xl sm:text-4xl lg:text-5xl text-slate-900 tracking-wide uppercase">
                What Readers Are Saying
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                Ratings, reviews, and thoughts from our global reading community
            </p>
        </div>

        <!-- Rating & Action Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 py-1">
            <div class="inline-flex flex-wrap items-center justify-center gap-2 sm:gap-3 bg-white sm:bg-transparent px-4 py-2 sm:p-0 rounded-2xl sm:rounded-none border sm:border-0 border-slate-200/80 shadow-2xs sm:shadow-none text-center">
                <div class="flex items-baseline gap-1">
                    <span class="font-brand text-2xl sm:text-3xl lg:text-4xl text-slate-900 leading-none">4.9</span>
                    <span class="text-[11px] sm:text-xs text-slate-400 font-medium">/ 5.0</span>
                </div>
                <div class="h-4 sm:h-5 w-px bg-slate-200"></div>
                <div class="flex items-center gap-1 text-amber-400 text-xs">
                    <i class="fa-solid fa-star text-[11px] sm:text-xs"></i>
                    <i class="fa-solid fa-star text-[11px] sm:text-xs"></i>
                    <i class="fa-solid fa-star text-[11px] sm:text-xs"></i>
                    <i class="fa-solid fa-star text-[11px] sm:text-xs"></i>
                    <i class="fa-solid fa-star text-[11px] sm:text-xs"></i>
                </div>
                <span class="text-[11px] sm:text-xs text-slate-500 font-medium whitespace-nowrap">(18,500+ verified reviews)</span>
            </div>

            <div class="hidden sm:block h-5 w-px bg-slate-200"></div>

            <!-- Controls: Toggle Pause/Play -->
            <button
                type="button"
                onclick="toggleHomeReviewsMarquee()"
                class="px-3.5 py-1.5 rounded-full bg-white hover:bg-brand-50 text-slate-700 hover:text-brand-700 border border-slate-200 hover:border-brand-300 font-semibold text-xs transition cursor-pointer shadow-2xs inline-flex items-center gap-1.5 shrink-0"
                title="Pause or resume auto-scrolling">
                <i id="home-reviews-toggle-icon" class="fa-solid fa-pause text-[10px]"></i>
                <span id="home-reviews-toggle-text">Pause Scroll</span>
            </button>
        </div>

        <!-- Continuous Reviews Carousel Viewport -->
        <div class="relative w-full pt-2">
            <div class="home-reviews-carousel-viewport py-2">
                <div id="home-reviews-continuous-track" class="home-reviews-continuous-track">
                    @for ($loop_i = 0; $loop_i < 2; $loop_i++)
                        @foreach ($items as $item)
                            @php
                                $isModel = $item instanceof \App\Models\Testimonial;
                                $name = $isModel ? $item->name : ($item['name'] ?? 'Verified Reader');
                                $role = $isModel ? ($item->profession ?: 'Verified Reader') : ($item['role'] ?? ($item['profession'] ?? 'Reader'));
                                $rating = $isModel ? $item->rating : ($item['rating'] ?? 5);
                                $quote = $isModel ? $item->message : ($item['quote'] ?? ($item['message'] ?? ''));
                                $bookTitle = $isModel ? $item->book?->title : ($item['book_read'] ?? null);
                                $bookSlug = $isModel && $item->book ? ($item->book->slug ?: $item->book->id) : ($item['book_slug'] ?? null);
                                $avatarInitials = strtoupper(substr($name, 0, 2));
                            @endphp
                            <div class="review-card-item w-[280px] xs:w-[320px] sm:w-[360px] shrink-0 bg-white rounded-2xl border border-slate-200/90 hover:border-brand-400/90 p-4.5 sm:p-6 shadow-2xs hover:shadow-md transition-all duration-300 flex flex-col justify-between text-left group cursor-default">
                                
                                <div class="space-y-3.5">
                                    <!-- Header: Avatar + Name + Role + Star Rating -->
                                    <div class="flex items-center justify-between gap-2.5">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-600 to-brand-400 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                                {{ $avatarInitials }}
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 truncate leading-tight group-hover:text-brand-600 transition-colors">
                                                    {{ $name }}
                                                </h4>
                                                <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                                    {{ $role }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Rating Stars -->
                                        <div class="flex items-center gap-0.5 text-amber-400 text-xs shrink-0">
                                            @for ($s = 1; $s <= $rating; $s++)
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                            @endfor
                                            @for ($s = $rating + 1; $s <= 5; $s++)
                                                <i class="fa-regular fa-star text-[10px] text-slate-300"></i>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Review Comment -->
                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal line-clamp-4">
                                        “{{ $quote }}”
                                    </p>
                                </div>

                                <!-- Card Footer: Book Tag & Verified Badge -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                                    @if ($bookTitle)
                                        @if ($bookSlug)
                                            <a href="{{ route('books.show', $bookSlug) }}"
                                               class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 px-2.5 py-1 rounded-lg truncate max-w-[190px] transition"
                                               title="Feedback for {{ $bookTitle }}">
                                                <i class="fa-solid fa-book-open text-[10px] text-brand-500 shrink-0"></i>
                                                <span class="truncate">{{ $bookTitle }}</span>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg truncate max-w-[190px]" title="{{ $bookTitle }}">
                                                <i class="fa-solid fa-book-open text-[10px] text-brand-500 shrink-0"></i>
                                                <span class="truncate">{{ $bookTitle }}</span>
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                            <i class="fa-solid fa-check-circle text-[10px] text-emerald-500"></i> Verified Reader
                                        </span>
                                    @endif

                                    <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 font-medium shrink-0 ml-auto">
                                        <i class="fa-regular fa-thumbs-up text-[10px]"></i> Helpful
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    const homeReviewsTrack = document.getElementById('home-reviews-continuous-track');
    let isHomeReviewsPaused = false;

    function toggleHomeReviewsMarquee() {
        if (!homeReviewsTrack) return;
        const icon = document.getElementById('home-reviews-toggle-icon');
        const text = document.getElementById('home-reviews-toggle-text');

        isHomeReviewsPaused = !isHomeReviewsPaused;

        if (isHomeReviewsPaused) {
            homeReviewsTrack.style.animationPlayState = 'paused';
            if (icon) icon.className = 'fa-solid fa-play text-[10px]';
            if (text) text.textContent = 'Play Scroll';
        } else {
            homeReviewsTrack.style.animationPlayState = 'running';
            if (icon) icon.className = 'fa-solid fa-pause text-[10px]';
            if (text) text.textContent = 'Pause Scroll';
        }
    }
</script>
