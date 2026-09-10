@extends('admin.layouts.app')

@section('title', 'Edit FAQ')

@section('content')
<div class="w-full space-y-6">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.faqs.index') }}" class="hover:text-brand-600 transition">FAQs</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Edit</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit FAQ</h1>
            <p class="text-xs sm:text-sm text-slate-500">Update the frequently asked question.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.faqs.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Cancel</a>
            <button type="submit" form="faq-form"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <form id="faq-form" action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="space-y-5" novalidate>
        @csrf @method('PUT')

        {{-- Card: FAQ Information --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <h2 class="text-sm font-bold text-slate-800">FAQ Information</h2>
            </div>

            <div class="grid grid-cols-1 gap-6">

                {{-- Question --}}
                <div class="space-y-1.5">
                    <label for="question" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Question <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="question" id="question" value="{{ old('question', $faq->question) }}" required
                        placeholder="e.g. How do I access my purchased e-books?"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('question') border-rose-300 bg-rose-50/50 @enderror">
                    @error('question')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Answer --}}
                <div class="space-y-1.5">
                    <label for="answer" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Answer <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="answer" id="answer" rows="4" required
                        placeholder="Provide the answer here..."
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-y @error('answer') border-rose-300 bg-rose-50/50 @enderror">{{ old('answer', $faq->answer) }}</textarea>
                    @error('answer')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Status --}}
                    <div class="space-y-1.5">
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $faq->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    {{-- Sort Order --}}
                    <div class="space-y-1.5">
                        <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0"
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('sort_order') border-rose-300 bg-rose-50/50 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Lower number = shown first.</p>
                        @error('sort_order')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-between gap-3 pt-1">
            {{-- Danger Zone inline --}}
            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                  onsubmit="return confirm('Delete this FAQ? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl border border-rose-200 text-rose-600 text-sm font-semibold hover:bg-rose-50 transition cursor-pointer flex items-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Delete FAQ
                </button>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.faqs.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-semibold transition">Cancel</a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Save Changes
                </button>
            </div>
        </div>

    </form>

</div>
@endsection
