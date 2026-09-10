@extends('admin.layouts.app')

@section('title', 'Admin Permissions')

@section('content')
<div class="space-y-6">

    <!-- Top Header: Title + Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Admin Permissions</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-brand-100 text-brand-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Granular authorization abilities, module gates, and access controls.</p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.admin-permissions.create') }}" 
                class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Permission</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form action="{{ route('admin.admin-permissions.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <!-- Search Bar -->
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search permissions by title or slug..." 
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
                    href="{{ route('admin.admin-permissions.index') }}" 
                    class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-semibold transition"
                    title="Clear Filters"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Admin Permissions Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Permission Details</th>
                        <th class="py-3.5 px-4">Slug Identifier</th>
                        <th class="py-3.5 px-4">Roles Linked</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($adminPermissions as $permission)
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        <!-- Permission Title & Description -->
                        <td class="py-3.5 px-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.admin-permissions.show', $permission) }}" class="font-bold text-slate-900 hover:text-brand-600 transition truncate block leading-tight">
                                        {{ $permission->title }}
                                    </a>
                                </div>
                            </div>
                        </td>

                        <!-- Slug -->
                        <td class="py-3.5 px-4 font-mono text-xs text-slate-600">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 font-semibold">
                                {{ $permission->slug }}
                            </span>
                        </td>

                        <!-- Roles Count -->
                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">
                            <span class="inline-flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved text-[11px] text-brand-500"></i>
                                <span>{{ $permission->roles_count }} roles</span>
                            </span>
                        </td>

                        <!-- Status Toggle -->
                        <td class="py-3.5 px-4">
                            <button 
                                type="button"
                                role="switch"
                                aria-checked="{{ $permission->status === 'active' ? 'true' : 'false' }}"
                                onclick="toggleRecordStatus(this, '{{ route('admin.admin-permissions.toggle-status', $permission) }}')"
                                class="status-toggle relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $permission->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                title="Status: {{ ucfirst($permission->status) }} (Click to toggle)"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out {{ $permission->status === 'active' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 sm:px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a 
                                    href="{{ route('admin.admin-permissions.show', $permission) }}" 
                                    class="w-8 h-8 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 hover:text-sky-700 border border-sky-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="View Details"
                                >
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                                <a 
                                    href="{{ route('admin.admin-permissions.edit', $permission) }}" 
                                    class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 hover:text-amber-700 border border-amber-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="Edit Permission"
                                >
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </a>
                                <button 
                                    type="button" 
                                    onclick="openDeletePermissionModal('{{ route('admin.admin-permissions.destroy', $permission) }}', '{{ addslashes($permission->title) }}')"
                                    class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 border border-rose-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="Delete Permission"
                                >
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">No Admin Permissions Found</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                                @if(request('search') || request('status'))
                                No permission matches your search filter. Try clearing filters.
                                @else
                                Get started by registering your first system permission.
                                @endif
                            </p>
                            @if(!request('search') && !request('status'))
                            <a 
                                href="{{ route('admin.admin-permissions.create') }}" 
                                class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-xs transition hover:bg-brand-700"
                            >
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Add First Permission</span>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($adminPermissions->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $adminPermissions->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div id="delete-permission-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200 animate-[fadeIn_0.2s_ease-out]">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Delete Admin Permission</h3>
            <p class="text-sm text-slate-500 mt-1">
                Are you sure you want to delete permission <span id="delete-permission-title" class="font-bold text-slate-800"></span>? This will also unbind it from all associated roles.
            </p>
        </div>
        <form id="delete-permission-form" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeletePermissionModal()" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition cursor-pointer">
                Cancel
            </button>
            <button type="submit" class="px-4.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition shadow-sm cursor-pointer">
                Yes, Delete Permission
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeletePermissionModal(actionUrl, permissionTitle) {
        const modal = document.getElementById('delete-permission-modal');
        const form = document.getElementById('delete-permission-form');
        const titleSpan = document.getElementById('delete-permission-title');
        
        form.action = actionUrl;
        titleSpan.textContent = permissionTitle;
        modal.classList.remove('hidden');
    }

    function closeDeletePermissionModal() {
        const modal = document.getElementById('delete-permission-modal');
        modal.classList.add('hidden');
    }
</script>
@endpush

@endsection
