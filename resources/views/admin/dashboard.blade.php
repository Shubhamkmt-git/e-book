@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    
    <!-- Page Header & Welcome Banner -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-brand-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-brand-950/20 relative overflow-hidden">
        <!-- Background Ambient Glow -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 -bottom-20 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-brand-200 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Admin Console Active</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white font-roboto">
                    Welcome back, {{ Auth::user()->name ?? 'Administrator' }}!
                </h1>
                <p class="text-sm sm:text-base text-brand-100/90 max-w-2xl font-normal">
                    Here is your live application overview and system status.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a 
                    href="{{ route('home') }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-semibold border border-white/20 backdrop-blur-md transition shadow-xs"
                >
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    <span>View Storefront</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Dynamic Metrics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
        
        <!-- Metric 1: Registered Users -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Registered Users</span>
                <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ $usersCount }}</span>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Active
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">Total database users</p>
        </div>

        <!-- Metric 2: Database Connection -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Database</span>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold {{ $dbConnected ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $dbConnected ? 'Connected' : 'Offline' }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">MySQL Database engine</p>
        </div>

        <!-- Metric 3: Application Environment -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Environment</span>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-server"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 capitalize">{{ config('app.env', 'local') }}</span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">PHP {{ $phpVersion }} &bull; Laravel v{{ $laravelVersion }}</p>
        </div>

        <!-- Metric 4: System Status -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">System Health</span>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-2xs">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 animate-ping"></span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600">Operational</span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">All services running smoothly</p>
        </div>

    </div>

    <!-- Active Administrator Session & System Overview -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Dashboard Workspace</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Admin control session and environment parameters.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <i class="fa-regular fa-clock text-brand-600"></i>
                <span>{{ now()->format('l, F j, Y — H:i T') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="p-4.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Logged Administrator</span>
                <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email ?? 'admin@ebook.com' }}</p>
            </div>

            <div class="p-4.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Application Name</span>
                <p class="text-sm font-bold text-slate-900">{{ config('app.name', 'E-Book') }}</p>
                <p class="text-xs text-slate-500 truncate mt-0.5">E-Book Store &amp; Digital Publishing</p>
            </div>

            <div class="p-4.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Access Level</span>
                <p class="text-sm font-bold text-emerald-700 flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-xs text-emerald-600"></i> Super Administrator
                </p>
                <p class="text-xs text-slate-500 truncate mt-0.5">Full console privileges</p>
            </div>
        </div>
    </div>

</div>
@endsection
