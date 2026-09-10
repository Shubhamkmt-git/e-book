@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
<div class="space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Categories</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-brand-100 text-brand-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage e-book categories with icons, featured flags, and status.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Category</span>
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex items-center gap-3 shadow-2xs">
            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base"><i class="fa-solid fa-layer-group"></i></div>
            <div><p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wide">Total</p><p class="text-xl font-extrabold text-slate-900">{{ $totalCount }}</p></div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex items-center gap-3 shadow-2xs">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base"><i class="fa-solid fa-circle-check"></i></div>
            <div><p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wide">Active</p><p class="text-xl font-extrabold text-slate-900">{{ $activeCount }}</p></div>
        </div>
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex items-center gap-3 shadow-2xs">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-base"><i class="fa-solid fa-star"></i></div>
            <div><p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wide">Featured</p><p class="text-xl font-extrabold text-slate-900">{{ $featuredCount }}</p></div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or slug..." class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-700 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <select name="featured" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-700 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer">
                    <option value="">All Types</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Regular</option>
                </select>
                @if(request('search') || request('status') || request('featured'))
                <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-semibold transition" title="Clear Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Category</th>
                        <th class="py-3.5 px-4">Slug</th>
                        <th class="py-3.5 px-4">Featured</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 hidden md:table-cell">Created</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        <td class="py-3.5 px-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}"></i>
                                    @else
                                        <i class="fa-solid fa-layer-group"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="font-bold text-slate-900 hover:text-brand-600 transition truncate block leading-tight">{{ $category->title }}</a>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-xs text-slate-600">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 font-semibold">{{ $category->slug }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($category->is_featured)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 text-[11px] font-bold">
                                <i class="fa-solid fa-star text-[10px]"></i> Featured
                            </span>
                            @else
                            <span class="text-xs text-slate-400 font-medium">—</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <button
                                type="button"
                                role="switch"
                                aria-checked="{{ $category->status === 'active' ? 'true' : 'false' }}"
                                onclick="toggleRecordStatus(this, '{{ route('admin.categories.toggle-status', $category) }}')"
                                class="status-toggle relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $category->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                title="Status: {{ ucfirst($category->status) }} (Click to toggle)"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out {{ $category->status === 'active' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell">
                            {{ $category->created_at?->format('M d, Y') ?? '—' }}
                        </td>
                        <td class="py-3.5 px-4 sm:px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.categories.show', $category) }}" class="w-8 h-8 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 border border-sky-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer" title="View">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer" title="Edit">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('admin.categories.destroy', $category) }}', '{{ addslashes($category->title) }}')" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition shadow-2xs cursor-pointer" title="Delete">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-14 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">No Categories Found</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xs mx-auto">
                                @if(request('search') || request('status') || request('featured'))
                                No category matches your current filters. Try clearing them.
                                @else
                                Get started by adding your first category.
                                @endif
                            </p>
                            @if(!request('search') && !request('status') && !request('featured'))
                            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-xs hover:bg-brand-700">
                                <i class="fa-solid fa-plus text-xs"></i> Add First Category
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
        <div class="p-4 border-t border-slate-100">{{ $categories->links() }}</div>
        @endif
    </div>

</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Delete Category</h3>
            <p class="text-sm text-slate-500 mt-1">Are you sure you want to delete <span id="delete-title" class="font-bold text-slate-800"></span>? This action cannot be undone.</p>
        </div>
        <form id="delete-form" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition cursor-pointer">Cancel</button>
            <button type="submit" class="px-4.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-sm transition cursor-pointer">Yes, Delete</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(url, title) {
        document.getElementById('delete-form').action = url;
        document.getElementById('delete-title').textContent = title;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endpush

@endsection
