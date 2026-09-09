<!-- Frontend Navigation Bar -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        <div class="flex items-center justify-between h-20">
            
            <!-- Left: Logo & Brand -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <div class="w-11 h-11 rounded-2xl bg-brand-600 text-white flex items-center justify-center shadow-md shadow-brand-600/25 group-hover:scale-105 transition-transform duration-200 font-bold">
                        <i class="fa-solid fa-book-open text-xl"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl text-slate-900 tracking-tight leading-none block">
                            E-Book<span class="text-brand-600">.</span>
                        </span>
                        <span class="text-[11px] font-semibold text-brand-600 tracking-wider uppercase mt-0.5 block">
                            Store &amp; Library
                        </span>
                    </div>
                </a>
            </div>

            <!-- Right: Navigation Links & Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-8">
                
                <!-- Main Nav Links -->
                <nav class="flex items-center gap-7">
                    <a href="{{ route('home') }}" class="text-sm font-semibold transition {{ request()->routeIs('home') ? 'text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600' }}">
                        Home
                    </a>
                    <a href="#browse" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">
                        Browse Books
                    </a>
                    <a href="#categories" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">
                        Categories
                    </a>
                    <a href="#authors" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">
                        Authors
                    </a>
                    <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">
                        Pricing
                    </a>
                    <a href="#about" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">
                        About
                    </a>
                </nav>

                <div class="h-6 w-px bg-slate-200"></div>

                <!-- Right Action Buttons -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.login') }}" class="text-sm font-semibold text-slate-700 hover:text-brand-600 transition flex items-center gap-2">
                        <i class="fa-regular fa-user text-slate-400"></i>
                        <span>Admin Portal</span>
                    </a>

                    <a href="#browse" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold shadow-md shadow-brand-600/25 transition-all duration-150 active:scale-[0.98]">
                        <span>Explore Library</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex lg:hidden items-center gap-3">
                <a href="{{ route('admin.login') }}" class="text-xs font-semibold text-brand-600 border border-brand-200 bg-brand-50 px-3 py-1.5 rounded-lg">
                    Admin
                </a>
                <button 
                    onclick="toggleFrontendMobileMenu()" 
                    class="p-2.5 rounded-xl text-slate-600 hover:text-brand-600 hover:bg-brand-50/60 border border-slate-200 transition cursor-pointer"
                    aria-label="Toggle Navigation Menu"
                >
                    <i id="mobile-menu-icon" class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown Navigation Menu -->
    <div id="frontend-mobile-menu" class="hidden lg:hidden border-t border-slate-200 bg-white/98 px-5 py-6 space-y-4 shadow-xl">
        <nav class="flex flex-col space-y-3">
            <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-base font-semibold text-brand-600 bg-brand-50">
                Home
            </a>
            <a href="#browse" class="px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600">
                Browse Books
            </a>
            <a href="#categories" class="px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600">
                Categories
            </a>
            <a href="#authors" class="px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600">
                Authors
            </a>
            <a href="#pricing" class="px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600">
                Pricing
            </a>
            <a href="#about" class="px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-600">
                About
            </a>
        </nav>

        <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
            <a href="{{ route('admin.login') }}" class="w-full text-center py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                Sign In to Admin
            </a>
            <a href="#browse" class="w-full text-center py-3 rounded-full bg-brand-600 text-white text-sm font-semibold shadow-md shadow-brand-600/25">
                Explore Library &rarr;
            </a>
        </div>
    </div>
</header>

<script>
    function toggleFrontendMobileMenu() {
        const menu = document.getElementById('frontend-mobile-menu');
        const icon = document.getElementById('mobile-menu-icon');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            icon.className = 'fa-solid fa-xmark text-xl';
        } else {
            menu.classList.add('hidden');
            icon.className = 'fa-solid fa-bars text-xl';
        }
    }
</script>
