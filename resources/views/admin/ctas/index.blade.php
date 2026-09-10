@extends('admin.layouts.app')

@section('title', 'CTAs')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">CTAs</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage Call to Action banners.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ctas.create') }}"
               class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add CTA</span>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col sm:flex-row gap-4 items-center justify-between">
        <form method="GET" action="{{ route('admin.ctas.index') }}" class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search CTAs..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            </div>
            <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.ctas.index') }}" class="px-4 py-2 rounded-xl text-slate-500 hover:bg-slate-100 text-sm font-semibold transition flex items-center justify-center">Clear</a>
            @endif
        </form>
    </div>

    {{-- List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ctas as $cta)
            <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs flex flex-col hover:shadow-md transition duration-300">
                {{-- Image --}}
                <div class="h-40 bg-slate-100 relative group overflow-hidden">
                    @if($cta->bg_image)
                        <img src="{{ $cta->bg_image_url }}" alt="CTA Background" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-slate-300">
                            <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    <div class="absolute bottom-3 left-3 flex gap-2">
                        @if($cta->status === 'active')
                            <span class="px-2 py-1 rounded-lg bg-emerald-500/90 text-white text-[10px] font-bold shadow-sm backdrop-blur-sm">Active</span>
                        @else
                            <span class="px-2 py-1 rounded-lg bg-slate-500/90 text-white text-[10px] font-bold shadow-sm backdrop-blur-sm">Inactive</span>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-5 flex-1 flex flex-col">
                    @if($cta->label)
                        <span class="inline-block px-2 py-1 rounded-md bg-brand-50 text-brand-700 text-[10px] font-bold uppercase tracking-wider mb-2 self-start">{{ $cta->label }}</span>
                    @endif
                    
                    <h3 class="font-bold text-slate-800 text-lg leading-tight line-clamp-1 mb-1" title="{{ $cta->title }}">
                        {{ $cta->title ?: 'No Title' }}
                    </h3>
                    
                    <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-1">
                        {{ $cta->subtitle ?: 'No subtitle provided.' }}
                    </p>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                        <span class="text-xs font-bold text-slate-400">Order: {{ $cta->sort_order }}</span>
                        <div class="flex items-center gap-1.5">
                            <form method="POST" action="{{ route('admin.ctas.toggle-status', $cta) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $cta->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center transition cursor-pointer {{ $cta->status === 'active' ? 'text-emerald-600 hover:bg-emerald-50' : 'text-slate-400 hover:bg-slate-100' }}">
                                    <i class="fa-solid fa-power-off text-sm"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.ctas.edit', $cta) }}" title="Edit CTA"
                               class="w-8 h-8 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 flex items-center justify-center transition cursor-pointer">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.ctas.destroy', $cta) }}" onsubmit="return confirm('Delete this CTA permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Delete CTA"
                                        class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition cursor-pointer">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-slate-200/90 rounded-2xl p-10 text-center shadow-2xs flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center text-2xl mb-3">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">No CTAs found</h3>
                <p class="text-xs text-slate-500 mt-1">Get started by creating your first Call to Action banner.</p>
                <a href="{{ route('admin.ctas.create') }}" class="mt-4 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold hover:bg-brand-700 transition">
                    Add CTA
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($ctas->hasPages())
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            {{ $ctas->links() }}
        </div>
    @endif
</div>
@endsection
