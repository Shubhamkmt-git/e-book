@extends('admin.layouts.app')

@section('title', 'Admin Roles')

@section('content')
<div class="space-y-6">

    <!-- Top Header: Title + Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Admin Roles</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-brand-100 text-brand-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure administrative role titles, system slugs, and authority levels.</p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.admin-roles.create') }}" 
                class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Role</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form action="{{ route('admin.admin-roles.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by role title, slug, or description..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium"
                >
            </div>

            <!-- Filters -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Status Filter -->
                <select 
                    name="status" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-50 text-slate-700 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer"
                >
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                @if(request('search') || request('status'))
                <a 
                    href="{{ route('admin.admin-roles.index') }}" 
                    class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-semibold transition"
                    title="Clear Filters"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Admin Roles Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Role Details</th>
                        <th class="py-3.5 px-4">Slug Identifier</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 hidden md:table-cell">Created Date</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($adminRoles as $role)
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        <!-- Role Title & Description -->
                        <td class="py-3.5 px-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.admin-roles.show', $role) }}" class="font-bold text-slate-900 hover:text-brand-600 transition truncate block leading-tight">
                                        {{ $role->title }}
                                    </a>
                                </div>
                            </div>
                        </td>

                        <!-- Slug -->
                        <td class="py-3.5 px-4 font-mono text-xs text-slate-600">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 font-semibold">
                                {{ $role->slug }}
                            </span>
                        </td>

                        <!-- Status Toggle -->
                        <td class="py-3.5 px-4">
                            @if($role->slug === 'super-admin')
                            <span class="relative inline-flex h-5 w-10 shrink-0 cursor-not-allowed rounded-full border-2 border-transparent bg-emerald-500/80" title="Super Admin role is permanently active">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 translate-x-5"></span>
                            </span>
                            @else
                            <button 
                                type="button"
                                role="switch"
                                aria-checked="{{ $role->status === 'active' ? 'true' : 'false' }}"
                                onclick="toggleRecordStatus(this, '{{ route('admin.admin-roles.toggle-status', $role) }}')"
                                class="status-toggle relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $role->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                title="Status: {{ ucfirst($role->status) }} (Click to toggle)"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out {{ $role->status === 'active' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            @endif
                        </td>

                        <!-- Created Date -->
                        <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell">
                            {{ $role->created_at ? $role->created_at->format('M d, Y') : '—' }}
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 sm:px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a 
                                    href="{{ route('admin.admin-roles.show', $role) }}" 
                                    class="w-8 h-8 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 hover:text-sky-700 border border-sky-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="View Details"
                                >
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                                <a 
                                    href="{{ route('admin.admin-roles.edit', $role) }}" 
                                    class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 hover:text-amber-700 border border-amber-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="Edit Role"
                                >
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </a>
                                @if($role->slug === 'super-admin')
                                <span 
                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center cursor-not-allowed"
                                    title="Super Admin role is protected and cannot be deleted"
                                >
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </span>
                                @else
                                <button 
                                    type="button" 
                                    onclick="openDeleteRoleModal('{{ route('admin.admin-roles.destroy', $role) }}', '{{ addslashes($role->title) }}')"
                                    class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 border border-rose-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="Delete Role"
                                >
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                                <i class="fa-solid fa-shield-slash"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">No Admin Roles Found</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                                @if(request('search') || request('status'))
                                No role matches your current search criteria. Try clearing filters.
                                @else
                                Get started by adding your first administrative role definition.
                                @endif
                            </p>
                            @if(!request('search') && !request('status'))
                            <a 
                                href="{{ route('admin.admin-roles.create') }}" 
                                class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-xs transition hover:bg-brand-700"
                            >
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Add First Role</span>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($adminRoles->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $adminRoles->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div id="delete-role-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200 animate-[fadeIn_0.2s_ease-out]">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Delete Admin Role</h3>
            <p class="text-sm text-slate-500 mt-1">
                Are you sure you want to delete role <span id="delete-role-title" class="font-bold text-slate-800"></span>? This action cannot be undone.
            </p>
        </div>
        <form id="delete-role-form" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteRoleModal()" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition cursor-pointer">
                Cancel
            </button>
            <button type="submit" class="px-4.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition shadow-sm cursor-pointer">
                Yes, Delete Role
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteRoleModal(actionUrl, roleTitle) {
        const modal = document.getElementById('delete-role-modal');
        const form = document.getElementById('delete-role-form');
        const titleSpan = document.getElementById('delete-role-title');
        
        form.action = actionUrl;
        titleSpan.textContent = roleTitle;
        modal.classList.remove('hidden');
    }

    function closeDeleteRoleModal() {
        const modal = document.getElementById('delete-role-modal');
        modal.classList.add('hidden');
    }
</script>
@endpush

@endsection
