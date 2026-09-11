<!-- Frontend Navigation Bar (Fixed) -->
<header class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        <div class="flex items-center justify-between h-20">
            
            <!-- Left: Logo & Brand -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    @if (!empty($appSetting?->logo_light_url))
                        <img
                            src="{{ $appSetting->logo_light_url }}"
                            alt="{{ $appSetting->app_name }}"
                            class="max-w-[180px] max-h-12 object-contain group-hover:scale-105 transition-transform duration-200"
                        >
                    @else
                        <div class="w-11 h-11 rounded-2xl bg-brand-600 text-white flex items-center justify-center shadow-md shadow-brand-600/25 group-hover:scale-105 transition-transform duration-200 font-bold">
                            <i class="fa-solid fa-book-open text-xl"></i>
                        </div>
                    @endif
                </a>
            </div>

            <!-- Center/Right: Search Bar (Desktop) & Nav Links -->
            <div class="hidden lg:flex items-center gap-6 xl:gap-8 flex-1 justify-end max-w-4xl">
                
                <!-- Search Bar Form -->
                <form action="{{ route('books.index') }}" method="GET" class="relative w-64 xl:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Search books, authors, topics..."
                        value="{{ request('search') }}"
                        class="w-full pl-9 pr-4 py-2 rounded-full bg-slate-100/90 hover:bg-slate-100 focus:bg-white text-slate-800 placeholder-slate-400 text-xs border border-slate-200/80 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all duration-200 font-medium"
                    >
                </form>

                <!-- Main Nav Links -->
                <nav class="flex items-center gap-6 shrink-0">
                    <a href="{{ route('home') }}" class="text-sm font-semibold transition {{ request()->routeIs('home') ? 'text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600' }}">
                        Home
                    </a>
                    <a href="{{ route('books.index') }}" class="text-sm font-semibold transition {{ request()->routeIs('books*') ? 'text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600' }}">
                        E-Books
                    </a>
                    <a href="{{ route('categories.index') }}" class="text-sm font-semibold transition {{ request()->routeIs('categories*') ? 'text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600' }}">
                        Categories
                    </a>
                </nav>

                <div class="h-6 w-px bg-slate-200 shrink-0"></div>

                <!-- Wishlist & Right Action Buttons -->
                <div class="flex items-center gap-3 shrink-0">
                    <!-- Wishlist Button -->
                    <button 
                        type="button" 
                        onclick="openWishlistDrawer()" 
                        class="relative w-10 h-10 rounded-full hover:bg-rose-50 text-slate-700 hover:text-rose-600 flex items-center justify-center transition-all duration-200 cursor-pointer group"
                        aria-label="View Wishlist"
                        title="My Wishlist"
                    >
                        <i class="fa-regular fa-heart text-lg group-hover:scale-110 transition-transform"></i>
                        <span id="wishlist-nav-badge" class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold items-center justify-center shadow-xs">0</span>
                    </button>

                    @if (auth('customer')->check())
                        <span class="text-sm font-semibold text-slate-600">Hi, {{ auth('customer')->user()->name }}</span>
                        <form action="{{ route('customer.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-full border border-slate-200 text-slate-700 hover:border-brand-500 hover:text-brand-600 text-xs sm:text-sm font-semibold transition-all duration-150 cursor-pointer">
                                Sign Out
                            </button>
                        </form>
                    @else
                        <button
                            type="button"
                            onclick="openAuthDrawer('signin')"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-brand-600/25 transition-all duration-150 active:scale-[0.98] cursor-pointer"
                        >
                            <span>Sign In / Up</span>
                        </button>
                    @endif
                </div>

            </div>

            <!-- Mobile Action Buttons -->
            <div class="flex lg:hidden items-center gap-2">
                <!-- Mobile Wishlist Button -->
                <button 
                    type="button" 
                    onclick="openWishlistDrawer()" 
                    class="relative w-10 h-10 rounded-xl text-slate-700 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 flex items-center justify-center transition cursor-pointer"
                    aria-label="Wishlist"
                >
                    <i class="fa-regular fa-heart text-lg"></i>
                    <span id="wishlist-mobile-badge" class="hidden absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold items-center justify-center shadow-xs">0</span>
                </button>

                <!-- Mobile Hamburger Button -->
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
        <!-- Mobile Search Input Form -->
        <form action="{{ route('books.index') }}" method="GET" class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input 
                type="text" 
                name="search"
                placeholder="Search books, authors, topics..."
                value="{{ request('search') }}"
                class="w-full pl-9 pr-4 py-2.5 rounded-full bg-slate-100 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:border-brand-500 focus:outline-none font-medium"
            >
        </form>

        <nav class="flex flex-col space-y-2 pt-1">
            <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-base font-semibold {{ request()->routeIs('home') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}">
                Home
            </a>
            <a href="{{ route('books.index') }}" class="px-3 py-2 rounded-xl text-base font-semibold {{ request()->routeIs('books*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}">
                E-Books
            </a>
            <a href="{{ route('categories.index') }}" class="px-3 py-2 rounded-xl text-base font-semibold {{ request()->routeIs('categories*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}">
                Categories
            </a>
            <button 
                type="button"
                onclick="toggleFrontendMobileMenu(); openWishlistDrawer();"
                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 text-left cursor-pointer"
            >
                <span class="flex items-center gap-2">
                    <i class="fa-regular fa-heart text-rose-500"></i>
                    <span>My Wishlist</span>
                </span>
                <span id="wishlist-mobile-menu-count" class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-xs font-bold">0</span>
            </button>
        </nav>

        <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
            @if (auth('customer')->check())
                <div class="px-3 text-sm font-semibold text-slate-600">Hi, {{ auth('customer')->user()->name }}</div>
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center py-3 rounded-full border border-slate-200 text-slate-700 text-sm font-semibold cursor-pointer">
                        Sign Out
                    </button>
                </form>
            @else
                <button
                    type="button"
                    onclick="toggleFrontendMobileMenu(); openAuthDrawer('signin');"
                    class="w-full text-center py-3 rounded-full bg-brand-600 text-white text-sm font-semibold shadow-md shadow-brand-600/25 cursor-pointer"
                >
                    <span>Sign In / Up</span>
                </button>
            @endif
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
