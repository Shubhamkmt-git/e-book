@extends('frontend.layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' • ' . (isset($appSetting) && $appSetting ? $appSetting->app_name : config('app.name', 'E-Book')))

@push('styles')
<style>
    .legal-body {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.85;
    }
    .legal-body h1, .legal-body h2, .legal-body h3, .legal-body h4 {
        color: #0f172a;
        font-weight: 700;
        letter-spacing: -0.015em;
    }
    .legal-body h2 {
        font-size: 1.35rem;
        margin-top: 2.25rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .legal-body h2:first-of-type {
        margin-top: 0;
    }
    .legal-body h3 {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1e293b;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .legal-body p {
        margin-bottom: 1.15rem;
    }
    .legal-body ul, .legal-body ol {
        margin-bottom: 1.25rem;
        padding-left: 1.25rem;
    }
    .legal-body ul {
        list-style-type: disc;
    }
    .legal-body ol {
        list-style-type: decimal;
    }
    .legal-body li {
        margin-bottom: 0.4rem;
    }
    .legal-body strong {
        color: #0f172a;
        font-weight: 600;
    }
    .legal-body a {
        color: #7a58a9;
        font-weight: 500;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.15s ease;
    }
    .legal-body a:hover {
        color: #563c78;
    }
    .legal-body blockquote {
        border-left: 3px solid #7a58a9;
        background-color: #faf5ff;
        border-radius: 0 0.5rem 0.5rem 0;
        padding: 0.75rem 1rem;
        color: #4b5563;
        margin: 1.25rem 0;
        font-size: 0.925rem;
    }
    .legal-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.9rem;
    }
    .legal-body th, .legal-body td {
        border: 1px solid #e2e8f0;
        padding: 0.6rem 0.85rem;
        text-align: left;
    }
    .legal-body th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #0f172a;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-white py-10 sm:py-14">
    <div class="w-[92%] sm:w-[86%] max-w-3xl mx-auto space-y-8">

        {{-- Minimal Header --}}
        <div class="space-y-3 pb-6 border-b border-slate-200/80">
            <h1 class="text-3xl sm:text-4xl lg:text-[2.65rem] font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $page->title }}
            </h1>

            @if($page->subtitle)
                <p class="text-sm sm:text-base text-slate-500 font-normal leading-relaxed">
                    {{ $page->subtitle }}
                </p>
            @endif

            <div class="pt-1 text-xs text-slate-400 font-medium flex items-center gap-1.5">
                <i class="fa-regular fa-calendar text-slate-400"></i>
                <span>Last updated {{ $page->last_updated_date ? $page->last_updated_date->format('F d, Y') : $page->updated_at->format('F d, Y') }}</span>
            </div>
        </div>

        {{-- Minimal Clean Article Content (Cardless / Borderless) --}}
        <article class="legal-body">
            {!! $page->content !!}
        </article>

        {{-- Minimal Support Note at the Bottom --}}
        @php
            $supportEmail = (isset($appSetting) && $appSetting?->contact_email) ? $appSetting->contact_email : config('mail.from.address', 'support@ebook.test');
        @endphp
        <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                <span class="font-semibold text-slate-700">Questions about our policies?</span>
                <span class="ml-1 text-slate-500">Contact our support team anytime.</span>
            </div>
            <div>
                <a href="mailto:{{ $supportEmail }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-700 text-slate-700 font-semibold transition">
                    <i class="fa-regular fa-envelope text-[11px]"></i>
                    <span>{{ $supportEmail }}</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection




