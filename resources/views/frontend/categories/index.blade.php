@extends('frontend.layouts.app')

@section('title', 'Explore All E-Book Categories & Genres')

@section('content')

@php
    $allCategories = $allCategories ?? [];
    $categoryCount = $totalCount ?? count($allCategories);
@endphp

<!-- ==========================================
     ALL CATEGORIES (CLEAN & DIRECT LAYOUT)
     ========================================== -->
<section class="py-10 sm:py-16 bg-slate-50">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Breadcrumb Navigation (Desktop Only) -->
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition">Home</a>
            <span>/</span>
            <span class="text-brand-600 font-semibold">Categories</span>
        </div>

        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 sm:mb-12 gap-4 pb-6 border-b border-slate-200/70">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Catalog Directory</span>
                <h1 class="font-brand text-4xl sm:text-5xl lg:text-6xl text-slate-900 tracking-wide uppercase mt-1 leading-[0.95]">
                    Explore All <span class="text-brand-600">Categories</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-500 mt-2.5 max-w-2xl font-normal leading-relaxed">
                    Browse our complete catalog of {{ $categoryCount }}+ genres and subjects. Find your next transformative read across technology, fiction, science, and beyond.
                </p>
            </div>
            <span class="text-xs text-slate-500 font-medium self-start sm:self-auto">
                Showing all {{ $categoryCount }} categories
            </span>
        </div>

        <!-- Category List Grid (5 Cards Per Row Matching Home) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 lg:gap-5">
            @foreach ($allCategories as $category)
                <a 
                    href="{{ route('categories.show', $category['slug'] ?? 'tech-coding') }}" 
                    class="group relative overflow-hidden bg-white p-3.5 sm:p-7 rounded-2xl border border-slate-200/80 hover:border-transparent hover:shadow-[0_16px_36px_-8px_rgba(122,88,169,0.30)] transition-all duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] flex flex-col items-center text-center cursor-pointer"
                >
                    <!-- Smooth Modern Gradient Background Overlay on Hover -->
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-600 to-brand-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] pointer-events-none"></div>

                    <!-- Ambient Glow Element on Hover -->
                    <div class="absolute -top-12 -right-12 w-28 h-28 bg-white/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] pointer-events-none"></div>

                    <!-- Icon Container (Smooth Color & Glassmorphism Dissolve) -->
                    <div class="relative z-10 w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-brand-50/80 text-brand-600 border border-brand-100/70 group-hover:bg-white/20 group-hover:text-white group-hover:border-white/25 group-hover:backdrop-blur-md flex items-center justify-center text-xl sm:text-2xl mb-2.5 sm:mb-4 transition-all duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] shadow-2xs">
                        <i class="{{ $category['icon'] }} transition-colors duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)]"></i>
                    </div>
                    
                    <!-- Category Title (Full Text, Mobile Responsive Font) -->
                    <h3 class="relative z-10 font-brand text-[15px] sm:text-[20px] text-slate-900 group-hover:text-white transition-colors duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] tracking-wide uppercase leading-tight w-full px-0.5 text-center break-words" title="{{ $category['name'] }}">
                        {{ $category['name'] }}
                    </h3>
                    
                    <!-- Category Count (Smooth Color Crossfade) -->
                    <span class="relative z-10 text-[11px] sm:text-xs font-semibold text-slate-400 group-hover:text-brand-100/90 transition-colors duration-500 [transition-timing-function:cubic-bezier(0.16,1,0.3,1)] mt-1 sm:mt-1.5 tracking-wide">
                        {{ $category['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

    </div>
</section>

<!-- ==========================================
     NEWSLETTER CTA COMPONENT
     ========================================== -->
@include('frontend.components.cta')

@endsection
