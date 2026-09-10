@extends('admin.layouts.app')

@section('title', 'Admin Role Details — ' . $adminRole->title)

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium pb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.admin-roles.index') }}" class="hover:text-brand-600 transition">Admin Roles</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">{{ $adminRole->title }}</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Role Details</h1>
            <p class="text-xs sm:text-sm text-slate-500">Overview of role authorization parameters and active membership.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a 
                href="{{ route('admin.admin-roles.index') }}" 
                class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition inline-flex items-center gap-1.5"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back</span>
            </a>
            <a 
                href="{{ route('admin.admin-roles.edit', $adminRole) }}" 
                class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-xs transition inline-flex items-center gap-1.5"
            >
                <i class="fa-regular fa-pen-to-square text-xs"></i>
                <span>Edit Role</span>
            </a>
        </div>
    </div>

    <!-- Main Role Details Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
        
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left border-b border-slate-100 pb-6">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl shadow-2xs shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <div class="space-y-1.5 flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 truncate">{{ $adminRole->title }}</h2>
                    <span class="px-2.5 py-0.5 rounded-md bg-slate-100 border border-slate-200 font-mono text-xs font-semibold text-slate-700">
                        {{ $adminRole->slug }}
                    </span>
                    @if($adminRole->status === 'active')
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
                    {{ $adminRole->description ?: 'No detailed scope description provided for this role.' }}
                </p>
            </div>
        </div>

        <!-- Role Attributes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Role ID</span>
                <span class="text-sm font-bold text-slate-800 font-mono">#{{ str_pad($adminRole->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Assigned Admin Users</span>
                <span class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-users text-xs text-brand-600"></i>
                    <span>{{ $adminRole->admin_users_count }} active users</span>
                </span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Created On</span>
                <span class="text-sm font-bold text-slate-800">
                    {{ $adminRole->created_at ? $adminRole->created_at->format('F j, Y — h:i A') : '—' }}
                </span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Last Updated</span>
                <span class="text-sm font-bold text-slate-800">
                    {{ $adminRole->updated_at ? $adminRole->updated_at->format('F j, Y — h:i A') : '—' }}
                </span>
            </div>
        </div>

        <!-- Granted Permissions Section -->
        <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Granted Permissions</h3>
                    <p class="text-xs text-slate-400">Capabilities explicitly assigned to this role.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-brand-50 border border-brand-200 text-brand-700 text-xs font-bold">
                    {{ $adminRole->permissions->count() }} {{ Str::plural('permission', $adminRole->permissions->count()) }}
                </span>
            </div>

            @if($adminRole->permissions->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach($adminRole->permissions as $permission)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $permission->title }}</p>
                            <p class="text-[10px] font-mono text-slate-400 truncate">{{ $permission->slug }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-white border border-slate-200 text-slate-600 shrink-0">
                            {{ $permission->group ?? 'general' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-center">
                    <p class="text-xs text-slate-500">No permissions currently assigned to this role.</p>
                    <a href="{{ route('admin.admin-roles.edit', $adminRole) }}" class="text-xs text-brand-600 font-semibold hover:underline mt-1 inline-block">Assign Permissions</a>
                </div>
            @endif
        </div>

        <!-- Danger Zone: Delete Option -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold text-rose-700 uppercase tracking-wider">Danger Zone</h3>
                <p class="text-xs text-slate-400 mt-0.5">Permanently remove this role definition from the authorization system.</p>
            </div>

            <form action="{{ route('admin.admin-roles.destroy', $adminRole) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this role?');">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition cursor-pointer"
                >
                    Delete Role
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
