@extends('admin.layouts.app')

@section('title', 'Hero Banners')

@section('content')
<div class="space-y-6">

    {{-- Top Header --}}
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
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.hero-banners.create') }}"
               class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Banner</span>
            </a>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.hero-banners.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by title…"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-search text-xs"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.hero-banners.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        @if($banners->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">#</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Image</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Title</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Buttons</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Order</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Created</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($banners as $banner)
                        <tr class="hover:bg-slate-50/70 transition group">
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">{{ $loop->iteration + ($banners->currentPage() - 1) * $banners->perPage() }}</td>

                            {{-- Banner Image Thumbnail --}}
                            <td class="px-5 py-4">
                                @if($banner->banner_image_url)
                                    <img src="{{ $banner->banner_image_url }}" alt="{{ $banner->title }}"
                                         class="w-20 h-12 rounded-lg object-cover border border-slate-200">
                                @else
                                    <div class="w-20 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-image text-sm"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Title --}}
                            <td class="px-5 py-4">
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 truncate max-w-[220px]">{{ $banner->title }}</p>
                                    @if($banner->title_l2)
                                        <p class="text-xs text-indigo-600 font-medium truncate max-w-[220px] mt-0.5">{{ $banner->title_l2 }}</p>
                                    @endif
                                </div>
                            </td>

                            {{-- Buttons --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($banner->primary_button)
                                        <span class="px-2 py-0.5 rounded-md bg-brand-50 text-brand-700 text-[11px] font-semibold border border-brand-200">{{ $banner->primary_button }}</span>
                                    @endif
                                    @if($banner->secondary_button)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-50 text-slate-600 text-[11px] font-semibold border border-slate-200">{{ $banner->secondary_button }}</span>
                                    @endif
                                    @if(!$banner->primary_button && !$banner->secondary_button)
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Sort Order --}}
                            <td class="px-5 py-4 text-slate-600 text-sm font-mono">{{ $banner->sort_order ?: '—' }}</td>

                            {{-- Status Toggle --}}
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.hero-banners.toggle-status', $banner) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="{{ $banner->is_active ? 'Deactivate' : 'Activate' }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition cursor-pointer
                                                   {{ $banner->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                        <i class="fa-solid {{ $banner->is_active ? 'fa-circle-check text-[10px]' : 'fa-circle-xmark text-[10px]' }}"></i>
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>

                            {{-- Created --}}
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $banner->created_at->format('d M Y') }}</td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.hero-banners.show', $banner) }}"
                                       title="View"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-500 flex items-center justify-center transition text-xs">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hero-banners.edit', $banner) }}"
                                       title="Edit"
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
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($banners->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between gap-4">
                <p class="text-xs text-slate-500">Showing {{ $banners->firstItem() }}–{{ $banners->lastItem() }} of {{ $banners->total() }} banners</p>
                {{ $banners->links() }}
            </div>
            @endif
        @else
            <div class="py-20 flex flex-col items-center text-center gap-3">
                <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-400 flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-image"></i>
                </div>
                <p class="text-base font-semibold text-slate-700">No hero banners found</p>
                <p class="text-sm text-slate-400">
                    @if(request('search'))
                        No banners match your search. <a href="{{ route('admin.hero-banners.index') }}" class="text-brand-600 hover:underline">Clear search</a>
                    @else
                        Create your first hero banner to display on the homepage.
                    @endif
                </p>
                <a href="{{ route('admin.hero-banners.create') }}"
                   class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus text-xs"></i> Add Banner
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
