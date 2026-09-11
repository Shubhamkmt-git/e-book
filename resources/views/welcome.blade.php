@extends('frontend.layouts.app')

@section('title', 'Discover & Read Thousands of Digital E-Books')

@section('content')

@php
    $bannersList = (!empty($heroBanners) && $heroBanners->count() > 0) 
        ? $heroBanners 
        : ( (!empty($heroBanner)) ? collect([$heroBanner]) : collect([]) );
    $hasMultipleBanners = $bannersList->count() > 1;
@endphp

<!-- ==========================================
     FULL-WIDTH HERO BANNER SECTION (MULTI-SLIDE WITH AUTO-SHIFT & COUNT DOTS)
     ========================================== -->
<section id="hero-carousel" class="relative w-full min-h-[80vh] lg:min-h-[70vh] flex items-center justify-center overflow-hidden bg-slate-950 group/carousel" aria-label="Hero Banners Carousel">
    
    @if($bannersList->isEmpty())
        {{-- Default Single Hero Banner Fallback --}}
        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('images/hero-banner.jpg') }}"
                alt="E-Book Digital Library Banner"
                class="w-full h-full object-cover object-center scale-105 transform motion-safe:animate-[pulse_10s_ease-in-out_infinite]"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-950/60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/60"></div>
            <div class="absolute inset-0 bg-radial from-brand-600/20 via-transparent to-slate-950/80"></div>
        </div>
        <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 relative z-10 py-12 sm:py-20">
            <div class="max-w-5xl text-center sm:text-left mt-8 sm:mt-0">
                <h1 class="font-brand text-4xl sm:text-6xl lg:text-7xl xl:text-8xl text-white tracking-wide uppercase leading-tight sm:leading-[0.95] mb-4 sm:mb-6">
                    Discover, Read &amp; Collect Your Favorite <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-indigo-300">E-Books.</span>
                </h1>
                <p class="text-sm sm:text-base lg:text-lg text-slate-300 leading-relaxed mb-6 sm:mb-8 max-w-3xl font-normal">
                    Your premier digital library for bestselling novels, academic textbooks, technology guides, and independent literature. Read seamlessly across all your devices anytime, anywhere.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="#browse" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase shadow-xl shadow-brand-600/35 transition-all duration-150 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-book-open text-base"></i>
                        <span>Explore Library</span>
                        <i class="fa-solid fa-arrow-right text-xs ml-0.5"></i>
                    </a>
                    <a href="{{ route('categories.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 text-white border border-white/25 backdrop-blur-md font-brand text-xl tracking-wider uppercase shadow-lg transition-all duration-150 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-layer-group text-slate-300"></i>
                        <span>Browse Genres</span>
                    </a>
                </div>
            </div>
        </div>
    @else
        {{-- Dynamic Slides --}}
        @foreach($bannersList as $index => $banner)
            <div 
                class="hero-slide absolute inset-0 flex items-center justify-center transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 pointer-events-none z-0' }}" 
                data-slide-index="{{ $index }}"
            >
                <!-- Background Image & Gradients -->
                <div class="absolute inset-0 z-0">
                    <img
                        src="{{ $banner->banner_image ? $banner->banner_image_url : asset('images/hero-banner.jpg') }}"
                        alt="{{ $banner->title }}"
                        class="w-full h-full object-cover object-center scale-105 transform motion-safe:animate-[pulse_10s_ease-in-out_infinite]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-950/60"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/60"></div>
                    <div class="absolute inset-0 bg-radial from-brand-600/20 via-transparent to-slate-950/80"></div>
                </div>

                <!-- Slide Content -->
                <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 relative z-10 py-12 sm:py-20">
                    <div class="max-w-5xl text-center sm:text-left mt-8 sm:mt-0">
                        <h1 class="font-brand text-4xl sm:text-6xl lg:text-7xl xl:text-8xl text-white tracking-wide uppercase leading-tight sm:leading-[0.95] mb-4 sm:mb-6">
                            {{ $banner->title }}
                            @if($banner->title_l2)
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-indigo-300">{{ $banner->title_l2 }}</span>
                            @endif
                        </h1>

                        @if($banner->description)
                            <p class="text-sm sm:text-base lg:text-lg text-slate-300 leading-relaxed mb-6 sm:mb-8 max-w-3xl font-normal">
                                {{ $banner->description }}
                            </p>
                        @endif

                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            @if($banner->primary_button)
                                <a href="{{ $banner->primary_button_link ?: '#browse' }}"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase shadow-xl shadow-brand-600/35 transition-all duration-150 transform hover:-translate-y-0.5">
                                    <i class="fa-solid fa-book-open text-base"></i>
                                    <span>{{ $banner->primary_button }}</span>
                                    <i class="fa-solid fa-arrow-right text-xs ml-0.5"></i>
                                </a>
                            @else
                                <a href="#browse"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase shadow-xl shadow-brand-600/35 transition-all duration-150 transform hover:-translate-y-0.5">
                                    <i class="fa-solid fa-book-open text-base"></i>
                                    <span>Explore Library</span>
                                    <i class="fa-solid fa-arrow-right text-xs ml-0.5"></i>
                                </a>
                            @endif

                            @if($banner->secondary_button)
                                <a href="{{ $banner->secondary_button_link ?: route('categories.index') }}"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 text-white border border-white/25 backdrop-blur-md font-brand text-xl tracking-wider uppercase shadow-lg transition-all duration-150 transform hover:-translate-y-0.5">
                                    <i class="fa-solid fa-layer-group text-slate-300"></i>
                                    <span>{{ $banner->secondary_button }}</span>
                                </a>
                            @else
                                <a href="{{ route('categories.index') }}"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 text-white border border-white/25 backdrop-blur-md font-brand text-xl tracking-wider uppercase shadow-lg transition-all duration-150 transform hover:-translate-y-0.5">
                                    <i class="fa-solid fa-layer-group text-slate-300"></i>
                                    <span>Browse Genres</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($hasMultipleBanners)
            {{-- Minimalist Dots Indicator without count numbers --}}
            <div class="absolute bottom-6 inset-x-0 z-20 flex items-center justify-center pointer-events-none">
                <div class="px-3 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/15 flex items-center gap-2 shadow-lg pointer-events-auto">
                    @foreach($bannersList as $dotIndex => $dotBanner)
                        <button 
                            type="button" 
                            onclick="goToHeroSlide({{ $dotIndex }})" 
                            class="hero-dot group/dot flex items-center transition-all duration-300 cursor-pointer focus:outline-none p-1"
                            aria-label="Go to banner {{ $dotIndex + 1 }}"
                        >
                            <span class="dot-indicator h-2.5 rounded-full transition-all duration-300 {{ $dotIndex === 0 ? 'w-8 bg-brand-400' : 'w-2.5 bg-white/40 group-hover/dot:bg-white/70' }}"></span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</section>

<!-- ==========================================
     EXPLORE GENRES & CATEGORIES
     ========================================== -->
<section id="categories" class="pt-5 pb-12 sm:pt-6 sm:pb-16 bg-slate-50">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 sm:mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Browse by Subject</span>
                <h2 class="font-brand text-4xl sm:text-5xl text-slate-900 tracking-wide uppercase mt-1">Explore Popular Genres</h2>
            </div>
            <a href="{{ route('categories.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1.5">
                <span>View all {{ $totalCategoriesCount ?? 18 }} categories</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Category List Component -->
        @include('frontend.components.category-list')
    </div>
</section>

<!-- ==========================================
     TRENDING & FEATURED E-BOOKS COMPONENT (4 CARDS PER ROW)
     ========================================== -->
@include('frontend.components.trending-books')

<!-- ==========================================
     SPOTLIGHT BOOK OF THE WEEK (MINIMAL & CLEAN)
     ========================================== -->
@if(!empty($spotlightBook))
<section class="py-16 sm:py-20 bg-white">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Minimal Showcase Card (Light Brand Gradient) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-50/80 via-white to-brand-100/50 border border-brand-200/80 text-slate-900 p-6 sm:p-10 lg:p-12 shadow-[0_20px_50px_-20px_rgba(122,88,169,0.15)]">
            
            <!-- Ambient Decorative Glow -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-brand-200/40 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-brand-300/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left: Book Artwork with Realistic Depth & Sheen (10:7) -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative group w-full max-w-sm">
                        <a href="{{ route('books.show', $spotlightBook->slug) }}" class="block w-full aspect-[10/7] rounded-2xl overflow-hidden shadow-2xl shadow-brand-900/20 ring-1 ring-black/5 bg-slate-950 transform group-hover:scale-[1.02] group-hover:-translate-y-1 transition-all duration-500 ease-out">
                            <img 
                                src="{{ $spotlightBook->cover_image ? $spotlightBook->cover_image_url : asset('images/books/spotlight.jpg') }}" 
                                alt="{{ $spotlightBook->title }}" 
                                class="w-full h-full object-cover"
                            >
                            


                            <!-- Wishlist Heart Button -->
                            <button 
                                type="button" 
                                aria-label="Add to wishlist"
                                data-wishlist-key="{{ $spotlightBook->slug }}"
                                onclick="toggleWishlist({{ json_encode([
                                    'id' => $spotlightBook->id,
                                    'slug' => $spotlightBook->slug,
                                    'title' => $spotlightBook->title,
                                    'author' => $spotlightBook->author_name,
                                    'category' => $spotlightBook->category?->title ?? 'E-Book',
                                    'price' => '₹' . number_format((float) $spotlightBook->selling_price, 0),
                                    'original_price' => $spotlightBook->price > $spotlightBook->selling_price ? ('₹' . number_format((float) $spotlightBook->price, 0)) : '',
                                    'image' => $spotlightBook->cover_image ? $spotlightBook->cover_image_url : asset('images/books/spotlight.jpg'),
                                    'url' => route('books.show', $spotlightBook->slug)
                                ]) }}, event)"
                                class="absolute top-3.5 right-3.5 w-9 h-9 rounded-full bg-slate-950/60 hover:bg-white text-white hover:text-rose-500 backdrop-blur-md border border-white/20 hover:border-white flex items-center justify-center transition-all duration-200 shadow-md cursor-pointer z-10"
                            >
                                <i class="fa-regular fa-heart text-sm"></i>
                            </button>
                        </a>
                    </div>
                </div>

                <!-- Right: Metadata & Content -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    
                    <!-- Top Pill Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-brand-100/80 border border-brand-200 text-brand-700 text-xs font-bold uppercase tracking-wider mb-3.5 shadow-2xs">
                        <i class="fa-solid fa-crown text-amber-500 text-xs"></i>
                        <span>{{ $spotlight?->badge_text ?: 'Book of the Week' }}</span>
                    </div>
                    
                    <!-- Title -->
                    <a href="{{ route('books.show', $spotlightBook->slug) }}" class="block">
                        <h2 class="font-brand text-3xl sm:text-5xl lg:text-6xl text-slate-900 hover:text-brand-600 transition-colors tracking-wide uppercase leading-tight sm:leading-[0.95] mb-2.5">
                            {{ $spotlight?->effective_title ?: $spotlightBook->title }}
                        </h2>
                    </a>

                    <!-- Author & Rating -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5 text-xs text-slate-500 mb-4">
                        <span class="font-bold text-slate-800 text-sm">{{ $spotlightBook->author_name }}</span>
                        <span class="text-slate-300">•</span>
                        <div class="flex items-center gap-1 text-amber-400">
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <span class="font-bold text-slate-800 ml-1">5.0</span>
                            <span class="text-slate-400 font-normal">(Verified Rating)</span>
                        </div>
                        @if($spotlightBook->pages)
                            <span class="text-slate-300 hidden sm:inline">•</span>
                            <span class="text-slate-500 font-medium hidden sm:inline">{{ $spotlightBook->pages }} Pages</span>
                        @endif
                        @if($spotlightBook->format)
                            <span class="text-slate-300 hidden sm:inline">•</span>
                            <span class="text-slate-500 font-medium hidden sm:inline">{{ $spotlightBook->format }}</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mb-6 font-normal">
                        {{ $spotlight?->effective_description ?: $spotlightBook->description }}
                    </p>

                    <!-- Price & Actions Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 pt-5 border-t border-brand-200/70">
                        <!-- Pricing Block -->
                        <div class="flex items-baseline justify-center lg:justify-start gap-2.5">
                            <span class="font-brand text-4xl sm:text-5xl text-brand-700 tracking-wider font-bold">₹{{ number_format((float) $spotlightBook->selling_price, 0) }}</span>
                            @if($spotlightBook->price > $spotlightBook->selling_price)
                                <span class="font-brand text-2xl text-slate-400 line-through tracking-wider">₹{{ number_format((float) $spotlightBook->price, 0) }}</span>
                                <span class="whitespace-nowrap px-2.5 py-0.5 rounded-full bg-emerald-100/90 text-emerald-800 text-xs font-bold border border-emerald-200 uppercase tracking-wider ml-1">{{ $spotlightBook->discount_percentage }}% OFF</span>
                            @endif
                        </div>

                        <!-- CTA Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto justify-center lg:justify-start">
                            <button 
                                type="button" 
                                onclick="initiateBookPurchase({{ json_encode([
                                    'id' => $spotlightBook->id,
                                    'slug' => $spotlightBook->slug,
                                    'title' => $spotlightBook->title,
                                    'author' => $spotlightBook->author_name,
                                    'category' => $spotlightBook->category?->title ?? 'E-Book',
                                    'price' => '₹' . number_format((float) $spotlightBook->selling_price, 0),
                                    'original_price' => $spotlightBook->price > $spotlightBook->selling_price ? ('₹' . number_format((float) $spotlightBook->price, 0)) : '',
                                    'image' => $spotlightBook->cover_image ? $spotlightBook->cover_image_url : asset('images/books/spotlight.jpg'),
                                    'url' => route('books.show', $spotlightBook->slug)
                                ]) }}, event)"
                                class="w-full sm:w-auto h-12 inline-flex items-center justify-center px-8 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 text-center shadow-lg shadow-brand-600/30 transform hover:-translate-y-0.5 cursor-pointer shrink-0"
                            >
                                <span>{{ $spotlight?->button_text ?: 'Buy Now' }}</span>
                            </button>
                            @if($spotlightBook->sample_file)
                                <a 
                                    href="{{ route('books.preview', $spotlightBook->slug) }}" 
                                    class="w-full sm:w-auto h-12 inline-flex items-center justify-center gap-2 px-7 rounded-full bg-white hover:bg-brand-50/80 text-brand-900 border border-brand-200 font-brand text-xl tracking-wider uppercase transition-all duration-200 text-center shadow-2xs hover:shadow-xs transform hover:-translate-y-0.5 shrink-0"
                                >
                                    <span>Free Sample</span>
                                    <i class="fa-solid fa-arrow-down text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
@endif

<!-- ==========================================
     TESTIMONIALS & READER REVIEWS COMPONENT
     ========================================== -->
@include('frontend.components.testimonials')

<!-- ==========================================
     FREQUENTLY ASKED QUESTIONS (FAQ) COMPONENT
     ========================================== -->
@include('frontend.components.faq')

<!-- ==========================================
     NEWSLETTER & COMMUNITY CTA COMPONENT
     ========================================== -->
@include('frontend.components.cta')

@endsection

@if($hasMultipleBanners)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const carousel = document.getElementById('hero-carousel');
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        const totalSlides = slides.length;
        if (totalSlides <= 1) return;

        let currentSlide = 0;
        let slideInterval = null;
        const autoShiftDelay = 5000; // 5.0 seconds per slide

        window.goToHeroSlide = function(index) {
            if (index < 0) {
                currentSlide = totalSlides - 1;
            } else if (index >= totalSlides) {
                currentSlide = 0;
            } else {
                currentSlide = index;
            }

            slides.forEach((slide, idx) => {
                if (idx === currentSlide) {
                    slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                }
            });

            dots.forEach((dot, idx) => {
                const indicator = dot.querySelector('.dot-indicator');
                if (idx === currentSlide) {
                    indicator?.classList.remove('w-2.5', 'bg-white/40');
                    indicator?.classList.add('w-8', 'bg-brand-400');
                } else {
                    indicator?.classList.remove('w-8', 'bg-brand-400');
                    indicator?.classList.add('w-2.5', 'bg-white/40');
                }
            });

            resetAutoShift();
        };

        window.shiftHeroSlide = function(step) {
            window.goToHeroSlide(currentSlide + step);
        };

        function startAutoShift() {
            if (slideInterval) clearInterval(slideInterval);
            slideInterval = setInterval(() => {
                window.shiftHeroSlide(1);
            }, autoShiftDelay);
        }

        function resetAutoShift() {
            startAutoShift();
        }

        function pauseAutoShift() {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
        }

        if (carousel) {
            carousel.addEventListener('mouseenter', pauseAutoShift);
            carousel.addEventListener('mouseleave', startAutoShift);

            // Touch Swipe Support
            let touchStartX = 0;
            let touchEndX = 0;

            carousel.addEventListener('touchstart', (e) => {
                pauseAutoShift();
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                startAutoShift();
            }, { passive: true });

            function handleSwipe() {
                const swipeThreshold = 40;
                if (touchEndX < touchStartX - swipeThreshold) {
                    window.shiftHeroSlide(1); // Swipe left -> Next slide
                }
                if (touchEndX > touchStartX + swipeThreshold) {
                    window.shiftHeroSlide(-1); // Swipe right -> Previous slide
                }
            }
        }

        startAutoShift();
    });
</script>
@endpush
@endif

