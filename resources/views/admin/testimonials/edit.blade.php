@extends('admin.layouts.app')

@section('title', 'Edit Testimonial')

@section('content')
<div class="w-full space-y-6">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.testimonials.index') }}" class="hover:text-brand-600 transition">Testimonials</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Edit</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit Testimonial</h1>
            <p class="text-xs sm:text-sm text-slate-500">Updating <span class="font-semibold text-slate-700">{{ $testimonial->name }}</span></p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.testimonials.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Cancel</a>
            <button type="submit" form="testimonial-form"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <form id="testimonial-form" action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" class="space-y-6" novalidate>
        @csrf @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left: Main Fields --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Card: Person --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-circle-user"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Person Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </span>
                                <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" required
                                    class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('name') border-rose-300 bg-rose-50/50 @enderror">
                            </div>
                            @error('name')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="profession" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Profession</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-briefcase text-xs"></i>
                                </span>
                                <input type="text" name="profession" id="profession" value="{{ old('profession', $testimonial->profession) }}"
                                    placeholder="e.g. Software Developer"
                                    class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('profession') border-rose-300 bg-rose-50/50 @enderror">
                            </div>
                            @error('profession')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label for="book_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Related E-Book <span class="text-slate-400 font-normal text-[11px] lowercase">(optional - shown in feedback of that book)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-book-bookmark text-xs"></i>
                                </span>
                                <select name="book_id" id="book_id"
                                    class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('book_id') border-rose-300 bg-rose-50/50 @enderror">
                                    <option value="">-- General / No Specific Book --</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ old('book_id', $testimonial->book_id) == $book->id ? 'selected' : '' }}>
                                            {{ $book->title }} (by {{ $book->author_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('book_id')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Card: Message --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-comment-dots"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Testimonial Message</h2>
                    </div>

                    <div class="space-y-1.5">
                        <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Message <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="message" id="message" rows="4" required
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-none @error('message') border-rose-300 bg-rose-50/50 @enderror">{{ old('message', $testimonial->message) }}</textarea>
                        @error('message')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

            </div>

            {{-- Right: Sidebar --}}
            <div class="space-y-5 sticky top-6 self-start">

                {{-- Card: Actions --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Actions</h2>
                    </div>
                    <button type="submit" form="testimonial-form"
                            class="w-full px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-sm transition cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.testimonials.index') }}"
                       class="block w-full text-center px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold transition">
                        Cancel
                    </a>
                </div>

                {{-- Card: Settings --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Settings</h2>
                    </div>

                    <div class="space-y-1.5">
                        <label for="rating" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Rating <span class="text-rose-500">*</span></label>
                        <select name="rating" id="rating"
                            class="w-full px-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order"
                            value="{{ old('sort_order', $testimonial->sort_order) }}" min="0"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-800 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                        <p class="text-[11px] text-slate-400">Lower number = displayed first.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Visibility</label>
                        <label class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-300 hover:bg-brand-50/30 transition select-none">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $testimonial->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-400 cursor-pointer">
                            <div class="text-xs flex-1">
                                <p class="font-bold text-slate-800">Active</p>
                                <p class="text-slate-500">Visible on the website</p>
                            </div>
                            <i class="fa-solid fa-eye text-brand-400"></i>
                        </label>
                    </div>
                </div>

                {{-- Card: Info --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Info</h2>
                    </div>
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">ID</span>
                            <span class="font-bold text-slate-700">#{{ $testimonial->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Created</span>
                            <span class="font-bold text-slate-700">{{ $testimonial->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Updated</span>
                            <span class="font-bold text-slate-700">{{ $testimonial->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
                    <button type="submit" form="testimonial-form"
                            class="w-full px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold shadow-sm transition cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i> Save Changes
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection
