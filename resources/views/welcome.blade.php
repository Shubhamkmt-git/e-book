@extends('frontend.layouts.app')

@section('title', 'Discover & Read Thousands of Digital E-Books')

@section('content')

<!-- ==========================================
     FULL-WIDTH HERO BANNER SECTION (70% HEIGHT)
     ========================================== -->
<section class="relative w-full min-h-[70vh] lg:h-[70vh] flex items-center justify-center overflow-hidden bg-slate-950">
    
    <!-- Full-Width Background Banner Image with Dark Gradient & Vignette Overlay -->
    <div class="absolute inset-0 z-0">
        @if(!empty($heroBanner) && $heroBanner->banner_image)
            <img
                src="{{ $heroBanner->banner_image_url }}"
                alt="{{ $heroBanner->title }}"
                class="w-full h-full object-cover object-center scale-105 transform motion-safe:animate-[pulse_10s_ease-in-out_infinite]"
            >
        @else
            <img
                src="{{ asset('images/hero-banner.jpg') }}"
                alt="E-Book Digital Library Banner"
                class="w-full h-full object-cover object-center scale-105 transform motion-safe:animate-[pulse_10s_ease-in-out_infinite]"
            >
        @endif
        <!-- Multi-layer Gradient Overlays for Readability & Brand Aesthetic -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-950/60"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/60"></div>
        <div class="absolute inset-0 bg-radial from-brand-600/20 via-transparent to-slate-950/80"></div>
    </div>

    <!-- Content Container (96% Width) -->
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 relative z-10 py-16 sm:py-20">
        <div class="max-w-5xl text-center sm:text-left">

            <!-- Main Banner Title -->
            <h1 class="font-brand text-5xl sm:text-7xl lg:text-8xl text-white tracking-wide uppercase leading-[0.95] mb-6">
                @if(!empty($heroBanner) && $heroBanner->title)
                    {{ $heroBanner->title }}
                    @if($heroBanner->title_l2)
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-indigo-300">{{ $heroBanner->title_l2 }}</span>
                    @endif
                @else
                    Discover, Read &amp; Collect Your Favorite <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-indigo-300">E-Books.</span>
                @endif
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-8 max-w-3xl font-normal">
                @if(!empty($heroBanner) && $heroBanner->description)
                    {{ $heroBanner->description }}
                @else
                    Your premier digital library for bestselling novels, academic textbooks, technology guides, and independent literature. Read seamlessly across all your devices anytime, anywhere.
                @endif
            </p>

            <!-- Primary & Secondary Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-4">
                @if(!empty($heroBanner) && $heroBanner->primary_button)
                    <a href="{{ $heroBanner->primary_button_link ?: '#browse' }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase shadow-xl shadow-brand-600/35 transition-all duration-150 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-book-open text-base"></i>
                        <span>{{ $heroBanner->primary_button }}</span>
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

                @if(!empty($heroBanner) && $heroBanner->secondary_button)
                    <a href="{{ $heroBanner->secondary_button_link ?: route('categories.index') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 text-white border border-white/25 backdrop-blur-md font-brand text-xl tracking-wider uppercase shadow-lg transition-all duration-150 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-layer-group text-slate-300"></i>
                        <span>{{ $heroBanner->secondary_button }}</span>
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
                <span>View all 32 categories</span>
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
                        <a href="{{ route('books.show', 'quantum-frontiers-next-century') }}" class="block w-full aspect-[10/7] rounded-2xl overflow-hidden shadow-2xl shadow-brand-900/20 ring-1 ring-black/5 bg-slate-950 transform group-hover:scale-[1.02] group-hover:-translate-y-1 transition-all duration-500 ease-out">
                            <img 
                                src="{{ asset('images/books/spotlight.jpg') }}" 
                                alt="Quantum Frontiers Book Cover" 
                                class="w-full h-full object-cover"
                            >
                            
                            <!-- Subtle Book Spine Gradient Effect -->
                            <div class="absolute inset-y-0 left-0 w-3 bg-gradient-to-r from-black/40 via-white/10 to-transparent pointer-events-none"></div>

                            <!-- Wishlist Heart Button -->
                            <button 
                                type="button" 
                                aria-label="Add to wishlist"
                                data-wishlist-key="quantum-frontiers-next-century"
                                onclick="toggleWishlist({{ json_encode([
                                    'id' => 5,
                                    'slug' => 'quantum-frontiers-next-century',
                                    'title' => 'Quantum Frontiers: The Next Century of Human Discovery',
                                    'author' => 'Dr. Evelyn Vance',
                                    'category' => 'Theoretical Physics',
                                    'price' => '₹699',
                                    'original_price' => '₹1,499',
                                    'image' => asset('images/books/spotlight.jpg'),
                                    'url' => route('books.show', 'quantum-frontiers-next-century')
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
                        <span>Book of the Week</span>
                    </div>
                    
                    <!-- Title -->
                    <a href="{{ route('books.show', 'quantum-frontiers-next-century') }}" class="block">
                        <h2 class="font-brand text-4xl sm:text-5xl lg:text-6xl text-slate-900 hover:text-brand-600 transition-colors tracking-wide uppercase leading-[0.95] mb-2.5">
                            Quantum Frontiers: The Next Century of Human Discovery
                        </h2>
                    </a>

                    <!-- Author & Rating -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5 text-xs text-slate-500 mb-4">
                        <span class="font-bold text-slate-800 text-sm">Dr. Evelyn Vance</span>
                        <span class="text-slate-300">•</span>
                        <div class="flex items-center gap-1 text-amber-400">
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <span class="font-bold text-slate-800 ml-1">5.0</span>
                            <span class="text-slate-400 font-normal">(4.8k reviews)</span>
                        </div>
                        <span class="text-slate-300 hidden sm:inline">•</span>
                        <span class="text-slate-500 font-medium hidden sm:inline">384 Pages</span>
                        <span class="text-slate-300 hidden sm:inline">•</span>
                        <span class="text-slate-500 font-medium hidden sm:inline">EPUB &amp; PDF</span>
                    </div>

                    <!-- Description -->
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mb-6 font-normal">
                        An illuminating journey through modern theoretical physics, unraveling quantum entanglement, wormholes, and multi-dimensional spacetime for specialists and curious minds alike.
                    </p>

                    <!-- Feature Tags -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/80 border border-brand-100 text-slate-700 text-xs font-medium">
                            <i class="fa-solid fa-bolt text-brand-600 text-[11px]"></i> Instant Download
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/80 border border-brand-100 text-slate-700 text-xs font-medium">
                            <i class="fa-solid fa-infinity text-brand-600 text-[11px]"></i> Lifetime Access
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/80 border border-brand-100 text-slate-700 text-xs font-medium">
                            <i class="fa-solid fa-headphones text-brand-600 text-[11px]"></i> Audio Companion Included
                        </span>
                    </div>

                    <!-- Price & Actions Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 pt-5 border-t border-brand-200/70">
                        <!-- Pricing Block -->
                        <div class="flex items-baseline justify-center lg:justify-start gap-2.5">
                            <span class="font-brand text-4xl sm:text-5xl text-brand-700 tracking-wider font-bold">₹699</span>
                            <span class="font-brand text-2xl text-slate-400 line-through tracking-wider">₹1,499</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100/90 text-emerald-800 text-xs font-bold border border-emerald-200 uppercase tracking-wider ml-1">53% OFF</span>
                        </div>

                        <!-- CTA Action Buttons -->
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-center">
                            <a 
                                href="{{ route('books.show', 'quantum-frontiers-next-century') }}" 
                                class="flex-1 sm:flex-none h-12 inline-flex items-center justify-center px-8 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 text-center shadow-lg shadow-brand-600/30 transform hover:-translate-y-0.5"
                            >
                                <span>Buy Now</span>
                            </a>
                            <a 
                                href="#" 
                                class="h-12 inline-flex items-center justify-center gap-2 px-7 rounded-full bg-white hover:bg-brand-50/80 text-brand-900 border border-brand-200 font-brand text-xl tracking-wider uppercase transition-all duration-200 text-center shadow-2xs hover:shadow-xs transform hover:-translate-y-0.5"
                            >
                                <span>Free Sample</span>
                                <i class="fa-solid fa-arrow-down text-xs"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

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
