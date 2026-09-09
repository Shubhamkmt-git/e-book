<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden hidden transition-opacity"></div>

<!-- Sidebar Container -->
<aside id="sidebar" class="fixed top-0 left-0 bottom-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
    
    <!-- Top Branding -->
    <div>
        <div class="h-16 px-6 flex items-center justify-between border-b border-slate-100">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-md shadow-brand-600/20">
                    <i class="fa-solid fa-book-open text-base"></i>
                </div>
                <div>
                    <span class="font-bold text-base text-slate-900 leading-none block">E-Book CMS</span>
                    <span class="text-[11px] font-medium text-brand-600 uppercase tracking-wider">Admin Console</span>
                </div>
            </a>

            <!-- Mobile Close Button -->
            <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="px-4 py-5 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)]">
            
            <!-- Group: Main -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Main</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-gauge-high w-4 text-center {{ request()->routeIs('admin.dashboard') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            <!-- Group: Catalog -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Catalog</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-book w-4 text-center text-slate-400"></i>
                            <span>E-Books</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-brand-50 text-brand-700">0</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        <i class="fa-solid fa-tags w-4 text-center text-slate-400"></i>
                        <span>Categories</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        <i class="fa-solid fa-feather-pointed w-4 text-center text-slate-400"></i>
                        <span>Authors</span>
                    </a>
                </nav>
            </div>

            <!-- Group: Users & Orders -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Commerce & Users</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        <i class="fa-solid fa-users w-4 text-center text-slate-400"></i>
                        <span>Users</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        <i class="fa-solid fa-cart-shopping w-4 text-center text-slate-400"></i>
                        <span>Orders</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        <i class="fa-solid fa-star w-4 text-center text-slate-400"></i>
                        <span>Reviews</span>
                    </a>
                </nav>
            </div>

            <!-- Group: Settings -->
            <div>
                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">System</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        <i class="fa-solid fa-gear w-4 text-center text-slate-400"></i>
                        <span>Settings</span>
                    </a>
                </nav>
            </div>

        </div>
    </div>

    <!-- Bottom Admin Profile Widget -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 2)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@ebook.com' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
