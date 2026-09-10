@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('content')
<div class="space-y-6">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">FAQs</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage Frequently Asked Questions.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.faqs.create') }}"
               class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add FAQ</span>
            </a>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.faqs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by question or answer…"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                >
            </div>
            <select name="status" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer">
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-search text-xs"></i>
                    Search
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.faqs.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        @if($faqs->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">#</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Question</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 hidden md:table-cell">Answer</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 hidden sm:table-cell">Order</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($faqs as $faq)
                        <tr class="hover:bg-slate-50/70 transition group">
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">{{ $loop->iteration + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                            <td class="px-5 py-4 max-w-xs">
                                <p class="font-semibold text-slate-800 line-clamp-2">{{ $faq->question }}</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell max-w-sm">
                                <p class="text-slate-500 text-xs line-clamp-2">{{ $faq->answer }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.faqs.toggle-status', $faq) }}">
                                    @csrf @method('PATCH')
                                    @if($faq->status === 'active')
                                        <button type="submit" title="Click to deactivate"
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200 hover:bg-emerald-100 transition cursor-pointer">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Active
                                        </button>
                                    @else
                                        <button type="submit" title="Click to activate"
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[11px] font-bold border border-slate-200 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 transition cursor-pointer">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i> Inactive
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="px-5 py-4 hidden sm:table-cell">
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $faq->sort_order }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                                       title="Edit"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-500 flex items-center justify-center transition text-xs">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                                          onsubmit="return confirm('Delete this FAQ? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Delete"
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 flex items-center justify-center transition text-xs cursor-pointer">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($faqs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between gap-4">
                <p class="text-xs text-slate-500">Showing {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} of {{ $faqs->total() }} FAQs</p>
                {{ $faqs->links() }}
            </div>
            @endif
        @else
            <div class="py-20 flex flex-col items-center text-center gap-3">
                <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-400 flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <p class="text-base font-semibold text-slate-700">No FAQs found</p>
                <p class="text-sm text-slate-400">
                    @if(request()->hasAny(['search', 'status']))
                        No FAQs match your search. <a href="{{ route('admin.faqs.index') }}" class="text-brand-600 hover:underline">Clear filters</a>
                    @else
                        Get started by creating your first frequently asked question.
                    @endif
                </p>
                <a href="{{ route('admin.faqs.create') }}"
                   class="mt-2 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus text-xs"></i> Add FAQ
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
