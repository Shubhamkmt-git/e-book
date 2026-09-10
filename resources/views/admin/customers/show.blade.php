@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}"
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-roboto">Customer Details</h1>
                <p class="text-sm text-slate-500 mt-0.5">Viewing <span class="font-semibold text-slate-700">{{ $customer->name }}</span></p>
            </div>
        </div>
        <a href="{{ route('admin.customers.edit', $customer) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition">
            <i class="fa-solid fa-pen-to-square text-xs"></i>
            Edit
        </a>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
        <div class="flex items-center gap-5 pb-5 mb-5 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center font-extrabold text-2xl shrink-0">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $customer->name }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $customer->email }}</p>
                <div class="mt-2">
                    @if($customer->email_verified_at)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Email Verified
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                            <i class="fa-solid fa-clock text-[10px]"></i> Not Verified
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Customer ID</p>
                <p class="text-sm font-semibold text-slate-700">#{{ $customer->id }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Mobile Number</p>
                <p class="text-sm font-semibold text-slate-700">
                    @if($customer->mobile)
                        <a href="tel:{{ $customer->mobile }}" class="text-brand-600 hover:underline">{{ $customer->mobile }}</a>
                    @else
                        <span class="text-slate-400 font-normal">Not provided</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Joined On</p>
                <p class="text-sm font-semibold text-slate-700">{{ $customer->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Last Updated</p>
                <p class="text-sm font-semibold text-slate-700">{{ $customer->updated_at->format('d M Y, h:i A') }}</p>
            </div>
            @if($customer->email_verified_at)
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Verified At</p>
                <p class="text-sm font-semibold text-slate-700">{{ $customer->email_verified_at->format('d M Y, h:i A') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-2xs">
        <p class="text-sm font-bold text-rose-700 mb-1">Danger Zone</p>
        <p class="text-xs text-slate-500 mb-4">Permanently remove this customer. This action cannot be undone.</p>
        <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
              onsubmit="return confirm('Delete {{ addslashes($customer->name) }}? This action is irreversible.')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-trash text-xs"></i>
                Delete Customer
            </button>
        </form>
    </div>

</div>
@endsection
