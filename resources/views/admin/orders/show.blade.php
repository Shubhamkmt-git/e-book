@extends('admin.layouts.app')

@section('title', 'Order #' . $order->id . ' - ' . $order->transaction_id)

@section('content')
<div class="space-y-6 max-w-6xl">

    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('admin.orders.index') }}" class="hover:text-slate-600 transition">Orders</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-600">Order #{{ $order->id }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Order #{{ $order->id }}</h1>
                @php
                    $status = strtolower($order->status);
                @endphp
                @if($status === 'paid' || $status === 'success')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>Paid</span>
                </span>
                @elseif($status === 'pending')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <i class="fa-solid fa-clock text-[11px]"></i>
                    <span>Pending</span>
                </span>
                @elseif($status === 'failed')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <i class="fa-solid fa-circle-xmark text-[11px]"></i>
                    <span>Failed</span>
                </span>
                @elseif($status === 'refunded')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                    <span>Refunded</span>
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    <span>{{ ucfirst($order->status) }}</span>
                </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Placed on {{ $order->created_at->format('d F Y \a\t h:i A') }} ({{ $order->created_at->diffForHumans() }})
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Orders</span>
            </a>
            @if($order->status === 'paid')
            <a href="{{ route('purchases.download', $order) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold transition shadow-sm">
                <i class="fa-solid fa-download text-xs"></i>
                <span>Download E-Book</span>
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Order & Payment & Book Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Transaction & Payment Summary Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <h2 class="font-bold text-slate-900 text-sm uppercase tracking-wide">Transaction & Payment Details</h2>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700">Easebuzz Gateway</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Transaction ID (TxnID)</span>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-slate-800 text-sm break-all">{{ $order->transaction_id }}</span>
                            <button 
                                type="button" 
                                onclick="navigator.clipboard.writeText('{{ $order->transaction_id }}'); alert('TxnID Copied!');" 
                                class="text-slate-400 hover:text-brand-600 transition"
                                title="Copy"
                            >
                                <i class="fa-regular fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Total Amount Paid</span>
                        <p class="font-extrabold text-slate-900 text-xl font-roboto">₹{{ number_format((float) $order->amount, 2) }}</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Gateway Status</span>
                        <p class="font-bold text-slate-800 text-sm uppercase tracking-wide">{{ $order->status }}</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Created Date & Time</span>
                        <p class="font-semibold text-slate-800 text-xs">{{ $order->created_at->format('d M Y, h:i:s A') }}</p>
                    </div>
                </div>

                <!-- Update Status Form -->
                <div class="pt-4 border-t border-slate-100">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex flex-col sm:flex-row sm:items-center gap-3">
                        @csrf
                        @method('PATCH')
                        <label for="order_status" class="text-xs font-bold text-slate-700 whitespace-nowrap">
                            Change Order Status:
                        </label>
                        <select 
                            id="order_status" 
                            name="status" 
                            class="px-3 py-2 rounded-xl bg-slate-50 text-slate-800 text-xs font-semibold border border-slate-200 focus:border-brand-500 focus:outline-none transition cursor-pointer"
                        >
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid (Successful)</option>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ $order->status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button 
                            type="submit" 
                            class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition cursor-pointer"
                        >
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- E-Book Item Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-book-bookmark"></i>
                        </div>
                        <h2 class="font-bold text-slate-900 text-sm uppercase tracking-wide">Purchased E-Book Item</h2>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start gap-4">
                    @if($book && $book->cover_image)
                    <img src="{{ $book->cover_image_url }}" alt="{{ $order->book_title }}" class="w-20 h-28 object-cover rounded-xl shadow-md border border-slate-200 shrink-0">
                    @else
                    <div class="w-20 h-28 rounded-xl bg-slate-100 text-slate-400 flex flex-col items-center justify-center gap-1 border border-slate-200 shrink-0">
                        <i class="fa-solid fa-book text-xl"></i>
                        <span class="text-[10px] font-semibold">E-Book</span>
                    </div>
                    @endif

                    <div class="space-y-2 flex-1">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-snug">{{ $order->book_title }}</h3>
                            @if($book)
                            <p class="text-xs text-slate-500 mt-0.5">By <span class="font-semibold text-slate-700">{{ $book->author_name }}</span> &bull; Category: <span class="font-semibold text-slate-700">{{ $book->category?->title ?? 'General' }}</span></p>
                            @endif
                        </div>

                        @if($book && $book->description)
                        <p class="text-xs text-slate-600 line-clamp-2">{{ Str::limit(strip_tags($book->description), 140) }}</p>
                        @endif

                        <div class="flex items-center gap-3 pt-1">
                            @if($book)
                            <a href="{{ route('admin.books.edit', $book) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 hover:text-brand-700 transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit E-Book in Catalog</span>
                            </a>
                            @endif
                            <a href="{{ route('books.show', $order->book_identifier) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800 transition">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                <span>View Public Page</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Easebuzz Raw Payload & Gateway Response -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <h2 class="font-bold text-slate-900 text-sm uppercase tracking-wide">Gateway Response Payload (Easebuzz)</h2>
                    </div>
                    <span class="text-[11px] text-slate-400 font-mono">JSON</span>
                </div>

                @if(!empty($order->gateway_response) && is_array($order->gateway_response))
                <div class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono overflow-x-auto max-h-80 border border-slate-800">
                    <pre>{{ json_encode($order->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
                @else
                <p class="text-xs text-slate-400 italic py-2">No raw gateway response callback recorded for this transaction.</p>
                @endif
            </div>

        </div>

        <!-- Right 1 Col: Customer Information & Delivery Summary -->
        <div class="space-y-6">

            <!-- Customer Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h2 class="font-bold text-slate-900 text-sm uppercase tracking-wide">Customer Details</h2>
                    </div>
                </div>

                @if($order->customer)
                <div class="space-y-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-extrabold text-base border border-brand-200">
                            {{ strtoupper(substr($order->customer->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-900 text-sm truncate">{{ $order->customer->name }}</h4>
                            <p class="text-xs text-slate-500 truncate">{{ $order->customer->email }}</p>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100 text-xs pt-1">
                        <div class="py-2 flex justify-between">
                            <span class="text-slate-400">Customer ID:</span>
                            <span class="font-mono font-bold text-slate-700">#{{ $order->customer->id }}</span>
                        </div>
                        <div class="py-2 flex justify-between">
                            <span class="text-slate-400">Phone:</span>
                            <span class="font-semibold text-slate-700">{{ $order->customer->mobile ?? 'Not provided' }}</span>
                        </div>
                        <div class="py-2 flex justify-between">
                            <span class="text-slate-400">Joined:</span>
                            <span class="font-medium text-slate-700">{{ $order->customer->created_at?->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('admin.customers.show', $order->customer) }}" class="block text-center py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs transition">
                            View Customer Profile &rarr;
                        </a>
                    </div>
                </div>
                @else
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center">
                    <p class="text-xs text-slate-400 italic">Customer account has been removed.</p>
                </div>
                @endif
            </div>

            <!-- Delivery & Fulfillment Summary Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h2 class="font-bold text-slate-900 text-sm uppercase tracking-wide">Fulfillment & Access</h2>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-start gap-3 p-3 rounded-xl {{ $order->status === 'paid' ? 'bg-emerald-50/70 border border-emerald-100' : 'bg-slate-50 border border-slate-100' }}">
                        <i class="fa-solid {{ $order->status === 'paid' ? 'fa-circle-check text-emerald-600' : 'fa-clock text-slate-400' }} mt-0.5 text-sm"></i>
                        <div>
                            <p class="font-bold text-slate-800">Digital Access: {{ $order->status === 'paid' ? 'Active & Unlocked' : 'Locked' }}</p>
                            <p class="text-slate-500 mt-0.5 text-[11px]">
                                {{ $order->status === 'paid' ? 'Customer has direct access to download the full publication.' : 'Payment has not been finalized yet.' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <i class="fa-solid fa-envelope text-brand-600 mt-0.5 text-sm"></i>
                        <div>
                            <p class="font-bold text-slate-800">Automated Email Delivery</p>
                            <p class="text-slate-500 mt-0.5 text-[11px]">
                                When verified paid, order confirmation and e-book download email is sent to customer's inbox.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone: Delete Order -->
            <div class="bg-white border border-rose-200/80 rounded-2xl p-5 shadow-2xs space-y-3">
                <h3 class="font-bold text-rose-700 text-xs uppercase tracking-wider">Danger Zone</h3>
                <p class="text-xs text-slate-500">Permanently delete this order and transaction record from the database.</p>
                <form 
                    action="{{ route('admin.orders.destroy', $order) }}" 
                    method="POST" 
                    onsubmit="return confirm('Are you sure you want to permanently delete order #{{ $order->transaction_id }}?');"
                >
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="w-full py-2 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-bold text-xs transition cursor-pointer"
                    >
                        <i class="fa-solid fa-trash-can mr-1.5"></i>
                        Delete Order
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
