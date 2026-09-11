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
</style>
@endpush

@section('content')

<form id="easebuzz-payment-form" action="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" method="POST" class="hidden">
    @csrf
</form>

<section class="pt-3 pb-24 sm:pt-4 sm:pb-24 bg-gradient-to-b from-brand-100/60 via-brand-50/70 to-slate-50 min-h-screen">
    <div class="w-[94%] sm:w-[88%] lg:w-[70%] mx-auto px-2 sm:px-4 space-y-5 sm:space-y-6">

        @if(session('payment_success'))
            <div class="p-5 sm:p-6 rounded-2xl border border-emerald-300 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 text-emerald-900 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                        <i class="fa-solid fa-check text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-900 text-base sm:text-lg leading-snug">
                            {{ session('payment_success') }}
                        </h4>
                        <p class="text-xs sm:text-sm text-emerald-700 mt-1">
                            Your full DRM-Free PDF edition has been dispatched to <strong>{{ session('customer_email') ?: 'your email' }}</strong>. Your browser download will begin automatically.
                        </p>
                    </div>
                </div>
                @if(session('auto_download_url'))
                    <a 
                        href="{{ session('auto_download_url') }}" 
                        download
                        class="shrink-0 px-6 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-brand text-lg uppercase tracking-wider text-center transition-all shadow-md transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-cloud-arrow-down text-sm"></i>
                        <span>Download PDF Now</span>
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
            <h1 class="font-brand text-3xl sm:text-4xl lg:text-5xl text-slate-900 uppercase tracking-wide leading-tight">
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

        <!-- 3. Centered Book Cover (Rectangular Aspect Ratio on Mobile) -->
        <div id="book-hero-section" class="w-full max-w-5xl mx-auto flex flex-col items-center">
            <div class="w-full aspect-[10/7] sm:aspect-auto sm:h-[70vh] lg:h-[80vh] rounded-2xl sm:rounded-3xl overflow-hidden relative shadow-[0_20px_50px_-15px_rgba(122,88,169,0.35)] bg-slate-950 ring-1 ring-black/10 group">
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

                <!-- Bottom Image Overlay Gradient + Buy Now Button -->
                <div class="absolute inset-x-0 bottom-0 pb-4 sm:pb-8 pt-14 sm:pt-20 bg-gradient-to-t from-slate-950/85 via-slate-950/40 to-transparent flex flex-col items-center justify-end px-4 z-10 pointer-events-auto">
                    <a 
                        href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                        class="btn-buy-motion w-full sm:w-auto min-w-[220px] sm:min-w-[280px] h-12 sm:h-14 inline-flex items-center justify-center gap-2.5 px-6 sm:px-10 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-lg sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.04] active:scale-[0.98] shadow-2xl text-center cursor-pointer group/btn"
                    >
                        <span>Buy Now ({{ $book['price'] }}/-)</span>
                        <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover/btn:translate-x-1 transition-transform duration-300"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. About The Book & Specifications (Centered) -->
        <div class="w-full space-y-6 mx-auto text-center">
            <!-- About The Book -->
            <div class="space-y-2">
                <h2 class="font-brand text-2xl sm:text-3xl text-slate-900 uppercase tracking-wide">
                    About The Book
                </h2>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal w-full mx-auto">
                    {{ $book['description'] }}
                </p>
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

            <!-- Buy Now CTA after Highlights -->
            <div class="pt-3 flex justify-center">
                <a 
                    href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                    class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                >
                    <span>Buy Now ({{ $book['price'] }}/-)</span>
                    <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
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

            <!-- Buy Now CTA after Table of Contents -->
            <div class="pt-3 flex justify-center">
                <a 
                    href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                    class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                >
                    <span>Buy Now ({{ $book['price'] }}/-)</span>
                    <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
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

            <!-- Buy Now CTA after Suggested Section -->
            <div class="pt-4 flex justify-center">
                <a 
                    href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                    class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                >
                    <span>Buy Now ({{ $book['price'] }}/-)</span>
                    <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
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

            <!-- Buy Now CTA after Reviews -->
            <div class="pt-6 flex justify-center">
                <a 
                    href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                    class="btn-buy-motion w-full sm:w-auto min-w-[260px] h-12 sm:h-13 inline-flex items-center justify-center gap-2 px-8 rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 hover:from-brand-500 hover:to-brand-500 active:from-brand-700 active:to-brand-700 text-white font-brand text-xl sm:text-2xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] text-center cursor-pointer group"
                >
                    <span>Buy Now ({{ $book['price'] }}/-)</span>
                    <i class="fa-solid fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
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
                    <a
                        href="{{ route('payments.initiate', $book['slug'] ?? $book['id']) }}" data-purchase-action
                        onclick="closeSampleReaderModal()"
                        class="btn-buy-motion px-6 py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-brand text-xl uppercase tracking-wider transition-all duration-300 transform hover:scale-[1.03] active:scale-[0.98] shadow-md inline-flex items-center gap-2 group">
                        <span>Buy Now ({{ $book['price'] }}/-)</span>
                    </a>
                </div>
            </div>
        </div>
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
</script>

@endsection
