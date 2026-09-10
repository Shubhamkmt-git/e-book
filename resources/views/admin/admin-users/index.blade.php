@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
<div class="space-y-6">

    <!-- Top Header: Title + Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Admin Users</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-brand-100 text-brand-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage administrative accounts, role assignments, and console access.</p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.admin-users.create') }}" 
                class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Admin User</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form action="{{ route('admin.admin-users.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by name or email..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium"
                >
            </div>

            <!-- Filters -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Role Filter -->
                <select 
                    name="role" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-50 text-slate-700 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer"
                >
                    <option value="">All Roles</option>
                    <option value="super-admin" {{ request('role') === 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="editor" {{ request('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                </select>

                <!-- Status Filter -->
                <select 
                    name="status" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-50 text-slate-700 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer"
                >
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>

                @if(request('search') || request('role') || request('status'))
                <a 
                    href="{{ route('admin.admin-users.index') }}" 
                    class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-semibold transition"
                    title="Clear Filters"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Admin Users Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Admin User</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 hidden md:table-cell">Created Date</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($adminUsers as $user)
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        <!-- User (Avatar + Name + Email) -->
                        <td class="py-3.5 px-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-2xs shrink-0">
                                @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-600 to-brand-400 text-white font-bold text-xs flex items-center justify-center shadow-2xs shrink-0">
                                    {{ $user->initials }}
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('admin.admin-users.show', $user) }}" class="font-bold text-slate-900 hover:text-brand-600 transition truncate block leading-tight">
                                        {{ $user->name }}
                                    </a>
                                    <span class="text-xs text-slate-400 truncate block mt-0.5">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Role Badge -->
                        <td class="py-3.5 px-4">
                            @php
                                $roleStyles = [
                                    'super-admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'admin' => 'bg-brand-50 text-brand-700 border-brand-200',
                                    'manager' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'editor' => 'bg-amber-50 text-amber-700 border-amber-200',
                                ];
                                $roleClass = $roleStyles[$user->role] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $roleClass }} capitalize">
                                {{ str_replace('-', ' ', $user->role) }}
                            </span>
                        </td>

                        <!-- Status Toggle -->
                        <td class="py-3.5 px-4">
                            @if($user->role === 'super-admin' || $user->email === 'admin@ebook.com')
                            <span class="relative inline-flex h-5 w-10 shrink-0 cursor-not-allowed rounded-full border-2 border-transparent bg-emerald-500/80" title="Super Admin accounts are permanently active">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 translate-x-5"></span>
                            </span>
                            @else
                            <button 
                                type="button"
                                role="switch"
                                aria-checked="{{ $user->status === 'active' ? 'true' : 'false' }}"
                                onclick="toggleRecordStatus(this, '{{ route('admin.admin-users.toggle-status', $user) }}')"
                                class="status-toggle relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                title="Status: {{ ucfirst($user->status) }} (Click to toggle)"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out {{ $user->status === 'active' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            @endif
                        </td>

                        <!-- Created Date -->
                        <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell">
                            {{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 sm:px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a 
                                    href="{{ route('admin.admin-users.show', $user) }}" 
                                    class="w-8 h-8 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 hover:text-sky-700 border border-sky-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="View Details"
                                >
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                                <a 
                                    href="{{ route('admin.admin-users.edit', $user) }}" 
                                    class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 hover:text-amber-700 border border-amber-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="Edit User"
                                >
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </a>
                                @if($user->role === 'super-admin' || $user->email === 'admin@ebook.com')
                                <span 
                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center cursor-not-allowed"
                                    title="Super Admin user is protected and cannot be deleted"
                                >
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </span>
                                @else
                                <button 
                                    type="button" 
                                    onclick="openDeleteModal('{{ route('admin.admin-users.destroy', $user) }}', '{{ addslashes($user->name) }}')"
                                    class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 border border-rose-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="Delete User"
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
                                <i class="fa-solid fa-user-xmark"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">No Admin Users Found</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                                @if(request('search') || request('role') || request('status'))
                                No admin user matches your current filter criteria. Try clearing filters.
                                @else
                                Get started by adding your first admin user account.
                                @endif
                            </p>
                            @if(!request('search') && !request('role') && !request('status'))
                            <a 
                                href="{{ route('admin.admin-users.create') }}" 
                                class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-xs transition hover:bg-brand-700"
                            >
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Add First Admin User</span>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($adminUsers->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $adminUsers->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200 animate-[fadeIn_0.2s_ease-out]">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Delete Admin User</h3>
            <p class="text-sm text-slate-500 mt-1">
                Are you sure you want to delete <span id="delete-user-name" class="font-bold text-slate-800"></span>? This action cannot be undone.
            </p>
        </div>
        <form id="delete-form" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition cursor-pointer">
                Cancel
            </button>
            <button type="submit" class="px-4.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition shadow-sm cursor-pointer">
                Yes, Delete User
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(actionUrl, userName) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        const nameSpan = document.getElementById('delete-user-name');
        
        form.action = actionUrl;
        nameSpan.textContent = userName;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        modal.classList.add('hidden');
    }
</script>
@endpush

@endsection
