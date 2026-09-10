@extends('admin.layouts.app')

@section('title', 'Admin Permission Details — ' . $adminPermission->title)

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.admin-permissions.index') }}" class="hover:text-brand-600 transition">Admin Permissions</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">{{ $adminPermission->title }}</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Permission Details</h1>
            <p class="text-xs sm:text-sm text-slate-500">Overview of access gate properties and linked authorization roles.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a 
                href="{{ route('admin.admin-permissions.index') }}" 
                class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition inline-flex items-center gap-1.5"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back</span>
            </a>
            <a 
                href="{{ route('admin.admin-permissions.edit', $adminPermission) }}" 
                class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-xs transition inline-flex items-center gap-1.5"
            >
                <i class="fa-regular fa-pen-to-square text-xs"></i>
                <span>Edit Permission</span>
            </a>
        </div>
    </div>

    <!-- Main Permission Details Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
        
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left border-b border-slate-100 pb-6">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl shadow-2xs shrink-0">
                <i class="fa-solid fa-key"></i>
            </div>

            <div class="space-y-1.5 flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 truncate">{{ $adminPermission->title }}</h2>
                    <span class="px-2.5 py-0.5 rounded-md bg-slate-100 border border-slate-200 font-mono text-xs font-semibold text-slate-700">
                        {{ $adminPermission->slug }}
                    </span>
                    @if($adminPermission->status === 'active')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                    </span>
                    @endif
                </div>

                <p class="text-sm text-slate-500 font-normal">
                    {{ $adminPermission->description ?: 'No description specified for this permission.' }}
                </p>
            </div>
        </div>

        <!-- Linked Roles Section -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Linked Roles ({{ $adminPermission->roles->count() }})</h3>
                <span class="text-xs text-slate-400">Roles granted this permission</span>
            </div>

            @if($adminPermission->roles->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($adminPermission->roles as $role)
                <a 
                    href="{{ route('admin.admin-roles.show', $role) }}" 
                    class="p-3.5 rounded-xl border border-slate-200/80 hover:border-brand-300 bg-slate-50/60 hover:bg-brand-50/40 transition flex items-center justify-between group"
                >
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-8 h-8 rounded-lg bg-white shadow-2xs border border-slate-200 flex items-center justify-center text-xs text-brand-600 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-shield-halved"></i>
                        </span>
                        <div class="min-w-0">
                            <span class="font-bold text-xs text-slate-800 group-hover:text-brand-700 block truncate">{{ $role->title }}</span>
                            <span class="text-[11px] font-mono text-slate-400 block truncate">{{ $role->slug }}</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-arrow-right text-xs text-slate-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                </a>
                @endforeach
            </div>
            @else
            <div class="p-6 rounded-xl bg-slate-50 text-center text-slate-400 border border-slate-100">
                <p class="text-xs">No roles currently have this permission assigned.</p>
            </div>
            @endif
        </div>

        <!-- Attributes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Permission ID</span>
                <span class="text-sm font-bold text-slate-800 font-mono">#{{ str_pad($adminPermission->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Created On</span>
                <span class="text-sm font-bold text-slate-800">
                    {{ $adminPermission->created_at ? $adminPermission->created_at->format('F j, Y — h:i A') : '—' }}
                </span>
            </div>
        </div>

        <!-- Danger Zone: Delete Option -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold text-rose-700 uppercase tracking-wider">Danger Zone</h3>
                <p class="text-xs text-slate-400 mt-0.5">Permanently remove this permission gate from the authorization matrix.</p>
            </div>

            <form action="{{ route('admin.admin-permissions.destroy', $adminPermission) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this permission?');">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition cursor-pointer"
                >
                    Delete Permission
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
