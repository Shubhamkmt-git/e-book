@extends('admin.layouts.app')

@section('title', 'Admin User Details — ' . $adminUser->name)

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.admin-users.index') }}" class="hover:text-brand-600 transition">Admin Users</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">{{ $adminUser->name }}</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">User Profile</h1>
            <p class="text-xs sm:text-sm text-slate-500">Detailed account properties and administrative authorization.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a 
                href="{{ route('admin.admin-users.index') }}" 
                class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition inline-flex items-center gap-1.5"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back</span>
            </a>
            <a 
                href="{{ route('admin.admin-users.edit', $adminUser) }}" 
                class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-xs transition inline-flex items-center gap-1.5"
            >
                <i class="fa-regular fa-pen-to-square text-xs"></i>
                <span>Edit Profile</span>
            </a>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
        
        <!-- Header Banner & Avatar -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left border-b border-slate-100 pb-6">
            @if($adminUser->avatar_url)
            <img src="{{ $adminUser->avatar_url }}" alt="{{ $adminUser->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-brand-200 shadow-md shrink-0">
            @else
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-600 to-brand-400 text-white font-bold text-2xl flex items-center justify-center shadow-md shrink-0">
                {{ $adminUser->initials }}
            </div>
            @endif

            <div class="space-y-1.5 flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 truncate">{{ $adminUser->name }}</h2>
                    
                    @php
                        $roleStyles = [
                            'super-admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'admin' => 'bg-brand-50 text-brand-700 border-brand-200',
                            'manager' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'editor' => 'bg-amber-50 text-amber-700 border-amber-200',
                        ];
                        $roleClass = $roleStyles[$adminUser->role] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleClass }} capitalize">
                        {{ str_replace('-', ' ', $adminUser->role) }}
                    </span>

                    @if($adminUser->status === 'active')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                    </span>
                    @elseif($adminUser->status === 'inactive')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> {{ ucfirst($adminUser->status) }}
                    </span>
                    @endif
                </div>

                <p class="text-sm text-slate-500 font-medium flex items-center justify-center sm:justify-start gap-1.5">
                    <i class="fa-regular fa-envelope text-xs text-slate-400"></i>
                    <span>{{ $adminUser->email }}</span>
                </p>
            </div>
        </div>

        <!-- Account Attributes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Account ID</span>
                <span class="text-sm font-bold text-slate-800 font-mono">#{{ str_pad($adminUser->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Assigned Role</span>
                <span class="text-sm font-bold text-slate-800 capitalize">{{ str_replace('-', ' ', $adminUser->role) }}</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Created On</span>
                <span class="text-sm font-bold text-slate-800">
                    {{ $adminUser->created_at ? $adminUser->created_at->format('F j, Y — h:i A') : '—' }}
                </span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Last Updated</span>
                <span class="text-sm font-bold text-slate-800">
                    {{ $adminUser->updated_at ? $adminUser->updated_at->format('F j, Y — h:i A') : '—' }}
                </span>
            </div>
        </div>

        <!-- Danger Zone: Delete Option -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold text-rose-700 uppercase tracking-wider">Danger Zone</h3>
                <p class="text-xs text-slate-400 mt-0.5">Deleting will immediately revoke this user's administrative console access.</p>
            </div>

            <form action="{{ route('admin.admin-users.destroy', $adminUser) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this admin user?');">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition cursor-pointer"
                >
                    Delete Account
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
