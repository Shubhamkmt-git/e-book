@extends('admin.layouts.app')

@section('title', 'Edit Hero Banner')

@section('content')
<div class="w-full space-y-6">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.hero-banners.index') }}" class="hover:text-brand-600 transition">Hero Banners</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Edit</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit Hero Banner</h1>
            <p class="text-xs sm:text-sm text-slate-500">Editing <span class="font-semibold text-slate-700">{{ $heroBanner->title }}</span></p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.hero-banners.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Cancel</a>
            <button type="submit" form="banner-form"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <form id="banner-form" action="{{ route('admin.hero-banners.update', $heroBanner) }}" method="POST" enctype="multipart/form-data" class="space-y-6" novalidate>
        @csrf @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left: Main Fields --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Card: Banner Image --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Banner Image</h2>
                        <p class="text-xs text-slate-400 ml-1">Recommended: 1920×600 px · Max 5 MB</p>
                    </div>

                    <div id="image-drop-zone"
                         onclick="document.getElementById('banner_image').click()"
                         class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 hover:border-brand-400 hover:bg-brand-50/20 transition cursor-pointer"
                         style="min-height:200px;">
                        <div id="image-placeholder" class="{{ $heroBanner->banner_image ? 'hidden' : '' }} flex flex-col items-center gap-3 py-8 px-6 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-600">Click or drag to replace</p>
                                <p class="text-xs text-slate-400 mt-0.5">JPEG, PNG, WebP, GIF</p>
                            </div>
                        </div>
                        <img id="image-preview"
                             src="{{ $heroBanner->banner_image_url ?? '' }}"
                             alt="Preview"
                             class="{{ $heroBanner->banner_image ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover rounded-xl">
                        <input type="file" id="banner_image" name="banner_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>

                    @error('banner_image')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror

                    <button type="button" id="remove-img-btn" onclick="removeImage()"
                            class="{{ $heroBanner->banner_image ? '' : 'hidden' }} text-xs text-rose-600 hover:text-rose-700 font-semibold flex items-center gap-1 transition">
                        <i class="fa-solid fa-xmark"></i> Remove Image
                    </button>
                </div>

                {{-- Card: Content --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-heading"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Banner Content</h2>
                    </div>

                    <div class="space-y-1.5">
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Title (Line 1) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $heroBanner->title) }}" required
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('title') border-rose-300 bg-rose-50/50 @enderror">
                        @error('title')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="title_l2" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Title (Line 2)</label>
                        <input type="text" name="title_l2" id="title_l2" value="{{ old('title_l2', $heroBanner->title_l2) }}"
                            placeholder="e.g. Your Gateway to Knowledge"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                    </div>

                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            placeholder="A short compelling subtitle…"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-none">{{ old('description', $heroBanner->description) }}</textarea>
                    </div>
                </div>

                {{-- Card: CTA Buttons --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-hand-pointer"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Call to Action Buttons</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label for="primary_button" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Primary Button Label</label>
                            <input type="text" name="primary_button" id="primary_button"
                                value="{{ old('primary_button', $heroBanner->primary_button) }}"
                                placeholder="e.g. Browse E-Books"
                                class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label for="primary_button_link" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Primary Button Link</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-link text-xs"></i>
                                </span>
                                <input type="text" name="primary_button_link" id="primary_button_link"
                                    value="{{ old('primary_button_link', $heroBanner->primary_button_link) }}"
                                    placeholder="e.g. /ebooks"
                                    class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label for="secondary_button" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Secondary Button Label</label>
                            <input type="text" name="secondary_button" id="secondary_button"
                                value="{{ old('secondary_button', $heroBanner->secondary_button) }}"
                                placeholder="e.g. Learn More"
                                class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label for="secondary_button_link" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Secondary Button Link</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-link text-xs"></i>
                                </span>
                                <input type="text" name="secondary_button_link" id="secondary_button_link"
                                    value="{{ old('secondary_button_link', $heroBanner->secondary_button_link) }}"
                                    placeholder="e.g. /about"
                                    class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Sidebar (sticky) --}}
            <div class="space-y-5 sticky top-6 self-start">

                {{-- Card: Actions --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Actions</h2>
                    </div>
                    <button type="submit" form="banner-form"
                            class="w-full px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.hero-banners.index') }}"
                       class="block w-full text-center px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold transition">
                        Cancel
                    </a>
                </div>

                {{-- Card: Settings --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Settings</h2>
                    </div>

                    <div class="space-y-1.5">
                        <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order"
                            value="{{ old('sort_order', $heroBanner->sort_order) }}" min="0"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                        <p class="text-[11px] text-slate-400">Lower number = displayed first.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Visibility</label>
                        <label class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-300 hover:bg-brand-50/30 transition select-none">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $heroBanner->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-400 cursor-pointer">
                            <div class="text-xs flex-1">
                                <p class="font-bold text-slate-800">Active</p>
                                <p class="text-slate-500">Visible on the homepage</p>
                            </div>
                            <i class="fa-solid fa-eye text-brand-400"></i>
                        </label>
                    </div>
                </div>

                {{-- Card: Banner Info --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Banner Info</h2>
                    </div>
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">ID</span>
                            <span class="font-bold text-slate-700">#{{ $heroBanner->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Created</span>
                            <span class="font-bold text-slate-700">{{ $heroBanner->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Updated</span>
                            <span class="font-bold text-slate-700">{{ $heroBanner->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card: Danger Zone --}}
                <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-2xs">
                    <p class="text-xs font-bold text-rose-700 mb-1">Danger Zone</p>
                    <p class="text-xs text-slate-400 mb-3">Permanently delete this banner and its image.</p>
                    <form method="POST" action="{{ route('admin.hero-banners.destroy', $heroBanner) }}"
                          onsubmit="return confirm('Delete this banner permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2 rounded-xl border border-rose-200 text-rose-600 text-xs font-semibold hover:bg-rose-50 transition cursor-pointer flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash text-xs"></i> Delete Banner
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </form>

</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('image-placeholder').classList.add('hidden');
            document.getElementById('remove-img-btn').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeImage() {
    document.getElementById('banner_image').value = '';
    document.getElementById('image-preview').src = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('image-placeholder').classList.remove('hidden');
    document.getElementById('remove-img-btn').classList.add('hidden');
}
const dropZone = document.getElementById('image-drop-zone');
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-brand-400', 'bg-brand-50'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-brand-400', 'bg-brand-50'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-brand-400', 'bg-brand-50');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('banner_image').files = dt.files;
        previewImage(document.getElementById('banner_image'));
    }
});
</script>
@endpush
@endsection
