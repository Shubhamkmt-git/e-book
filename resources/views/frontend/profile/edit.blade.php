@extends('frontend.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="bg-slate-50 min-h-screen py-10 lg:py-14">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4 max-w-4xl">
        
        <!-- Page Header -->
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('customer.profile') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-brand-600 hover:border-brand-200 hover:bg-brand-50 transition-all cursor-pointer">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Edit Profile</h1>
                <p class="text-slate-500 text-sm mt-1">Update your personal information and password.</p>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden p-6 sm:p-8">
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all" required>
                        @error('name')
                            <p class="mt-1.5 text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all" required>
                        @error('email')
                            <p class="mt-1.5 text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mobile -->
                    <div>
                        <label for="mobile" class="block text-sm font-bold text-slate-700 mb-1.5">Mobile Number (Optional)</label>
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $customer->mobile) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                        @error('mobile')
                            <p class="mt-1.5 text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-slate-100 my-6">

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Change Password</h3>
                        <p class="text-sm text-slate-500">Leave blank if you don't want to change your password.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">New Password</label>
                            <input type="password" name="password" id="password" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                            @error('password')
                                <p class="mt-1.5 text-xs font-semibold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('customer.profile') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-sm transition-all">
                        Save
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
