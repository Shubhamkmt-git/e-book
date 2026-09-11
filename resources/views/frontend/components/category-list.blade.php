@php
    $categoryItems = $categories ?? collect();
@endphp

@if($categoryItems->isNotEmpty())
<!-- Category List Component (Minimal Cards with Brand & Brand Muted Styling) -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-5">
    @foreach ($categoryItems as $category)
        <a 
            href="{{ route('categories.show', $category->slug) }}" 
            class="group relative overflow-hidden bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 hover:border-transparent hover:shadow-[0_16px_36px_-8px_rgba(122,88,169,0.30)] transition-all duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] flex flex-col items-center text-center cursor-pointer"
        >
            <!-- Smooth Modern Gradient Background Overlay on Hover -->
            <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-600 to-brand-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] pointer-events-none"></div>

            <!-- Ambient Glow Element on Hover -->
            <div class="absolute -top-12 -right-12 w-28 h-28 bg-white/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] pointer-events-none"></div>

            <!-- Icon Container -->
            <div class="relative z-10 w-14 h-14 rounded-2xl bg-brand-50/80 text-brand-600 border border-brand-100/70 group-hover:bg-white/20 group-hover:text-white group-hover:border-white/25 group-hover:backdrop-blur-md flex items-center justify-center text-2xl mb-4 transition-all duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] shadow-2xs">
                <i class="{{ $category->icon ?: 'fa-solid fa-book-open' }} transition-colors duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)]"></i>
            </div>
            
            <!-- Category Title in 1 Single Row -->
            <h3 class="relative z-10 font-brand text-[19px] sm:text-[21px] text-slate-900 group-hover:text-white transition-colors duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] tracking-wider uppercase leading-snug whitespace-nowrap truncate w-full px-1" title="{{ $category->title }}">
                {{ $category->title }}
            </h3>
            
            <!-- Book Count -->
            <span class="relative z-10 text-xs font-semibold text-slate-400 group-hover:text-brand-100/90 transition-colors duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] mt-1.5 tracking-wide">
                {{ $category->books_count ?? 0 }} {{ \Illuminate\Support\Str::plural('Book', $category->books_count ?? 0) }}
            </span>
        </a>
    @endforeach
</div>
@endif
