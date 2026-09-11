<header id="admin-header" class="h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/80 fixed top-0 right-0 left-0 lg:left-64 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-all duration-300">
    
    <!-- Left: Sidebar Toggle Button (Visible on both Desktop & Mobile) -->
    <div class="flex items-center gap-4">
        <button 
            onclick="toggleSidebar()" 
            class="p-2.5 rounded-xl text-slate-600 hover:text-brand-600 hover:bg-brand-50/60 border border-slate-200 transition cursor-pointer flex items-center justify-center text-lg" 
            title="Toggle Sidebar"
            aria-label="Toggle Sidebar Navigation"
        >
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
    </div>

    <!-- Right: Profile Dropdown -->
    <div class="flex items-center gap-4">
        @php
            $authUser = Auth::user();
            $avatarUrl = $authUser?->avatar_url;
            $initials = $authUser?->initials ?? strtoupper(substr($authUser->name ?? 'A', 0, 1));
            $roleTitle = $authUser?->role_title ?? 'Super Admin';
        @endphp

        <!-- User Profile Dropdown Toggle -->
        <div class="relative">
            <button onclick="toggleProfileDropdown()" class="flex items-center gap-3.5 p-1.5 rounded-xl hover:bg-slate-100/80 transition cursor-pointer">
                @if(!empty($avatarUrl))
                    <img 
                        src="{{ $avatarUrl }}" 
                        alt="{{ $authUser->name ?? 'Administrator' }}" 
                        class="w-10 h-10 rounded-full object-cover border border-brand-200 shadow-2xs shrink-0"
                    >
                @else
                    <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                        {{ $initials }}
                    </div>
                @endif
                <div class="text-left hidden md:block">
                    <span class="block text-sm font-semibold text-slate-800 leading-tight">{{ $authUser->name ?? 'Administrator' }}</span>
                    <span class="block text-xs text-slate-500 leading-tight mt-0.5">{{ $roleTitle }}</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs text-slate-400 hidden md:block"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl shadow-slate-900/10 border border-slate-200/80 py-2.5 z-50">
                <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3">
                    @if(!empty($avatarUrl))
                        <img 
                            src="{{ $avatarUrl }}" 
                            alt="{{ $authUser->name ?? 'Admin' }}" 
                            class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0"
                        >
                    @else
                        <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ $authUser->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $authUser->email ?? 'admin@ebook.com' }}</p>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-1.5">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 cursor-pointer">
                            <i class="fa-solid fa-arrow-right-from-bracket w-4 text-rose-500 text-sm"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</header>
