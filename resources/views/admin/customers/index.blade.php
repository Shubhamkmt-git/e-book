@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
<div class="space-y-6">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Customers</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-violet-100 text-violet-700 font-bold text-xs">{{ $totalCount }}</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage registered customers of the platform.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.create') }}"
               class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Customer</span>
            </a>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or email…"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-search text-xs"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.customers.index') }}"
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
        @if($customers->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">#</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Name</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Email</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Mobile</th>

                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Joined</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($customers as $customer)
                        <tr class="hover:bg-slate-50/70 transition group">
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $customer->email }}</td>
                            <td class="px-5 py-4 text-slate-600 text-sm">
                                @if($customer->mobile)
                                    <a href="tel:{{ $customer->mobile }}" class="hover:text-brand-600 transition">{{ $customer->mobile }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $customer->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.customers.show', $customer) }}"
                                       title="View"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-500 flex items-center justify-center transition text-xs">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}"
                                       title="Edit"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-violet-50 hover:text-violet-600 text-slate-500 flex items-center justify-center transition text-xs">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                                          onsubmit="return confirm('Delete customer {{ addslashes($customer->name) }}? This cannot be undone.')">
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
            @if($customers->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between gap-4">
                <p class="text-xs text-slate-500">Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ $customers->total() }} customers</p>
                {{ $customers->links() }}
            </div>
            @endif
        @else
            <div class="py-20 flex flex-col items-center text-center gap-3">
                <div class="w-16 h-16 rounded-full bg-violet-50 text-violet-400 flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <p class="text-base font-semibold text-slate-700">No customers found</p>
                <p class="text-sm text-slate-400">
                    @if(request('search'))
                        No customers match your search. <a href="{{ route('admin.customers.index') }}" class="text-brand-600 hover:underline">Clear search</a>
                    @else
                        No customers have registered yet.
                    @endif
                </p>
                <a href="{{ route('admin.customers.create') }}"
                   class="mt-2 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus text-xs"></i> Add Customer
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
