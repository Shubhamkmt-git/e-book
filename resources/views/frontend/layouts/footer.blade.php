<!-- Frontend Footer -->
<footer class="bg-slate-950 text-slate-400 border-t border-slate-900 pt-16 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
            
            <!-- Col 1: Brand Info (2 cols on lg) -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-brand-600 text-white flex items-center justify-center font-bold shadow-md shadow-brand-600/25">
                        <i class="fa-solid fa-book-open text-lg"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl text-white tracking-tight leading-none block">
                            E-Book<span class="text-brand-500">.</span>
                        </span>
                        <span class="text-[11px] font-semibold text-brand-400 tracking-wider uppercase mt-0.5 block">
                            Store &amp; Digital Library
                        </span>
                    </div>
                </a>
                <p class="text-sm text-slate-400 max-w-sm leading-relaxed">
                    Your premier digital destination for thousands of bestselling e-books, academic titles, audiobooks, and independent authors worldwide.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-brand-600 hover:text-white border border-slate-800 text-slate-400 flex items-center justify-center transition">
                        <i class="fa-brands fa-x-twitter text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-brand-600 hover:text-white border border-slate-800 text-slate-400 flex items-center justify-center transition">
                        <i class="fa-brands fa-github text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-brand-600 hover:text-white border border-slate-800 text-slate-400 flex items-center justify-center transition">
                        <i class="fa-brands fa-discord text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-brand-600 hover:text-white border border-slate-800 text-slate-400 flex items-center justify-center transition">
                        <i class="fa-brands fa-linkedin-in text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Explore</p>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#browse" class="hover:text-white transition">All E-Books</a></li>
                    <li><a href="#bestsellers" class="hover:text-white transition">Bestsellers</a></li>
                    <li><a href="#categories" class="hover:text-white transition">Browse Genres</a></li>
                    <li><a href="#authors" class="hover:text-white transition">Featured Authors</a></li>
                    <li><a href="#pricing" class="hover:text-white transition">Membership Plans</a></li>
                </ul>
            </div>

            <!-- Col 3: Categories -->
            <div>
                <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Top Genres</p>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#categories" class="hover:text-white transition">Sci-Fi &amp; Fantasy</a></li>
                    <li><a href="#categories" class="hover:text-white transition">Technology &amp; AI</a></li>
                    <li><a href="#categories" class="hover:text-white transition">Business &amp; Finance</a></li>
                    <li><a href="#categories" class="hover:text-white transition">Self-Improvement</a></li>
                    <li><a href="#categories" class="hover:text-white transition">Science &amp; Nature</a></li>
                </ul>
            </div>

            <!-- Col 4: Admin & Legal -->
            <div>
                <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Account &amp; Admin</p>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a href="{{ route('admin.login') }}" class="text-brand-400 hover:text-brand-300 font-semibold flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-lock text-xs"></i>
                            <span>Admin Portal</span>
                        </a>
                    </li>
                    <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition">Help &amp; Support</a></li>
                    <li><a href="#" class="hover:text-white transition">Publish Your Book</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} <span class="text-slate-400 font-semibold">{{ config('app.name', 'E-Book Platform') }}</span>. All rights reserved.
            </div>
            <div class="flex items-center gap-6">
                <span>Designed with <i class="fa-solid fa-heart text-brand-500 mx-0.5"></i> using Laravel &amp; Tailwind</span>
            </div>
        </div>
    </div>
</footer>
