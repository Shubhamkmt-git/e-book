<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name', 'E-Book') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen selection:bg-brand-600 selection:text-white">
    <!-- Navbar -->
    <nav class="border-b border-slate-200 bg-white/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-md shadow-brand-600/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <span class="font-bold text-lg text-slate-900">E-Book Admin</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Administrator' }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email ?? 'admin@ebook.com' }}</div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button 
                        type="submit" 
                        class="px-3.5 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition cursor-pointer"
                    >
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Overview</h1>
            <p class="text-sm text-slate-500 mt-1">Welcome back, manage your e-book platform and settings.</p>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:border-brand-300 transition-colors">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total E-Books</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">0</div>
                <div class="text-xs text-brand-600 font-medium mt-3 flex items-center gap-1">
                    <span>Manage collection &rarr;</span>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:border-brand-300 transition-colors">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registered Users</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ \App\Models\User::count() }}</div>
                <div class="text-xs text-brand-600 font-medium mt-3 flex items-center gap-1">
                    <span>View all users &rarr;</span>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">System Status</div>
                <div class="text-3xl font-bold text-emerald-600 mt-2 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Healthy</span>
                </div>
                <div class="text-xs text-slate-500 mt-3">Laravel 13 &bull; PHP 8.3 &bull; Vite 8</div>
            </div>
        </div>
    </main>
</body>
</html>
