@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    
    <!-- Page Header & Welcome -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Here is a summary of your e-book store catalog, activity, and analytics.</p>
        </div>

        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Export Report</span>
            </button>

            <a href="#" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Publish New E-Book</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total E-Books -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Books</span>
                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-slate-900">0</span>
                <span class="text-xs font-semibold text-emerald-600 flex items-center">
                    &uarr; 0%
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Ready for uploads</p>
        </div>

        <!-- Total Users -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registered Users</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-slate-900">{{ \App\Models\User::count() }}</span>
                <span class="text-xs font-semibold text-emerald-600 flex items-center">
                    &uarr; Active
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Platform subscribers</p>
        </div>

        <!-- Total Downloads / Orders -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Downloads</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-slate-900">0</span>
                <span class="text-xs font-medium text-slate-400">Total</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Direct PDF & EPUB reads</p>
        </div>

        <!-- Revenue / Status -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">System Status</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-emerald-600">Online</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">MySQL Database Connected</p>
        </div>

    </div>

    <!-- Main Content Grid (Recent Books & Quick Actions) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Table Section (2 Cols) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Recent E-Books</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Latest published digital books in catalog</p>
                </div>
                <a href="#" class="text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline">View All &rarr;</a>
            </div>

            <!-- Table or Empty state -->
            <div class="p-8 text-center">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center mx-auto mb-4 text-brand-600">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">No E-Books Added Yet</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-5">
                    Start creating your library by adding your first book with cover, description, authors, and downloadable file.
                </p>
                <a href="#" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add First E-Book</span>
                </a>
            </div>
        </div>

        <!-- Right Side Panel: Quick Actions & System Info -->
        <div class="space-y-6">
            
            <!-- Quick Actions Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
                <h2 class="text-sm font-bold text-slate-900 mb-4">Quick Management</h2>
                <div class="space-y-2.5">
                    <a href="#" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-brand-50/50 hover:border-brand-200 text-xs font-semibold text-slate-700 hover:text-brand-700 transition group">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-white shadow-2xs flex items-center justify-center text-slate-500 group-hover:text-brand-600">
                                📖
                            </span>
                            <span>Create Genre / Category</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>

                    <a href="#" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-brand-50/50 hover:border-brand-200 text-xs font-semibold text-slate-700 hover:text-brand-700 transition group">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-white shadow-2xs flex items-center justify-center text-slate-500 group-hover:text-brand-600">
                                ✍️
                            </span>
                            <span>Add Author Profile</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>

                    <a href="#" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-brand-50/50 hover:border-brand-200 text-xs font-semibold text-slate-700 hover:text-brand-700 transition group">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-white shadow-2xs flex items-center justify-center text-slate-500 group-hover:text-brand-600">
                                👥
                            </span>
                            <span>Manage Registered Users</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Server Environment -->
            <div class="bg-gradient-to-br from-brand-900 to-brand-700 rounded-2xl p-6 text-white shadow-md shadow-brand-900/10">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand-200 uppercase tracking-wider">Stack Details</span>
                    <span class="px-2 py-0.5 rounded-full bg-white/20 text-[10px] font-mono font-medium">Production Ready</span>
                </div>
                <div class="text-lg font-bold">Laravel 13 + Vite</div>
                <div class="mt-3 pt-3 border-t border-white/10 space-y-1.5 text-xs text-brand-100">
                    <div class="flex justify-between">
                        <span>Database</span>
                        <span class="font-mono font-semibold">MySQL (3307)</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Brand Color</span>
                        <span class="font-mono font-semibold">#7A58A9</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
