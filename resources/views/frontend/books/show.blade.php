@extends('frontend.layouts.app')

@section('title', $book['title'] . ' — E-Book Details')

@section('content')

<!-- ==========================================
     BOOK DETAIL PAGE (PERFECT, CLEAN & MINIMAL)
     ========================================== -->
<section class="pt-4 pb-12 sm:pt-6 sm:pb-16 bg-gradient-to-b from-brand-100/70 via-brand-50/80 to-brand-100/50 min-h-[85vh] relative">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-xs text-slate-500 mb-4 sm:mb-5 font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition">Home</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('books.index') }}" class="hover:text-brand-600 transition">E-Books</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('categories.show', $book['category_slug'] ?? 'tech-coding') }}" class="hover:text-brand-600 transition">{{ $book['category'] }}</a>
            <span class="text-slate-300">/</span>
            <span class="text-brand-600 font-semibold truncate max-w-[200px] sm:max-w-none">{{ $book['title'] }}</span>
        </nav>

        <!-- Main Product Presentation (50% Left Sticky Image, 50% Right Content) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 xl:gap-16 items-start">

            <!-- ==========================================
                 LEFT: BOOK COVER PRESENTATION (50% & STICKY)
                 ========================================== -->
            <div class="self-start lg:sticky lg:top-24 z-20 flex flex-col items-center lg:items-start w-full">
                <div class="w-full max-w-lg lg:max-w-none flex flex-col">
                    
                    <!-- 10:7 Realistic Book Cover with Natural Depth & Spine -->
                    <div class="w-full aspect-[10/7] rounded-2xl overflow-hidden relative shadow-[0_25px_60px_-15px_rgba(122,88,169,0.35)] bg-slate-950 ring-1 ring-black/10 group">
                        <img 
                            src="{{ asset($book['image']) }}" 
                            alt="{{ $book['title'] }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                        >

                        <!-- Realistic Book Spine Shading Overlay -->
                        <div class="absolute inset-y-0 left-0 w-3.5 bg-gradient-to-r from-black/40 via-white/10 to-transparent pointer-events-none"></div>
                        
                        <!-- Glossy Light Reflection -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-white/20 pointer-events-none"></div>

                        <!-- Top-Right Format Badge -->
                        <div class="absolute top-3.5 right-3.5 px-3 py-1 rounded-full bg-slate-950/75 backdrop-blur-md text-white text-[11px] font-bold uppercase tracking-wider border border-white/20 shadow-md">
                            PDF &amp; EPUB
                        </div>

                        <!-- Wishlist Heart Button -->
                        <button 
                            type="button" 
                            aria-label="Add to wishlist"
                            data-wishlist-key="{{ $book['slug'] ?? $book['id'] }}"
                            onclick="toggleWishlist({{ json_encode([
                                'id' => $book['id'] ?? '',
                                'slug' => $book['slug'] ?? '',
                                'title' => $book['title'],
                                'author' => $book['author'],
                                'category' => $book['category'] ?? 'E-Book',
                                'price' => $book['price'],
                                'original_price' => $book['original_price'] ?? '',
                                'image' => asset($book['image']),
                                'url' => route('books.show', $book['slug'] ?? $book['id'])
                            ]) }}, event)"
                            class="absolute top-3.5 left-3.5 w-9 h-9 rounded-full bg-slate-950/60 hover:bg-white text-white hover:text-rose-500 backdrop-blur-md border border-white/20 hover:border-white flex items-center justify-center transition-all duration-200 shadow-md cursor-pointer z-10"
                        >
                            <i class="fa-regular fa-heart text-sm"></i>
                        </button>
                    </div>

                    <!-- Read It Now Action Button Below Image (Same UI as Get It Now) -->
                    <div class="w-full mt-4">
                        <a 
                            href="#" 
                            class="w-full h-12 sm:h-13 inline-flex items-center justify-center px-8 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-2xl uppercase tracking-wider shadow-lg shadow-brand-600/30 hover:shadow-brand-600/45 transition-all duration-200 transform hover:-translate-y-0.5 text-center cursor-pointer"
                        >
                            <span>Read It Now</span>
                        </a>
                    </div>

                </div>
            </div>

            <!-- ==========================================
                 RIGHT: BASIC DETAILS & DESCRIPTION (50% CLEAN & UNBOXED)
                 ========================================== -->
            <div class="w-full space-y-6">

                <!-- Book Title & Author -->
                <div class="space-y-2">
                    <h1 class="font-brand text-4xl sm:text-5xl lg:text-6xl text-slate-900 tracking-wide uppercase leading-none">
                        {{ $book['title'] }}
                    </h1>
                    <p class="text-sm sm:text-base font-bold text-slate-700 flex flex-wrap items-center gap-2">
                        <span>By <span class="text-brand-600">{{ $book['author'] }}</span></span>
                        <i class="fa-solid fa-circle-check text-brand-600 text-xs" title="Verified Author"></i>
                        @if(!empty($book['author_role']))
                            <span class="text-xs font-normal text-slate-400">({{ $book['author_role'] }})</span>
                        @endif
                    </p>
                </div>

                <!-- Rating & Reviews Bar -->
                <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-500 font-medium">
                    <div class="flex items-center gap-0.5 text-amber-400">
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                    </div>
                    <span class="font-bold text-slate-900 text-sm">{{ $book['rating'] }}</span>
                    <span class="text-slate-300">•</span>
                    <span>{{ $book['reviews'] }} Reader Reviews</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-emerald-700 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                        Verified E-Book
                    </span>
                </div>

                <!-- Price Block (Clean Inline Display) -->
                <div class="pt-3 pb-2 border-y border-brand-200/70 flex flex-wrap items-baseline gap-4">
                    <span class="font-brand text-4xl sm:text-5xl text-brand-600 font-bold tracking-wider leading-none">
                        {{ $book['price'] }}
                    </span>
                    @if(!empty($book['original_price']))
                        <span class="font-brand text-2xl text-slate-400 line-through tracking-wider">
                            {{ $book['original_price'] }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
                            {{ $book['discount'] ?? '50% OFF' }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-400 font-medium ml-auto">
                        DRM-Free • Lifetime Access
                    </span>
                </div>

                <!-- Action Button (Full Width Clean & Prominent) -->
                <div class="pt-1 w-full">
                    <a 
                        href="#" 
                        class="w-full h-12 sm:h-13 inline-flex items-center justify-center px-8 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-2xl uppercase tracking-wider shadow-lg shadow-brand-600/30 hover:shadow-brand-600/45 transition-all duration-200 transform hover:-translate-y-0.5 text-center cursor-pointer"
                    >
                        <span>Get It Now</span>
                    </a>
                </div>

                <!-- Minimal Specs Row (Inline Clean Tags) -->
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-600 pt-2 font-medium">
                    <div><strong class="text-slate-900">Length:</strong> {{ $book['pages'] ?? 350 }} Pages</div>
                    <span class="text-slate-300 hidden sm:inline">•</span>
                    <div><strong class="text-slate-900">Language:</strong> {{ $book['language'] ?? 'English' }}</div>
                    <span class="text-slate-300 hidden sm:inline">•</span>
                    <div><strong class="text-slate-900">Format:</strong> {{ $book['format'] ?? 'PDF, EPUB' }}</div>
                    <span class="text-slate-300 hidden sm:inline">•</span>
                    <div><strong class="text-slate-900">File Size:</strong> {{ $book['file_size'] ?? '15 MB' }}</div>
                </div>

                <!-- Description (Clean, Readable, Unboxed) -->
                <div class="pt-4 border-t border-brand-200/70 space-y-3">
                    <h2 class="font-brand text-2xl sm:text-3xl text-slate-900 tracking-wide uppercase">Description</h2>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal">
                        {{ $book['description'] }}
                    </p>
                </div>

                <!-- Key Highlights Checklist -->
                @if(!empty($book['highlights']))
                    <div class="pt-4 border-t border-brand-200/70 space-y-2.5">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Key Highlights</h3>
                        <ul class="space-y-2">
                            @foreach ($book['highlights'] as $highlight)
                                <li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-600 font-medium">
                                    <div class="w-4 h-4 rounded-full bg-brand-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="fa-solid fa-check text-[8px]"></i>
                                    </div>
                                    <span>{{ $highlight }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Chapters / Table of Contents Preview -->
                @if(!empty($book['chapters']))
                    <div class="pt-4 border-t border-brand-200/70 space-y-2.5">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Chapters &amp; Contents</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600 font-medium">
                            @foreach ($book['chapters'] as $chapter)
                                <div class="flex items-center gap-2 py-1">
                                    <i class="fa-solid fa-bookmark text-brand-600 text-[10px]"></i>
                                    <span class="truncate">{{ $chapter }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Minimal Guarantees Strip -->
                <div class="pt-5 border-t border-brand-200/70 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-500 font-medium">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-cloud-arrow-down text-brand-600"></i>
                        <span>Instant PDF/EPUB</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-brand-600"></i>
                        <span>Secure Checkout</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-infinity text-brand-600"></i>
                        <span>Lifetime Access</span>
                    </div>
                </div>

                <!-- ==========================================
                     CLEAN & MINIMAL READER REVIEWS
                     ========================================== -->
                <div id="reviews-section" class="pt-6 border-t border-brand-200/80 space-y-4">
                    
                    <!-- Top Summary & Action Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-brand-50/50 rounded-2xl p-4 border border-brand-100">
                        <div class="flex items-center gap-3">
                            <div class="flex items-baseline gap-1.5">
                                <span class="font-brand text-3xl text-slate-900 leading-none">{{ $book['rating'] }}</span>
                                <span class="text-xs text-slate-400 font-medium">/ 5.0</span>
                            </div>
                            <div class="h-6 w-px bg-brand-200/60"></div>
                            <div>
                                <div class="flex items-center gap-1 text-amber-400 text-xs">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <span class="text-[11px] text-slate-500 font-medium">{{ $book['reviews'] }} verified ratings</span>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            onclick="toggleReviewForm()" 
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-brand-600 text-brand-700 hover:text-white border border-brand-200 hover:border-brand-600 font-semibold text-xs transition-all duration-200 shadow-2xs cursor-pointer group"
                        >
                            <i class="fa-solid fa-pen text-[10px] group-hover:scale-110 transition-transform"></i>
                            <span id="review-btn-text">Write a Review</span>
                        </button>
                    </div>

                    <!-- Collapsible Clean Form -->
                    <div id="add-review-form-container" class="hidden bg-white rounded-2xl border border-brand-200 p-4 sm:p-5 shadow-sm space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Write Your Review</span>
                            <button 
                                type="button" 
                                onclick="toggleReviewForm()" 
                                class="w-6 h-6 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-xs cursor-pointer transition"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <form id="new-review-form" onsubmit="submitNewReview(event)" class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-500 font-medium">Your Rating:</span>
                                <input type="hidden" id="selected-rating" value="5">
                                <div class="flex items-center gap-1" id="star-rating-picker">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button 
                                            type="button" 
                                            onclick="setStarRating({{ $i }})" 
                                            data-rating="{{ $i }}"
                                            class="star-pick text-amber-400 hover:scale-125 transition-transform text-base cursor-pointer p-0.5"
                                        >
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                    @endfor
                                </div>
                                <span id="rating-label" class="text-xs font-semibold text-slate-700 ml-1">5.0 / 5 (Excellent)</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Your Name</label>
                                    <input 
                                        type="text" 
                                        id="review-author" 
                                        required 
                                        placeholder="e.g. Alex Morgan" 
                                        class="w-full px-3 py-2 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:outline-none transition font-medium"
                                    >
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Review Title</label>
                                    <input 
                                        type="text" 
                                        id="review-title" 
                                        required 
                                        placeholder="e.g. Practical and insightful" 
                                        class="w-full px-3 py-2 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:outline-none transition font-medium"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Your Feedback</label>
                                <textarea 
                                    id="review-comment" 
                                    rows="3" 
                                    required 
                                    placeholder="What did you like or learn from this book?" 
                                    class="w-full px-3 py-2 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:outline-none transition font-medium resize-none"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-1">
                                <button 
                                    type="button" 
                                    onclick="toggleReviewForm()" 
                                    class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-800 font-medium cursor-pointer"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-5 py-2 rounded-full bg-brand-600 hover:bg-brand-500 text-white font-brand text-base tracking-wider uppercase transition cursor-pointer shadow-sm shadow-brand-600/20"
                                >
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Success Alert Placeholder (Dynamic) -->
                    <div id="review-success-message" class="hidden p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                        <span>Thank you! Your review has been successfully submitted.</span>
                    </div>

                    <!-- Clean & Legible Reviews List -->
                    <div id="reviews-list" class="divide-y divide-brand-100/70">
                        @foreach ($reviews as $rev)
                            <div class="py-4 first:pt-1 last:pb-0 space-y-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($rev['name'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span class="font-bold text-xs sm:text-sm text-slate-900">{{ $rev['name'] }}</span>
                                                @if(!empty($rev['verified']))
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-semibold border border-emerald-200/60">
                                                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                                                        Verified
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-400 font-normal">{{ $rev['date'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-0.5 text-amber-400 text-xs shrink-0">
                                        @for ($s = 1; $s <= 5; $s++)
                                            @if ($s <= ($rev['rating'] ?? 5))
                                                <i class="fa-solid fa-star"></i>
                                            @else
                                                <i class="fa-regular fa-star text-slate-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>

                                <div class="pl-10 space-y-1">
                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug">
                                        {{ $rev['title'] }}
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                        {{ $rev['comment'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>

        </div>

        <!-- ==========================================
             RELATED E-BOOKS (3 CARDS PER ROW)
             ========================================== -->
        <div class="mt-16 sm:mt-20 pt-12 border-t border-brand-200/80">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Recommended For You</span>
                    <h2 class="font-brand text-3xl sm:text-4xl text-slate-900 tracking-wide uppercase mt-1">
                        More E-Books You May Like
                    </h2>
                </div>
                <a href="{{ route('books.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1.5">
                    <span>View all e-books</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- 3 Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($relatedBooks as $related)
                    <div class="group bg-white rounded-3xl border border-brand-200/80 hover:border-brand-400/90 p-5 hover:shadow-[0_20px_45px_-12px_rgba(122,88,169,0.22)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full">
                        <div>
                            <!-- Book Cover (10:7) -->
                            <a href="{{ route('books.show', $related['slug'] ?? $related['id']) }}" class="block aspect-[10/7] rounded-2xl overflow-hidden relative shadow-sm group-hover:shadow-md transition-all duration-300 bg-slate-950 ring-1 ring-black/5">
                                <img 
                                    src="{{ asset($related['image']) }}" 
                                    alt="{{ $related['title'] }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div class="absolute inset-y-0 left-0 w-2.5 bg-gradient-to-r from-black/35 via-white/10 to-transparent pointer-events-none"></div>
                                
                                <button 
                                    type="button" 
                                    aria-label="Add to wishlist"
                                    data-wishlist-key="{{ $related['slug'] ?? $related['id'] }}"
                                    onclick="toggleWishlist({{ json_encode([
                                        'id' => $related['id'] ?? '',
                                        'slug' => $related['slug'] ?? '',
                                        'title' => $related['title'],
                                        'author' => $related['author'],
                                        'category' => $related['category'] ?? 'E-Book',
                                        'price' => $related['price'],
                                        'original_price' => $related['original_price'] ?? '',
                                        'image' => asset($related['image']),
                                        'url' => route('books.show', $related['slug'] ?? $related['id'])
                                    ]) }}, event)"
                                    class="absolute top-3 right-3 w-8.5 h-8.5 rounded-full bg-slate-950/60 hover:bg-white text-white hover:text-rose-500 backdrop-blur-md border border-white/20 hover:border-white flex items-center justify-center transition-all duration-200 shadow-md cursor-pointer z-10"
                                >
                                    <i class="fa-regular fa-heart text-xs"></i>
                                </button>
                            </a>

                            <!-- Metadata -->
                            <div class="mt-4 flex items-end justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block mb-0.5">{{ $related['category'] }}</span>
                                    <a href="{{ route('books.show', $related['slug'] ?? $related['id']) }}" class="font-bold text-[15px] sm:text-base text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1 leading-snug tracking-tight block">
                                        {{ $related['title'] }}
                                    </a>
                                    <p class="text-xs text-slate-400 font-medium line-clamp-1 mt-0.5">{{ $related['author'] }}</p>

                                    <div class="flex items-center gap-1.5 mt-2">
                                        <div class="flex items-center gap-0.5 text-amber-400">
                                            <i class="fa-solid fa-star text-[11px]"></i>
                                            <i class="fa-solid fa-star text-[11px]"></i>
                                            <i class="fa-solid fa-star text-[11px]"></i>
                                            <i class="fa-solid fa-star text-[11px]"></i>
                                            <i class="fa-solid fa-star text-[11px]"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-800 ml-0.5">{{ $related['rating'] }}</span>
                                        <span class="text-xs text-slate-400 font-normal">({{ $related['reviews'] }})</span>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end shrink-0 text-right">
                                    @if (!empty($related['original_price']))
                                        <span class="font-brand text-sm text-slate-400 line-through tracking-wider leading-none">
                                            {{ $related['original_price'] }}
                                        </span>
                                    @endif
                                    <span class="font-brand text-2xl sm:text-3xl text-brand-600 font-bold tracking-wider leading-none mt-0.5">
                                        {{ $related['price'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer: Action Button (Get It) -->
                        <div class="mt-5 pt-3.5 border-t border-slate-100">
                            <a 
                                href="{{ route('books.show', $related['slug'] ?? $related['id']) }}" 
                                class="w-full h-11 inline-flex items-center justify-center px-5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 shadow-md shadow-brand-600/25 text-center transform hover:-translate-y-0.5"
                            >
                                <span>Get It</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

<!-- ==========================================
     NEWSLETTER CTA COMPONENT
     ========================================== -->
@include('frontend.components.cta')

<!-- ==========================================
     REVIEWS INTERACTION SCRIPT
     ========================================== -->
<script>
    function toggleReviewForm() {
        const formContainer = document.getElementById('add-review-form-container');
        const btnText = document.getElementById('review-btn-text');
        
        if (formContainer.classList.contains('hidden')) {
            formContainer.classList.remove('hidden');
            btnText.textContent = 'Close Review Form';
            document.getElementById('review-author').focus();
        } else {
            formContainer.classList.add('hidden');
            btnText.textContent = 'Write a Review';
        }
    }

    const ratingDescriptions = {
        1: '1.0 / 5 (Poor)',
        2: '2.0 / 5 (Fair)',
        3: '3.0 / 5 (Good)',
        4: '4.0 / 5 (Very Good)',
        5: '5.0 / 5 (Excellent)'
    };

    function setStarRating(rating) {
        document.getElementById('selected-rating').value = rating;
        document.getElementById('rating-label').textContent = ratingDescriptions[rating] || `${rating}.0 / 5`;

        const starButtons = document.querySelectorAll('.star-pick');
        starButtons.forEach((btn, index) => {
            const starValue = index + 1;
            const icon = btn.querySelector('i');
            if (starValue <= rating) {
                btn.className = 'star-pick text-amber-400 hover:scale-125 transition-transform text-xl cursor-pointer p-0.5';
                icon.className = 'fa-solid fa-star';
            } else {
                btn.className = 'star-pick text-slate-300 hover:scale-125 transition-transform text-xl cursor-pointer p-0.5';
                icon.className = 'fa-regular fa-star';
            }
        });
    }

    function submitNewReview(e) {
        e.preventDefault();
        
        const name = document.getElementById('review-author').value.trim();
        const rating = parseInt(document.getElementById('selected-rating').value, 10) || 5;
        const title = document.getElementById('review-title').value.trim();
        const comment = document.getElementById('review-comment').value.trim();

        if (!name || !title || !comment) return;

        // Create initials
        const initials = name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) || 'U';

        // Star icons string
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                starsHtml += '<i class="fa-solid fa-star"></i>';
            } else {
                starsHtml += '<i class="fa-regular fa-star text-slate-300"></i>';
            }
        }

        // New clean review item
        const newCard = document.createElement('div');
        newCard.className = 'py-4 first:pt-1 last:pb-0 space-y-2 transition-all animate-[fadeIn_0.5s_ease-out]';
        newCard.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold text-xs flex items-center justify-center shrink-0">
                        ${escapeHtml(initials)}
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="font-bold text-xs sm:text-sm text-slate-900">${escapeHtml(name)}</span>
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-semibold border border-emerald-200/60">
                                <i class="fa-solid fa-circle-check text-[9px]"></i>
                                Just Now
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-normal">Just now</span>
                    </div>
                </div>

                <div class="flex items-center gap-0.5 text-amber-400 text-xs shrink-0">
                    ${starsHtml}
                </div>
            </div>

            <div class="pl-10 space-y-1">
                <h4 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug">
                    ${escapeHtml(title)}
                </h4>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    ${escapeHtml(comment)}
                </p>
            </div>
        `;

        const reviewsList = document.getElementById('reviews-list');
        reviewsList.prepend(newCard);

        // Show success alert
        const successMsg = document.getElementById('review-success-message');
        successMsg.classList.remove('hidden');

        // Reset form & close
        document.getElementById('new-review-form').reset();
        setStarRating(5);
        toggleReviewForm();

        // Smooth scroll to reviews list
        newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function markHelpful(btn, count) {
        const countSpan = btn.querySelector('.helpful-count');
        const icon = btn.querySelector('i');
        
        if (btn.classList.contains('text-brand-600')) {
            btn.classList.remove('text-brand-600');
            icon.className = 'fa-regular fa-thumbs-up';
            countSpan.textContent = count;
        } else {
            btn.classList.add('text-brand-600');
            icon.className = 'fa-solid fa-thumbs-up';
            countSpan.textContent = count + 1;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>

@endsection
