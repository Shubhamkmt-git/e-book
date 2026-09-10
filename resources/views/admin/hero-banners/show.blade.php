@extends('admin.layouts.app')

@section('title', 'Hero Banner Details')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.hero-banners.index') }}"
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-roboto">Banner Details</h1>
                <p class="text-sm text-slate-500 mt-0.5">Viewing <span class="font-semibold text-slate-700">{{ $heroBanner->title }}</span></p>
            </div>
        </div>
        <a href="{{ route('admin.hero-banners.edit', $heroBanner) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition">
            <i class="fa-solid fa-pen-to-square text-xs"></i> Edit
        </a>
    </div>

    {{-- Image Preview --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        @if($heroBanner->banner_image_url)
            <img src="{{ $heroBanner->banner_image_url }}" alt="{{ $heroBanner->title }}"
                 class="w-full object-cover" style="max-height:340px;">
        @else
            <div class="h-44 flex flex-col items-center justify-center gap-3 bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                <i class="fa-solid fa-image text-4xl"></i>
                <p class="text-sm font-medium">No image uploaded</p>
            </div>
        @endif

        {{-- Overlay Preview --}}
        <div class="p-6 bg-gradient-to-br from-slate-900/90 to-indigo-900/80 -mt-1">
            <div class="max-w-lg">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight">{{ $heroBanner->title }}</h2>
                @if($heroBanner->title_l2)
                    <p class="text-lg font-semibold text-indigo-300 mt-1">{{ $heroBanner->title_l2 }}</p>
                @endif
                @if($heroBanner->description)
                    <p class="text-sm text-slate-300 mt-3 leading-relaxed">{{ $heroBanner->description }}</p>
                @endif
                @if($heroBanner->primary_button || $heroBanner->secondary_button)
                    <div class="flex flex-wrap gap-3 mt-5">
                        @if($heroBanner->primary_button)
                            <a href="{{ $heroBanner->primary_button_link ?? '#' }}"
                               class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition">
                                {{ $heroBanner->primary_button }}
                            </a>
                        @endif
                        @if($heroBanner->secondary_button)
                            <a href="{{ $heroBanner->secondary_button_link ?? '#' }}"
                               class="px-5 py-2.5 rounded-xl border-2 border-white/40 text-white text-sm font-semibold hover:bg-white/10 transition">
                                {{ $heroBanner->secondary_button }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
        <h2 class="text-sm font-bold text-slate-700 mb-5 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-indigo-500"></i> Banner Info
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status</p>
                @if($heroBanner->is_active)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-xs font-bold border border-slate-200">
                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Inactive
                    </span>
                @endif
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sort Order</p>
                <p class="text-sm font-semibold text-slate-700">{{ $heroBanner->sort_order }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Primary Button</p>
                <p class="text-sm font-semibold text-slate-700">{{ $heroBanner->primary_button ?: '—' }}</p>
                @if($heroBanner->primary_button_link)
                    <a href="{{ $heroBanner->primary_button_link }}" target="_blank"
                       class="text-xs text-brand-600 hover:underline break-all">{{ $heroBanner->primary_button_link }}</a>
                @endif
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Secondary Button</p>
                <p class="text-sm font-semibold text-slate-700">{{ $heroBanner->secondary_button ?: '—' }}</p>
                @if($heroBanner->secondary_button_link)
                    <a href="{{ $heroBanner->secondary_button_link }}" target="_blank"
                       class="text-xs text-brand-600 hover:underline break-all">{{ $heroBanner->secondary_button_link }}</a>
                @endif
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Created</p>
                <p class="text-sm font-semibold text-slate-700">{{ $heroBanner->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Last Updated</p>
                <p class="text-sm font-semibold text-slate-700">{{ $heroBanner->updated_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

    {{-- Toggle Status --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-slate-700">Banner Visibility</p>
            <p class="text-xs text-slate-400 mt-0.5">Toggle whether this banner is shown on the site.</p>
        </div>
        <form method="POST" action="{{ route('admin.hero-banners.toggle-status', $heroBanner) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition cursor-pointer
                           {{ $heroBanner->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200' }}">
                <i class="fa-solid {{ $heroBanner->is_active ? 'fa-toggle-on text-emerald-600' : 'fa-toggle-off' }} text-lg"></i>
                {{ $heroBanner->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-2xs">
        <p class="text-sm font-bold text-rose-700 mb-1">Danger Zone</p>
        <p class="text-xs text-slate-500 mb-4">Permanently delete this hero banner and its image. This cannot be undone.</p>
        <form method="POST" action="{{ route('admin.hero-banners.destroy', $heroBanner) }}"
              onsubmit="return confirm('Delete this banner permanently?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-trash text-xs"></i> Delete Banner
            </button>
        </form>
    </div>

</div>
@endsection
