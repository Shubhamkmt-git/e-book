@extends('admin.layouts.app')

@section('title', 'Edit Admin Permission — ' . $adminPermission->title)

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Top Header -->
    <div class="space-y-1">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.admin-permissions.index') }}" class="hover:text-brand-600 transition">Admin Permissions</a>
            <span class="text-slate-300">/</span>
            <span class="text-brand-600 font-semibold">Edit</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit Admin Permission</h1>
        <p class="text-xs sm:text-sm text-slate-500">Update permission title, system gate slug, and description.</p>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs">
        <form action="{{ route('admin.admin-permissions.update', $adminPermission) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 2-Column Grid: Title & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Permission Title -->
                <div class="space-y-1.5">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Permission Title <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title', $adminPermission->title) }}" 
                        required 
                        placeholder="e.g. Edit E-Books"
                        oninput="generateSlug(this.value)"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('title') border-rose-300 bg-rose-50/50 @enderror"
                    >
                    @error('title')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permission Slug -->
                <div class="space-y-1.5">
                    <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Permission Key / Slug <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="slug" 
                            id="slug" 
                            value="{{ old('slug', $adminPermission->slug) }}" 
                            required 
                            placeholder="e.g. edit-ebooks"
                            class="w-full pl-3.5 pr-10 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm font-mono border border-slate-200 focus:border-brand-500 focus:outline-none transition @error('slug') border-rose-300 bg-rose-50/50 @enderror"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key text-xs"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Unique identifier checked via middleware or Gates/Policies.</p>
                    @error('slug')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-1.5 max-w-md">
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    Status <span class="text-rose-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status" 
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer @error('status') border-rose-300 @enderror"
                >
                    @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ old('status', $adminPermission->status) === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    Description
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    placeholder="Describe what action this permission allows..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-none @error('description') border-rose-300 bg-rose-50/50 @enderror"
                >{{ old('description', $adminPermission->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a 
                    href="{{ route('admin.admin-permissions.index') }}" 
                    class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function generateSlug(text) {
        const slugInput = document.getElementById('slug');
        if (!slugInput) return;

        const slug = text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');

        slugInput.value = slug;
    }
</script>
@endpush
@endsection
