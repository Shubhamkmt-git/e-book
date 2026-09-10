@extends('admin.layouts.app')

@section('title', $category->title . ' - Category')

@section('content')
<div class="w-full space-y-6 max-w-2xl mx-auto">

    <!-- Breadcrumb -->
    <div class="space-y-1">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-brand-600 transition">Categories</a>
            <span class="text-slate-300">/</span>
            <span class="text-brand-600 font-semibold">{{ $category->title }}</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">{{ $category->title }}</h1>
    </div>

    <!-- Details Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-3xl shadow-sm">
                @if($category->icon)
                    <i class="{{ $category->icon }}"></i>
                @else
                    <i class="fa-solid fa-layer-group"></i>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $category->title }}</h2>
                <span class="font-mono text-xs text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $category->slug }}</span>
            </div>
        </div>

        @if($category->description)
        <div class="pt-4 border-t border-slate-100">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Description</p>
            <p class="text-sm text-slate-700 leading-relaxed">{{ $category->description }}</p>
        </div>
        @endif

        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</p>
                @if($category->status === 'active')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Active</span>
                @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 text-xs font-bold"><span class="w-1.5 h-1.5 rounded-full bg-slate-400 inline-block"></span>Inactive</span>
                @endif
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Featured</p>
                @if($category->is_featured)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 border border-amber-200 text-xs font-bold"><i class="fa-solid fa-star text-[10px]"></i> Featured</span>
                @else
                <span class="text-sm text-slate-400">Not Featured</span>
                @endif
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Icon Class</p>
                <span class="font-mono text-xs text-slate-600">{{ $category->icon ?? '—' }}</span>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Created</p>
                <span class="text-sm text-slate-700 font-medium">{{ $category->created_at?->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Back to List</a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-regular fa-pen-to-square text-xs mr-1"></i> Edit Category
            </a>
        </div>
    </div>

</div>
@endsection
