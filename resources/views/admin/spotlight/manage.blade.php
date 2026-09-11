@extends('admin.layouts.app')

@section('title', 'Book of the Week')

@section('content')
<div class="w-full space-y-6 max-w-6xl">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Book of the Week</span>
            </nav>
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Book of the Week</h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Select and spotlight a featured e-book on the homepage with custom highlights and badges.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button type="submit" form="spotlight-form"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <form id="spotlight-form" action="{{ route('admin.spotlight.update') }}" method="POST" class="space-y-6" novalidate>
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left 2 Cols: Main Configuration --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Card 1: Book Selection --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-book-bookmark"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Select E-Book</h2>
                            <p class="text-xs text-slate-400">Choose which active e-book to showcase in the Book of the Week spotlight.</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="book_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Choose E-Book from Catalog <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="book_id" 
                            id="book_id" 
                            onchange="updateBookPreview(this)"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer @error('book_id') border-rose-300 bg-rose-50/50 @enderror"
                        >
                            <option value="">-- Select an E-Book --</option>
                            @foreach($books as $book)
                                <option 
                                    value="{{ $book->id }}" 
                                    {{ old('book_id', $spotlight->book_id) == $book->id ? 'selected' : '' }}
                                    data-title="{{ $book->title }}"
                                    data-author="{{ $book->author_name }}"
                                    data-category="{{ $book->category?->title ?? 'General' }}"
                                    data-price="₹{{ number_format((float) $book->selling_price, 0) }}"
                                    data-cover="{{ $book->cover_image ? $book->cover_image_url : asset('images/books/spotlight.jpg') }}"
                                    data-desc="{{ Str::limit(strip_tags($book->description), 140) }}"
                                >
                                    {{ $book->title }} &bull; By {{ $book->author_name }} (₹{{ number_format((float) $book->selling_price, 0) }})
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Card 2: Section Customization & Overrides --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Spotlight Header &amp; Content Customization</h2>
                            <p class="text-xs text-slate-400">Customize labels, headings, and description or leave blank to use the book's defaults.</p>
                        </div>
                    </div>

                    {{-- Badge Text --}}
                    <div class="space-y-1.5">
                        <label for="badge_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Top Badge / Ribbon Text</label>
                        <input type="text" name="badge_text" id="badge_text" value="{{ old('badge_text', $spotlight->badge_text) }}"
                            placeholder="e.g. Book of the Week, Editor's Choice, Featured Read"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('badge_text') border-rose-300 bg-rose-50/50 @enderror">
                        @error('badge_text')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    {{-- Custom Title --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="custom_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Custom Title (Optional)</label>
                            <span class="text-[11px] text-slate-400">Leave blank to use selected book title</span>
                        </div>
                        <input type="text" name="custom_title" id="custom_title" value="{{ old('custom_title', $spotlight->custom_title) }}"
                            placeholder="Defaults to book title if empty"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('custom_title') border-rose-300 bg-rose-50/50 @enderror">
                        @error('custom_title')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    {{-- Custom Description --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="custom_description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Custom Highlight Description (Optional)</label>
                            <span class="text-[11px] text-slate-400">Leave blank to use book description</span>
                        </div>
                        <textarea name="custom_description" id="custom_description" rows="3"
                            placeholder="Defaults to selected book's synopsis if left empty..."
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('custom_description') border-rose-300 bg-rose-50/50 @enderror">{{ old('custom_description', $spotlight->custom_description) }}</textarea>
                        @error('custom_description')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    {{-- Feature Tags --}}
                    <div class="space-y-1.5">
                        <label for="feature_tags" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Feature Badges / Tags (Comma separated)</label>
                        <input type="text" name="feature_tags" id="feature_tags" 
                            value="{{ old('feature_tags', is_array($spotlight->feature_tags) ? implode(', ', $spotlight->feature_tags) : $spotlight->feature_tags) }}"
                            placeholder="e.g. Instant Download, Lifetime Access, DRM-Free Edition"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('feature_tags') border-rose-300 bg-rose-50/50 @enderror">
                        <p class="text-[11px] text-slate-400">Separate multiple feature tags with commas.</p>
                        @error('feature_tags')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    {{-- Button Text --}}
                    <div class="space-y-1.5">
                        <label for="button_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700">CTA Button Text</label>
                        <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $spotlight->button_text) }}"
                            placeholder="e.g. Buy Now, Get Your Copy, Explore Book"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('button_text') border-rose-300 bg-rose-50/50 @enderror">
                        @error('button_text')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                </div>

            </div>

            {{-- Right 1 Col: Status & Live Preview Card --}}
            <div class="space-y-6">

                {{-- Status Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Display Status</h3>
                    
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <p class="text-xs font-bold text-slate-800">Show on Homepage</p>
                            <p class="text-[11px] text-slate-400">Enable or disable this showcase section</p>
                        </div>
                        <select name="status" id="status" class="px-3 py-1.5 rounded-lg bg-white text-xs font-bold border border-slate-200 text-slate-800 focus:outline-none cursor-pointer">
                            <option value="active" {{ old('status', $spotlight->status) === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                            <option value="inactive" {{ old('status', $spotlight->status) === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow-sm cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Save Spotlight</span>
                    </button>
                </div>

                {{-- Selected Book Preview Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <i class="fa-solid fa-eye text-brand-600 text-xs"></i>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Selected Book Preview</h3>
                    </div>

                    <div id="preview-container" class="space-y-3">
                        <div class="flex items-start gap-3">
                            <img 
                                id="preview-cover"
                                src="{{ $spotlight->book?->cover_image ? $spotlight->book->cover_image_url : asset('images/books/spotlight.jpg') }}" 
                                alt="Cover"
                                class="w-16 h-22 object-cover rounded-lg shadow-sm border border-slate-200 shrink-0"
                            >
                            <div class="min-w-0 flex-1 space-y-1">
                                <h4 id="preview-title" class="font-bold text-slate-900 text-xs leading-snug truncate">
                                    {{ $spotlight->book?->title ?? 'No book selected' }}
                                </h4>
                                <p id="preview-author" class="text-[11px] text-slate-500 truncate">
                                    By {{ $spotlight->book?->author_name ?? 'Author' }}
                                </p>
                                <p id="preview-price" class="text-xs font-extrabold text-brand-700 font-roboto">
                                    ₹{{ number_format((float) ($spotlight->book?->selling_price ?? 0), 0) }}
                                </p>
                            </div>
                        </div>

                        <p id="preview-desc" class="text-[11px] text-slate-500 leading-relaxed line-clamp-3">
                            {{ $spotlight->book?->description ?? 'No description available.' }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

<script>
function updateBookPreview(select) {
    const selectedOption = select.options[select.selectedIndex];
    if (!selectedOption || !selectedOption.value) return;

    const title = selectedOption.getAttribute('data-title') || '';
    const author = selectedOption.getAttribute('data-author') || '';
    const price = selectedOption.getAttribute('data-price') || '';
    const cover = selectedOption.getAttribute('data-cover') || '';
    const desc = selectedOption.getAttribute('data-desc') || '';

    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-author').textContent = 'By ' + author;
    document.getElementById('preview-price').textContent = price;
    document.getElementById('preview-cover').src = cover;
    document.getElementById('preview-desc').textContent = desc;
}
</script>
@endsection
