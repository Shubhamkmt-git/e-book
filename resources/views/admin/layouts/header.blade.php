<header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    
    <!-- Left: Mobile Hamburger & Search -->
    <div class="flex items-center gap-4 flex-1">
        <!-- Mobile Sidebar Button -->
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition cursor-pointer" aria-label="Open navigation">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <!-- Search Input -->
        <div class="relative w-full max-w-md hidden sm:block">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input 
                type="text" 
                placeholder="Search e-books, authors, categories..." 
                class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200/90 focus:border-brand-600 rounded-xl pl-9 pr-12 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600/15 transition shadow-2xs font-normal"
            >
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200 rounded-md shadow-2xs">Ctrl K</kbd>
            </div>
        </div>
    </div>

    <!-- Right: Quick Actions & Profile Dropdown -->
    <div class="flex items-center gap-3">
        
        <!-- Action Button -->
        <a href="#" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm shadow-brand-600/25 transition">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add Book</span>
        </a>

        <!-- Notifications -->
        <button class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition cursor-pointer" aria-label="View notifications">
            <i class="fa-regular fa-bell text-base"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-brand-600 ring-2 ring-white"></span>
        </button>

        <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>

        <!-- User Profile Dropdown Toggle -->
        <div class="relative">
            <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 p-1 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="text-left hidden md:block">
                    <span class="block text-xs font-semibold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <span class="block text-[11px] text-slate-400 leading-tight">Super Admin</span>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden md:block"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl shadow-slate-900/10 border border-slate-200/80 py-2 z-50">
                <div class="px-4 py-2 border-b border-slate-100">
                    <p class="text-xs font-semibold text-slate-800">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@ebook.com' }}</p>
                </div>

                <div class="py-1">
                    <a href="#" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                        <i class="fa-regular fa-user w-4 text-slate-400"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                        <i class="fa-solid fa-sliders w-4 text-slate-400"></i>
                        <span>Account Settings</span>
                    </a>
                </div>

                <div class="border-t border-slate-100 pt-1">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 cursor-pointer">
                            <i class="fa-solid fa-arrow-right-from-bracket w-4 text-rose-500"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>
