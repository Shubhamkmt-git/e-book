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
                    <span>{{ $appSetting->app_name ?? config('app.name', 'E-Book') }} &bull; Live Analytics</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white font-roboto">
                    Welcome back, {{ Auth::user()->name ?? 'Administrator' }}!
                </h1>
                <p class="text-sm sm:text-base text-brand-100/90 max-w-2xl font-normal">
                    Here is your live e-book store performance, sales analytics, and order activity.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-semibold border border-white/20 backdrop-blur-md transition shadow-xs">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    <span>View Storefront</span>
                </a>
                @if(Auth::user()?->hasPermission('ebook'))
                <a
                    href="{{ route('admin.books.create') }}"
                    class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-white text-brand-900 hover:bg-brand-50 text-xs sm:text-sm font-bold shadow-md transition">
                    <i class="fa-solid fa-plus text-xs text-brand-700"></i>
                    <span>Add E-Book</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Dynamic Metrics Cards Grid (6 Business Metric Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 sm:gap-5">

        <!-- Metric 1: Available E-Books -->
        <a href="{{ route('admin.books.index') }}" class="group block bg-white border border-slate-200/90 hover:border-brand-300 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-brand-700 transition-colors">Available E-Books</span>
                <div class="w-10 h-10 rounded-xl bg-violet-50 group-hover:bg-brand-50 text-brand-600 flex items-center justify-center text-base shadow-2xs transition-colors">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
            <div class="mt-3.5 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-roboto">{{ number_format($activeBooksCount) }}</span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">Active</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                <span>{{ $booksCount }} in catalog</span>
                <span class="text-slate-400">&bull; {{ $featuredBooksCount }} featured</span>
            </div>
        </a>

        <!-- Metric 2: Total Categories -->
        <a href="{{ route('admin.categories.index') }}" class="group block bg-white border border-slate-200/90 hover:border-purple-300 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-purple-700 transition-colors">Total Categories</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base shadow-2xs transition-colors">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <div class="mt-3.5 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-roboto">{{ number_format($categoriesCount) }}</span>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded-md">Genres</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                <span>{{ $activeCategoriesCount }} active</span>
                <span class="text-slate-400">&bull; {{ $categoriesCount - $activeCategoriesCount }} hidden</span>
            </div>
        </a>

        <!-- Metric 3: Total Revenue -->
        <a href="{{ route('admin.orders.index') }}" class="group block bg-white border border-slate-200/90 hover:border-emerald-300 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-emerald-700 transition-colors">Total Revenue</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-2xs transition-colors">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <div class="mt-3.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 font-roboto">₹{{ number_format($totalRevenue, 2) }}</span>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-emerald-700 font-semibold mt-2">
                <i class="fa-solid fa-arrow-trend-up text-[11px]"></i>
                <span>₹{{ number_format($todayRevenue, 0) }} today</span>
            </div>
        </a>

        <!-- Metric 4: Total Orders -->
        <a href="{{ route('admin.orders.index') }}" class="group block bg-white border border-slate-200/90 hover:border-blue-300 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-blue-700 transition-colors">Total Orders</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base shadow-2xs transition-colors">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
            <div class="mt-3.5 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-roboto">{{ number_format($ordersCount) }}</span>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-md">{{ $todayOrders }} today</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                <span>{{ $paidOrdersCount }} paid</span>
                <span class="text-slate-400">&bull; {{ $pendingOrdersCount }} pending</span>
            </div>
        </a>

        <!-- Metric 5: Registered Customers -->
        <a href="{{ route('admin.customers.index') }}" class="group block bg-white border border-slate-200/90 hover:border-cyan-300 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-cyan-700 transition-colors">Registered Readers</span>
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-base shadow-2xs transition-colors">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-3.5 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-roboto">{{ number_format($customersCount) }}</span>
                <span class="text-xs font-semibold text-cyan-600 bg-cyan-50 px-1.5 py-0.5 rounded-md">Users</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                <span>{{ $purchasedCustomersCount }} active buyers</span>
                <span class="text-slate-400">&bull; {{ $usersCount }} staff</span>
            </div>
        </a>

        <!-- Metric 6: Avg. Order Value -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Avg. Order Value</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="mt-3.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-roboto">₹{{ number_format($avgOrderValue, 0) }}</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                <span>{{ $conversionRate }}% conversion</span>
                <span class="text-slate-400">&bull; {{ $heroBannersCount }} banners</span>
            </div>
        </div>

    </div>

    <!-- Graphical Representations & Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: 7-Day Revenue & Sales Trend Chart (Span 2 Cols) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-slate-100 gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-brand-600 text-sm"></i>
                        <span>Revenue &amp; Order Activity (Last 7 Days)</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daily realized earnings and sales volume breakdown</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-brand-600"></span>
                        <span class="text-slate-600">Revenue (₹)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        <span class="text-slate-600">Orders</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 relative h-64 sm:h-72 w-full">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Right: Order Status Distribution & Top Genres -->
        <div class="space-y-6">

            <!-- Order Status Donut Chart Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Order Fulfillment</h3>
                        <p class="text-[11px] text-slate-500">Transactions by status</p>
                    </div>
                    <span class="text-xs font-bold text-brand-600">{{ $ordersCount }} Total</span>
                </div>

                <div class="mt-4 flex items-center justify-center relative h-44">
                    <canvas id="orderStatusChart"></canvas>
                </div>

                <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                    <div class="p-2 rounded-lg bg-emerald-50/60 border border-emerald-100">
                        <span class="text-[10px] uppercase font-bold text-emerald-700 block">Paid</span>
                        <span class="text-sm font-extrabold text-emerald-700">{{ $paidOrdersCount }}</span>
                    </div>
                    <div class="p-2 rounded-lg bg-amber-50/60 border border-amber-100">
                        <span class="text-[10px] uppercase font-bold text-amber-700 block">Pending</span>
                        <span class="text-sm font-extrabold text-amber-700">{{ $pendingOrdersCount }}</span>
                    </div>
                    <div class="p-2 rounded-lg bg-rose-50/60 border border-rose-100">
                        <span class="text-[10px] uppercase font-bold text-rose-700 block">Failed</span>
                        <span class="text-sm font-extrabold text-rose-700">{{ $failedOrdersCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Categories Progress Bar Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-sm font-bold text-slate-900">Top Genres by Catalog</h3>
                    <a href="{{ route('admin.categories.index') }}" class="text-[11px] font-bold text-brand-600 hover:underline">View All</a>
                </div>

                <div class="space-y-3.5">
                    @forelse($topCategories as $topCat)
                    @php
                    $percentage = $booksCount > 0 ? round(($topCat->books_count / $booksCount) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700 truncate max-w-[160px]">{{ $topCat->title }}</span>
                            <span class="text-slate-500 font-mono">{{ $topCat->books_count }} books ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-brand-500 to-indigo-500 rounded-full" style="width: {{ max($percentage, 5) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic text-center py-4">No categories created yet.</p>
                    @endforelse
                </div>
            </div>

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
                    <p class="text-xs text-slate-500">Latest transactions processed through Easebuzz payment gateway</p>
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

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Sales & Revenue Trend Chart
        const salesCanvas = document.getElementById('salesTrendChart');
        if (salesCanvas) {
            const labels = @json($chartLabels);
            const revenueData = @json($chartRevenue);
            const ordersData = @json($chartOrders);

            const ctx = salesCanvas.getContext('2d');
            const brandGradient = ctx.createLinearGradient(0, 0, 0, 240);
            brandGradient.addColorStop(0, 'rgba(122, 88, 169, 0.35)');
            brandGradient.addColorStop(1, 'rgba(122, 88, 169, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Revenue (₹)',
                            data: revenueData,
                            borderColor: '#7A58A9',
                            backgroundColor: brandGradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#7A58A9',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders',
                            data: ordersData,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            borderWidth: 2,
                            type: 'bar',
                            borderRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 11
                            },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label.includes('Revenue')) {
                                        return label + ': ₹' + Number(context.raw).toLocaleString('en-IN', {
                                            minimumFractionDigits: 2
                                        });
                                    }
                                    return label + ': ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#64748b'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(226, 232, 240, 0.6)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#64748b',
                                callback: function(value) {
                                    return '₹' + value;
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#10B981',
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // 2. Order Status Distribution Donut Chart
        const statusCanvas = document.getElementById('orderStatusChart');
        if (statusCanvas) {
            const statusData = @json($orderStatusDistribution);
            const total = (statusData.paid || 0) + (statusData.pending || 0) + (statusData.failed || 0);

            new Chart(statusCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Pending', 'Failed / Cancelled'],
                    datasets: [{
                        data: total === 0 ? [1, 0, 0] : [statusData.paid || 0, statusData.pending || 0, statusData.failed || 0],
                        backgroundColor: total === 0 ? ['#e2e8f0', '#f1f5f9', '#f8fafc'] : ['#10B981', '#F59E0B', '#F43F5E'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: total > 0,
                            backgroundColor: '#0f172a',
                            padding: 8,
                            cornerRadius: 6
                        }
                    }
                }
            });
        }
    });
</script>
@endpush