@extends('admin.layouts.app')

@section('title', 'Add Admin User')

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Top Header -->
    <div class="space-y-1">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.admin-users.index') }}" class="hover:text-brand-600 transition">Admin Users</a>
            <span class="text-slate-300">/</span>
            <span class="text-brand-600 font-semibold">Add New</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Add Admin User</h1>
        <p class="text-xs sm:text-sm text-slate-500">Create a new administrative account with role-based permissions.</p>
    </div>

    <!-- Create Form Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs">
        <form action="{{ route('admin.admin-users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Profile Image Upload with Live Preview -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Profile Picture</label>
                <div class="flex items-center gap-4">
                    <div id="avatar-preview-container" class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs text-slate-400 text-xl font-bold">
                        <i class="fa-regular fa-user" id="avatar-placeholder-icon"></i>
                        <img id="avatar-preview-img" src="" alt="Avatar Preview" class="w-full h-full object-cover hidden">
                    </div>
                    <div class="space-y-1">
                        <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold cursor-pointer transition">
                            <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            <span>Choose Image</span>
                            <input 
                                type="file" 
                                name="profile_image" 
                                id="profile-image-input" 
                                accept="image/jpeg,image/png,image/jpg,image/webp" 
                                class="hidden" 
                                onchange="previewAvatar(event)"
                            >
                        </label>
                        <p class="text-[11px] text-slate-400">JPG, PNG, or WebP up to 2MB.</p>
                    </div>
                </div>
                @error('profile_image')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
                        required 
                        placeholder="e.g. John Doe"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('name') border-rose-300 bg-rose-50/50 @enderror"
                    >
                    @error('name')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="e.g. john@example.com"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('email') border-rose-300 bg-rose-50/50 @enderror"
                    >
                    @error('email')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Role -->
                <div class="space-y-1.5">
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Role <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="role" 
                        id="role" 
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer @error('role') border-rose-300 @enderror"
                    >
                        @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role', 'admin') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

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
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            placeholder="At least 8 characters"
                            class="w-full pl-3.5 pr-10 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('password') border-rose-300 bg-rose-50/50 @enderror"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('password', this)"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                        >
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Confirm Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required 
                            placeholder="Re-type password"
                            class="w-full pl-3.5 pr-10 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('password_confirmation', this)"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                        >
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a 
                    href="{{ route('admin.admin-users.index') }}" 
                    class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
                >
                    Create Admin User
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;

        const img = document.getElementById('avatar-preview-img');
        const icon = document.getElementById('avatar-placeholder-icon');

        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.classList.remove('hidden');
            icon.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function togglePasswordVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash text-xs';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye text-xs';
        }
    }
</script>
@endpush

@endsection
