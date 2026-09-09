<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-brand-950/60 backdrop-blur-xs lg:hidden hidden transition-opacity"></div>

<!-- Sidebar Container in Brand Theme (#7A58A9) -->
<aside id="sidebar" class="fixed top-0 left-0 bottom-0 z-50 w-64 bg-gradient-to-b from-brand-950 via-brand-900 to-brand-950 text-white border-r border-brand-800/60 flex flex-col justify-between transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-xl shadow-black/10">
    
    <!-- Top Branding -->
    <div>
        <div class="h-20 px-6 flex items-center justify-between border-b border-brand-800/60 sidebar-header-branding">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-white text-brand-700 flex items-center justify-center shadow-md shadow-black/10 font-bold shrink-0">
                    <i class="fa-solid fa-book-open text-lg"></i>
                </div>
                <div class="sidebar-brand-text truncate">
                    <span class="font-bold text-lg text-white leading-none block">E-Book CMS</span>
                    <span class="text-xs font-semibold text-brand-300 uppercase tracking-wider mt-1 block">Admin Console</span>
                </div>
            </a>

            <!-- Sidebar Collapse Button -->
            <button onclick="toggleSidebar()" class="sidebar-toggle-btn p-2 rounded-lg text-brand-300 hover:text-white hover:bg-white/10 transition cursor-pointer" title="Collapse to Icon Mode" aria-label="Collapse Sidebar">
                <i class="fa-solid fa-angles-left text-base"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="px-3.5 py-6 space-y-7 overflow-y-auto max-h-[calc(100vh-170px)]">
            
            <!-- Group: Main -->
            <div>
                <p class="sidebar-heading px-3 text-xs font-bold text-brand-300 uppercase tracking-wider mb-2.5">Main</p>
                <nav class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-gauge-high w-6 text-center text-base shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                </nav>
            </div>

            <!-- Group: Catalog -->
            <div>
                <p class="sidebar-heading px-3 text-xs font-bold text-brand-300 uppercase tracking-wider mb-2.5">Catalog</p>
                <nav class="space-y-1.5">
                    <a href="#" title="E-Books" class="sidebar-link flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <div class="flex items-center gap-3.5">
                            <i class="fa-solid fa-book w-6 text-center text-base shrink-0 text-brand-300"></i>
                            <span class="sidebar-label font-medium">E-Books</span>
                        </div>
                        <span class="sidebar-badge px-2.5 py-0.5 text-xs font-bold rounded-full bg-white/15 text-white">0</span>
                    </a>

                    <a href="#" title="Categories" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-tags w-6 text-center text-base shrink-0 text-brand-300"></i>
                        <span class="sidebar-label font-medium">Categories</span>
                    </a>

                    <a href="#" title="Authors" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-feather-pointed w-6 text-center text-base shrink-0 text-brand-300"></i>
                        <span class="sidebar-label font-medium">Authors</span>
                    </a>
                </nav>
            </div>

            <!-- Group: Commerce & Users -->
            <div>
                <p class="sidebar-heading px-3 text-xs font-bold text-brand-300 uppercase tracking-wider mb-2.5">Commerce & Users</p>
                <nav class="space-y-1.5">
                    <a href="#" title="Users" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-users w-6 text-center text-base shrink-0 text-brand-300"></i>
                        <span class="sidebar-label font-medium">Users</span>
                    </a>

                    <a href="#" title="Orders" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-cart-shopping w-6 text-center text-base shrink-0 text-brand-300"></i>
                        <span class="sidebar-label font-medium">Orders</span>
                    </a>

                    <a href="#" title="Reviews" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-star w-6 text-center text-base shrink-0 text-brand-300"></i>
                        <span class="sidebar-label font-medium">Reviews</span>
                    </a>
                </nav>
            </div>

            <!-- Group: System -->
            <div>
                <p class="sidebar-heading px-3 text-xs font-bold text-brand-300 uppercase tracking-wider mb-2.5">System</p>
                <nav class="space-y-1.5">
                    <a href="#" title="Settings" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition">
                        <i class="fa-solid fa-gear w-6 text-center text-base shrink-0 text-brand-300"></i>
                        <span class="sidebar-label font-medium">Settings</span>
                    </a>
                </nav>
            </div>

        </div>
    </div>

    <!-- Bottom Admin Profile Widget -->
    <div class="p-4 border-t border-brand-800/60 bg-brand-950/50 sidebar-profile-box">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3.5 overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-white text-brand-900 flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 2)) }}
                </div>
                <div class="sidebar-user-info truncate">
                    <p class="text-sm font-semibold text-white truncate leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-brand-300 truncate mt-0.5">{{ Auth::user()->email ?? 'admin@ebook.com' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}" class="sidebar-user-info">
                @csrf
                <button type="submit" title="Logout" class="p-2.5 text-brand-300 hover:text-white hover:bg-white/10 rounded-lg transition cursor-pointer">
                    <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
