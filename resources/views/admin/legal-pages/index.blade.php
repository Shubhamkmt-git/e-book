@extends('admin.layouts.app')

@section('title', 'Legal Pages & Policies')

@section('content')
<div class="w-full space-y-6 max-w-6xl">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Legal Pages</span>
            </nav>
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Legal Pages & Policies</h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Manage, edit, and publish dynamic Privacy Policy and Terms of Service documents for your storefront.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- Cards Grid for Legal Pages --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($pages as $page)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-2xs hover:shadow-md transition-all duration-300 flex flex-col justify-between space-y-5">
            <div class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $page->slug === 'privacy-policy' ? 'bg-sky-50 text-sky-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center text-lg">
                            <i class="{{ $page->slug === 'privacy-policy' ? 'fa-solid fa-shield-halved' : 'fa-solid fa-scale-balanced' }}"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 font-roboto">{{ $page->title }}</h2>
                            <span class="text-xs font-mono text-slate-400">/{{ $page->slug }}</span>
                        </div>
                    </div>

                    <!-- Status Toggle Button -->
                    <button 
                        type="button" 
                        role="switch" 
                        aria-checked="{{ $page->status === 'active' ? 'true' : 'false' }}"
                        onclick="toggleRecordStatus(this, '{{ route('admin.legal-pages.toggle-status', $page->slug) }}')"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $page->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"
                        title="Status: {{ ucfirst($page->status) }} (Click to toggle)"
                    >
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out {{ $page->status === 'active' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                    {{ $page->subtitle ?: 'Legal agreement and guidelines for digital publication customers.' }}
                </p>

                <div class="flex items-center gap-4 text-xs text-slate-400 pt-2 border-t border-slate-100">
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-check text-slate-400"></i>
                        <span>Updated: {{ $page->last_updated_date ? $page->last_updated_date->format('M d, Y') : $page->updated_at->format('M d, Y') }}</span>
                    </span>
                    <span>•</span>
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-file-lines text-slate-400"></i>
                        <span>{{ Str::wordCount(strip_tags($page->content)) }} words</span>
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <a 
                    href="{{ route('admin.legal-pages.edit', $page->slug) }}" 
                    class="flex-1 h-10 inline-flex items-center justify-center gap-2 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-semibold shadow-xs transition"
                >
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    <span>Edit Content</span>
                </a>

                <a 
                    href="{{ $page->slug === 'privacy-policy' ? route('privacy-policy') : route('terms') }}" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="h-10 inline-flex items-center justify-center gap-1.5 px-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition"
                    title="View on Storefront"
                >
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    <span class="hidden sm:inline">Preview</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
