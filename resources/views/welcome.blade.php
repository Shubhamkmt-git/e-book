@extends('frontend.layouts.app')

@section('title', 'Welcome to E-Book Store & Library')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-24 lg:pt-20 lg:pb-32 bg-gradient-to-b from-white via-slate-50 to-brand-50/30">
    
    <!-- Ambient Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-brand-200/30 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-50 border border-brand-200/80 text-xs font-semibold text-brand-700 mb-6">
                <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                <span>Discover Thousands of Digital E-Books</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] mb-6">
                Your Digital World of <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-600">Knowledge &amp; Stories.</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-600 leading-relaxed mb-10 max-w-2xl mx-auto">
                Explore bestsellers, academic materials, fiction, and exclusive digital releases. Read on any device, anywhere, anytime.
            </p>

            <!-- Search Bar in Hero -->
            <div class="max-w-xl mx-auto mb-8">
                <form action="#browse" method="GET" class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-base"></i>
                    </div>
                    <input 
                        type="text" 
                        placeholder="Search by book title, author, or category..."
                        class="w-full bg-white border border-slate-300 focus:border-brand-600 rounded-full pl-12 pr-36 py-4 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-brand-600/15 shadow-lg shadow-slate-200/60 transition"
                    >
                    <button 
                        type="submit"
                        class="absolute right-2 px-6 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-md shadow-brand-600/25 transition cursor-pointer"
                    >
                        Search
                    </button>
                </form>
            </div>

            <!-- Quick Category Tags -->
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-slate-600">
                <span class="font-semibold text-slate-400">Popular:</span>
                <a href="#fiction" class="px-3 py-1 rounded-full bg-white border border-slate-200 hover:border-brand-300 hover:text-brand-600 transition">Fiction</a>
                <a href="#technology" class="px-3 py-1 rounded-full bg-white border border-slate-200 hover:border-brand-300 hover:text-brand-600 transition">Technology</a>
                <a href="#business" class="px-3 py-1 rounded-full bg-white border border-slate-200 hover:border-brand-300 hover:text-brand-600 transition">Business & Finance</a>
                <a href="#science" class="px-3 py-1 rounded-full bg-white border border-slate-200 hover:border-brand-300 hover:text-brand-600 transition">Science</a>
            </div>

        </div>
    </div>
</section>
@endsection
