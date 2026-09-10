@extends('admin.layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="w-full space-y-6">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.customers.index') }}" class="hover:text-brand-600 transition">Customers</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Edit</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit Customer</h1>
            <p class="text-xs sm:text-sm text-slate-500">Updating <span class="font-semibold text-slate-700">{{ $customer->name }}</span></p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.customers.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Cancel</a>
            <button type="submit" form="customer-form"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <form id="customer-form" action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-5" novalidate>
        @csrf @method('PUT')

        {{-- Card: Personal Information --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-user"></i>
                </div>
                <h2 class="text-sm font-bold text-slate-800">Personal Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Name --}}
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                            class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('name') border-rose-300 bg-rose-50/50 @enderror">
                    </div>
                    @error('name')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Mobile --}}
                <div class="space-y-1.5">
                    <label for="mobile" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Mobile Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </span>
                        <input type="tel" name="mobile" id="mobile" value="{{ old('mobile', $customer->mobile) }}"
                            placeholder="e.g. +91 98765 43210"
                            class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('mobile') border-rose-300 bg-rose-50/50 @enderror">
                    </div>
                    @error('mobile')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Email (full width) --}}
                <div class="space-y-1.5 md:col-span-2">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                            class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('email') border-rose-300 bg-rose-50/50 @enderror">
                    </div>
                    @error('email')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Card: Reset Password --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Reset Password</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Leave blank to keep the current password.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            placeholder="Min 8 characters"
                            class="w-full px-3.5 pr-10 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('password') border-rose-300 bg-rose-50/50 @enderror">
                        <button type="button" onclick="togglePwd('password', 'eye-pwd')"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                            <i id="eye-pwd" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Repeat new password"
                            class="w-full px-3.5 pr-10 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                        <button type="button" onclick="togglePwd('password_confirmation', 'eye-confirm')"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                            <i id="eye-confirm" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-between gap-3 pt-1">
            {{-- Danger Zone inline --}}
            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                  onsubmit="return confirm('Delete {{ addslashes($customer->name) }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl border border-rose-200 text-rose-600 text-sm font-semibold hover:bg-rose-50 transition cursor-pointer flex items-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Delete Customer
                </button>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.customers.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-semibold transition">Cancel</a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Save Changes
                </button>
            </div>
        </div>

    </form>

</div>

@push('scripts')
<script>
function togglePwd(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
@endsection
