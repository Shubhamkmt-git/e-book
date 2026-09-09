@php
    $trendingBooks = $trendingBooks ?? [
        [
            'id' => 1,
            'slug' => 'algorithms-and-elegance',
            'title' => 'Algorithms & Elegance',
            'author' => 'Prof. Julian Hayes',
            'category' => 'Computer Science',
            'tag' => 'TECH',
            'price' => '₹499',
            'original_price' => '₹999',
            'rating' => '4.9',
            'reviews' => '1,420',
            'image' => 'images/books/algorithms.jpg',
        ],
        [
            'id' => 2,
            'slug' => 'whispers-of-the-nebula',
            'title' => 'Whispers of the Nebula',
            'author' => 'S. K. Hawthorne',
            'category' => 'Sci-Fi Fantasy',
            'tag' => 'FICTION',
            'price' => '₹349',
            'original_price' => '₹699',
            'rating' => '4.8',
            'reviews' => '980',
            'image' => 'images/books/nebula.jpg',
        ],
        [
            'id' => 3,
            'slug' => 'the-compound-founder',
            'title' => 'The Compound Founder',
            'author' => 'Marcus Bennett',
            'category' => 'Business & Scale',
            'tag' => 'BUSINESS',
            'price' => '₹599',
            'original_price' => '₹1,199',
            'rating' => '5.0',
            'reviews' => '2,110',
            'image' => 'images/books/founder.jpg',
        ],
        [
            'id' => 4,
            'slug' => 'atomic-focus',
            'title' => 'Atomic Focus',
            'author' => 'Dr. Aris Thorne',
            'category' => 'Psychology',
            'tag' => 'MINDSET',
            'price' => '₹299',
            'original_price' => '₹599',
            'rating' => '4.9',
            'reviews' => '3,540',
            'image' => 'images/books/atomic.jpg',
        ],
        [
            'id' => 5,
            'slug' => 'quantum-frontiers-next-century',
            'title' => 'Quantum Frontiers',
            'author' => 'Dr. Evelyn Vance',
            'category' => 'Theoretical Physics',
            'tag' => 'SCIENCE',
            'price' => '₹699',
            'original_price' => '₹1,499',
            'rating' => '5.0',
            'reviews' => '4,820',
            'image' => 'images/books/spotlight.jpg',
        ],
        [
            'id' => 6,
            'slug' => 'the-neuroscience-of-flow',
            'title' => 'The Neuroscience of Flow',
            'author' => 'Dr. Andrew Miller',
            'category' => 'Self Development',
            'tag' => 'MINDSET',
            'price' => '₹399',
            'original_price' => '₹799',
            'rating' => '4.8',
            'reviews' => '840',
            'image' => 'images/books/atomic.jpg',
        ],
    ];
@endphp

<!-- Trending E-Books Section Component (3 Cards Per Row) -->
<section id="browse" class="py-16 sm:py-20 bg-gradient-to-b from-brand-100/70 via-brand-50/80 to-brand-100/50 relative overflow-hidden">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 sm:mb-12 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Editor's Picks</span>
                <h2 class="font-brand text-4xl sm:text-5xl text-slate-900 tracking-wide uppercase mt-1">Trending E-Books</h2>
            </div>
            <a href="{{ route('books.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1.5 self-start sm:self-auto">
                <span>View all trending books</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- 3 Cards in One Row Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach ($trendingBooks as $book)
                <div class="group bg-white rounded-3xl border border-brand-200/80 hover:border-brand-400/90 p-5 hover:shadow-[0_20px_45px_-12px_rgba(122,88,169,0.22)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full">
                    
                    <div>
                        <!-- Book Cover Image with Depth & Spine Effect (10:7) -->
                        <a href="{{ route('books.show', $book['slug'] ?? $book['id']) }}" class="block aspect-[10/7] rounded-2xl overflow-hidden relative shadow-sm group-hover:shadow-md transition-all duration-300 bg-slate-950 ring-1 ring-black/5">
                            <img 
                                src="{{ asset($book['image']) }}" 
                                alt="{{ $book['title'] }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                            >

                            <!-- Subtle Book Spine Gradient Effect -->
                            <div class="absolute inset-y-0 left-0 w-3 bg-gradient-to-r from-black/35 via-white/10 to-transparent pointer-events-none"></div>

                            <!-- Wishlist Button (Top-Right Corner) -->
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
                            <!-- Left: Title, Author & Rating Stars -->
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block mb-0.5">{{ $book['category'] }}</span>
                                <a href="{{ route('books.show', $book['slug'] ?? $book['id']) }}" class="font-bold text-[15px] sm:text-base text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1 leading-snug tracking-tight block">
                                    {{ $book['title'] }}
                                </a>
                                <p class="text-xs text-slate-400 font-medium line-clamp-1 mt-0.5">{{ $book['author'] }}</p>

                                <!-- Rating Stars & Reviews -->
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

                            <!-- Right: Strikethrough & Main Price (Attached to Bottom) -->
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

                    <!-- Card Footer: Action Button (Get It) -->
                    <div class="mt-5 pt-3.5 border-t border-slate-100">
                        <a 
                            href="{{ route('books.show', $book['slug'] ?? $book['id']) }}" 
                            class="w-full h-11 inline-flex items-center justify-center px-5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 shadow-md shadow-brand-600/25 text-center transform hover:-translate-y-0.5"
                        >
                            <span>Get It</span>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
