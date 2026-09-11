@extends('frontend.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="bg-slate-50 min-h-screen py-10 lg:py-14">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 max-w-6xl">
        
        <!-- Page Header -->
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('home') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-brand-600 hover:border-brand-200 hover:bg-brand-50 transition-all cursor-pointer">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">My Profile</h1>
                <p class="text-slate-500 text-sm mt-1">Manage your account and view your purchase history.</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 mb-2 shadow-sm">
                    <i class="fa-solid fa-circle-check"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Top: Customer Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                    
                    <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start justify-between text-center sm:text-left border-b border-slate-100/80 gap-4">
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <div class="w-20 h-20 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-3xl font-bold border border-brand-100">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">{{ $customer->name }}</h2>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('customer.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:text-brand-600 hover:border-brand-200 hover:bg-brand-50 text-sm font-semibold transition-all">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span class="hidden sm:inline">Edit Profile</span>
                            </a>
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 text-sm font-semibold transition-all cursor-pointer">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span class="hidden sm:inline">Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-around px-6 sm:px-8 pb-8 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <i class="fa-regular fa-envelope text-brand-300 text-lg"></i>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Email</p>
                                <p class="text-slate-700 font-bold text-sm">{{ $customer->email }}</p>
                            </div>
                        </div>
                        @if($customer->mobile)
                        <div class="hidden sm:block w-px h-10 bg-slate-200"></div>
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <i class="fa-solid fa-phone text-brand-300 text-lg"></i>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Mobile</p>
                                <p class="text-slate-700 font-bold text-sm">{{ $customer->mobile }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="hidden sm:block w-px h-10 bg-slate-200"></div>
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <i class="fa-regular fa-calendar text-brand-300 text-lg"></i>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Joined</p>
                                <p class="text-slate-700 font-bold text-sm">{{ $customer->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom: Purchase History -->
            <div>
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/60 shadow-sm">
                    
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-brand-500"></i>
                            Purchase History
                        </h3>
                        <span class="whitespace-nowrap bg-brand-50 text-brand-600 text-xs font-bold px-3 py-1 rounded-full border border-brand-200/50">
                            {{ $purchases->count() }} Orders
                        </span>
                    </div>

                    @if($purchases->isEmpty())
                        <div class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-100 text-slate-300">
                                <i class="fa-solid fa-box-open text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-700 mb-1">No purchases yet</h4>
                            <p class="text-slate-500 text-sm mb-6 max-w-sm mx-auto">You haven't bought any e-books yet. Explore our collection and find your next great read!</p>
                            <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-brand-600 hover:bg-brand-500 text-white text-sm font-semibold transition-all shadow-md shadow-brand-600/20">
                                Browse Books
                            </a>
                        </div>
                    @else
                        <div class="space-y-4 sm:space-y-6">
                            @foreach($purchases as $purchase)
                                @php
                                    $book = $purchase->book;
                                    $bookUrl = $book ? route('books.show', $book->slug ?? $book->id) : '#';
                                    $bookImage = $book ? (str_starts_with($book->image, 'http') ? $book->image : asset($book->image)) : '/images/books/spotlight.jpg';
                                    $bookCategory = $book ? ($book->category->name ?? 'E-Book') : 'E-Book';
                                    $bookAuthor = $book ? $book->author : 'Unknown Author';
                                @endphp
                                <div class="group flex flex-row items-center gap-4 sm:gap-5 p-4 sm:p-5 rounded-2xl border border-slate-200/80 hover:border-brand-300/80 hover:shadow-xl hover:shadow-brand-500/5 hover:-translate-y-0.5 transition-all duration-300 bg-white relative overflow-hidden">
                                    
                                    <!-- Book Cover Thumbnail (10:7 aspect) -->
                                    <a href="{{ $bookUrl }}" class="block w-20 sm:w-28 shrink-0 aspect-[10/7] rounded-xl overflow-hidden bg-slate-950 border border-slate-200/80 shadow-sm relative group-hover:border-brand-200 transition-colors">
                                        <img 
                                            src="{{ $bookImage }}" 
                                            alt="{{ $purchase->book_title }}" 
                                            class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                                            onerror="this.src='/images/books/spotlight.jpg'"
                                        >
                                    </a>

                                    <!-- Purchase Details & Actions -->
                                    <div class="flex flex-col sm:flex-row flex-1 min-w-0 gap-3 sm:gap-4 sm:items-center justify-between">
                                        
                                        <div class="flex flex-col justify-center min-w-0">
                                            <div class="mb-0.5">
                                                <span class="text-[9px] sm:text-[10px] font-bold text-brand-600 uppercase tracking-wider">{{ $bookCategory }}</span>
                                            </div>
                                            <a href="{{ $bookUrl }}" class="text-sm sm:text-lg font-bold text-slate-800 hover:text-brand-600 transition-colors truncate block leading-snug mb-0.5">
                                                {{ $purchase->book_title }}
                                            </a>
                                            <p class="text-[11px] sm:text-xs text-slate-500 mb-2 truncate">{{ $bookAuthor }}</p>

                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] sm:text-xs">
                                                <div class="flex items-center gap-1 text-slate-600">
                                                    <span class="font-brand font-bold text-xs sm:text-sm">₹{{ number_format($purchase->amount, 2) }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-slate-500">
                                                    <i class="fa-regular fa-calendar text-slate-400 text-[10px] sm:text-xs"></i>
                                                    {{ $purchase->created_at->format('d M Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right side: Status and Action -->
                                        <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center shrink-0 gap-3">
                                            @if($purchase->status === 'success' || $purchase->status === 'paid')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md bg-emerald-50 text-emerald-600 text-[9px] sm:text-[10px] font-bold border border-emerald-200/60 uppercase tracking-wider shadow-sm">
                                                    <i class="fa-solid fa-check-circle text-[8px] sm:text-[9px]"></i> Success
                                                </span>
                                                <a href="{{ route('purchases.download', $purchase->id) }}" class="inline-flex items-center justify-center gap-1.5 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-brand-50 hover:bg-brand-600 text-brand-700 hover:text-white border border-brand-200/60 hover:border-brand-600 text-xs sm:text-sm font-semibold transition-all group/btn shadow-sm">
                                                    <i class="fa-solid fa-cloud-arrow-down group-hover/btn:animate-bounce"></i>
                                                    <span class="hidden sm:inline">Download</span>
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md bg-amber-50 text-amber-600 text-[9px] sm:text-[10px] font-bold border border-amber-200/60 uppercase tracking-wider shadow-sm">
                                                    <i class="fa-solid fa-clock text-[8px] sm:text-[9px]"></i> {{ ucfirst($purchase->status) }}
                                                </span>
                                                <button disabled class="inline-flex items-center justify-center gap-1.5 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-slate-50 text-slate-400 border border-slate-200/60 text-xs sm:text-sm font-semibold cursor-not-allowed">
                                                    <i class="fa-solid fa-lock"></i>
                                                    <span class="hidden sm:inline">Locked</span>
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                    
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
