@extends('admin.layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.categories.index') }}" class="hover:text-brand-600 transition">Categories</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Add New</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Add Category</h1>
            <p class="text-xs sm:text-sm text-slate-500">Create a new e-book category with icon, featured flag, sort order, and status.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Cancel</a>
            <button type="submit" form="category-form" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs mr-1"></i> Create Category
            </button>
        </div>
    </div>

    <form id="category-form" action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Left: Main Fields (2/3 width) -->
            <div class="xl:col-span-2 space-y-5">

                <!-- Card: Basic Info -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs"><i class="fa-solid fa-circle-info"></i></div>
                        <h2 class="text-sm font-bold text-slate-800">Basic Information</h2>
                    </div>

                    <!-- Title (full width) -->
                    <div class="space-y-1.5">
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            placeholder="Category title..."
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('title') border-rose-300 bg-rose-50/50 @enderror">
                        @error('title')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        <p class="text-[11px] text-slate-400">Slug will be auto-generated from the title.</p>
                    </div>

                    <!-- Description -->
                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            placeholder="Brief description of this category..."
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-none @error('description') border-rose-300 bg-rose-50/50 @enderror"
                        >{{ old('description') }}</textarea>
                        @error('description')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Card: Icon Picker -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs"><i class="fa-solid fa-icons"></i></div>
                        <h2 class="text-sm font-bold text-slate-800">Category Icon</h2>
                        <p class="text-xs text-slate-400 ml-1">Click an icon to select</p>
                    </div>

                    <input type="hidden" name="icon" id="icon-input" value="{{ old('icon', 'fa-solid fa-layer-group') }}">

                    <!-- Preview -->
                    <div class="flex items-center gap-3 p-3 bg-brand-50 border border-brand-200/70 rounded-xl">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center text-lg shadow-sm shrink-0">
                            <i id="icon-preview-i" class="{{ old('icon', 'fa-solid fa-layer-group') }}"></i>
                        </div>
                        <div class="text-xs">
                            <p class="font-bold text-slate-700">Selected Icon</p>
                            <p id="icon-preview-label" class="text-slate-500 font-mono text-[11px]">{{ old('icon', 'fa-solid fa-layer-group') }}</p>
                        </div>
                    </div>

                    <!-- Icon Grid -->
                    <div class="grid grid-cols-8 sm:grid-cols-10 md:grid-cols-12 gap-2 max-h-52 overflow-y-auto pr-1">
                        @foreach($icons as $icon)
                        <button type="button"
                            onclick="selectIcon('{{ $icon['class'] }}', '{{ $icon['label'] }}')"
                            title="{{ $icon['label'] }}"
                            class="icon-btn w-10 h-10 rounded-xl border-2 flex items-center justify-center text-base transition cursor-pointer
                                {{ old('icon', 'fa-solid fa-layer-group') === $icon['class'] ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-slate-200 bg-slate-50 text-slate-500 hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600' }}"
                            data-icon="{{ $icon['class'] }}"
                        >
                            <i class="{{ $icon['class'] }}"></i>
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right: Settings (1/3 width) -->
            <div class="space-y-5">

                <!-- Card: Status & Options -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs"><i class="fa-solid fa-sliders"></i></div>
                        <h2 class="text-sm font-bold text-slate-800">Settings</h2>
                    </div>

                    <!-- Status -->
                    <div class="space-y-1.5">
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Status <span class="text-rose-500">*</span></label>
                        <select name="status" id="status" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer">
                            @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status','active') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Sort Order -->
                    <div class="space-y-1.5">
                        <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" max="9999"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('sort_order') border-rose-300 @enderror">
                        <p class="text-[11px] text-slate-400">Lower number = appears first.</p>
                        @error('sort_order')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Featured -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Featured</label>
                        <label class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:border-amber-300 hover:bg-amber-50/30 transition select-none">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded text-amber-500 border-slate-300 focus:ring-amber-400 cursor-pointer">
                            <div class="text-xs flex-1">
                                <p class="font-bold text-slate-800">Mark as Featured</p>
                                <p class="text-slate-500">Shown in featured sections</p>
                            </div>
                            <i class="fa-solid fa-star text-amber-400"></i>
                        </label>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
    function selectIcon(cls, label) {
        document.getElementById('icon-input').value = cls;
        document.getElementById('icon-preview-i').className = cls;
        document.getElementById('icon-preview-label').textContent = cls;
        document.querySelectorAll('.icon-btn').forEach(btn => {
            const sel = btn.dataset.icon === cls;
            btn.classList.toggle('border-brand-500', sel);
            btn.classList.toggle('bg-brand-50', sel);
            btn.classList.toggle('text-brand-600', sel);
            btn.classList.toggle('border-slate-200', !sel);
            btn.classList.toggle('bg-slate-50', !sel);
            btn.classList.toggle('text-slate-500', !sel);
        });
    }
</script>
@endpush

@endsection
