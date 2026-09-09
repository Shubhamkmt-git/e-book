@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-9">
    
    <!-- Page Header & Welcome -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">Dashboard</h1>
            <p class="text-base text-slate-500 mt-1.5">Here is a summary of your e-book store catalog, activity, and analytics.</p>
        </div>

        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-xs transition">
                <i class="fa-solid fa-file-arrow-down text-slate-500 text-sm"></i>
                <span>Export Report</span>
            </button>

            <a href="#" class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-md shadow-brand-600/20 transition">
                <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                <span>Publish New E-Book</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total E-Books -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Books</span>
                <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">0</span>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up text-xs"></i> 0%
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">Ready for uploads</p>
        </div>

        <!-- Total Users -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Registered Users</span>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">{{ \App\Models\User::count() }}</span>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-xs"></i> Active
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">Platform subscribers</p>
        </div>

        <!-- Total Downloads / Orders -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Downloads</span>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-download"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">0</span>
                <span class="text-xs font-semibold text-slate-400">Total</span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">Direct PDF & EPUB reads</p>
        </div>

        <!-- Revenue / Status -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">System Status</span>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-emerald-600">Online</span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">MySQL Database Connected</p>
        </div>

    </div>

    <!-- Main Content Grid (Recent Books & Quick Actions) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Table Section (2 Cols) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Recent E-Books</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Latest published digital books in catalog</p>
                </div>
                <a href="#" class="text-sm font-semibold text-brand-600 hover:text-brand-700 hover:underline">View All &rarr;</a>
            </div>

            <!-- Table or Empty state -->
            <div class="p-10 text-center">
                <div class="w-20 h-20 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center mx-auto mb-4 text-brand-600 text-3xl">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900">No E-Books Added Yet</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-1.5 mb-6">
                    Start creating your library by adding your first book with cover, description, authors, and downloadable file.
                </p>
                <a href="#" class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-md shadow-brand-600/20 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add First E-Book</span>
                </a>
            </div>
        </div>

        <!-- Right Side Panel: Quick Actions & System Info -->
        <div class="space-y-6">
            
            <!-- Quick Actions Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
                <h2 class="text-base font-bold text-slate-900 mb-5">Quick Management</h2>
                <div class="space-y-3">
                    <a href="#" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-brand-50/50 hover:border-brand-200 text-sm font-semibold text-slate-700 hover:text-brand-700 transition group">
                        <div class="flex items-center gap-3.5">
                            <span class="w-8 h-8 rounded-lg bg-white shadow-2xs flex items-center justify-center text-slate-500 group-hover:text-brand-600 text-sm">
                                <i class="fa-solid fa-layer-group"></i>
                            </span>
                            <span>Create Genre / Category</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                    </a>

                    <a href="#" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-brand-50/50 hover:border-brand-200 text-sm font-semibold text-slate-700 hover:text-brand-700 transition group">
                        <div class="flex items-center gap-3.5">
                            <span class="w-8 h-8 rounded-lg bg-white shadow-2xs flex items-center justify-center text-slate-500 group-hover:text-brand-600 text-sm">
                                <i class="fa-solid fa-feather"></i>
                            </span>
                            <span>Add Author Profile</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                    </a>

                    <a href="#" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-brand-50/50 hover:border-brand-200 text-sm font-semibold text-slate-700 hover:text-brand-700 transition group">
                        <div class="flex items-center gap-3.5">
                            <span class="w-8 h-8 rounded-lg bg-white shadow-2xs flex items-center justify-center text-slate-500 group-hover:text-brand-600 text-sm">
                                <i class="fa-solid fa-user-shield"></i>
                            </span>
                            <span>Manage Registered Users</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Server Environment -->
            <div class="bg-gradient-to-br from-brand-900 to-brand-700 rounded-2xl p-6 text-white shadow-md shadow-brand-900/10">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-brand-200 uppercase tracking-wider">Stack Details</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-xs font-mono font-medium">Production Ready</span>
                </div>
                <div class="text-lg font-bold flex items-center gap-2.5">
                    <i class="fa-brands fa-laravel text-2xl"></i>
                    <span>Laravel 13 + Vite</span>
                </div>
                <div class="mt-4 pt-3.5 border-t border-white/10 space-y-2 text-sm text-brand-100">
                    <div class="flex justify-between">
                        <span>Database</span>
                        <span class="font-mono font-bold">MySQL (3307)</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Typography</span>
                        <span class="font-semibold">Poppins</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
