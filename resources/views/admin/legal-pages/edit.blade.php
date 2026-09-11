@extends('admin.layouts.app')

@section('title', 'Edit ' . $page->title)

@push('scripts')
{{-- TinyMCE WYSIWYG Text Editor with API Key from Environment --}}
@php
    $tinyApiKey = config('services.tinymce.key', 'no-api-key');
@endphp
<script src="https://cdn.tiny.cloud/1/{{ $tinyApiKey }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: 'textarea#content',
                height: 520,
                menubar: 'edit insert view format table tools help',
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | ' +
                    'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link table | removeformat | code fullscreen',
                content_style: `
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                        font-size: 15px;
                        line-height: 1.8;
                        color: #1e293b;
                        padding: 16px;
                    }
                    h2 { font-size: 1.6rem; font-weight: 700; color: #0f172a; margin-top: 1.5rem; margin-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
                    h3 { font-size: 1.25rem; font-weight: 600; color: #334155; margin-top: 1.2rem; }
                    p { margin-bottom: 1rem; color: #475569; }
                    ul, ol { margin-bottom: 1rem; padding-left: 1.5rem; color: #475569; }
                    a { color: #7a58a9; text-decoration: underline; }
                    strong { color: #0f172a; }
                `,
                branding: false,
                promotion: false,
                setup: function (editor) {
                    editor.on('change keyup NodeChange', function () {
                        editor.save();
                    });
                }
            });
        }

        const form = document.getElementById('legal-page-form');
        if (form) {
            form.addEventListener('submit', function () {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
            });
        }
    });
</script>
@endpush

@section('content')
<div class="w-full space-y-6 max-w-6xl">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.legal-pages.index') }}" class="hover:text-brand-600 transition">Legal Pages</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Edit {{ $page->title }}</span>
            </nav>
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit {{ $page->title }}</h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Update legal clauses, effective date, and metadata.</p>
        </div>
        
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ $page->slug === 'privacy-policy' ? route('privacy-policy') : route('terms') }}" target="_blank" rel="noopener noreferrer"
               class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>View Live</span>
            </a>
            <button type="submit" form="legal-page-form"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    @if ($errors->any())
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1">
        <div class="font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-0.5 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="legal-page-form" action="{{ route('admin.legal-pages.update', $page->slug) }}" method="POST" class="space-y-6" novalidate>
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Content (Left 2 Columns) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic Details Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-heading text-brand-600 text-xs"></i>
                        <span>Header & Titles</span>
                    </h2>

                    <div>
                        <label for="title" class="block text-xs font-semibold text-slate-700 mb-1.5">Document Title <span class="text-rose-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required
                               placeholder="e.g. Privacy Policy"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800">
                    </div>

                    <div>
                        <label for="subtitle" class="block text-xs font-semibold text-slate-700 mb-1.5">Short Subtitle / Summary</label>
                        <textarea id="subtitle" name="subtitle" rows="2"
                                  placeholder="e.g. How we collect, safeguard, and process your personal data and digital purchases."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800">{{ old('subtitle', $page->subtitle) }}</textarea>
                    </div>
                </div>

                {{-- Document Body Content Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-brand-600 text-xs"></i>
                            <span>Document Body Content</span>
                        </h2>
                    </div>

                    <div>
                        <textarea id="content" name="content" rows="18" required
                                  placeholder="Enter policy content in formatted text..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800 leading-relaxed">{{ old('content', $page->content) }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Sidebar (Right Column) --}}
            <div class="space-y-6">

                {{-- Status & Publish Date Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-toggle-on text-brand-600 text-xs"></i>
                        <span>Publishing Settings</span>
                    </h2>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Visibility Status <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800">
                            <option value="active" {{ old('status', $page->status) === 'active' ? 'selected' : '' }}>Active (Published)</option>
                            <option value="inactive" {{ old('status', $page->status) === 'inactive' ? 'selected' : '' }}>Inactive (Draft / Hidden)</option>
                        </select>
                    </div>

                    <div>
                        <label for="last_updated_date" class="block text-xs font-semibold text-slate-700 mb-1.5">Effective / Last Updated Date</label>
                        <input type="date" id="last_updated_date" name="last_updated_date" 
                               value="{{ old('last_updated_date', $page->last_updated_date ? $page->last_updated_date->format('Y-m-d') : date('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800">
                        <p class="text-[11px] text-slate-400 mt-1">Displayed to users at the top of the policy.</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                        <p><span class="font-semibold text-slate-700">URL Slug:</span> <span class="font-mono text-brand-600">/{{ $page->slug }}</span></p>
                        <p><span class="font-semibold text-slate-700">Created:</span> {{ $page->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                {{-- SEO Metadata Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-brand-600 text-xs"></i>
                        <span>SEO & Metadata</span>
                    </h2>

                    <div>
                        <label for="meta_title" class="block text-xs font-semibold text-slate-700 mb-1.5">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                               placeholder="e.g. Privacy Policy - E-Book Library"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-xs font-semibold text-slate-700 mb-1.5">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                                  placeholder="Short summary for search engines..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection
