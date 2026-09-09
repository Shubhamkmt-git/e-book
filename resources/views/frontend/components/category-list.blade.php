@php
    $categories = $categories ?? [
        [
            'name' => 'Sci-Fi & Fantasy',
            'count' => '4,280 Titles',
            'icon' => 'fa-solid fa-rocket',
            'link' => '#browse'
        ],
        [
            'name' => 'Tech & Coding',
            'count' => '3,120 Titles',
            'icon' => 'fa-solid fa-laptop-code',
            'link' => '#browse'
        ],
        [
            'name' => 'Business & Finance',
            'count' => '2,850 Titles',
            'icon' => 'fa-solid fa-chart-line',
            'link' => '#browse'
        ],
        [
            'name' => 'Psychology',
            'count' => '5,410 Titles',
            'icon' => 'fa-solid fa-brain',
            'link' => '#browse'
        ],
        [
            'name' => 'Design & Arts',
            'count' => '1,940 Titles',
            'icon' => 'fa-solid fa-palette',
            'link' => '#browse'
        ],
    ];
@endphp

<!-- Category List Component (5 Minimal Cards with Brand & Brand Muted Styling) -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-5">
    @foreach ($categories as $category)
        <a 
            href="{{ $category['link'] }}" 
            class="group bg-white hover:bg-brand-600 p-6 rounded-2xl border border-slate-200/80 hover:border-brand-600 hover:shadow-xl hover:shadow-brand-600/25 transition-all duration-300 ease-out flex flex-col items-center text-center transform hover:-translate-y-1 cursor-pointer"
        >
            <!-- Icon Container (Muted Brand -> Softer White on Card Hover) -->
            <div class="w-14 h-14 rounded-2xl bg-brand-50/90 text-brand-600 border border-brand-100/80 group-hover:bg-white/20 group-hover:text-white group-hover:border-white/30 group-hover:backdrop-blur-xs flex items-center justify-center text-2xl mb-3.5 group-hover:scale-105 transition-all duration-300 shadow-2xs">
                <i class="{{ $category['icon'] }}"></i>
            </div>
            
            <!-- Category Title in Bebas Neue Brand Font (Dark -> White on Card Hover) -->
            <h3 class="font-brand text-xl text-slate-900 group-hover:text-white transition-colors duration-300 tracking-wide uppercase">
                {{ $category['name'] }}
            </h3>
            
            <!-- Category Count (Muted Brand -> Softer Light Brand White on Card Hover) -->
            <span class="text-xs font-semibold text-brand-700/70 group-hover:text-brand-100 transition-colors duration-300 mt-1">
                {{ $category['count'] }}
            </span>
        </a>
    @endforeach
</div>
