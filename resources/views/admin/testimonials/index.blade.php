@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="space-y-6">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-quote-left"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Testimonials</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage customer testimonials displayed on the site.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.testimonials.create') }}"
               class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Testimonial</span>
            </a>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.testimonials.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, profession, or message…"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-search text-xs"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark text-xs"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        @if($testimonials->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">#</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Name</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Profession</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Related Book</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Rating</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Message</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Order</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($testimonials as $testimonial)
                        <tr class="hover:bg-slate-50/70 transition group">
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">{{ $loop->iteration + ($testimonials->currentPage() - 1) * $testimonials->perPage() }}</td>

                            {{-- Name with Avatar --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($testimonial->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $testimonial->name }}</span>
                                </div>
                            </td>

                            {{-- Profession --}}
                            <td class="px-5 py-4 text-slate-600 text-sm">{{ $testimonial->profession ?: '—' }}</td>

                            {{-- Related Book --}}
                            <td class="px-5 py-4">
                                @if($testimonial->book)
                                    <a href="{{ route('admin.books.edit', $testimonial->book) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-semibold max-w-[180px] truncate transition" title="{{ $testimonial->book->title }}">
                                        <i class="fa-solid fa-book text-[10px] text-brand-500 shrink-0"></i>
                                        <span class="truncate">{{ $testimonial->book->title }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Rating Stars --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-0.5 text-amber-400">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <i class="fa-{{ $s <= $testimonial->rating ? 'solid' : 'regular' }} fa-star text-[11px]"></i>
                                    @endfor
                                </div>
                            </td>

                            {{-- Message --}}
                            <td class="px-5 py-4">
                                <p class="text-slate-600 text-xs line-clamp-2 max-w-[220px]">{{ $testimonial->message }}</p>
                            </td>

                            {{-- Sort Order --}}
                            <td class="px-5 py-4 text-slate-600 text-sm font-mono">{{ $testimonial->sort_order ?: '—' }}</td>

                            {{-- Status Toggle --}}
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.testimonials.toggle-status', $testimonial) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="{{ $testimonial->is_active ? 'Deactivate' : 'Activate' }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition cursor-pointer
                                                   {{ $testimonial->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                        <i class="fa-solid {{ $testimonial->is_active ? 'fa-circle-check text-[10px]' : 'fa-circle-xmark text-[10px]' }}"></i>
                                        {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.testimonials.show', $testimonial) }}" title="View"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-500 flex items-center justify-center transition text-xs">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" title="Edit"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-50 hover:text-amber-600 text-slate-500 flex items-center justify-center transition text-xs">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                          onsubmit="return confirm('Delete testimonial from {{ addslashes($testimonial->name) }}?')">
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
            @if($testimonials->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between gap-4">
                <p class="text-xs text-slate-500">Showing {{ $testimonials->firstItem() }}–{{ $testimonials->lastItem() }} of {{ $testimonials->total() }} testimonials</p>
                {{ $testimonials->links() }}
            </div>
            @endif
        @else
            <div class="py-20 flex flex-col items-center text-center gap-3">
                <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-400 flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-quote-left"></i>
                </div>
                <p class="text-base font-semibold text-slate-700">No testimonials found</p>
                <p class="text-sm text-slate-400">
                    @if(request('search'))
                        No testimonials match your search. <a href="{{ route('admin.testimonials.index') }}" class="text-brand-600 hover:underline">Clear search</a>
                    @else
                        Add your first testimonial to showcase customer feedback.
                    @endif
                </p>
                <a href="{{ route('admin.testimonials.create') }}"
                   class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus text-xs"></i> Add Testimonial
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
