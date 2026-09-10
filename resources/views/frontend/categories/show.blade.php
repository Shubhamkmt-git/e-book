@extends('frontend.layouts.app')

@section('title', $category['name'] . ' E-Books & Digital Publications')

@section('content')

<!-- ==========================================
     CATEGORY DETAIL & BOOKS COLLECTION
     ========================================== -->
<section class="py-10 sm:py-16 bg-gradient-to-b from-brand-100/70 via-brand-50/80 to-brand-100/50 min-h-[75vh] relative overflow-hidden">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition">Home</a>
            <span>/</span>
            <a href="{{ route('categories.index') }}" class="hover:text-slate-900 transition">Categories</a>
            <span>/</span>
            <span class="text-brand-600 font-semibold">{{ $category['name'] }}</span>
        </div>

        <!-- Page Header -->
        <div class="mb-10 sm:mb-12 pb-6 border-b border-brand-200/70">
            <div class="max-w-3xl">
                <h1 class="font-brand text-4xl sm:text-5xl lg:text-6xl text-slate-900 tracking-wide uppercase leading-none">
                    {{ $category['name'] }} <span class="text-brand-600">Books</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-500 mt-2.5 font-normal leading-relaxed">
                    {{ $category['description'] }}
                </p>
            </div>
        </div>

        <!-- 3 Cards Per Row Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach ($category['books'] as $book)
                <div class="group bg-white rounded-3xl border border-brand-200/80 hover:border-brand-400/90 p-5 hover:shadow-[0_20px_45px_-12px_rgba(122,88,169,0.22)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full">
                    
                    <div>
                        <!-- Book Cover with Realistic Spine Depth (10:7) -->
                        <a href="{{ route('books.show', $book['slug'] ?? \Illuminate\Support\Str::slug($book['title'])) }}" class="block aspect-[10/7] rounded-2xl overflow-hidden relative shadow-sm group-hover:shadow-md transition-all duration-300 bg-slate-950 ring-1 ring-black/5">
                            <img 
                                src="{{ asset($book['image']) }}" 
                                alt="{{ $book['title'] }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                            >

                            <!-- Subtle Spine Shadow Overlay -->
                            <div class="absolute inset-y-0 left-0 w-3 bg-gradient-to-r from-black/35 via-white/10 to-transparent pointer-events-none"></div>

                            <!-- Wishlist Button -->
                            <button 
                                type="button" 
                                aria-label="Add to wishlist"
                                data-wishlist-key="{{ $book['slug'] ?? \Illuminate\Support\Str::slug($book['title']) }}"
                                onclick="toggleWishlist({{ json_encode([
                                    'id' => $book['id'] ?? '',
                                    'slug' => $book['slug'] ?? \Illuminate\Support\Str::slug($book['title']),
                                    'title' => $book['title'],
                                    'author' => $book['author'],
                                    'category' => $category['name'] ?? 'E-Book',
                                    'price' => $book['price'],
                                    'original_price' => $book['original_price'] ?? '',
                                    'image' => asset($book['image']),
                                    'url' => route('books.show', $book['slug'] ?? \Illuminate\Support\Str::slug($book['title']))
                                ]) }}, event)"
                                class="absolute top-3 right-3 w-8.5 h-8.5 rounded-full bg-slate-950/60 hover:bg-white text-white hover:text-rose-500 backdrop-blur-md border border-white/20 hover:border-white flex items-center justify-center transition-all duration-200 shadow-md cursor-pointer z-10"
                            >
                                <i class="fa-regular fa-heart text-xs"></i>
                            </button>
                        </a>

                        <!-- Card Metadata -->
                        <div class="mt-4 flex items-end justify-between gap-3">
                            <!-- Left: Title, Author & Rating -->
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block mb-0.5">{{ $category['name'] }}</span>
                                <a href="{{ route('books.show', $book['slug'] ?? \Illuminate\Support\Str::slug($book['title'])) }}" class="font-normal text-[15px] sm:text-base text-slate-800 group-hover:text-brand-600 transition-colors line-clamp-1 leading-snug tracking-tight block">
                                    {{ $book['title'] }}
                                </a>
                                <p class="text-xs text-slate-400 font-medium line-clamp-1 mt-0.5">{{ $book['author'] }}</p>

                                <!-- Star Ratings -->
                                <div class="flex items-center gap-1.5 mt-2">
                                    <div class="flex items-center gap-0.5 text-amber-400">
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 ml-0.5">{{ $book['rating'] }}</span>
                                    <span class="text-xs text-slate-400 font-normal">({{ $book['reviews'] }})</span>
                                </div>
                            </div>

                            <!-- Right: Price Block (Attached to Bottom) -->
                            <div class="flex flex-col items-end shrink-0 text-right">
                                @if (!empty($book['original_price']))
                                    <span class="font-brand text-sm text-slate-400 line-through tracking-wider leading-none">
                                        {{ $book['original_price'] }}
                                    </span>
                                @endif
                                <span class="font-brand text-2xl sm:text-3xl text-brand-600 font-bold tracking-wider leading-none mt-0.5">
                                    {{ $book['price'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Action Button (Buy Now) -->
                    <div class="mt-5 pt-3.5 border-t border-slate-100">
                        <a 
                            href="{{ route('books.show', $book['slug'] ?? \Illuminate\Support\Str::slug($book['title'])) }}" 
                            class="w-full h-11 inline-flex items-center justify-center px-5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 shadow-md shadow-brand-600/25 text-center transform hover:-translate-y-0.5"
                        >
                            <span>Buy Now</span>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- ==========================================
     NEWSLETTER CTA COMPONENT
     ========================================== -->
@include('frontend.components.cta')

@endsection
