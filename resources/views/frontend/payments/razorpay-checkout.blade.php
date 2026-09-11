@extends('frontend.layouts.app')

@section('title', 'Razorpay Checkout - ' . $purchase->book_title)

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 bg-slate-950/40 relative">
    <!-- Ambient Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-sky-600/15 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-lg bg-slate-900/90 border border-slate-700/70 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2 border-b border-slate-800 pb-5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/10 border border-sky-500/30 text-sky-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Razorpay Secure Checkout</span>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight font-roboto">Complete Your Purchase</h1>
            <p class="text-xs text-slate-400">Opening the secure payment gateway modal...</p>
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
                <span class="text-xs text-slate-400 font-medium">Order ID:</span>
                <span class="text-xs font-mono font-bold text-sky-300">{{ $purchase->razorpay_order_id ?? $purchase->transaction_id }}</span>
            </div>
            <div class="pt-2 border-t border-slate-700/50 flex items-center justify-between">
                <span class="text-sm font-bold text-slate-200">Total Payable:</span>
                <span class="text-xl font-extrabold text-sky-400 font-roboto">₹{{ number_format((float) $purchase->amount, 2) }}</span>
            </div>
        </div>

        <!-- Action / Fallback Button -->
        <div class="space-y-3">
            <button 
                type="button" 
                id="rzp-button"
                onclick="openRazorpayModal()" 
                class="w-full py-3.5 px-5 rounded-2xl bg-gradient-to-r from-sky-600 to-brand-600 hover:from-sky-500 hover:to-brand-500 text-white text-sm font-bold shadow-lg shadow-sky-950/40 transition transform active:scale-[0.99] flex items-center justify-center gap-2.5 cursor-pointer"
            >
                <i class="fa-solid fa-lock text-sm"></i>
                <span>Pay ₹{{ number_format((float) $purchase->amount, 2) }} with Razorpay</span>
            </button>
        </div>

        <!-- Hidden callback form -->
        <form id="razorpay-callback-form" action="{{ route('payments.razorpay.callback', $purchase) }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>

        <div class="text-center">
            <a href="{{ route('books.show', $purchase->book_identifier) }}" class="text-xs text-slate-500 hover:text-slate-300 transition">
                &larr; Cancel and return to e-book
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const rzpOptions = {
        key: "{{ $razorpayKey }}",
        amount: "{{ (int) round($purchase->amount * 100) }}",
        currency: "INR",
        name: "{{ addslashes($appName) }}",
        description: "{{ addslashes($purchase->book_title) }}",
        image: "{{ $appLogo }}",
        order_id: "{{ $purchase->razorpay_order_id }}",
        handler: function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('razorpay-callback-form').submit();
        },
        prefill: {
            name: "{{ addslashes($purchase->customer?->name ?? 'Customer') }}",
            email: "{{ addslashes($purchase->customer?->email ?? '') }}",
            contact: "{{ addslashes($purchase->customer?->mobile ?? '') }}"
        },
        theme: {
            color: "#0284c7"
        },
        modal: {
            ondismiss: function() {
                console.log('Razorpay modal dismissed.');
            }
        }
    };

    const rzp = new Razorpay(rzpOptions);

    function openRazorpayModal() {
        rzp.open();
    }

    rzp.on('payment.failed', function (response) {
        alert('Payment Failed: ' + (response.error.description || 'Unknown error.'));
        window.location.href = "{{ route('books.show', $purchase->book_identifier) }}?payment_error=" + encodeURIComponent(response.error.description || 'Payment Failed');
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Automatically trigger Razorpay checkout popup
        setTimeout(() => {
            openRazorpayModal();
        }, 300);
    });
</script>
@endpush
