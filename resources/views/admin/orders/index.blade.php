@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Orders & Transactions</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-brand-100 text-brand-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage customer e-book purchases, payments, transaction logs, and delivery statuses.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer" title="Refresh Orders">
                <i class="fa-solid fa-rotate text-xs"></i>
                <span>Refresh</span>
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider truncate">Total Orders</p>
                <p class="text-2xl font-extrabold text-slate-900 font-roboto">{{ number_format($totalCount) }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider truncate">Total Revenue</p>
                <p class="text-2xl font-extrabold text-emerald-600 font-roboto">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider truncate">Paid / Success</p>
                <p class="text-2xl font-extrabold text-slate-900 font-roboto">{{ number_format($paidCount) }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider truncate">Pending / Failed</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-amber-600 font-roboto">{{ number_format($pendingCount) }}</span>
                    <span class="text-xs text-rose-500 font-medium">({{ $failedCount }} failed)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by Txn ID, customer name, email, or e-book title..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium"
                >
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-700 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200">
                    <label for="date_from" class="text-slate-400">From:</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()" class="bg-transparent text-slate-700 text-xs focus:outline-none cursor-pointer">
                </div>

                <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200">
                    <label for="date_to" class="text-slate-400">To:</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()" class="bg-transparent text-slate-700 text-xs focus:outline-none cursor-pointer">
                </div>

                @if(request('search') || request('status') || request('date_from') || request('date_to'))
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-semibold transition flex items-center gap-1.5" title="Clear Filters">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Order / Txn ID</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">E-Book Purchased</th>
                        <th class="py-3.5 px-4">Amount</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($orders as $order)
                    @php
                        $status = strtolower($order->status);
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        
                        <!-- Order / Txn ID -->
                        <td class="py-3.5 px-4 sm:px-6">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 font-mono text-xs">#{{ $order->id }}</span>
                            </div>
                            <div class="mt-0.5 flex items-center gap-1.5">
                                <span class="text-[11px] font-mono text-slate-500 truncate max-w-[140px] sm:max-w-[180px]" title="{{ $order->transaction_id }}">
                                    {{ $order->transaction_id }}
                                </span>
                                <button 
                                    type="button"
                                    onclick="navigator.clipboard.writeText('{{ $order->transaction_id }}'); alert('Transaction ID copied: {{ $order->transaction_id }}');" 
                                    class="text-slate-400 hover:text-brand-600 transition text-[10px]" 
                                    title="Copy Transaction ID"
                                >
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                            <span class="inline-block mt-1 text-[10px] font-semibold uppercase tracking-wider px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">
                                Easebuzz
                            </span>
                        </td>

                        <!-- Customer -->
                        <td class="py-3.5 px-4">
                            @if($order->customer)
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center font-bold text-xs shrink-0 border border-brand-200">
                                    {{ strtoupper(substr($order->customer->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.customers.show', $order->customer) }}" class="font-semibold text-slate-900 hover:text-brand-600 transition truncate block max-w-[150px]">
                                        {{ $order->customer->name }}
                                    </a>
                                    <p class="text-[11px] text-slate-500 truncate max-w-[150px]">{{ $order->customer->email }}</p>
                                    @if($order->customer->mobile)
                                    <p class="text-[10px] text-slate-400">{{ $order->customer->mobile }}</p>
                                    @endif
                                </div>
                            </div>
                            @else
                            <span class="text-xs text-slate-400 italic">Customer Deleted</span>
                            @endif
                        </td>

                        <!-- E-Book Purchased -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2.5">
                                @if($order->book && $order->book->cover_image)
                                <img src="{{ $order->book->cover_image_url }}" alt="{{ $order->book_title }}" class="w-8 h-11 object-cover rounded shadow-2xs shrink-0">
                                @else
                                <div class="w-8 h-10 rounded bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-book text-xs"></i>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 truncate max-w-[180px] sm:max-w-[220px]" title="{{ $order->book_title }}">
                                        {{ $order->book_title }}
                                    </p>
                                    @if($order->book)
                                    <a href="{{ route('admin.books.edit', $order->book) }}" class="text-[11px] text-brand-600 hover:underline">
                                        View Book in Catalog &rarr;
                                    </a>
                                    @else
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $order->book_identifier }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="text-sm sm:text-base font-extrabold text-slate-900 font-roboto">
                                ₹{{ number_format((float) $order->amount, 2) }}
                            </span>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($status === 'paid' || $status === 'success')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <i class="fa-solid fa-circle-check text-[11px]"></i>
                                <span>Paid</span>
                            </span>
                            @elseif($status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                <i class="fa-solid fa-clock text-[11px]"></i>
                                <span>Pending</span>
                            </span>
                            @elseif($status === 'failed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                <i class="fa-solid fa-circle-xmark text-[11px]"></i>
                                <span>Failed</span>
                            </span>
                            @elseif($status === 'refunded')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                <span>Refunded</span>
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                <span>{{ ucfirst($order->status) }}</span>
                            </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <p class="text-xs font-medium text-slate-800">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                            <p class="text-[11px] text-slate-400">{{ $order->created_at->diffForHumans() }}</p>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a 
                                    href="{{ route('admin.orders.show', $order) }}" 
                                    class="p-2 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-600 transition" 
                                    title="View Order Details"
                                >
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>

                                @if($order->status === 'paid')
                                <a 
                                    href="{{ route('purchases.download', $order) }}" 
                                    target="_blank"
                                    class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition" 
                                    title="Download E-Book File"
                                >
                                    <i class="fa-solid fa-download text-xs"></i>
                                </a>
                                @endif

                                <form 
                                    action="{{ route('admin.orders.destroy', $order) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Are you sure you want to delete order #{{ $order->transaction_id }}?');" 
                                    class="inline-block"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 transition cursor-pointer" 
                                        title="Delete Order Record"
                                    >
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto text-center space-y-3">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800">No Orders Found</h3>
                                <p class="text-xs text-slate-500">
                                    @if(request('search') || request('status') || request('date_from') || request('date_to'))
                                        No transactions match your current search or filter criteria.
                                    @else
                                        Customer orders and Easebuzz payments will automatically appear here once purchases are made.
                                    @endif
                                </p>
                                @if(request('search') || request('status') || request('date_from') || request('date_to'))
                                <div>
                                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-semibold transition">
                                        <i class="fa-solid fa-rotate-left"></i>
                                        <span>Reset Filters</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/80 bg-slate-50/50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
