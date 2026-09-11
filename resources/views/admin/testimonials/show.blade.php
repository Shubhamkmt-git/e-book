@extends('admin.layouts.app')

@section('title', 'View Testimonial')

@section('content')
<div class="w-full space-y-6 max-w-4xl">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.testimonials.index') }}" class="hover:text-brand-600 transition">Testimonials</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">View</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Testimonial Details</h1>
            <p class="text-sm text-slate-500 mt-0.5">Viewing <span class="font-semibold text-slate-700">{{ $testimonial->name }}</span></p>
        </div>
        <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
           class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
            <i class="fa-solid fa-pen-to-square text-xs"></i>
            <span>Edit Testimonial</span>
        </a>
    </div>

    {{-- Card: Testimonial Preview --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-6 sm:p-8 space-y-6">

        {{-- Person Header --}}
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-lg shrink-0">
                {{ strtoupper(substr($testimonial->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">{{ $testimonial->name }}</h2>
                @if($testimonial->profession)
                    <p class="text-sm text-slate-500">{{ $testimonial->profession }}</p>
                @endif
            </div>
            <div class="ml-auto">
                @if($testimonial->is_active)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[11px] font-bold border border-slate-200">
                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Inactive
                    </span>
                @endif
            </div>
        </div>

        {{-- Rating --}}
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-0.5 text-amber-400">
                @for ($s = 1; $s <= 5; $s++)
                    <i class="fa-{{ $s <= $testimonial->rating ? 'solid' : 'regular' }} fa-star text-sm"></i>
                @endfor
            </div>
            <span class="text-sm text-slate-500 font-medium">{{ $testimonial->rating }}/5</span>
        </div>

        {{-- Message --}}
        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
            <i class="fa-solid fa-quote-left text-slate-200 text-2xl mb-2"></i>
            <p class="text-sm text-slate-700 leading-relaxed">{{ $testimonial->message }}</p>
        </div>

        {{-- Related Book --}}
        @if($testimonial->book)
        <div class="bg-brand-50/60 rounded-xl p-4 border border-brand-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center text-sm shrink-0 shadow-2xs">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Associated Book Feedback</p>
                    <h3 class="text-sm font-bold text-slate-900 truncate">{{ $testimonial->book->title }}</h3>
                    <p class="text-xs text-slate-500">by {{ $testimonial->book->author_name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.books.edit', $testimonial->book) }}" class="px-3 py-1.5 rounded-lg bg-white border border-brand-200 hover:bg-brand-100 text-brand-700 text-xs font-semibold transition">
                    View Book in Admin
                </a>
            </div>
        </div>
        @endif

        {{-- Meta --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Related Book</p>
                <p class="text-sm font-semibold text-slate-700 truncate">{{ $testimonial->book?->title ?: 'None (General)' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sort Order</p>
                <p class="text-sm font-semibold text-slate-700">{{ $testimonial->sort_order }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Created</p>
                <p class="text-sm font-semibold text-slate-700">{{ $testimonial->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Updated</p>
                <p class="text-sm font-semibold text-slate-700">{{ $testimonial->updated_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
