@extends('frontend.layouts.app')

@section('title', $book['title'] . ' — E-Book Details')

@push('styles')
<style>
    @keyframes ctaShimmer {
        0% { transform: translateX(-140%) skewX(-20deg); }
        45%, 100% { transform: translateX(250%) skewX(-20deg); }
    }
    @keyframes ctaPulseGlow {
        0%, 100% { 
            box-shadow: 0 4px 16px -2px rgba(122, 88, 169, 0.35);
        }
        50% { 
            box-shadow: 0 8px 28px 4px rgba(122, 88, 169, 0.65);
        }
    }
    .btn-buy-motion {
        position: relative;
        overflow: hidden;
        animation: ctaPulseGlow 1.8s infinite ease-in-out;
    }
    .btn-buy-motion::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            60deg,
            rgba(255, 255, 255, 0) 30%,
            rgba(255, 255, 255, 0.55) 50%,
            rgba(255, 255, 255, 0) 70%
        );
        transform: translateX(-140%) skewX(-20deg);
        animation: ctaShimmer 1.8s infinite cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    .btn-buy-motion:hover::after {
        animation-duration: 1s;
    }
    @keyframes reviewsContinuousMarquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .reviews-continuous-track {
        display: flex;
        width: max-content;
        gap: 1.25rem;
        animation: reviewsContinuousMarquee 30s linear infinite;
        will-change: transform;
    }
    .reviews-continuous-track:hover,
    .reviews-continuous-track:active {
        animation-play-state: paused;
    }
    .reviews-carousel-viewport {
        overflow: hidden;
        mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
        -webkit-mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
    }

    /* Hero Book Cover Frame: Constrained to fit within the viewport screen while strictly preserving 10/7 aspect ratio */
    .book-cover-frame {
        width: min(100%, calc((100vh - 15rem) * 10 / 7));
        max-height: calc(100vh - 15rem);
        aspect-ratio: 10 / 7;
        margin-left: auto;
        margin-right: auto;
    }
    @media (min-width: 640px) {
        .book-cover-frame {
            max-height: min(calc(100vh - 14.5rem), 520px);
            width: min(100%, calc(min(calc(100vh - 14.5rem), 520px) * 10 / 7));
            max-width: 760px;
        }
    }
    @media (max-width: 639px) {
        .book-cover-frame {
            width: 100%;
            max-width: 100%;
            max-height: none;
            aspect-ratio: 10 / 7;
        }
    }

    /* Formatted Rich Text for About The Book */
    .book-description-content {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #334155;
        line-height: 1.85;
    }
    .book-description-content h1, 
    .book-description-content h2, 
    .book-description-content h3, 
    .book-description-content h4 {
        color: #0f172a;
        font-weight: 700;
        letter-spacing: -0.015em;
        margin-top: 1.5rem;
        margin-bottom: 0.6rem;
    }
    .book-description-content h1 { font-size: 1.45rem; }
    .book-description-content h2 { font-size: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.35rem; }
    .book-description-content h3 { font-size: 1.1rem; font-weight: 600; color: #1e293b; }
    .book-description-content h4 { font-size: 1rem; font-weight: 600; }
    .book-description-content p {
        margin-bottom: 1.1rem;
    }
    .book-description-content p:last-child {
        margin-bottom: 0;
    }
    .book-description-content ul, 
    .book-description-content ol {
        margin-top: 0.5rem;
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }
    .book-description-content ul {
        list-style-type: disc;
    }
    .book-description-content ol {
        list-style-type: decimal;
    }
    .book-description-content li {
        margin-bottom: 0.45rem;
        color: #475569;
    }
    .book-description-content strong {
        color: #0f172a;
        font-weight: 600;
    }
    .book-description-content em {
        color: #334155;
        font-style: italic;
    }
    .book-description-content a {
        color: #7a58a9;
        font-weight: 500;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.15s ease;
    }
    .book-description-content a:hover {
        color: #563c78;
    }
    .book-description-content blockquote {
        border-left: 3px solid #7a58a9;
        background-color: #faf5ff;
        border-radius: 0 0.5rem 0.5rem 0;
        padding: 0.75rem 1.25rem;
        color: #4b5563;
        margin: 1.25rem 0;
        font-style: italic;
    }
    .book-description-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.9rem;
    }
    .book-description-content th, 
    .book-description-content td {
        border: 1px solid #e2e8f0;
        padding: 0.6rem 0.85rem;
        text-align: left;
    }
    .book-description-content th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #0f172a;
    }
    .book-description-content code {
        background-color: #f1f5f9;
        color: #0f172a;
        padding: 0.15rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.875em;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
    .book-description-content pre {
        background-color: #0f172a;
        color: #f8fafc;
        padding: 1rem;
        border-radius: 0.75rem;
        overflow-x: auto;
        margin: 1.25rem 0;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')

<section class="pt-3 pb-24 sm:pt-4 sm:pb-24 bg-gradient-to-b from-brand-100/60 via-brand-50/70 to-slate-50 min-h-screen">
    <div class="w-[94%] sm:w-[88%] lg:w-[70%] mx-auto px-2 sm:px-4 space-y-5 sm:space-y-6">

        @if(session('payment_success'))
            <div class="p-3.5 sm:p-4 rounded-2xl bg-white border border-emerald-200/90 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm leading-tight flex items-center gap-2">
                            <span>Payment Completed Successfully</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Ready</span>
                        </h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            PDF dispatched to <strong class="text-slate-700 font-medium">{{ session('customer_email') ?: 'your email' }}</strong>. Auto-download starting...
                        </p>
                    </div>
                </div>
                @if(session('auto_download_url'))
                    <a 
                        href="{{ session('auto_download_url') }}" 
                        download
                        class="shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition"
                    >
                        <i class="fa-solid fa-cloud-arrow-down text-xs"></i>
                        <span>Download PDF</span>
                    </a>

                    <!-- Automatic Download Trigger Script -->
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            setTimeout(() => {
                                const dl = document.createElement('a');
                                dl.href = "{{ session('auto_download_url') }}";
                                dl.setAttribute('download', '');
                                document.body.appendChild(dl);
                                dl.click();
                                document.body.removeChild(dl);
                            }, 500);
                        });
                    </script>
                @endif
            </div>
        @elseif(session('payment_error'))
            <div class="px-5 py-4 rounded-2xl border border-rose-200 bg-rose-50 text-rose-800 text-sm font-medium flex items-center gap-3 shadow-xs" role="alert">
                <div class="w-8 h-8 rounded-full bg-rose-200 text-rose-700 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-exclamation text-sm"></i>
                </div>
                <span>{{ session('payment_error') }}</span>
            </div>
        @endif

        <!-- 1 & 2. Compact Centered Header: Breadcrumb + Title + Author/Rating -->
        <div class="text-center space-y-1.5 sm:space-y-2 w-full mx-auto">
            <!-- Breadcrumb Navigation (Centered, Desktop Only) -->
            <nav aria-label="Breadcrumb" class="hidden sm:flex flex-wrap items-center justify-center gap-1.5 text-xs text-slate-500 font-medium pb-0.5">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition">Home</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('books.index') }}" class="hover:text-brand-600 transition">E-Books</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('categories.show', $book['category_slug'] ?? 'tech-coding') }}" class="hover:text-brand-600 transition">{{ $book['category'] }}</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold truncate max-w-[200px] sm:max-w-none">{{ $book['title'] }}</span>
            </nav>

            <!-- Centered Book Title -->
            <h1 class="font-brand font-bold text-3xl sm:text-4xl lg:text-5xl text-slate-900 uppercase tracking-wide leading-tight">
                {{ $book['title'] }}
            </h1>

            <!-- Centered Author / Star Rating -->
            <div class="flex items-center justify-center gap-2 text-xs sm:text-sm text-slate-600 font-medium flex-wrap">
                <span>By <strong class="text-slate-900 font-bold">{{ $book['author'] }}</strong></span>
                <span class="text-slate-300">•</span>
                <div class="flex items-center gap-1 text-amber-400">
                    <div class="flex items-center gap-0.5">
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                        <i class="fa-solid fa-star text-xs"></i>
                    </div>
                    <span class="font-bold text-slate-900 ml-1">{{ $book['rating'] }}</span>
                    <span class="text-slate-400 font-normal">({{ $book['reviews'] }})</span>
                </div>
            </div>
        </div>

        <!-- 3. Centered Book Cover (Constrained within viewport screen with consistent 10/7 ratio) -->
        <div id="book-hero-section" class="w-full flex flex-col items-center justify-center mx-auto">
            <div class="book-cover-frame rounded-2xl sm:rounded-3xl overflow-hidden relative shadow-[0_20px_50px_-15px_rgba(122,88,169,0.35)] bg-slate-950 ring-1 ring-black/10 group">
                <img 
                    src="{{ asset($book['image']) }}" 
                    alt="{{ $book['title'] }}" 
                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out"
                >

                <!-- Realistic Book Spine Shading -->
                <div class="absolute inset-y-0 left-0 w-3.5 bg-gradient-to-r from-black/45 via-white/10 to-transparent pointer-events-none"></div>
                <!-- Glossy light -->
                <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-white/20 pointer-events-none"></div>

                <!-- Add to Favorites / Wishlist Toggle (Top Right) -->
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
                    class="absolute top-3.5 right-3.5 w-8.5 h-8.5 rounded-full bg-slate-950/60 hover:bg-white text-white hover:text-rose-500 backdrop-blur-md border border-white/20 flex items-center justify-center transition shadow-md cursor-pointer z-10"
                >
                    <i class="fa-regular fa-heart text-xs"></i>
                </button>

                <!-- Bottom Image Overlay Gradient + Buy / Download Button -->
                <div class="absolute inset-x-0 bottom-0 pb-3 sm:pb-5 pt-10 sm:pt-14 bg-gradient-to-t from-slate-950/85 via-slate-950/40 to-transparent flex flex-col items-center justify-end px-4 z-10 pointer-events-auto">
                    @if($hasPurchased ?? false)
                        <a 
                            href="{{ route('purchases.download', $userPurchase->id) }}"
                            class="w-full sm:w-auto min-w-[200px] sm:min-w-[250px] h-11 sm:h-13 inline-flex items-center justify-center gap-2.5 px-6 sm:px-8 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-brand text-base sm:text-xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.04] active:scale-[0.98] shadow-2xl text-center cursor-pointer group/btn"
                        >
                            <i class="fa-solid fa-download text-sm"></i>
                            <span>Download Full E-Book (PDF)</span>
                        </a>
                    @else
                        <a 
                            href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                            class="btn-buy-motion w-full sm:w-auto min-w-[200px] sm:min-w-[250px] h-11 sm:h-13 inline-flex items-center justify-center gap-2.5 px-6 sm:px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-base sm:text-xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.04] active:scale-[0.98] shadow-2xl text-center cursor-pointer group/btn"
                        >
                            <span>Buy Now ({{ $book['price'] }}/-)</span>
                            <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover/btn:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 4. About The Book & Specifications (Centered) -->
        <div class="w-full space-y-6 mx-auto text-center">
            <!-- About The Book -->
            <div class="space-y-4">
                <h2 class="font-brand text-2xl sm:text-3xl text-slate-900 uppercase tracking-wide">
                    About The Book
                </h2>
                <div class="book-description-content text-sm sm:text-base text-slate-700 leading-relaxed font-normal w-full mx-auto text-left max-w-3xl">
                    {!! $book['description'] ?? '' !!}
                </div>
            </div>

            <!-- Book Specifications (Clean & Centered) -->
            <div class="py-3.5 border-y border-slate-200/75 w-full">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 text-center">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold block mb-0.5">Length</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 block truncate">{{ $book['pages'] ?? 412 }} Pages</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold block mb-0.5">Language</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 block truncate">{{ $book['language'] ?? 'English' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold block mb-0.5">Format</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 block truncate">{{ $book['format'] ?? 'PDF, EPUB & MOBI' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold block mb-0.5">File Size</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 block truncate">{{ $book['file_size'] ?? '18.4 MB' }}</span>
                    </div>
                </div>
            </div>

            <!-- Pricing & Action Buttons (Centered) -->
            <div class="space-y-3 pt-2 max-w-md mx-auto w-full">
                @if($hasPurchased ?? false)
                    <!-- Already Owned Banner & Instant Full Download -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 text-center space-y-3 shadow-2xs">
                        <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-bold uppercase tracking-wider shadow-2xs">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>You Own This E-Book</span>
                        </div>
                        <p class="text-xs text-emerald-800 font-medium leading-relaxed">
                            Purchased on {{ $userPurchase?->created_at ? $userPurchase->created_at->format('M d, Y') : 'Recent order' }}. You have lifetime access.
                        </p>

                        <!-- Download Full E-Book Button -->
                        <a 
                            href="{{ route('purchases.download', $userPurchase->id) }}"
                            class="w-full h-13 sm:h-14 inline-flex items-center justify-center gap-2.5 px-8 rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-brand text-2xl sm:text-3xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] text-center cursor-pointer shadow-md group"
                        >
                            <i class="fa-solid fa-download text-lg group-hover:translate-y-0.5 transition-transform"></i>
                            <span>Download Full E-Book (PDF)</span>
                        </a>

                        <div class="flex items-center justify-center gap-4 text-xs font-semibold text-slate-500 pt-1">
                            <a href="{{ route('customer.profile') }}" class="text-emerald-700 hover:text-emerald-800 underline underline-offset-2">
                                <i class="fa-solid fa-book-open mr-1"></i>View in My Library
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Price Display -->
                    <div class="flex items-baseline justify-center gap-3">
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
                    </div>

                    <!-- Buy Now Button with Price & Motion -->
                    <a 
                        href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                        class="btn-buy-motion w-full h-13 sm:h-14 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-2xl sm:text-3xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] text-center cursor-pointer group"
                    >
                        <span>Buy Now ({{ $book['price'] }}/-)</span>
                        <i class="fa-solid fa-arrow-right text-base text-white/80 group-hover:translate-x-1.5 transition-transform duration-300"></i>
                    </a>

                    <!-- Download Sample Button Below Buy -->
                    <a 
                        href="{{ route('books.preview', $book['slug'] ?? $book['id']) }}" 
                        download
                        class="w-full h-11 inline-flex items-center justify-center gap-2 px-6 rounded-full bg-white hover:bg-brand-50 text-brand-700 font-bold text-xs uppercase tracking-wider border border-brand-200 hover:border-brand-400 transition shadow-2xs cursor-pointer"
                    >
                        <i class="fa-solid fa-file-arrow-down text-brand-600"></i>
                        <span>Download Sample (PDF)</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- 5. Key Highlights (Minimal & Clean - Center Aligned) -->
        @if(!empty($book['highlights']))
        <div class="w-full pt-8 pb-3 border-t border-slate-200/75 space-y-5 mx-auto">
            <div class="text-center space-y-1">
                <h2 class="font-brand text-2xl sm:text-3xl text-slate-900 uppercase tracking-wide">
                    Key Highlights
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Core concepts and practical techniques you will master
                </p>
            </div>

            <div class="space-y-2.5 pt-2 w-full mx-auto">
                @foreach ($book['highlights'] as $highlight)
                <div class="flex items-start justify-center gap-3.5 py-2 border-b border-slate-100 last:border-0 text-left">
                    <div class="w-5 h-5 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center shrink-0 mt-0.5 font-bold text-[10px]">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal flex-1">
                        {{ $highlight }}
                    </p>
                </div>
                @endforeach
            </div>

            <!-- Buy / Download CTA after Highlights -->
            <div class="pt-3 flex justify-center">
                @if($hasPurchased ?? false)
                    <a 
                        href="{{ route('purchases.download', $userPurchase->id) }}"
                        class="w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group shadow-md"
                    >
                        <i class="fa-solid fa-download text-sm"></i>
                        <span>Download Full E-Book (PDF)</span>
                    </a>
                @else
                    <a 
                        href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                        class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                    >
                        <span>Buy Now ({{ $book['price'] }}/-)</span>
                        <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- 6. Table of Contents (Clean Minimal Table with Visible Grid Lines) -->
        @if(!empty($book['chapters']))
        <div class="w-full pt-8 pb-3 border-t border-slate-200/80 space-y-5 mx-auto">
            <div class="text-center space-y-1">
                <h2 class="font-brand text-2xl sm:text-3xl text-slate-900 uppercase tracking-wide">
                    Table of Contents
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Comprehensive breakdown of all modules and topics
                </p>
            </div>

            <div class="overflow-x-auto pt-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-bold tracking-wider text-slate-500 uppercase">
                            <th class="pb-2.5 pr-3 w-12 text-center">#</th>
                            <th class="pb-2.5 px-3">Title</th>
                            <th class="pb-2.5 pl-3 text-right w-24">Pages</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($book['chapters'] as $ch)
                        @php
                        $isArr = is_array($ch);
                        $num = $isArr ? ($ch['number'] ?? $loop->iteration) : $loop->iteration;
                        $title = $isArr ? ($ch['title'] ?? '') : $ch;
                        $rawPages = $isArr ? ($ch['pages'] ?? null) : null;
                        $pages = $rawPages ? preg_replace('/^pp\.\s*/i', '', $rawPages) : null;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 pr-3 font-bold text-xs text-brand-600 tracking-wider align-middle text-center">
                                {{ str_pad($num, 2, '0', STR_PAD_LEFT) }}.
                            </td>
                            <td class="py-3 px-3 font-medium text-xs sm:text-sm text-slate-800 align-middle">
                                {{ $title }}
                            </td>
                            <td class="py-3 pl-3 text-right text-xs text-slate-500 font-medium whitespace-nowrap align-middle">
                                {{ $pages ?? '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Buy / Download CTA after Table of Contents -->
            <div class="pt-3 flex justify-center">
                @if($hasPurchased ?? false)
                    <a 
                        href="{{ route('purchases.download', $userPurchase->id) }}"
                        class="w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group shadow-md"
                    >
                        <i class="fa-solid fa-download text-sm"></i>
                        <span>Download Full E-Book (PDF)</span>
                    </a>
                @else
                    <a 
                        href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                        class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                    >
                        <span>Buy Now ({{ $book['price'] }}/-)</span>
                        <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                @endif
            </div>
        </div>
        @endif


        <!-- Reference Images Section (Visual Previews & Inside Pages) -->
        @if(!empty($book['gallery_image_urls']))
        <div class="w-full pt-8 pb-3 border-t border-slate-200/80 space-y-6 mx-auto">
            <!-- Centered Header -->
            <div class="text-center space-y-1">
                <h2 class="font-brand font-bold text-2xl sm:text-3xl text-slate-900 uppercase tracking-wide">
                    Reference Images
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Visual previews, diagrams, and sample pages from this book
                </p>
            </div>

            <!-- Reference Images Grid (Equal size to Suggested For Cards: 4 columns on desktop/tablet, 2 on mobile) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4 pt-1">
                @foreach ($book['gallery_image_urls'] as $idx => $url)
                    <div 
                        class="group relative overflow-hidden bg-slate-900 rounded-2xl border border-slate-200/80 hover:border-brand-500 hover:shadow-[0_16px_36px_-8px_rgba(122,88,169,0.30)] transition-all duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] aspect-[4/3] cursor-pointer flex items-center justify-center"
                        onclick="openLightbox({{ $idx }})"
                        title="Click to preview image {{ $idx + 1 }}"
                    >
                        <img 
                            src="{{ $url }}" 
                            alt="{{ $book['title'] }} Reference Image {{ $idx + 1 }}" 
                            class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                            loading="lazy"
                        >
                        <!-- Subtle hover darken overlay -->
                        <div class="absolute inset-0 bg-slate-950/0 group-hover:bg-slate-950/25 transition-colors duration-300 flex items-center justify-center">
                            <div class="w-10 h-10 rounded-full bg-white/95 text-brand-600 flex items-center justify-center opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 shadow-lg">
                                <i class="fa-solid fa-magnifying-glass-plus text-sm"></i>
                            </div>
                        </div>

                        <!-- Preview Badge on Hover -->
                        <div class="absolute bottom-3 left-3 px-2 py-0.5 rounded-md bg-slate-950/75 backdrop-blur-sm text-white text-[10px] font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-1 shadow-sm pointer-events-none">
                            <i class="fa-solid fa-expand text-[9px]"></i>
                            <span>Preview</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 7. "Suggested For" Section (Who This Book Is Relevant To - 8 Mini-Cards) -->
        @php
            $profiles = $suggestedFor ?? [
                ['title' => 'Software Engineers', 'icon' => 'fa-solid fa-code'],
                ['title' => 'System Architects', 'icon' => 'fa-solid fa-server'],
                ['title' => 'CS Students', 'icon' => 'fa-solid fa-graduation-cap'],
                ['title' => 'Backend Developers', 'icon' => 'fa-solid fa-database'],
                ['title' => 'Tech Leads & CTOs', 'icon' => 'fa-solid fa-laptop-code'],
                ['title' => 'Self-Taught Devs', 'icon' => 'fa-solid fa-terminal'],
                ['title' => 'Performance Leads', 'icon' => 'fa-solid fa-gauge-high'],
                ['title' => 'Problem Solvers', 'icon' => 'fa-solid fa-puzzle-piece'],
            ];
        @endphp
        <div class="w-full pt-8 pb-3 border-t border-slate-200/80 space-y-6 mx-auto">
            <!-- Centered Header -->
            <div class="text-center space-y-1">
                <h2 class="font-brand text-2xl sm:text-3xl text-slate-900 uppercase tracking-wide">
                    Suggested For
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Who will get the most value and practical mastery from this book
                </p>
            </div>

            <!-- 8 Mini-Cards Grid (4 Per Row on Desktop/Tablet, 2 on Mobile - Category Card Style with Icon & Title Only) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4 pt-1">
                @foreach ($profiles as $item)
                    <div 
                        class="group relative overflow-hidden bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 hover:border-transparent hover:shadow-[0_16px_36px_-8px_rgba(122,88,169,0.30)] transition-all duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] flex flex-col items-center text-center cursor-default"
                    >
                        <!-- Smooth Modern Gradient Background Overlay on Hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-600 to-brand-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] pointer-events-none"></div>

                        <!-- Ambient Glow Element on Hover -->
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] pointer-events-none"></div>

                        <!-- Category-Style Icon Container -->
                        <div class="relative z-10 w-12 h-12 rounded-2xl bg-brand-50/80 text-brand-600 border border-brand-100/70 group-hover:bg-white/20 group-hover:text-white group-hover:border-white/25 group-hover:backdrop-blur-md flex items-center justify-center text-xl mb-3 transition-all duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] shadow-2xs">
                            <i class="{{ $item['icon'] }} transition-colors duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)]"></i>
                        </div>
                        
                        <!-- Target Title (Clean unbold) -->
                        <h3 class="relative z-10 font-normal text-xs sm:text-sm text-slate-900 group-hover:text-white transition-colors duration-500 line-clamp-2 leading-snug">
                            {{ $item['title'] }}
                        </h3>
                    </div>
                @endforeach
            </div>

            <!-- Buy / Download CTA after Suggested Section -->
            <div class="pt-4 flex justify-center">
                @if($hasPurchased ?? false)
                    <a 
                        href="{{ route('purchases.download', $userPurchase->id) }}"
                        class="w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group shadow-md"
                    >
                        <i class="fa-solid fa-download text-sm"></i>
                        <span>Download Full E-Book (PDF)</span>
                    </a>
                @else
                    <a 
                        href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                        class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                    >
                        <span>Buy Now ({{ $book['price'] }}/-)</span>
                        <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- 8. Reader Reviews & Feedback (Center-Aligned Carousel) -->
        <div id="reviews-section" class="w-full pt-8 pb-3 border-t border-slate-200/80 space-y-6 mx-auto">
            <!-- Centered Header -->
            <div class="text-center space-y-1">
                <h2 class="font-brand text-2xl sm:text-3xl text-slate-900 uppercase tracking-wide">
                    Reader Reviews &amp; Feedback
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Ratings and thoughts from our readers
                </p>
            </div>

            <!-- Centered Rating & Action Bar -->
            <div class="flex items-center justify-center flex-wrap gap-4 py-1">
                <div class="flex items-center gap-3">
                    <div class="flex items-baseline gap-1">
                        <span id="reviews-avg-rating" class="font-brand text-3xl sm:text-4xl text-slate-900 leading-none">{{ $avgRating ?? ($book['rating'] ?? '5.0') }}</span>
                        <span class="text-xs text-slate-400">/ 5.0</span>
                    </div>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <div class="flex items-center gap-1 text-amber-400 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">(<span id="reviews-count-display">{{ $reviewCount ?? count($reviews) }}</span> reviews)</span>
                </div>

                <div class="hidden sm:block h-5 w-px bg-slate-200"></div>

                <button
                    type="button"
                    onclick="toggleReviewForm()"
                    class="px-4 py-2 rounded-full bg-white hover:bg-brand-50 text-slate-700 hover:text-brand-700 border border-slate-200 hover:border-brand-300 font-bold text-xs uppercase tracking-wider transition cursor-pointer shadow-2xs inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-pen text-[10px]"></i>
                    <span id="review-btn-text">Write a Review</span>
                </button>
            </div>

            <!-- Collapsible Clean Form -->
            <div id="add-review-form-container" class="hidden bg-white rounded-2xl border border-slate-200 p-5 space-y-3 shadow-sm max-w-2xl mx-auto">
                <form id="new-review-form" action="{{ route('books.reviews.store', $book['slug'] ?? $book['id']) }}" method="POST" onsubmit="submitNewReview(event)" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <input
                            type="text"
                            name="name"
                            id="review-author"
                            required
                            placeholder="Your Name *"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:outline-none focus:border-brand-500 font-medium">
                        <input
                            type="text"
                            name="profession"
                            id="review-title"
                            placeholder="Profession / Title (optional)"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:outline-none focus:border-brand-500 font-medium">
                        <select
                            name="rating"
                            id="review-rating"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 text-slate-800 text-xs border border-slate-200 focus:outline-none focus:border-brand-500 font-medium">
                            <option value="5" selected>★★★★★ 5 Stars</option>
                            <option value="4">★★★★☆ 4 Stars</option>
                            <option value="3">★★★☆☆ 3 Stars</option>
                            <option value="2">★★☆☆☆ 2 Stars</option>
                            <option value="1">★☆☆☆☆ 1 Star</option>
                        </select>
                    </div>
                    <textarea
                        name="message"
                        id="review-comment"
                        rows="2"
                        required
                        placeholder="What did you learn from this book? *"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:outline-none focus:border-brand-500 font-medium resize-none"></textarea>
                    
                    <div id="review-form-status" class="hidden text-xs font-semibold py-1"></div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleReviewForm()" class="px-3.5 py-1 text-xs text-slate-500 cursor-pointer hover:text-slate-700">Cancel</button>
                        <button type="submit" id="review-submit-btn" class="px-4 py-2 rounded-full bg-brand-600 text-white font-brand text-base uppercase tracking-wider cursor-pointer shadow-2xs hover:bg-brand-500 transition">Submit Review</button>
                    </div>
                </form>
            </div>

            <!-- Continuous Reviews Carousel Viewport -->
            <div class="relative w-full pt-1">
                <div class="reviews-carousel-viewport py-2">
                    <div id="reviews-continuous-track" class="reviews-continuous-track">
                        @for ($loop_i = 0; $loop_i < 2; $loop_i++)
                            @foreach ($reviews as $rev)
                            <div 
                                class="review-card-item w-[290px] sm:w-[350px] shrink-0 bg-white rounded-2xl border border-slate-200/90 hover:border-brand-400/90 p-5 sm:p-6 shadow-2xs transition-colors duration-300 flex flex-col justify-between text-left group cursor-default"
                            >
                                <div class="space-y-3">
                                    <!-- Header: Avatar + Name + Verified Badge + Date -->
                                    <div class="flex items-center justify-between gap-2.5">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-600 to-brand-400 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                                {{ $rev['avatar'] ?? strtoupper(substr($rev['name'] ?? 'U', 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 truncate leading-tight">{{ $rev['name'] }}</h4>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="text-[10px] text-slate-400">{{ $rev['date'] ?? 'Recent' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 5 Gold Stars -->
                                        <div class="flex items-center gap-0.5 text-amber-400 text-xs shrink-0">
                                            @for ($s = 1; $s <= ($rev['rating'] ?? 5); $s++)
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Review Title & Comment -->
                                    <div class="space-y-1 text-left">
                                        <h5 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug line-clamp-1 group-hover:text-brand-600 transition-colors">{{ $rev['title'] }}</h5>
                                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal line-clamp-4">{{ $rev['comment'] }}</p>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
                                    <span class="inline-flex items-center gap-1 text-[11px] text-slate-500">
                                        <i class="fa-regular fa-thumbs-up text-[10px] text-slate-400"></i> Helpful ({{ $rev['helpful'] ?? 14 }})
                                    </span>

                                </div>
                            </div>
                            @endforeach
                        @endfor
                    </div>
                </div>


            </div>

            <!-- Buy Now / Download Full E-Book CTA after Reviews -->
            <div class="pt-6 flex justify-center">
                @if($hasPurchased ?? false)
                    <a 
                        href="{{ route('purchases.download', $userPurchase->id) }}"
                        class="w-full sm:w-auto min-w-[280px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group shadow-md"
                    >
                        <i class="fa-solid fa-download text-sm"></i>
                        <span>Download Full E-Book (PDF)</span>
                    </a>
                @else
                    <a 
                        href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                        class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                    >
                        <span>Buy Now ({{ $book['price'] }}/-)</span>
                        <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                @endif
            </div>
        </div>

    </div>
</section>

<!-- ==========================================
     INTERACTIVE "LOOK INSIDE" MODAL
     ========================================== -->
<div
    id="sample-reader-modal"
    class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-6"
    onclick="handleModalBackdropClick(event)">
    <div
        class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden animate-[fadeIn_0.3s_ease-out]"
        onclick="event.stopPropagation()">
        <!-- Modal Top Bar -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-7 rounded-md bg-slate-900 overflow-hidden shrink-0 border border-slate-200">
                    <img src="{{ asset($book['image']) }}" alt="{{ $book['title'] }}" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-xs sm:text-sm text-slate-900 truncate">{{ $book['title'] }}</h3>
                        <span class="px-2 py-0.5 rounded-full bg-brand-100 text-brand-700 text-[10px] font-bold uppercase shrink-0">Sample Preview</span>
                    </div>
                    <p class="text-[11px] text-slate-500 truncate">By {{ $book['author'] }} &bull; {{ $book['sample_pages'] ?? 25 }} Pages Excerpt</p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a
                    href="{{ route('books.preview', $book['slug'] ?? $book['id']) }}"
                    download
                    class="px-3.5 py-1.5 rounded-full bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs flex items-center gap-1.5 transition shadow-2xs">
                    <i class="fa-solid fa-file-arrow-down text-xs"></i>
                    <span>Download PDF</span>
                </a>

                <button
                    type="button"
                    onclick="closeSampleReaderModal()"
                    class="w-8 h-8 rounded-full hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition cursor-pointer"
                    aria-label="Close modal">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Modal Scrollable Reader Body -->
        <div class="p-6 sm:p-8 overflow-y-auto space-y-6 font-serif text-slate-800 leading-relaxed max-h-[calc(90vh-140px)] selection:bg-brand-200 selection:text-brand-900">
            <div class="text-center space-y-2 border-b border-slate-100 pb-6">
                <span class="font-sans text-[11px] font-bold text-brand-600 uppercase tracking-widest">Free Sample Preview</span>
                <h2 class="font-sans font-bold text-2xl sm:text-3xl text-slate-900 uppercase tracking-tight">
                    {{ $book['title'] }}
                </h2>
                <p class="font-sans text-xs text-slate-500">
                    Written by {{ $book['author'] }} &bull; Published by E-Book Press ({{ $book['release_date'] ?? '2026' }})
                </p>
            </div>

            <!-- Table of Contents in Preview -->
            <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200 font-sans space-y-2">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700">Included in Full Edition ({{ $book['pages'] ?? 350 }} Pages):</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-xs text-slate-600">
                    @foreach($book['chapters'] as $ch)
                    @php
                    $isArr = is_array($ch);
                    $name = $isArr ? ($ch['number'] ?? '') . '. ' . ($ch['title'] ?? '') : $ch;
                    @endphp
                    <div class="truncate {{ ($isArr && !empty($ch['is_sample'])) ? 'text-brand-700 font-bold' : '' }}">
                        &bull; {{ $name }} {{ ($isArr && !empty($ch['is_sample'])) ? '(★ In this sample)' : '' }}
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Sample Chapter 1 Text -->
            @if(!empty($book['sample_content']))
            <div class="space-y-4 pt-2">
                <div class="font-sans text-xs font-bold text-brand-600 uppercase tracking-wider">
                    {{ $book['sample_content']['chapter_title'] }}
                </div>

                <p class="text-base sm:text-lg font-medium text-slate-900 leading-relaxed">
                    {{ $book['sample_content']['intro'] }}
                </p>

                @if(!empty($book['sample_content']['sections']))
                @foreach($book['sample_content']['sections'] as $s)
                <h3 class="font-sans font-bold text-base sm:text-lg text-slate-900 pt-2">
                    {{ $s['heading'] }}
                </h3>
                <p class="text-sm sm:text-base text-slate-700 leading-relaxed font-normal">
                    {{ $s['content'] }}
                </p>
                @endforeach
                @endif
            </div>
            @endif

            <!-- Modal Bottom Call to Action -->
            <div class="mt-8 p-6 rounded-2xl bg-gradient-to-br from-brand-900 via-brand-800 to-slate-900 text-white text-center font-sans space-y-3">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-400 text-slate-950 font-bold text-[10px] uppercase tracking-wider inline-block">
                    End of Sample Preview
                </span>
                <h3 class="font-brand text-3xl uppercase tracking-wide">
                    Unlock All {{ $book['pages'] ?? 350 }} Pages
                </h3>
                <p class="text-xs text-slate-300 max-w-md mx-auto">
                    Get the complete book in DRM-free PDF, EPUB and MOBI formats, plus all bonus materials.
                </p>
                <div class="pt-2 flex items-center justify-center gap-3">
                    @if($hasPurchased ?? false)
                        <a
                            href="{{ route('purchases.download', $userPurchase->id) }}"
                            class="px-6 py-2.5 rounded-full bg-emerald-500 hover:bg-emerald-400 text-white font-brand text-xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] shadow-md inline-flex items-center gap-2 group">
                            <i class="fa-solid fa-download text-sm"></i>
                            <span>Download Full E-Book (PDF)</span>
                        </a>
                    @else
                        <a
                            href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                            onclick="closeSampleReaderModal()"
                            class="btn-buy-motion px-6 py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-brand text-xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] shadow-md inline-flex items-center gap-2 group">
                            <span>Buy Now ({{ $book['price'] }}/-)</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     IMAGE GALLERY LIGHTBOX MODAL WITH NEXT & PREVIOUS
     ========================================== -->
<div 
    id="gallery-lightbox-modal" 
    class="fixed inset-0 z-[60] bg-slate-950/95 backdrop-blur-md hidden flex items-center justify-center p-2 sm:p-4 opacity-0 transition-opacity duration-300 select-none"
    onclick="closeLightbox(event)"
>
    <!-- Counter Badge (Top Left) -->
    <div class="absolute top-4 left-4 sm:top-6 sm:left-6 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/15 text-white text-xs font-semibold tracking-wider backdrop-blur-md border border-white/15 shadow-lg flex items-center gap-2 pointer-events-none">
        <i class="fa-regular fa-image text-brand-300 text-xs"></i>
        <span id="lightbox-counter">1 / 1</span>
    </div>

    <!-- Close Button (Top Right) -->
    <button 
        type="button" 
        onclick="closeLightbox(event)" 
        class="absolute top-4 right-4 sm:top-6 sm:right-6 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/10 hover:bg-white/20 active:scale-95 text-white flex items-center justify-center transition backdrop-blur-md z-30 cursor-pointer border border-white/15 shadow-xl"
        title="Close (Esc)"
        aria-label="Close Lightbox"
    >
        <i class="fa-solid fa-xmark text-lg sm:text-xl"></i>
    </button>

    <!-- Previous Button (Left) -->
    <button 
        type="button" 
        id="lightbox-prev-btn"
        onclick="prevLightbox(event)" 
        class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/15 hover:bg-white/30 active:scale-90 text-white flex items-center justify-center transition-all backdrop-blur-md z-30 cursor-pointer border border-white/20 shadow-2xl hover:shadow-brand-500/20"
        title="Previous Image (Left Arrow)"
        aria-label="Previous Image"
    >
        <i class="fa-solid fa-chevron-left text-base sm:text-lg"></i>
    </button>

    <!-- Next Button (Right) -->
    <button 
        type="button" 
        id="lightbox-next-btn"
        onclick="nextLightbox(event)" 
        class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/15 hover:bg-white/30 active:scale-90 text-white flex items-center justify-center transition-all backdrop-blur-md z-30 cursor-pointer border border-white/20 shadow-2xl hover:shadow-brand-500/20"
        title="Next Image (Right Arrow)"
        aria-label="Next Image"
    >
        <i class="fa-solid fa-chevron-right text-base sm:text-lg"></i>
    </button>

    <!-- Center Image Container -->
    <div class="relative w-full max-w-5xl max-h-[85vh] flex items-center justify-center pointer-events-none px-12 sm:px-16" onclick="event.stopPropagation()">
        <img 
            id="lightbox-image" 
            src="" 
            alt="Reference Image Preview" 
            class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl pointer-events-auto transform scale-95 transition-all duration-300"
        >
    </div>
</div>

<!-- ==========================================
     STICKY BOTTOM DRAWER / FLOATING PURCHASE BAR (ALWAYS OPEN)
     ========================================== -->
<div 
    id="sticky-bottom-drawer" 
    class="fixed bottom-0 inset-x-0 z-40 bg-white/70 backdrop-blur-xl border-t border-slate-200/60 shadow-[0_-8px_30px_rgba(0,0,0,0.08)] py-3 px-4 sm:px-8"
>
    <div class="w-[94%] sm:w-[88%] lg:w-[70%] mx-auto flex items-center justify-between gap-4">
        <!-- Book Info (Left - Hidden on mobile, visible on tablet/desktop) -->
        <div class="hidden sm:flex items-center gap-3.5 min-w-0">
            <div class="w-14 h-10 rounded-lg overflow-hidden bg-slate-950 shrink-0 border border-slate-200 shadow-2xs aspect-[10/7]">
                <img src="{{ asset($book['image']) }}" alt="{{ $book['title'] }}" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <h3 class="font-normal text-xs sm:text-sm text-slate-900 truncate leading-tight">
                    {{ $book['title'] }}
                </h3>
                <p class="text-[11px] text-slate-500 truncate mt-0.5">
                    By {{ $book['author'] }} &bull; <span class="text-amber-500 font-semibold"><i class="fa-solid fa-star text-[10px]"></i> {{ $book['rating'] }}</span>
                </p>
            </div>
        </div>

        <!-- Pricing & Action (Full width on mobile, right-aligned on tablet/desktop) -->
        <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end gap-3 sm:gap-4 shrink-0">
            @if($hasPurchased ?? false)
                <!-- Owned Status & Download (Desktop) -->
                <div class="text-right hidden sm:block">
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Purchased
                    </span>
                </div>

                <!-- Download Button (Full-width on mobile) -->
                <a 
                    href="{{ route('purchases.download', $userPurchase->id) }}"
                    class="w-full sm:w-auto text-center px-5 sm:px-7 py-3 sm:py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-brand text-xl sm:text-xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] whitespace-nowrap cursor-pointer inline-flex items-center justify-center gap-2 shadow-md group"
                >
                    <i class="fa-solid fa-download text-sm"></i>
                    <span>Download E-Book</span>
                </a>
            @else
                <!-- Price Display (Desktop) -->
                <div class="text-right hidden sm:block">
                    <div class="flex items-baseline gap-1.5 justify-end">
                        <span class="font-brand text-2xl text-brand-600 font-bold tracking-wider leading-none">
                            {{ $book['price'] }}
                        </span>
                        @if(!empty($book['original_price']))
                            <span class="text-xs text-slate-400 line-through">
                                {{ $book['original_price'] }}
                            </span>
                        @endif
                    </div>
                    @if(!empty($book['discount']))
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                            {{ $book['discount'] }}
                        </span>
                    @endif
                </div>

                <!-- Buy Now Button (Full-width on mobile) -->
                <a 
                    href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                    class="btn-buy-motion w-full sm:w-auto text-center px-5 sm:px-7 py-3 sm:py-2.5 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] whitespace-nowrap cursor-pointer inline-flex items-center justify-center gap-1.5 group"
                >
                    <span>Buy Now ({{ $book['price'] }}/-)</span>
                    <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- ==========================================
     INTERACTION JAVASCRIPT
     ========================================== -->
<script>

    // Sample Reader Modal Handlers
    function openSampleReaderModal() {
        const modal = document.getElementById('sample-reader-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSampleReaderModal() {
        const modal = document.getElementById('sample-reader-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function handleModalBackdropClick(event) {
        if (event.target.id === 'sample-reader-modal') {
            closeSampleReaderModal();
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeSampleReaderModal();
        }
    });

    // Reviews Form Toggle
    function toggleReviewForm() {
        const formContainer = document.getElementById('add-review-form-container');
        const btnText = document.getElementById('review-btn-text');
        
        if (!formContainer) return;

        if (formContainer.classList.contains('hidden')) {
            formContainer.classList.remove('hidden');
            if (btnText) btnText.textContent = 'Close Form';
            const authorInput = document.getElementById('review-author');
            if (authorInput) authorInput.focus();
        } else {
            formContainer.classList.add('hidden');
            if (btnText) btnText.textContent = 'Write a Review';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ==========================================
    // CONTINUOUS REVIEWS CAROUSEL HANDLERS
    // ==========================================
    const reviewsContinuousTrack = document.getElementById('reviews-continuous-track');
    let isReviewsMarqueePaused = false;

    window.toggleReviewsMarquee = function() {
        if (!reviewsContinuousTrack) return;
        const icon = document.getElementById('reviews-marquee-toggle-icon');
        const text = document.getElementById('reviews-marquee-toggle-text');

        isReviewsMarqueePaused = !isReviewsMarqueePaused;

        if (isReviewsMarqueePaused) {
            reviewsContinuousTrack.style.animationPlayState = 'paused';
            if (icon) icon.className = 'fa-solid fa-play text-[10px]';
            if (text) text.textContent = 'Play';
        } else {
            reviewsContinuousTrack.style.animationPlayState = 'running';
            if (icon) icon.className = 'fa-solid fa-pause text-[10px]';
            if (text) text.textContent = 'Pause';
        }
    };

    function submitNewReview(e) {
        e.preventDefault();
        
        const form = document.getElementById('new-review-form');
        const nameInput = document.getElementById('review-author');
        const titleInput = document.getElementById('review-title');
        const ratingInput = document.getElementById('review-rating');
        const commentInput = document.getElementById('review-comment');
        const submitBtn = document.getElementById('review-submit-btn');
        const statusDiv = document.getElementById('review-form-status');

        if (!form || !nameInput || !commentInput) return;

        const name = nameInput.value.trim();
        const profession = titleInput ? titleInput.value.trim() : '';
        const rating = ratingInput ? parseInt(ratingInput.value) : 5;
        const message = commentInput.value.trim();

        if (!name || !message) return;

        const originalBtnHTML = submitBtn ? submitBtn.innerHTML : 'Submit Review';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Submitting...';
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || form.querySelector('input[name="_token"]')?.value;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                name: name,
                profession: profession,
                rating: rating,
                message: message,
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw new Error(data.message || 'Failed to submit review'); });
            }
            return response.json();
        })
        .then(data => {
            const rev = data.review || {
                name: name,
                title: profession || 'Verified Reader Review',
                rating: rating,
                comment: message,
                date: 'Just now',
                avatar: name.substring(0, 2).toUpperCase()
            };

            const starHTML = Array.from({length: rev.rating || 5}, () => '<i class="fa-solid fa-star text-[10px]"></i>').join('');

            const cardHTML = `
                <div class="review-card-item w-[290px] sm:w-[350px] shrink-0 bg-white rounded-2xl border border-brand-300 hover:border-brand-500 p-5 sm:p-6 shadow-2xs transition-colors duration-300 flex flex-col justify-between text-left group cursor-default">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-600 to-brand-400 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                    ${escapeHtml(rev.avatar || name.substring(0, 2).toUpperCase())}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 truncate leading-tight">${escapeHtml(rev.name)}</h4>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-[10px] text-brand-600 font-semibold">Verified Review • ${escapeHtml(rev.date || 'Just now')}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs shrink-0">
                                ${starHTML}
                            </div>
                        </div>
                        <div class="space-y-1 text-left">
                            <h5 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug line-clamp-1 group-hover:text-brand-600 transition-colors">${escapeHtml(rev.title || 'Reader Review')}</h5>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal line-clamp-4">${escapeHtml(rev.comment || message)}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-500">
                            <i class="fa-regular fa-thumbs-up text-[10px] text-slate-400"></i> Helpful (1)
                        </span>
                    </div>
                </div>
            `;

            if (reviewsContinuousTrack) {
                reviewsContinuousTrack.insertAdjacentHTML('afterbegin', cardHTML);
            }

            const countDisplay = document.getElementById('reviews-count-display');
            if (countDisplay) {
                const currentCount = parseInt(countDisplay.textContent) || 0;
                countDisplay.textContent = currentCount + 1;
            }

            form.reset();
            toggleReviewForm();
            alert('Thank you! Your review has been submitted.');
        })
        .catch(err => {
            if (statusDiv) {
                statusDiv.classList.remove('hidden');
                statusDiv.className = 'text-xs font-semibold py-1 text-rose-600';
                statusDiv.textContent = err.message || 'Error submitting review. Please try again.';
            } else {
                alert(err.message || 'Error submitting review.');
            }
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHTML;
            }
        });
    }

    const currentBookData = {
        id: @json($book['id'] ?? ''),
        slug: @json($book['slug'] ?? ''),
        title: @json($book['title']),
        author: @json($book['author']),
        category: @json($book['category'] ?? 'E-Book'),
        price: @json($book['price']),
        original_price: @json($book['original_price'] ?? ''),
        image: @json(asset($book['image'])),
        url: @json(route('books.show', $book['slug'] ?? $book['id']))
    };

    document.querySelectorAll('[data-purchase-action]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            initiateBookPurchase(currentBookData, event);
        });
    });

    @if(request()->has('checkout') || session('payment_error'))
        document.addEventListener('DOMContentLoaded', () => {
            initiateBookPurchase(currentBookData);
        });
    @endif

    const galleryImageUrls = @json($book['gallery_image_urls'] ?? []);
    let currentLightboxIndex = 0;

    function updateLightboxImage(index) {
        if (!galleryImageUrls || !galleryImageUrls.length) return;
        if (index < 0) index = galleryImageUrls.length - 1;
        if (index >= galleryImageUrls.length) index = 0;
        currentLightboxIndex = index;

        const img = document.getElementById('lightbox-image');
        const counter = document.getElementById('lightbox-counter');
        const prevBtn = document.getElementById('lightbox-prev-btn');
        const nextBtn = document.getElementById('lightbox-next-btn');

        if (img) {
            img.src = galleryImageUrls[currentLightboxIndex];
        }
        if (counter) {
            counter.textContent = `${currentLightboxIndex + 1} / ${galleryImageUrls.length}`;
        }
        if (prevBtn && nextBtn) {
            const display = galleryImageUrls.length > 1 ? 'flex' : 'none';
            prevBtn.style.display = display;
            nextBtn.style.display = display;
        }
    }

    function openLightbox(indexOrUrl) {
        const modal = document.getElementById('gallery-lightbox-modal');
        const img = document.getElementById('lightbox-image');
        if (!modal || !img) return;

        if (typeof indexOrUrl === 'number') {
            updateLightboxImage(indexOrUrl);
        } else if (typeof indexOrUrl === 'string') {
            const foundIdx = galleryImageUrls.indexOf(indexOrUrl);
            updateLightboxImage(foundIdx >= 0 ? foundIdx : 0);
        } else {
            updateLightboxImage(0);
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            img.classList.remove('scale-95');
            img.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        const modal = document.getElementById('gallery-lightbox-modal');
        const img = document.getElementById('lightbox-image');
        if (!modal || !img) return;
        modal.classList.add('opacity-0');
        img.classList.remove('scale-100');
        img.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            img.src = '';
            document.body.style.overflow = '';
        }, 300);
    }

    function prevLightbox(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        updateLightboxImage(currentLightboxIndex - 1);
    }

    function nextLightbox(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        updateLightboxImage(currentLightboxIndex + 1);
    }

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('gallery-lightbox-modal');
        if (!modal || modal.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            prevLightbox();
        } else if (e.key === 'ArrowRight') {
            nextLightbox();
        }
    });
</script>

@endsection
