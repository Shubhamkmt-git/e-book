<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-brand-950/60 backdrop-blur-xs lg:hidden hidden transition-opacity"></div>

<!-- Sidebar Container in Brand Theme (#7A58A9) -->
<aside id="sidebar" class="fixed top-0 left-0 bottom-0 z-50 w-64 bg-gradient-to-b from-brand-950 via-brand-900 to-brand-950 text-white border-r border-brand-800/60 flex flex-col justify-between transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-xl shadow-black/10 overflow-x-hidden">
    
    <!-- Top Branding -->
    <div>
        <div class="h-20 px-6 flex items-center justify-between border-b border-brand-800/60 sidebar-header-branding">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 overflow-hidden" title="{{ $appSetting->app_name ?? 'E-Book CMS' }}">
                @if($appSetting->logo_dark_url)
                    <img
                        src="{{ $appSetting->logo_dark_url }}"
                        alt="{{ $appSetting->app_name ?? 'Logo' }}"
                        class="h-10 w-auto max-w-[140px] object-contain shrink-0 sidebar-brand-logo"
                    >
                @else
                    <div class="w-10 h-10 rounded-xl bg-white text-brand-700 flex items-center justify-center shadow-md shadow-black/10 font-bold shrink-0">
                        <i class="fa-solid fa-book-open text-lg"></i>
                    </div>
                @endif
                <div class="sidebar-brand-text truncate">
                    <span class="font-bold text-lg text-white leading-none block">{{ $appSetting->app_name ?? 'E-Book CMS' }}</span>
                    <span class="text-xs font-semibold text-brand-300 uppercase tracking-wider mt-1 block">Admin Console</span>
                </div>
            </a>

            <!-- Sidebar Collapse Button -->
            <button onclick="toggleSidebar()" class="sidebar-toggle-btn p-2 rounded-lg text-brand-300 hover:text-white hover:bg-white/10 transition cursor-pointer" title="Collapse Sidebar" aria-label="Collapse Sidebar">
                <i class="fa-solid fa-angles-left text-base"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="px-3.5 py-6 space-y-7 overflow-y-auto overflow-x-hidden max-h-[calc(100vh-170px)]">
            
            <!-- Group: Main -->
            <div>
                <p class="sidebar-heading px-3 text-xs font-bold text-brand-300 uppercase tracking-wider mb-2.5">Main</p>
                <nav class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-gauge-high w-6 text-center text-base shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span class="sidebar-label">Dashboard</span>
                    </a>

                    <!-- User Manage Dropdown Item -->
                    <div class="space-y-1">
                        <button 
                            type="button" 
                            onclick="toggleSidebarSubmenu('user-manage-submenu', this)" 
                            class="sidebar-link w-full flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-medium text-brand-100 hover:bg-white/10 hover:text-white transition cursor-pointer"
                            title="User Manage"
                            aria-expanded="{{ (request()->routeIs('admin.admin-users.*') || request()->routeIs('admin.admin-roles.*') || request()->routeIs('admin.admin-permissions.*')) ? 'true' : 'false' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <i class="fa-solid fa-user-gear w-6 text-center text-base shrink-0 text-brand-300"></i>
                                <span class="sidebar-label font-medium">User Manage</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-brand-300 transition-transform duration-200 submenu-arrow {{ (request()->routeIs('admin.admin-users.*') || request()->routeIs('admin.admin-roles.*') || request()->routeIs('admin.admin-permissions.*')) ? 'rotate-180' : '' }}"></i>
                        </button>

                        <!-- Submenu: admin-user, role, permission -->
                        <div id="user-manage-submenu" class="sidebar-submenu {{ (request()->routeIs('admin.admin-users.*') || request()->routeIs('admin.admin-roles.*') || request()->routeIs('admin.admin-permissions.*')) ? '' : 'hidden' }} pl-9 pr-2 py-1 space-y-1">
                            <a 
                                href="{{ route('admin.admin-users.index') }}" 
                                title="Admin Users"
                                class="sidebar-sublink flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('admin.admin-users.*') ? 'bg-white/15 text-white font-bold' : 'text-brand-200 hover:text-white hover:bg-white/10' }}"
                            >
                                <i class="fa-solid fa-user-shield w-5 text-center text-xs shrink-0 {{ request()->routeIs('admin.admin-users.*') ? 'text-white' : 'text-brand-300' }}"></i>
                                <span class="sidebar-sublabel">admin-user</span>
                            </a>
                            <a 
                                href="{{ route('admin.admin-roles.index') }}" 
                                title="Admin Roles"
                                class="sidebar-sublink flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('admin.admin-roles.*') ? 'bg-white/15 text-white font-bold' : 'text-brand-200 hover:text-white hover:bg-white/10' }}"
                            >
                                <i class="fa-solid fa-shield-halved w-5 text-center text-xs shrink-0 {{ request()->routeIs('admin.admin-roles.*') ? 'text-white' : 'text-brand-300' }}"></i>
                                <span class="sidebar-sublabel">role</span>
                            </a>
                            <a 
                                href="{{ route('admin.admin-permissions.index') }}" 
                                title="Admin Permissions"
                                class="sidebar-sublink flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('admin.admin-permissions.*') ? 'bg-white/15 text-white font-bold' : 'text-brand-200 hover:text-white hover:bg-white/10' }}"
                            >
                                <i class="fa-solid fa-key w-5 text-center text-xs shrink-0 {{ request()->routeIs('admin.admin-permissions.*') ? 'text-white' : 'text-brand-300' }}"></i>
                                <span class="sidebar-sublabel">permission</span>
                            </a>
                        </div>
                    </div>

                    <!-- App Setting -->
                    <a href="{{ route('admin.app-setting.index') }}" title="App Setting" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.app-setting.*') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-sliders w-6 text-center text-base shrink-0 {{ request()->routeIs('admin.app-setting.*') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span class="sidebar-label">App Setting</span>
                    </a>

                    <!-- Categories -->
                    <a href="{{ route('admin.categories.index') }}" title="Categories" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.categories.*') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-layer-group w-6 text-center text-base shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span class="sidebar-label">Categories</span>
                    </a>

                    <!-- E-Books -->
                    <a href="{{ route('admin.books.index') }}" title="E-Books" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.books.*') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-book-bookmark w-6 text-center text-base shrink-0 {{ request()->routeIs('admin.books.*') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span class="sidebar-label">E-Books</span>
                    </a>

                    <!-- Customers -->
                    <a href="{{ route('admin.customers.index') }}" title="Customers" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.customers.*') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-users w-6 text-center text-base shrink-0 {{ request()->routeIs('admin.customers.*') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span class="sidebar-label">Customers</span>
                    </a>

                    <!-- Hero Banners -->
                    <a href="{{ route('admin.hero-banners.index') }}" title="Hero Banners" class="sidebar-link flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.hero-banners.*') ? 'bg-white text-brand-900 font-bold shadow-md shadow-black/10' : 'text-brand-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-panorama w-6 text-center text-base shrink-0 {{ request()->routeIs('admin.hero-banners.*') ? 'text-brand-700' : 'text-brand-300' }}"></i>
                        <span class="sidebar-label">Hero Banners</span>
                    </a>
                </nav>
            </div>

        </div>
    </div>

    <!-- Bottom Admin Profile Widget -->
    <div class="p-4 border-t border-brand-800/60 bg-brand-950/50 sidebar-profile-box">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3.5 overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-white text-brand-900 flex items-center justify-center font-bold text-sm shrink-0 shadow-xs" title="{{ Auth::user()->name ?? 'Administrator' }}">
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
