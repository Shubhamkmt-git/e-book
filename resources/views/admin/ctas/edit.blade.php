@extends('admin.layouts.app')

@section('title', 'Edit CTA')

@section('content')
<div class="w-full space-y-6">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.ctas.index') }}" class="hover:text-brand-600 transition">CTAs</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Edit</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit CTA</h1>
            <p class="text-xs sm:text-sm text-slate-500">Update the call to action banner.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.ctas.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Cancel</a>
            <button type="submit" form="cta-form"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <form id="cta-form" action="{{ route('admin.ctas.update', $cta) }}" method="POST" enctype="multipart/form-data" class="space-y-5" novalidate>
        @csrf @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left: Main Content --}}
            <div class="xl:col-span-2 space-y-6">
                
                {{-- Card: Image --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Background Image</h2>
                        <p class="text-xs text-slate-400 ml-1">Optional, max 5MB.</p>
                    </div>

                    <div id="image-drop-zone"
                         onclick="document.getElementById('bg_image').click()"
                         class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 hover:border-brand-400 hover:bg-brand-50/20 transition cursor-pointer"
                         style="min-height:200px;">
                        <div id="image-placeholder" class="{{ $cta->bg_image ? 'hidden' : '' }} flex flex-col items-center gap-3 py-8 px-6 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-600">Click or drag to replace</p>
                                <p class="text-xs text-slate-400 mt-0.5">JPEG, PNG, WebP, GIF</p>
                            </div>
                        </div>
                        <img id="image-preview" src="{{ $cta->bg_image_url ?? '' }}" alt="Preview" class="{{ $cta->bg_image ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover rounded-xl">
                        <input type="file" id="bg_image" name="bg_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>

                    @error('bg_image')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror

                    <button type="button" id="remove-img-btn" onclick="removeImage()"
                            class="{{ $cta->bg_image ? '' : 'hidden' }} text-xs text-rose-600 hover:text-rose-700 font-semibold flex items-center gap-1 transition">
                        <i class="fa-solid fa-xmark"></i> Remove Image
                    </button>
                </div>

                {{-- Card: Details --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Content Details</h2>
                    </div>

                    <div class="space-y-4">
                        {{-- Label --}}
                        <div class="space-y-1.5">
                            <label for="label" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Label</label>
                            <input type="text" name="label" id="label" value="{{ old('label', $cta->label) }}"
                                placeholder="e.g. Special Offer"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('label') border-rose-300 bg-rose-50/50 @enderror">
                            @error('label')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        {{-- Title --}}
                        <div class="space-y-1.5">
                            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $cta->title) }}"
                                placeholder="e.g. Get 50% Off All E-Books!"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('title') border-rose-300 bg-rose-50/50 @enderror">
                            @error('title')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        {{-- Subtitle --}}
                        <div class="space-y-1.5">
                            <label for="subtitle" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Subtitle</label>
                            <textarea name="subtitle" id="subtitle" rows="3"
                                placeholder="e.g. Use code SAVE50 at checkout..."
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-y @error('subtitle') border-rose-300 bg-rose-50/50 @enderror">{{ old('subtitle', $cta->subtitle) }}</textarea>
                            @error('subtitle')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Sidebar --}}
            <div class="space-y-6 sticky top-6 self-start">
                
                {{-- Card: Settings --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Settings</h2>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1.5">
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $cta->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    {{-- Sort Order --}}
                    <div class="space-y-1.5">
                        <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $cta->sort_order) }}" min="0"
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('sort_order') border-rose-300 bg-rose-50/50 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Lower number = shown first.</p>
                        @error('sort_order')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="flex-1 px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold shadow-sm transition cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i> Save
                    </button>
                    <button type="submit" form="delete-form" onclick="return confirm('Delete this CTA permanently?')"
                            class="px-4 py-3 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-sm font-semibold transition cursor-pointer flex items-center justify-center" title="Delete">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    {{-- Delete form (outside main form) --}}
    <form id="delete-form" method="POST" action="{{ route('admin.ctas.destroy', $cta) }}" class="hidden">
        @csrf @method('DELETE')
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
    document.getElementById('bg_image').value = '';
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
        document.getElementById('bg_image').files = dt.files;
        previewImage(document.getElementById('bg_image'));
    }
});
</script>
@endpush
@endsection
