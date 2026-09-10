@extends('admin.layouts.app')

@section('title', 'Hero Banners')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-image"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Hero Banners</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage homepage hero banners – images, titles, and CTA buttons.</p>
        </div>
        <a href="{{ route('admin.hero-banners.create') }}"
           class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add Banner</span>
        </a>
    </div>

    {{-- Grid --}}
    @if($banners->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($banners as $banner)
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden flex flex-col group">

            {{-- Banner Image --}}
            <div class="relative h-44 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden">
                @if($banner->banner_image_url)
                    <img src="{{ $banner->banner_image_url }}" alt="{{ $banner->title }}"
                         class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-2">
                        <i class="fa-solid fa-image text-3xl"></i>
                        <span class="text-xs font-medium">No Image</span>
                    </div>
                @endif

                {{-- Status Badge --}}
                <div class="absolute top-3 right-3">
                    @if($banner->is_active)
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[11px] font-bold shadow-sm">Active</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full bg-slate-400 text-white text-[11px] font-bold shadow-sm">Inactive</span>
                    @endif
                </div>

                {{-- Sort Order --}}
                <div class="absolute top-3 left-3">
                    <span class="px-2 py-1 rounded-lg bg-black/50 text-white text-[11px] font-bold backdrop-blur-xs">#{{ $banner->sort_order ?: '—' }}</span>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-4 flex flex-col flex-1 gap-2">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base leading-snug line-clamp-1">{{ $banner->title }}</h3>
                    @if($banner->title_l2)
                        <p class="text-sm font-semibold text-indigo-600 mt-0.5 line-clamp-1">{{ $banner->title_l2 }}</p>
                    @endif
                    @if($banner->description)
                        <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">{{ $banner->description }}</p>
                    @endif
                </div>

                {{-- Buttons Preview --}}
                @if($banner->primary_button || $banner->secondary_button)
                <div class="flex flex-wrap gap-1.5 mt-1">
                    @if($banner->primary_button)
                        <span class="px-2.5 py-1 rounded-lg bg-brand-600 text-white text-[11px] font-semibold">{{ $banner->primary_button }}</span>
                    @endif
                    @if($banner->secondary_button)
                        <span class="px-2.5 py-1 rounded-lg border border-slate-300 text-slate-600 text-[11px] font-semibold">{{ $banner->secondary_button }}</span>
                    @endif
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="px-4 pb-4 pt-1 flex items-center justify-between gap-2 border-t border-slate-100 mt-auto pt-3">
                {{-- Toggle --}}
                <form method="POST" action="{{ route('admin.hero-banners.toggle-status', $banner) }}">
                    @csrf @method('PATCH')
                    <button type="submit" title="{{ $banner->is_active ? 'Deactivate' : 'Activate' }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer
                                   {{ $banner->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                        <i class="fa-solid {{ $banner->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-sm"></i>
                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </form>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.hero-banners.show', $banner) }}" title="View"
                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-500 flex items-center justify-center transition text-xs">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.hero-banners.edit', $banner) }}" title="Edit"
                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-500 flex items-center justify-center transition text-xs">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.hero-banners.destroy', $banner) }}"
                          onsubmit="return confirm('Delete banner \'{{ addslashes($banner->title) }}\'?')">
                        @csrf @method('DELETE')
                        <button type="submit" title="Delete"
                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 flex items-center justify-center transition text-xs cursor-pointer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($banners->hasPages())
    <div class="flex justify-center">
        {{ $banners->links() }}
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs py-20 flex flex-col items-center text-center gap-3">
        <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-400 flex items-center justify-center text-2xl">
            <i class="fa-solid fa-image"></i>
        </div>
        <p class="text-base font-semibold text-slate-700">No hero banners yet</p>
        <p class="text-sm text-slate-400">Create your first hero banner to display on the homepage.</p>
        <a href="{{ route('admin.hero-banners.create') }}"
           class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition">
            <i class="fa-solid fa-plus text-xs"></i> Add Banner
        </a>
    </div>
    @endif

</div>
@endsection
