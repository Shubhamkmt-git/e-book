@extends('frontend.layouts.app')

@section('title', 'Explore All E-Books & Digital Publications')

@section('content')

<!-- ==========================================
     ALL E-BOOKS COLLECTION PAGE
     ========================================== -->
<section class="py-10 sm:py-16 bg-gradient-to-b from-brand-100/70 via-brand-50/80 to-brand-100/50 min-h-[85vh] relative overflow-hidden">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition">Home</a>
            <span>/</span>
            <span class="text-brand-600 font-semibold">E-Books</span>
        </div>

        <!-- Section Header -->
        <div class="mb-8 pb-6 border-b border-brand-200/70">
            <div class="max-w-3xl">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Digital Catalogue</span>
                <h1 class="font-brand text-4xl sm:text-5xl lg:text-6xl text-slate-900 tracking-wide uppercase leading-none mt-1">
                    Explore All <span class="text-brand-600">E-Books</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-500 mt-2 font-normal leading-relaxed">
                    Search and discover thousands of digital titles across software engineering, science, business, mindset, and literature.
                </p>
            </div>
        </div>

        <!-- Search Bar Form & Metrics (5xl Centered) -->
        <div class="mb-10 space-y-3 max-w-5xl mx-auto w-full">
            <!-- Search Bar Form -->
            <form action="{{ route('books.index') }}" method="GET" class="relative w-full">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-5 sm:pl-6 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-base sm:text-lg"></i>
                    </div>
                    <input 
                        id="ebook-search-input"
                        type="text" 
                        name="search"
                        value="{{ $searchQuery }}"
                        placeholder="Search books by title, author, or genre..."
                        class="w-full pl-13 sm:pl-16 pr-28 sm:pr-32 py-4 sm:py-4.5 rounded-full bg-white text-slate-900 placeholder-slate-400 text-sm sm:text-base border border-brand-200/90 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none shadow-sm hover:shadow-md transition-all font-medium"
                    >
                    <button 
                        type="submit"
                        class="absolute right-2 top-2 bottom-2 px-6 sm:px-8 rounded-full bg-brand-600 hover:bg-brand-500 text-white font-brand text-lg sm:text-xl uppercase tracking-wider transition-all cursor-pointer flex items-center justify-center shadow-xs hover:shadow-md active:scale-95"
                    >
                        <span>Search</span>
                    </button>
                </div>
            </form>

            <!-- Result Metrics Counter -->
            <div class="pt-1 flex items-center justify-between px-2 text-xs sm:text-sm text-slate-500 font-medium">
                <span id="books-count-label">Showing {{ count($books) }} of {{ $totalCount }} E-Books</span>
                @if(!empty($searchQuery))
                    <a href="{{ route('books.index') }}" class="text-brand-600 hover:text-brand-700 font-semibold hover:underline flex items-center gap-1.5">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Reset Search</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- 3 Cards Per Row Grid -->
        <div id="books-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse ($books as $book)
                <div class="book-card group bg-white rounded-3xl border border-brand-200/80 hover:border-brand-400/90 p-5 hover:shadow-[0_20px_45px_-12px_rgba(122,88,169,0.22)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full" data-title="{{ strtolower($book['title']) }}" data-author="{{ strtolower($book['author']) }}" data-category="{{ strtolower($book['category']) }}">
                    
                    <div>
                        <!-- Book Cover with Realistic Spine Depth (10:7) -->
                        <a href="{{ route('books.show', $book['slug'] ?? $book['id']) }}" class="block aspect-[10/7] rounded-2xl overflow-hidden relative shadow-sm group-hover:shadow-md transition-all duration-300 bg-slate-950 ring-1 ring-black/5">
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
                                class="absolute top-3 right-3 w-8.5 h-8.5 rounded-full bg-slate-950/60 hover:bg-white text-white hover:text-rose-500 backdrop-blur-md border border-white/20 hover:border-white flex items-center justify-center transition-all duration-200 shadow-md cursor-pointer z-10"
                            >
                                <i class="fa-regular fa-heart text-xs"></i>
                            </button>
                        </a>

                        <!-- Card Metadata -->
                        <div class="mt-4 flex items-end justify-between gap-3">
                            <!-- Left: Title, Author & Rating -->
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block mb-0.5">{{ $book['category'] }}</span>
                                <a href="{{ route('books.show', $book['slug'] ?? $book['id']) }}" class="font-normal text-[15px] sm:text-base text-slate-800 group-hover:text-brand-600 transition-colors line-clamp-1 leading-snug tracking-tight block">
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
                            href="{{ route('books.show', $book['slug'] ?? $book['id']) }}" 
                            class="w-full h-11 inline-flex items-center justify-center px-5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 shadow-md shadow-brand-600/25 text-center transform hover:-translate-y-0.5"
                        >
                            <span>Buy Now</span>
                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-brand-200/80 p-8 shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="font-brand text-3xl text-slate-900 uppercase">No E-Books Found</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">We couldn't find any books matching your search query. Try searching with different keywords or reset filters.</p>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-brand-600 text-white font-brand text-lg uppercase tracking-wider mt-5 hover:bg-brand-500 transition shadow-xs">
                        <span>View All E-Books</span>
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</section>

<!-- Client-side Instant Filter Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('ebook-search-input');
        const cards = document.querySelectorAll('.book-card');
        const countLabel = document.getElementById('books-count-label');

        if (searchInput && cards.length > 0) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                let visibleCount = 0;

                cards.forEach(card => {
                    const title = card.getAttribute('data-title') || '';
                    const author = card.getAttribute('data-author') || '';
                    const category = card.getAttribute('data-category') || '';

                    if (title.includes(query) || author.includes(query) || category.includes(query)) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (countLabel) {
                    countLabel.textContent = `Showing ${visibleCount} E-Books`;
                }
            });
        }
    });
</script>

<!-- ==========================================
     NEWSLETTER CTA COMPONENT
     ========================================== -->
@include('frontend.components.cta')

@endsection
