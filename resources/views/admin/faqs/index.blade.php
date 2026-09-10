@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">FAQs</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage Frequently Asked Questions.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.faqs.create') }}"
               class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add FAQ</span>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col sm:flex-row gap-4 items-center justify-between">
        <form method="GET" action="{{ route('admin.faqs.index') }}" class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search FAQs..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            </div>
            <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2 rounded-xl text-slate-500 hover:bg-slate-100 text-sm font-semibold transition flex items-center justify-center">Clear</a>
            @endif
        </form>
    </div>

    {{-- List --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        @if($faqs->count())
            <div class="divide-y divide-slate-100">
                @foreach($faqs as $faq)
                    <div class="p-5 flex flex-col sm:flex-row gap-4 hover:bg-slate-50/50 transition">
                        <div class="flex-1 space-y-1.5">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-sm font-bold text-slate-800">{{ $faq->question }}</h3>
                                <div class="shrink-0 flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold">#{{ $faq->sort_order }}</span>
                                    @if($faq->status === 'active')
                                        <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">Active</span>
                                    @else
                                        <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-500 border border-slate-200 text-[10px] font-bold">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm text-slate-500 line-clamp-2">{{ $faq->answer }}</p>
                        </div>
                        <div class="shrink-0 flex items-center justify-end sm:justify-start gap-2 border-t sm:border-t-0 sm:border-l border-slate-100 pt-3 sm:pt-0 sm:pl-4 mt-2 sm:mt-0">
                            <form method="POST" action="{{ route('admin.faqs.toggle-status', $faq) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="Toggle Status"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center transition cursor-pointer {{ $faq->status === 'active' ? 'text-emerald-600 hover:bg-emerald-50' : 'text-slate-400 hover:bg-slate-100' }}">
                                    <i class="fa-solid fa-power-off"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.faqs.edit', $faq) }}" title="Edit"
                               class="w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 flex items-center justify-center transition cursor-pointer">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Delete"
                                        class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition cursor-pointer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            @if($faqs->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $faqs->links() }}
                </div>
            @endif
        @else
            <div class="p-10 text-center flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center text-2xl mb-3">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">No FAQs found</h3>
                <p class="text-xs text-slate-500 mt-1">Get started by creating a new frequently asked question.</p>
                <a href="{{ route('admin.faqs.create') }}" class="mt-4 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold hover:bg-brand-700 transition">
                    Add FAQ
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
