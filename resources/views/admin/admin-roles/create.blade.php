@extends('admin.layouts.app')

@section('title', 'Add Admin Role')

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Top Header -->
    <div class="space-y-1">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.admin-roles.index') }}" class="hover:text-brand-600 transition">Admin Roles</a>
            <span class="text-slate-300">/</span>
            <span class="text-brand-600 font-semibold">Add New</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Add Admin Role</h1>
        <p class="text-xs sm:text-sm text-slate-500">Define a new administrative authorization role and system identifier.</p>
    </div>

    <!-- Create Form Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs">
        <form action="{{ route('admin.admin-roles.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 2-Column Grid: Title & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Role Title -->
                <div class="space-y-1.5">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Role Title <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}" 
                        required 
                        placeholder="e.g. Content Manager"
                        oninput="generateSlug(this.value)"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('title') border-rose-300 bg-rose-50/50 @enderror"
                    >
                    @error('title')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Slug -->
                <div class="space-y-1.5">
                    <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        System Slug <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="slug" 
                            id="slug" 
                            value="{{ old('slug') }}" 
                            required 
                            placeholder="e.g. content-manager"
                            class="w-full pl-3.5 pr-10 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm font-mono border border-slate-200 focus:border-brand-500 focus:outline-none transition @error('slug') border-rose-300 bg-rose-50/50 @enderror"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-link text-xs"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Auto-generated from title. Unique key used by permissions and routing.</p>
                    @error('slug')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 2-Column Grid: Status & Description -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Status -->
                <div class="space-y-1.5">
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
                        <option value="{{ $key }}" {{ old('status', 'active') === $key ? 'selected' : '' }}>
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
                    <input 
                        type="text"
                        name="description" 
                        id="description" 
                        value="{{ old('description') }}"
                        placeholder="Briefly describe the responsibilities and scope..."
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('description') border-rose-300 bg-rose-50/50 @enderror"
                    >
                    @error('description')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role Permissions Assignment -->
            <div class="space-y-3 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Assign Permissions
                        </label>
                        <p class="text-xs text-slate-400">Select capabilities granted to this administrative role.</p>
                    </div>
                    @if(isset($permissions) && $permissions->isNotEmpty())
                    <button 
                        type="button" 
                        onclick="toggleAllPermissions(this)" 
                        class="text-xs text-brand-600 hover:text-brand-800 font-semibold cursor-pointer select-none"
                    >
                        Select All
                    </button>
                    @endif
                </div>

                @if(isset($permissions) && $permissions->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($permissions as $permission)
                        <label class="flex items-start gap-3 p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl hover:border-brand-300 hover:bg-brand-50/30 transition cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="permissions[]" 
                                value="{{ $permission->id }}" 
                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                class="permission-checkbox mt-0.5 w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500 cursor-pointer"
                            >
                            <div class="text-xs min-w-0 flex-1">
                                <p class="font-bold text-slate-800 leading-snug">{{ $permission->title }}</p>
                                <p class="text-[11px] font-mono text-slate-500 mt-0.5">{{ $permission->slug }}</p>
                                @if($permission->description)
                                <p class="text-[11px] text-slate-400 mt-1 line-clamp-2">{{ $permission->description }}</p>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-center">
                        <p class="text-xs text-slate-500">No permissions created yet. You can <a href="{{ route('admin.admin-permissions.create') }}" class="text-brand-600 font-semibold underline">add permissions</a> first.</p>
                    </div>
                @endif
                @error('permissions')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a 
                    href="{{ route('admin.admin-roles.index') }}" 
                    class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
                >
                    Create Role
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

    function toggleAllPermissions(btn) {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
        checkboxes.forEach(cb => cb.checked = anyUnchecked);
        btn.textContent = anyUnchecked ? 'Deselect All' : 'Select All';
    }
</script>
@endpush

@endsection
