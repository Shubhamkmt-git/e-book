<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-brand-950/60 backdrop-blur-xs lg:hidden hidden transition-opacity"></div>

<!-- Sidebar Container in Brand Theme (#7A58A9) -->
<aside id="sidebar" class="fixed top-0 left-0 bottom-0 z-50 w-64 bg-gradient-to-b from-brand-950 via-brand-900 to-brand-950 text-white border-r border-brand-800/60 flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-xl shadow-black/10">
    
    <!-- Top Branding -->
    <div>
        <div class="h-16 px-6 flex items-center justify-between border-b border-brand-800/60">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white text-brand-700 flex items-center justify-center shadow-md shadow-black/10 font-bold">
                    <i class="fa-solid fa-book-open text-base"></i>
                </div>
                <div>
                    <span class="font-bold text-base text-white leading-none block">E-Book CMS</span>
                    <span class="text-[10px] font-medium text-brand-300 uppercase tracking-wider">Admin Console</span>
                </div>
            </a>

            <!-- Mobile Close Button -->
            <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-brand-300 hover:text-white hover:bg-white/10 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="px-4 py-5 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)]">
            
            <!-- Group: Main -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-brand-300/80 uppercase tracking-wider mb-2">Main</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-gauge-high w-4 text-center {{ request()->routeIs('admin.dashboard') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            <!-- Group: Catalog -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-brand-300/80 uppercase tracking-wider mb-2">Catalog</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-book w-4 text-center text-brand-300"></i>
                            <span>E-Books</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-white/15 text-white">0</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-tags w-4 text-center text-brand-300"></i>
                        <span>Categories</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-feather-pointed w-4 text-center text-brand-300"></i>
                        <span>Authors</span>
                    </a>
                </nav>
            </div>

            <!-- Group: Users & Commerce -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-brand-300/80 uppercase tracking-wider mb-2">Commerce & Users</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-users w-4 text-center text-brand-300"></i>
                        <span>Users</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-cart-shopping w-4 text-center text-brand-300"></i>
                        <span>Orders</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-star w-4 text-center text-brand-300"></i>
                        <span>Reviews</span>
                    </a>
                </nav>
            </div>

            <!-- Group: System -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-brand-300/80 uppercase tracking-wider mb-2">System</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-gear w-4 text-center text-brand-300"></i>
                        <span>Settings</span>
                    </a>
                </nav>
            </div>

        </div>
    </div>

    <!-- Bottom Admin Profile Widget -->
    <div class="p-4 border-t border-brand-800/60 bg-brand-950/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-white text-brand-900 flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 2)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-brand-300 truncate">{{ Auth::user()->email ?? 'admin@ebook.com' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" title="Logout" class="p-2 text-brand-300 hover:text-white hover:bg-white/10 rounded-lg transition cursor-pointer">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
