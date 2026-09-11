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
        
        <!-- Metric 1: Total Orders -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Orders</span>
                <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-roboto">{{ number_format($ordersCount) }}</span>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $paidOrdersCount }} Paid
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">Easebuzz online purchases</p>
        </div>

        <!-- Metric 2: Total Revenue -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Revenue</span>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 font-roboto">₹{{ number_format($totalRevenue, 2) }}</span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">Net realized sales</p>
        </div>

        <!-- Metric 3: E-Books & Catalogue -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">E-Books</span>
                <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ $booksCount }}</span>
                <span class="text-xs font-medium text-slate-500">in catalog</span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">{{ $customersCount }} registered customers</p>
        </div>

        <!-- Metric 4: System Status -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Database & System</span>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-2xs">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 animate-ping"></span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold {{ $dbConnected ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $dbConnected ? 'Operational' : 'Offline' }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">PHP {{ $phpVersion }} &bull; Laravel v{{ $laravelVersion }}</p>
        </div>

    </div>

    <!-- Recent Orders List Preview -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Recent Customer Orders</h2>
                    <p class="text-xs text-slate-500">Latest transactions processed through Easebuzz</p>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-700 transition">
                <span>View All Orders</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-6">Txn ID</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">E-Book</th>
                        <th class="py-3 px-4">Amount</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($recentOrders as $order)
                    @php $status = strtolower($order->status); @endphp
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-6 font-mono font-bold text-slate-800">
                            #{{ $order->id }} <span class="text-slate-400 text-[11px] font-normal block">{{ $order->transaction_id }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-slate-900">{{ $order->customer->name ?? 'Customer' }}</span>
                            <span class="text-slate-400 block text-[11px]">{{ $order->customer->email ?? '' }}</span>
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800 max-w-[200px] truncate">
                            {{ $order->book_title }}
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900">
                            ₹{{ number_format((float) $order->amount, 2) }}
                        </td>
                        <td class="py-3 px-4">
                            @if($status === 'paid' || $status === 'success')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Paid</span>
                            </span>
                            @elseif($status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                <span>Pending</span>
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                <span>{{ ucfirst($order->status) }}</span>
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:text-brand-700 font-semibold text-xs">
                                View &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 italic">No orders received yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
