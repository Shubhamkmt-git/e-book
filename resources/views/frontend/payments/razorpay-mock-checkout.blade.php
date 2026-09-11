@extends('frontend.layouts.app')

@section('title', 'Razorpay Payment Gateway Simulator')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 bg-slate-950/40 relative">
    <!-- Ambient Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-sky-600/15 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-lg bg-slate-900/90 border border-slate-700/70 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
        
        <!-- Simulator Header -->
        <div class="text-center space-y-2 border-b border-slate-800 pb-5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/10 border border-sky-500/30 text-sky-400 text-xs font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                <span>Razorpay Test Simulator</span>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight font-roboto">Razorpay Sandbox Checkout</h1>
            <p class="text-xs text-slate-400">Simulate online payment transactions for local testing &amp; validation.</p>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-4.5 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">E-Book Publication:</span>
                <span class="text-xs font-bold text-white max-w-[200px] truncate text-right">{{ $purchase->book_title }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Customer:</span>
                <span class="text-xs font-semibold text-slate-300">{{ $purchase->customer?->name ?? 'Customer' }} ({{ $purchase->customer?->email ?? '' }})</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Transaction ID:</span>
                <span class="text-xs font-mono font-bold text-sky-300">{{ $purchase->transaction_id }}</span>
            </div>
            <div class="pt-2 border-t border-slate-700/50 flex items-center justify-between">
                <span class="text-sm font-bold text-slate-200">Total Payable:</span>
                <span class="text-xl font-extrabold text-sky-400 font-roboto">₹{{ number_format((float) $purchase->amount, 2) }}</span>
            </div>
        </div>

        <!-- Simulation Actions -->
        <div class="space-y-3">
            <p class="text-xs font-bold text-slate-300 uppercase tracking-wider text-center">Choose Simulation Result</p>

            <!-- Success Simulation Form -->
            <form action="{{ route('payments.razorpay.mock-process', $purchase) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="success">
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-5 rounded-2xl bg-gradient-to-r from-sky-600 to-emerald-600 hover:from-sky-500 hover:to-emerald-500 text-white text-sm font-bold shadow-lg shadow-sky-950/30 transition transform active:scale-[0.99] flex items-center justify-center gap-2.5 cursor-pointer"
                >
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>Simulate Successful Razorpay Payment</span>
                </button>
            </form>

            <!-- Failure Simulation Form -->
            <form action="{{ route('payments.razorpay.mock-process', $purchase) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="failure">
                <button 
                    type="submit" 
                    class="w-full py-3 px-5 rounded-2xl bg-slate-800 hover:bg-rose-950/40 border border-slate-700 hover:border-rose-500/40 text-rose-300 text-xs sm:text-sm font-semibold transition flex items-center justify-center gap-2 cursor-pointer"
                >
                    <i class="fa-solid fa-circle-xmark text-sm text-rose-400"></i>
                    <span>Simulate Failed / Cancelled Razorpay Payment</span>
                </button>
            </form>
        </div>

        <!-- Help Notice -->
        <div class="p-3.5 rounded-xl bg-sky-950/40 border border-sky-800/40 text-xs text-sky-200/80 space-y-1">
            <div class="flex items-center gap-2 font-bold text-sky-300">
                <i class="fa-solid fa-circle-info"></i>
                <span>Razorpay Sandbox Simulation Mode</span>
            </div>
            <p class="text-[11px] leading-relaxed text-slate-400">
                This sandbox simulator verifies the complete Razorpay workflow, webhook signature structures, delivery email dispatch, and admin purchases reporting.
            </p>
        </div>

        <div class="text-center">
            <a href="{{ route('books.show', $purchase->book_identifier) }}" class="text-xs text-slate-500 hover:text-slate-300 transition">
                &larr; Cancel and return to e-book
            </a>
        </div>

    </div>
</div>
@endsection
