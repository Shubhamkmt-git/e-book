@extends('frontend.layouts.app')

@section('title', 'Discover & Read Thousands of Digital E-Books')

@section('content')

<!-- ==========================================
     FULL-WIDTH HERO BANNER SECTION (70% HEIGHT)
     ========================================== -->
<section class="relative w-full min-h-[70vh] lg:h-[70vh] flex items-center justify-center overflow-hidden bg-slate-950">
    
    <!-- Full-Width Background Banner Image with Dark Gradient & Vignette Overlay -->
    <div class="absolute inset-0 z-0">
        <img 
            src="{{ asset('images/hero-banner.jpg') }}" 
            alt="E-Book Digital Library Banner" 
            class="w-full h-full object-cover object-center scale-105 transform motion-safe:animate-[pulse_10s_ease-in-out_infinite]"
        >
        <!-- Multi-layer Gradient Overlays for Readability & Brand Aesthetic -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-950/60"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/60"></div>
        <div class="absolute inset-0 bg-radial from-brand-600/20 via-transparent to-slate-950/80"></div>
    </div>

    <!-- Content Container (96% Width) -->
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 relative z-10 py-16 sm:py-20">
        <div class="max-w-3xl text-center sm:text-left">

            <!-- Main Banner Title -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.15] mb-6">
                Discover, Read &amp; Collect Your Favorite <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-indigo-300">E-Books.</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-8 max-w-2xl font-normal">
                Your premier digital library for bestselling novels, academic textbooks, technology guides, and independent literature. Read seamlessly across all your devices anytime, anywhere.
            </p>

            <!-- Primary & Secondary Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Primary Button -->
                <a 
                    href="#browse" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white text-sm font-bold shadow-xl shadow-brand-600/35 transition-all duration-150 transform hover:-translate-y-0.5"
                >
                    <i class="fa-solid fa-book-open text-base"></i>
                    <span>Explore Library</span>
                    <i class="fa-solid fa-arrow-right text-xs ml-0.5"></i>
                </a>

                <!-- Secondary Button -->
                <a 
                    href="#categories" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 active:bg-white/25 text-white border border-white/25 backdrop-blur-md text-sm font-semibold shadow-lg transition-all duration-150 transform hover:-translate-y-0.5"
                >
                    <i class="fa-solid fa-layer-group text-slate-300"></i>
                    <span>Browse Genres</span>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     METRICS & TRUST BANNER
     ========================================== -->
<section class="border-y border-slate-200/80 bg-white py-10">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
            
            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-slate-900">50,000+</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Digital E-Books</div>
            </div>

            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-slate-900">120,000+</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Active Readers</div>
            </div>

            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-brand-600 flex items-center justify-center gap-1">
                    <span>4.9</span>
                    <i class="fa-solid fa-star text-amber-400 text-2xl"></i>
                </div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Reader Satisfaction</div>
            </div>

            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-slate-900">100%</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">DRM-Free Formats</div>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     EXPLORE GENRES & CATEGORIES
     ========================================== -->
<section id="categories" class="py-20 bg-slate-50">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Browse by Subject</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Explore Popular Genres</h2>
            </div>
            <a href="#browse" class="text-sm font-semibold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1.5">
                <span>View all 32 categories</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
            
            <!-- Category Card 1 -->
            <a href="#browse" class="group bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-900/5 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">4,280 Books</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Sci-Fi &amp; Cyberpunk</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">Space exploration and dystopian adventures.</p>
            </a>

            <!-- Category Card 2 -->
            <a href="#browse" class="group bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-900/5 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">3,120 Books</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Computer Science</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">Programming, algorithms, and engineering.</p>
            </a>

            <!-- Category Card 3 -->
            <a href="#browse" class="group bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-900/5 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">2,850 Books</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Business &amp; Finance</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">Startups, leadership, and venture capital.</p>
            </a>

            <!-- Category Card 4 -->
            <a href="#browse" class="group bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-900/5 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">5,410 Books</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Psychology &amp; Habits</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">Mindset, emotional health, and productivity.</p>
            </a>

            <!-- Category Card 5 -->
            <a href="#browse" class="group bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-900/5 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">1,940 Books</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Design &amp; Arts</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">Visual theory, UI/UX, and graphic design.</p>
            </a>

            <!-- Category Card 6 -->
            <a href="#browse" class="group bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-900/5 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">2,110 Books</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors">Science &amp; Physics</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">Cosmology, quantum theory, and nature.</p>
            </a>

        </div>
    </div>
</section>

<!-- ==========================================
     TRENDING & FEATURED E-BOOKS
     ========================================== -->
<section id="browse" class="py-20 bg-white">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Editor's Picks</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Trending E-Books</h2>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold shadow-xs">All</button>
                <button class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">Fiction</button>
                <button class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">Non-Fiction</button>
                <button class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">Tech</button>
            </div>
        </div>

        <!-- Book Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            
            <!-- Book 1 -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 hover:shadow-xl hover:shadow-brand-900/10 transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Book Cover Design -->
                    <div class="aspect-[3/4] rounded-xl bg-gradient-to-br from-indigo-900 to-purple-800 p-5 text-white flex flex-col justify-between relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-200 shadow-sm">
                        <span class="self-start text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/20">TECH</span>
                        <div>
                            <h4 class="font-bold text-base leading-snug">Algorithms &amp; Elegance</h4>
                            <p class="text-[11px] text-indigo-200 mt-1">Prof. Julian Hayes</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400">Computer Science</span>
                            <div class="flex items-center text-amber-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-slate-700 ml-1">4.9</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 mt-1 group-hover:text-brand-600 transition-colors line-clamp-1">
                            Algorithms &amp; Elegance
                        </h3>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-900">$18.99</span>
                    <a href="#" class="px-3 py-1 rounded-full bg-brand-50 hover:bg-brand-600 text-brand-700 hover:text-white text-xs font-semibold transition">
                        Get Book
                    </a>
                </div>
            </div>

            <!-- Book 2 -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 hover:shadow-xl hover:shadow-brand-900/10 transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Book Cover Design -->
                    <div class="aspect-[3/4] rounded-xl bg-gradient-to-br from-brand-900 to-rose-900 p-5 text-white flex flex-col justify-between relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-200 shadow-sm">
                        <span class="self-start text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/20">FICTION</span>
                        <div>
                            <h4 class="font-bold text-base leading-snug">Whispers of the Nebula</h4>
                            <p class="text-[11px] text-rose-200 mt-1">S. K. Hawthorne</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400">Sci-Fi Fantasy</span>
                            <div class="flex items-center text-amber-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-slate-700 ml-1">4.8</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 mt-1 group-hover:text-brand-600 transition-colors line-clamp-1">
                            Whispers of the Nebula
                        </h3>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-900">$12.50</span>
                    <a href="#" class="px-3 py-1 rounded-full bg-brand-50 hover:bg-brand-600 text-brand-700 hover:text-white text-xs font-semibold transition">
                        Get Book
                    </a>
                </div>
            </div>

            <!-- Book 3 -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 hover:shadow-xl hover:shadow-brand-900/10 transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Book Cover Design -->
                    <div class="aspect-[3/4] rounded-xl bg-gradient-to-br from-cyan-900 to-slate-900 p-5 text-white flex flex-col justify-between relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-200 shadow-sm">
                        <span class="self-start text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/20">BUSINESS</span>
                        <div>
                            <h4 class="font-bold text-base leading-snug">The Compound Founder</h4>
                            <p class="text-[11px] text-cyan-200 mt-1">Marcus Bennett</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400">Startups &amp; Scale</span>
                            <div class="flex items-center text-amber-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-slate-700 ml-1">5.0</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 mt-1 group-hover:text-brand-600 transition-colors line-clamp-1">
                            The Compound Founder
                        </h3>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-900">$15.00</span>
                    <a href="#" class="px-3 py-1 rounded-full bg-brand-50 hover:bg-brand-600 text-brand-700 hover:text-white text-xs font-semibold transition">
                        Get Book
                    </a>
                </div>
            </div>

            <!-- Book 4 -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 hover:shadow-xl hover:shadow-brand-900/10 transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Book Cover Design -->
                    <div class="aspect-[3/4] rounded-xl bg-gradient-to-br from-emerald-900 to-teal-800 p-5 text-white flex flex-col justify-between relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-200 shadow-sm">
                        <span class="self-start text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/20">GROWTH</span>
                        <div>
                            <h4 class="font-bold text-base leading-snug">Atomic Focus</h4>
                            <p class="text-[11px] text-emerald-200 mt-1">Dr. Aris Thorne</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400">Psychology</span>
                            <div class="flex items-center text-amber-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-slate-700 ml-1">4.9</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 mt-1 group-hover:text-brand-600 transition-colors line-clamp-1">
                            Atomic Focus
                        </h3>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-900">$9.99</span>
                    <a href="#" class="px-3 py-1 rounded-full bg-brand-50 hover:bg-brand-600 text-brand-700 hover:text-white text-xs font-semibold transition">
                        Get Book
                    </a>
                </div>
            </div>

            <!-- Book 5 -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 hover:shadow-xl hover:shadow-brand-900/10 transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Book Cover Design -->
                    <div class="aspect-[3/4] rounded-xl bg-gradient-to-br from-purple-900 to-indigo-950 p-5 text-white flex flex-col justify-between relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-200 shadow-sm">
                        <span class="self-start text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/20">DESIGN</span>
                        <div>
                            <h4 class="font-bold text-base leading-snug">Design Systems in Scale</h4>
                            <p class="text-[11px] text-purple-200 mt-1">Clara Oswald</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400">UI/UX &amp; Code</span>
                            <div class="flex items-center text-amber-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-slate-700 ml-1">4.9</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 mt-1 group-hover:text-brand-600 transition-colors line-clamp-1">
                            Design Systems in Scale
                        </h3>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-900">$21.00</span>
                    <a href="#" class="px-3 py-1 rounded-full bg-brand-50 hover:bg-brand-600 text-brand-700 hover:text-white text-xs font-semibold transition">
                        Get Book
                    </a>
                </div>
            </div>

            <!-- Book 6 -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 hover:shadow-xl hover:shadow-brand-900/10 transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Book Cover Design -->
                    <div class="aspect-[3/4] rounded-xl bg-gradient-to-br from-amber-900 to-orange-950 p-5 text-white flex flex-col justify-between relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-200 shadow-sm">
                        <span class="self-start text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/20">CLASSIC</span>
                        <div>
                            <h4 class="font-bold text-base leading-snug">The Odyssey Rewritten</h4>
                            <p class="text-[11px] text-amber-200 mt-1">Homer / Trad. Alex Ross</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400">Literature</span>
                            <div class="flex items-center text-amber-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-slate-700 ml-1">4.7</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 mt-1 group-hover:text-brand-600 transition-colors line-clamp-1">
                            The Odyssey Rewritten
                        </h3>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-900">$8.99</span>
                    <a href="#" class="px-3 py-1 rounded-full bg-brand-50 hover:bg-brand-600 text-brand-700 hover:text-white text-xs font-semibold transition">
                        Get Book
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ==========================================
     SPOTLIGHT BOOK OF THE WEEK
     ========================================== -->
<section class="py-16 bg-gradient-to-r from-brand-950 via-brand-900 to-brand-950 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-pattern opacity-20 pointer-events-none"></div>
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <div class="lg:col-span-4 flex justify-center">
                <div class="w-64 aspect-[3/4] rounded-2xl bg-gradient-to-tr from-indigo-600 via-brand-500 to-cyan-400 p-6 shadow-2xl flex flex-col justify-between transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                    <span class="self-start text-xs font-extrabold px-3 py-1 rounded-full bg-black/30">SPOTLIGHT</span>
                    <div>
                        <h3 class="text-2xl font-black text-white">Quantum Frontiers</h3>
                        <p class="text-xs text-brand-100 mt-1">By Prof. Kenneth Sterling</p>
                    </div>
                    <div class="text-xs text-brand-100 font-mono">520 Pages &bull; Hardcover &amp; Digital</div>
                </div>
            </div>

            <div class="lg:col-span-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-brand-200 mb-4">
                    <i class="fa-solid fa-crown text-amber-400"></i>
                    <span>Book of the Week</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Quantum Frontiers: The Next Century of Human Discovery
                </h2>
                <p class="text-base text-brand-100 mt-4 leading-relaxed max-w-3xl">
                    "An illuminating journey through modern theoretical physics, unraveling mysteries from entanglement to multi-dimensional space, written for both specialists and curious minds."
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    <a href="#" class="px-7 py-3 rounded-full bg-white hover:bg-brand-50 text-brand-900 font-bold text-sm shadow-xl transition">
                        Start Reading Now
                    </a>
                    <a href="#" class="px-6 py-3 rounded-full border border-white/25 hover:bg-white/10 text-white font-semibold text-sm transition">
                        Download Free Sample PDF
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ==========================================
     PLATFORM HIGHLIGHTS / BENEFITS
     ========================================== -->
<section class="py-20 bg-slate-50">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Why E-Book Platform</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Built for the Modern Reader</h2>
            <p class="text-sm text-slate-500 mt-2">Enjoy a seamless digital reading experience tailored for mobile, tablet, and desktop.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl mx-auto mb-5">
                    <i class="fa-solid fa-cloud-arrow-down"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Offline Access</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Download complete books in EPUB or PDF format and read uninterrupted even without an internet connection.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mx-auto mb-5">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Instant Cloud Sync</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Your reading progress, bookmarks, and personal highlights automatically sync across all your phones and tablets.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-5">
                    <i class="fa-solid fa-moon"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Custom Reader Mode</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Personalize your reading comfort with dark mode, sepia tone, custom fonts, and adjustable text sizes.
                </p>
            </div>

        </div>

    </div>
</section>

<!-- ==========================================
     NEWSLETTER & COMMUNITY CTA
     ========================================== -->
<section class="py-20 bg-white">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        <div class="bg-gradient-to-br from-brand-900 via-brand-700 to-indigo-700 rounded-3xl p-8 sm:p-14 text-white text-center relative overflow-hidden shadow-2xl shadow-brand-900/20 w-full mx-auto">
            
            <div class="relative z-10 max-w-3xl mx-auto">
                <span class="inline-block px-3 py-1 rounded-full bg-white/15 text-xs font-semibold text-brand-200 mb-4">
                    Join 120,000+ Book Enthusiasts
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Get 3 Free Bestseller E-Books Today
                </h2>
                <p class="text-sm sm:text-base text-brand-100 mt-3 leading-relaxed">
                    Subscribe to our weekly curated newsletter. Receive hand-picked book summaries, discounts, and free weekly releases directly to your inbox.
                </p>

                <form class="mt-8 flex flex-col sm:flex-row items-center gap-3 max-w-md mx-auto">
                    <input 
                        type="email" 
                        placeholder="Enter your email address..."
                        class="w-full bg-white/95 text-slate-900 placeholder-slate-400 px-5 py-3.5 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-white shadow-md font-medium"
                        required
                    >
                    <button 
                        type="submit"
                        class="w-full sm:w-auto px-7 py-3.5 rounded-full bg-white text-brand-900 hover:bg-brand-50 font-bold text-sm shadow-md transition whitespace-nowrap cursor-pointer"
                    >
                        Claim Free Books
                    </button>
                </form>
                <p class="text-[11px] text-brand-200 mt-3">No spam ever. Unsubscribe at any time with one click.</p>
            </div>

        </div>
    </div>
</section>

@endsection
