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
            class="group relative overflow-hidden bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-brand-600/60 hover:shadow-xl hover:shadow-brand-900/15 transition-all duration-500 ease-out flex flex-col items-center text-center cursor-pointer"
        >
            <!-- Smooth Modern Gradient Background Overlay on Hover -->
            <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-600 to-brand-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-out pointer-events-none"></div>

            <!-- Icon Container (Smooth Color & Glassmorphism Dissolve) -->
            <div class="relative z-10 w-14 h-14 rounded-2xl bg-brand-50/90 text-brand-600 border border-brand-100/80 group-hover:bg-white/20 group-hover:text-white group-hover:border-white/30 group-hover:backdrop-blur-sm flex items-center justify-center text-2xl mb-3.5 transition-all duration-500 ease-out shadow-2xs">
                <i class="{{ $category['icon'] }} transition-colors duration-500 ease-out"></i>
            </div>
            
            <!-- Category Title in Bebas Neue Brand Font (Smooth Color Crossfade) -->
            <h3 class="relative z-10 font-brand text-xl text-slate-900 group-hover:text-white transition-colors duration-500 ease-out tracking-wide uppercase">
                {{ $category['name'] }}
            </h3>
            
            <!-- Category Count (Smooth Color Crossfade) -->
            <span class="relative z-10 text-xs font-semibold text-brand-700/70 group-hover:text-brand-100/90 transition-colors duration-500 ease-out mt-1">
                {{ $category['count'] }}
            </span>
        </a>
    @endforeach
</div>
