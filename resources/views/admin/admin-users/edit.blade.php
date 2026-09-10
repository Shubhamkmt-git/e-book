@extends('admin.layouts.app')

@section('title', 'Edit Admin User — ' . $adminUser->name)

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Top Header -->
    <div class="space-y-1">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.admin-users.index') }}" class="hover:text-brand-600 transition">Admin Users</a>
            <span class="text-slate-300">/</span>
            <span class="text-brand-600 font-semibold">Edit</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit Admin User</h1>
        <p class="text-xs sm:text-sm text-slate-500">Update account credentials, administrative role, and access status.</p>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs">
        <form action="{{ route('admin.admin-users.update', $adminUser) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Profile Image Upload with Live Preview & Remove Option -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Profile Picture</label>
                <div class="flex items-center gap-4">
                    <div id="avatar-preview-container" class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs text-slate-400 text-xl font-bold">
                        @if($adminUser->avatar_url)
                            <img id="avatar-preview-img" src="{{ $adminUser->avatar_url }}" alt="{{ $adminUser->name }}" class="w-full h-full object-cover">
                            <i class="fa-regular fa-user hidden" id="avatar-placeholder-icon"></i>
                        @else
                            <img id="avatar-preview-img" src="" alt="Avatar Preview" class="w-full h-full object-cover hidden">
                            <span id="avatar-placeholder-icon" class="text-brand-600">{{ $adminUser->initials }}</span>
                        @endif
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold cursor-pointer transition">
                                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                                <span>Change Image</span>
                                <input 
                                    type="file" 
                                    name="profile_image" 
                                    id="profile-image-input" 
                                    accept="image/jpeg,image/png,image/jpg,image/webp" 
                                    class="hidden" 
                                    onchange="previewAvatar(event)"
                                >
                            </label>

                            @if($adminUser->profile_image)
                            <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-700 font-medium cursor-pointer ml-2">
                                <input type="checkbox" name="remove_profile_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                <span>Remove Image</span>
                            </label>
                            @endif
                        </div>
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
                        value="{{ old('name', $adminUser->name) }}" 
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
                        value="{{ old('email', $adminUser->email) }}" 
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
                        <option value="{{ $key }}" {{ old('role', $adminUser->role) === $key ? 'selected' : '' }}>
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
                    @if($adminUser->role === 'super-admin' || $adminUser->email === 'admin@ebook.com')
                    <input type="hidden" name="status" value="active">
                    <div class="w-full px-3.5 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-sm border border-slate-200 font-medium flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-emerald-700 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active (Protected)
                        </span>
                        <i class="fa-solid fa-lock text-xs text-slate-400"></i>
                    </div>
                    @else
                    <select 
                        name="status" 
                        id="status" 
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer @error('status') border-rose-300 @enderror"
                    >
                        @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $adminUser->status) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @endif
                    @error('status')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Change Section (Optional) -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div class="space-y-0.5">
                    <h3 class="text-sm font-bold text-slate-800">Security Credentials</h3>
                    <p class="text-xs text-slate-400">Leave password fields blank if you do not wish to change the existing password.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- New Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            New Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                placeholder="Leave blank to keep current"
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

                    <!-- Confirm New Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                placeholder="Re-type new password"
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
                    Save Changes
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
            if (icon) icon.classList.add('hidden');
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
