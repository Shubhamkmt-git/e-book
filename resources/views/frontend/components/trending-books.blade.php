@php
    $trendingBooks = $trendingBooks ?? collect();
@endphp

@if($trendingBooks->isNotEmpty())
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
                @php
                    $isObj = is_object($book);
                    $bookId = $isObj ? $book->id : ($book['id'] ?? null);
                    $bookSlug = $isObj ? $book->slug : ($book['slug'] ?? $bookId);
                    $bookTitle = $isObj ? $book->title : ($book['title'] ?? '');
                    $bookAuthor = $isObj ? ($book->author_name ?? 'Author') : ($book['author'] ?? 'Author');
                    $bookCategory = $isObj ? ($book->category?->title ?? 'E-Book') : ($book['category'] ?? 'E-Book');
                    $bookImage = $isObj ? ($book->cover_image ? $book->cover_image_url : asset('images/books/algorithms.jpg')) : (isset($book['image']) ? asset($book['image']) : asset('images/books/algorithms.jpg'));
                    $bookSellingPrice = $isObj ? (float) $book->selling_price : (float) preg_replace('/[^0-9.]/', '', (string) ($book['price'] ?? 0));
                    $bookOriginalPrice = $isObj ? (float) $book->price : (float) preg_replace('/[^0-9.]/', '', (string) ($book['original_price'] ?? 0));
                    $displayPrice = '₹' . number_format($bookSellingPrice, 0);
                    $displayOriginal = $bookOriginalPrice > $bookSellingPrice ? ('₹' . number_format($bookOriginalPrice, 0)) : null;
                    $bookRating = $isObj ? '5.0' : ($book['rating'] ?? '5.0');
                    $bookReviews = $isObj ? '120+' : ($book['reviews'] ?? '120+');
                @endphp
                <div class="group bg-white rounded-3xl border border-brand-200/80 hover:border-brand-400/90 hover:shadow-[0_20px_45px_-12px_rgba(122,88,169,0.22)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full overflow-hidden">
                    
                    <div>
                        <!-- Book Cover Image with Depth & Spine Effect (10:7) -->
                        <a href="{{ route('books.show', $bookSlug) }}" class="block aspect-[10/7] overflow-hidden relative shadow-sm group-hover:shadow-md transition-all duration-300 bg-slate-950">
                            <img 
                                src="{{ $bookImage }}" 
                                alt="{{ $bookTitle }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                            >



                            <!-- Wishlist Button (Top-Right Corner) -->
                            <button 
                                type="button" 
                                aria-label="Add to wishlist"
                                data-wishlist-key="{{ $bookSlug }}"
                                onclick="toggleWishlist({{ json_encode([
                                    'id' => $bookId,
                                    'slug' => $bookSlug,
                                    'title' => $bookTitle,
                                    'author' => $bookAuthor,
                                    'category' => $bookCategory,
                                    'price' => $displayPrice,
                                    'original_price' => $displayOriginal ?? '',
                                    'image' => $bookImage,
                                    'url' => route('books.show', $bookSlug)
                                ]) }}, event)"
                                class="absolute top-3 right-3 w-8.5 h-8.5 rounded-full bg-slate-950/60 hover:bg-white text-white hover:text-rose-500 backdrop-blur-md border border-white/20 hover:border-white flex items-center justify-center transition-all duration-200 shadow-md cursor-pointer z-10"
                            >
                                <i class="fa-regular fa-heart text-xs"></i>
                            </button>
                        </a>

                        <!-- Card Metadata -->
                        <div class="mt-5 px-5 flex items-end justify-between gap-3">
                            <!-- Left: Title, Author & Rating Stars -->
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block mb-0.5">{{ $bookCategory }}</span>
                                <a href="{{ route('books.show', $bookSlug) }}" class="font-normal text-[15px] sm:text-base text-slate-800 group-hover:text-brand-600 transition-colors line-clamp-1 leading-snug tracking-tight block">
                                    {{ $bookTitle }}
                                </a>
                                <p class="text-xs text-slate-400 font-medium line-clamp-1 mt-0.5">{{ $bookAuthor }}</p>

                                <!-- Rating Stars & Reviews -->
                                <div class="flex items-center gap-1.5 mt-2">
                                    <div class="flex items-center gap-0.5 text-amber-400">
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                        <i class="fa-solid fa-star text-[11px]"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 ml-0.5">{{ $bookRating }}</span>
                                    <span class="text-xs text-slate-400 font-normal">({{ $bookReviews }})</span>
                                </div>
                            </div>

                            <!-- Right: Strikethrough & Main Price (Attached to Bottom) -->
                            <div class="flex flex-col items-end shrink-0 text-right">
                                @if (!empty($displayOriginal))
                                    <span class="font-brand text-sm text-slate-400 line-through tracking-wider leading-none">
                                        {{ $displayOriginal }}
                                    </span>
                                @endif
                                <span class="font-brand text-2xl sm:text-3xl text-brand-600 font-bold tracking-wider leading-none mt-0.5">
                                    {{ $displayPrice }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Action Button (Buy Now) -->
                    <div class="mt-5 pt-3.5 pb-5 px-5 border-t border-slate-100">
                        <button 
                            type="button" 
                            onclick="initiateBookPurchase({{ json_encode([
                                'id' => $bookId,
                                'slug' => $bookSlug,
                                'title' => $bookTitle,
                                'author' => $bookAuthor,
                                'category' => $bookCategory,
                                'price' => $displayPrice,
                                'original_price' => $displayOriginal ?? '',
                                'image' => $bookImage,
                                'url' => route('books.show', $bookSlug)
                            ]) }}, event)"
                            class="w-full h-11 inline-flex items-center justify-center px-5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 shadow-md shadow-brand-600/25 text-center transform hover:-translate-y-0.5 cursor-pointer"
                        >
                            <span>Buy Now</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
@endif
