@extends('admin.layouts.app')

@section('title', 'Edit E-Book: ' . $book->title)

@section('content')
<div class="w-full space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.books.index') }}" class="hover:text-brand-600 transition">E-Books</a>
                <span class="text-slate-300">/</span>
                <span class="text-brand-600 font-semibold">Edit</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Edit E-Book</h1>
            <p class="text-xs sm:text-sm text-slate-500">Update book details, pricing, files, and category assignment across 5 steps.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.books.show', $book) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">
                <i class="fa-regular fa-eye text-xs mr-1"></i> View
            </a>
            <a href="{{ route('admin.books.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">Cancel</a>
            <button type="submit" form="book-form" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Update E-Book</span>
            </button>
        </div>
    </div>

    <!-- Multi-Step Stepper Navigation -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-4 shadow-2xs">
        <div class="grid grid-cols-5 gap-1 sm:gap-2">
            
            <!-- Step 1 Tab -->
            <button type="button" onclick="goToStep(1)" id="step-tab-1" class="step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left bg-brand-50 border border-brand-200 text-brand-700 font-bold">
                <div class="step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center text-xs font-extrabold shrink-0 shadow-2xs">1</div>
                <div class="min-w-0 text-center sm:text-left">
                    <p class="text-[10px] sm:text-[11px] uppercase tracking-wider text-slate-400 font-semibold hidden sm:block">Step 1</p>
                    <p class="step-title text-[11px] sm:text-xs font-bold truncate">Basic Info</p>
                </div>
            </button>

            <!-- Step 2 Tab -->
            <button type="button" onclick="goToStep(2)" id="step-tab-2" class="step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left hover:bg-slate-50 text-slate-600">
                <div class="step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-extrabold shrink-0">2</div>
                <div class="min-w-0 text-center sm:text-left">
                    <p class="text-[10px] sm:text-[11px] uppercase tracking-wider text-slate-400 font-semibold hidden sm:block">Step 2</p>
                    <p class="step-title text-[11px] sm:text-xs font-bold truncate">Pricing & Specs</p>
                </div>
            </button>

            <!-- Step 3 Tab -->
            <button type="button" onclick="goToStep(3)" id="step-tab-3" class="step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left hover:bg-slate-50 text-slate-600">
                <div class="step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-extrabold shrink-0">3</div>
                <div class="min-w-0 text-center sm:text-left">
                    <p class="text-[10px] sm:text-[11px] uppercase tracking-wider text-slate-400 font-semibold hidden sm:block">Step 3</p>
                    <p class="step-title text-[11px] sm:text-xs font-bold truncate">Content & TOC</p>
                </div>
            </button>

            <!-- Step 4 Tab -->
            <button type="button" onclick="goToStep(4)" id="step-tab-4" class="step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left hover:bg-slate-50 text-slate-600">
                <div class="step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-extrabold shrink-0">4</div>
                <div class="min-w-0 text-center sm:text-left">
                    <p class="text-[10px] sm:text-[11px] uppercase tracking-wider text-slate-400 font-semibold hidden sm:block">Step 4</p>
                    <p class="step-title text-[11px] sm:text-xs font-bold truncate">Files & PDFs</p>
                </div>
            </button>

            <!-- Step 5 Tab -->
            <button type="button" onclick="goToStep(5)" id="step-tab-5" class="step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left hover:bg-slate-50 text-slate-600">
                <div class="step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-extrabold shrink-0">5</div>
                <div class="min-w-0 text-center sm:text-left">
                    <p class="text-[10px] sm:text-[11px] uppercase tracking-wider text-slate-400 font-semibold hidden sm:block">Step 5</p>
                    <p class="step-title text-[11px] sm:text-xs font-bold truncate">SEO & Audience</p>
                </div>
            </button>

        </div>
    </div>

    <!-- Main Multi-Step Form -->
    <form id="book-form" action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- STEP 1: BASIC INFORMATION -->
        <div id="step-content-1" class="step-content space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg font-bold shadow-2xs">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Step 1: Basic Information</h2>
                        <p class="text-xs text-slate-500">Provide the title, author, and primary category</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Title -->
                    <div class="space-y-1.5">
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Book Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                            placeholder="e.g. Masterclass in Scalable Distributed Architecture"
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('title') border-rose-300 bg-rose-50/50 @enderror">
                        @error('title')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        <p class="text-[11px] text-slate-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-link text-[10px] text-brand-500"></i>
                            <span>Current Slug: <span class="font-mono font-bold text-slate-700">{{ $book->slug }}</span> (auto-refreshes on title update)</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Author Name -->
                        <div class="space-y-1.5">
                            <label for="author_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Author Name <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-user-pen text-xs"></i>
                                </span>
                                <input type="text" name="author_name" id="author_name" value="{{ old('author_name', $book->author_name) }}" required
                                    placeholder="e.g. Dr. Julian Hayes"
                                    class="w-full pl-9 pr-3.5 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('author_name') border-rose-300 bg-rose-50/50 @enderror">
                            </div>
                            @error('author_name')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <!-- Category -->
                        <div class="space-y-1.5">
                            <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Category <span class="text-rose-500">*</span></label>
                            <select name="category_id" id="category_id" required class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer @error('category_id') border-rose-300 @enderror">
                                <option value="">Select Category...</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->title }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <!-- Status -->
                        <div class="space-y-1.5">
                            <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Status <span class="text-rose-500">*</span></label>
                            <select name="status" id="status" required class="w-full px-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium cursor-pointer">
                                @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $book->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Featured Toggle -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Featured Recommendation</label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:border-amber-300 hover:bg-amber-50/30 transition select-none">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded text-amber-500 border-slate-300 focus:ring-amber-400 cursor-pointer">
                                <div class="text-xs flex-1">
                                    <p class="font-bold text-slate-800">Mark as Featured</p>
                                    <p class="text-slate-500">Show on homepage and top picks</p>
                                </div>
                                <i class="fa-solid fa-star text-amber-400 text-base"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 1 Actions -->
            <div class="flex items-center justify-between pt-2">
                <span class="text-xs text-slate-400">Step 1 of 5</span>
                <button type="button" onclick="nextStep(1)" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <span>Next: Pricing & Specifications</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: PRICING & SPECIFICATIONS -->
        <div id="step-content-2" class="step-content hidden space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold shadow-2xs">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Step 2: Pricing & Book Specifications</h2>
                            <p class="text-xs text-slate-500">Configure retail pricing, discount, and book specifications</p>
                        </div>
                    </div>
                    <div id="discount-badge" class="hidden px-3.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt text-emerald-500 text-xs"></i>
                        <span><span id="discount-percent">0</span>% OFF</span>
                        <span id="discount-savings" class="text-xs text-emerald-600 font-medium hidden sm:inline">(Save ₹<span id="savings-amount">0</span>)</span>
                    </div>
                </div>

                <!-- Pricing Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Regular / Original Price -->
                    <div class="space-y-1.5">
                        <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Original / MRP Price (₹) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-sm">₹</span>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $book->price) }}" required
                                oninput="calculateDiscount()"
                                class="w-full pl-9 pr-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-semibold @error('price') border-rose-300 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400">Regular printed / MRP price</p>
                        @error('price')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Selling Price -->
                    <div class="space-y-1.5">
                        <label for="selling_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Selling / Offer Price (₹) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-600 font-bold text-sm">₹</span>
                            <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price', $book->selling_price) }}" required
                                oninput="calculateDiscount()"
                                class="w-full pl-9 pr-4 py-3 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-emerald-500 focus:outline-none transition font-extrabold text-emerald-700 @error('selling_price') border-rose-300 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400">Actual customer checkout price</p>
                        @error('selling_price')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Specifications Section -->
                <div class="pt-4 border-t border-slate-100 space-y-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-brand-600 text-sm"></i>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Book Technical Specifications</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Length / Pages -->
                        <div class="space-y-1.5">
                            <label for="pages" class="block text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-file-lines text-slate-400 text-xs"></i>
                                <span>Length (Pages)</span>
                            </label>
                            <input type="number" name="pages" id="pages" value="{{ old('pages', $book->pages ?? '368') }}" placeholder="e.g. 368"
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                            <p class="text-[11px] text-slate-400">Total page count</p>
                        </div>

                        <!-- Language -->
                        <div class="space-y-1.5">
                            <label for="language" class="block text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-language text-slate-400 text-xs"></i>
                                <span>Language</span>
                            </label>
                            <input type="text" name="language" id="language" value="{{ old('language', $book->language ?? 'English') }}" placeholder="e.g. English"
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                            <p class="text-[11px] text-slate-400">Primary book language</p>
                        </div>

                        <!-- Format -->
                        <div class="space-y-1.5">
                            <label for="format" class="block text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-file-pdf text-slate-400 text-xs"></i>
                                <span>Format</span>
                            </label>
                            <input type="text" name="format" id="format" value="{{ old('format', $book->format ?? 'PDF, EPUB') }}" placeholder="e.g. PDF, EPUB"
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                            <p class="text-[11px] text-slate-400">File format(s) provided</p>
                        </div>

                        <!-- File Size -->
                        <div class="space-y-1.5">
                            <label for="file_size" class="block text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-hard-drive text-slate-400 text-xs"></i>
                                <span>File Size</span>
                            </label>
                            <input type="text" name="file_size" id="file_size" value="{{ old('file_size', $book->file_size ?? '14.1 MB') }}" placeholder="e.g. 14.1 MB"
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                            <p class="text-[11px] text-slate-400">Auto-filled if uploaded</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 Actions -->
            <div class="flex items-center justify-between pt-2">
                <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back: Basic Info</span>
                </button>
                <button type="button" onclick="nextStep(2)" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <span>Next: Content & Highlights</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: CONTENT & CURRICULUM -->
        <div id="step-content-3" class="step-content hidden space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold shadow-2xs">
                        <i class="fa-solid fa-align-left"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Step 3: About, Highlights & Table of Contents</h2>
                        <p class="text-xs text-slate-500">Add detailed summary, bullet point highlights, and chapter syllabus</p>
                    </div>
                </div>

                <!-- About / Description -->
                <div class="space-y-1.5">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">About the Book (Description)</label>
                    <textarea name="description" id="description" rows="5"
                        placeholder="Write a clear, engaging overview and summary of this e-book..."
                        class="w-full p-4 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition leading-relaxed resize-y @error('description') border-rose-300 @enderror"
                    >{{ old('description', $book->description) }}</textarea>
                    @error('description')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Key Highlights (One point per line) -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="key_highlights" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Key Highlights</label>
                            <p class="text-[11px] text-slate-400">Type one key takeaway or bullet point on each line</p>
                        </div>
                        <button type="button" onclick="insertSampleHighlights()" class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold transition border border-amber-200/80 cursor-pointer">
                            <i class="fa-solid fa-wand-magic-sparkles text-[10px] mr-1"></i> Sample Bullets
                        </button>
                    </div>
                    <textarea name="key_highlights" id="key_highlights" rows="5"
                        placeholder="Master core algorithmic design patterns
Detailed memory layout & CPU cache optimization
Hands-on concurrency and thread-safe data structures
Real-world performance benchmarking frameworks"
                        class="w-full p-4 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition leading-relaxed resize-y @error('key_highlights') border-rose-300 @enderror"
                    >{{ old('key_highlights', $book->key_highlights) }}</textarea>
                    @error('key_highlights')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Table of Contents -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="table_of_contents" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Table of Contents</label>
                            <p class="text-[11px] text-slate-400">Outline chapters, parts, and sections</p>
                        </div>
                        <button type="button" onclick="insertSampleTOC()" class="px-2.5 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-semibold transition border border-brand-200/80 cursor-pointer">
                            <i class="fa-solid fa-wand-magic-sparkles text-[10px] mr-1"></i> Sample Structure
                        </button>
                    </div>
                    <textarea name="table_of_contents" id="table_of_contents" rows="6"
                        placeholder="Chapter 1: Foundations of High-Throughput Architectures
Chapter 2: Memory Locality, Cache Lines & Register Utilization
Chapter 3: Lock-Free & Wait-Free Data Structures
Chapter 4: Deterministic Distributed Consensus Protocols
Chapter 5: Production Case Studies & Bottleneck Hunting"
                        class="w-full p-4 rounded-xl bg-slate-50 focus:bg-white text-slate-900 placeholder-slate-400 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition leading-relaxed resize-y @error('table_of_contents') border-rose-300 @enderror"
                    >{{ old('table_of_contents', $book->table_of_contents) }}</textarea>
                    @error('table_of_contents')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Step 3 Actions -->
            <div class="flex items-center justify-between pt-2">
                <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back: Pricing</span>
                </button>
                <button type="button" onclick="nextStep(3)" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <span>Next: Files & Media</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 4: FILES & DOWNLOADS (NO LIMIT) -->
        <div id="step-content-4" class="step-content hidden space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold shadow-2xs">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Step 4: Artwork & Digital Files Upload</h2>
                        <p class="text-xs text-slate-500">Upload book cover image, free sample preview PDF, and full e-book file (No file size limits)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- 1. Cover Image Upload -->
                    <div class="space-y-3 p-5 rounded-2xl bg-slate-50 border border-slate-200/90 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-purple-700 font-bold text-sm mb-1">
                                <i class="fa-solid fa-image"></i>
                                <span>1. Cover Artwork</span>
                            </div>
                            <p class="text-[11px] text-slate-500">JPG, PNG, WEBP, SVG, AVIF (No size limit)</p>
                        </div>

                        <div id="cover-preview-container" class="{{ $book->cover_image_url ? '' : 'hidden' }} relative rounded-xl overflow-hidden border border-slate-200 bg-white aspect-3/4 max-w-[170px] mx-auto shadow-sm group">
                            <img id="cover-preview-img" src="{{ $book->cover_image_url }}" alt="Cover preview" class="w-full h-full object-cover">
                            <button type="button" onclick="removeCoverImage()" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div id="cover-dropzone" class="{{ $book->cover_image_url ? 'hidden' : '' }} border-2 border-dashed border-slate-300 hover:border-purple-400 bg-white rounded-xl p-5 text-center transition cursor-pointer" onclick="document.getElementById('cover_image').click()">
                            <i class="fa-solid fa-cloud-arrow-up text-purple-600 text-2xl mb-2"></i>
                            <p class="text-xs font-bold text-slate-800">Upload Cover</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Click or drag image</p>
                            <input type="file" name="cover_image" id="cover_image" accept="image/*" class="hidden" onchange="previewCoverImage(this)">
                        </div>
                        @error('cover_image')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- 2. Sample Download PDF Upload -->
                    <div class="space-y-3 p-5 rounded-2xl bg-slate-50 border border-slate-200/90 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-sky-700 font-bold text-sm mb-1">
                                <i class="fa-solid fa-file-arrow-down"></i>
                                <span>2. Sample Preview PDF</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Free preview sample document for visitors</p>
                        </div>

                        <div id="sample-preview-box" class="{{ $book->sample_file ? '' : 'hidden' }} p-3 rounded-xl bg-sky-50 border border-sky-200 text-xs text-sky-800 flex items-center justify-between">
                            <div class="flex items-center gap-2 truncate">
                                <i class="fa-solid fa-file-pdf text-sky-600 text-base shrink-0"></i>
                                <span id="sample-filename" class="font-semibold truncate">{{ basename($book->sample_file ?? 'sample.pdf') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if($book->sample_file_url)
                                <a href="{{ $book->sample_file_url }}" target="_blank" class="text-sky-700 hover:text-sky-900 font-bold underline text-[11px]">View</a>
                                @endif
                                <button type="button" onclick="removeSampleFile()" class="text-sky-600 hover:text-rose-600 p-1 cursor-pointer">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>

                        <div id="sample-dropzone" class="{{ $book->sample_file ? 'hidden' : '' }} border-2 border-dashed border-slate-300 hover:border-sky-400 bg-white rounded-xl p-5 text-center transition cursor-pointer" onclick="document.getElementById('sample_file').click()">
                            <i class="fa-solid fa-file-pdf text-sky-600 text-2xl mb-2"></i>
                            <p class="text-xs font-bold text-slate-800">Upload Sample PDF</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">PDF, EPUB, DOC (No size limit)</p>
                            <input type="file" name="sample_file" id="sample_file" accept=".pdf,.epub,.doc,.docx" class="hidden" onchange="previewSampleFile(this)">
                        </div>
                        @error('sample_file')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- 3. Full E-Book PDF Upload -->
                    <div class="space-y-3 p-5 rounded-2xl bg-slate-50 border border-slate-200/90 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-emerald-700 font-bold text-sm mb-1">
                                <i class="fa-solid fa-book-bookmark"></i>
                                <span>3. Full E-Book PDF</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Complete paid e-book file provided upon purchase</p>
                        </div>

                        <div id="ebook-preview-box" class="{{ $book->ebook_file ? '' : 'hidden' }} p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center justify-between">
                            <div class="flex items-center gap-2 truncate">
                                <i class="fa-solid fa-file-shield text-emerald-600 text-base shrink-0"></i>
                                <span id="ebook-filename" class="font-semibold truncate">{{ basename($book->ebook_file ?? 'ebook.pdf') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if($book->ebook_file_url)
                                <a href="{{ $book->ebook_file_url }}" target="_blank" class="text-emerald-700 hover:text-emerald-900 font-bold underline text-[11px]">View</a>
                                @endif
                                <button type="button" onclick="removeEbookFile()" class="text-emerald-600 hover:text-rose-600 p-1 cursor-pointer">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>

                        <div id="ebook-dropzone" class="{{ $book->ebook_file ? 'hidden' : '' }} border-2 border-dashed border-slate-300 hover:border-emerald-400 bg-white rounded-xl p-5 text-center transition cursor-pointer" onclick="document.getElementById('ebook_file').click()">
                            <i class="fa-solid fa-file-shield text-emerald-600 text-2xl mb-2"></i>
                            <p class="text-xs font-bold text-slate-800">Upload Full E-Book</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">PDF, EPUB, ZIP (No size limit)</p>
                            <input type="file" name="ebook_file" id="ebook_file" accept=".pdf,.epub,.mobi,.zip,.rar,.doc,.docx" class="hidden" onchange="previewEbookFile(this)">
                        </div>
                        @error('ebook_file')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- 4. Gallery Images Upload -->
                    <div class="col-span-1 md:col-span-3 space-y-3 p-5 rounded-2xl bg-slate-50 border border-slate-200/90 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-indigo-700 font-bold text-sm mb-1">
                                <i class="fa-solid fa-images"></i>
                                <span>4. Gallery Images</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Upload multiple images to show in the book details page (JPG, PNG, WEBP)</p>
                        </div>

                        @php $hasGallery = !empty($book->gallery_images); @endphp
                        <div id="gallery-preview-container" class="{{ $hasGallery ? '' : 'hidden' }} grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 mt-3">
                            @if($hasGallery)
                                @foreach($book->gallery_image_urls as $url)
                                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-white aspect-[10/7] shadow-sm gallery-existing-image">
                                        <img src="{{ $url }}" alt="Gallery image" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div id="gallery-dropzone" class="{{ $hasGallery ? 'hidden' : '' }} border-2 border-dashed border-slate-300 hover:border-indigo-400 bg-white rounded-xl p-5 text-center transition cursor-pointer mt-3" onclick="document.getElementById('gallery_images').click()">
                            <i class="fa-solid fa-cloud-arrow-up text-indigo-600 text-2xl mb-2"></i>
                            <p class="text-xs font-bold text-slate-800">Upload Gallery Images</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Click to select multiple images (replaces existing)</p>
                            <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple class="hidden" onchange="previewGalleryImages(this)">
                        </div>
                        <div class="flex justify-end items-center {{ $hasGallery ? '' : 'hidden' }}" id="gallery-actions">
                            <button type="button" onclick="removeGalleryImages()" class="text-xs text-rose-600 hover:text-rose-700 font-bold px-3 py-1.5 rounded hover:bg-rose-50 cursor-pointer transition">
                                <i class="fa-solid fa-trash-can mr-1"></i> Clear All
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 {{ $hasGallery ? '' : 'hidden' }}" id="gallery-warning"><i class="fa-solid fa-circle-info text-sky-500"></i> Note: Uploading new images will replace all existing gallery images.</p>
                        @error('gallery_images.*')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            <!-- Step 4 Actions -->
            <div class="flex items-center justify-between pt-2">
                <button type="button" onclick="goToStep(3)" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back: Content</span>
                </button>
                <button type="button" onclick="nextStep(4)" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <span>Next: SEO & Audience</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 5: SEO & AUDIENCE -->
        <div id="step-content-5" class="step-content hidden space-y-6">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg font-bold shadow-2xs">
                        <i class="fa-solid fa-magnifying-glass-chart"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Step 5: Target Audience & SEO Settings</h2>
                        <p class="text-xs text-slate-500">Multi-select reader tags and optimize search engine metadata</p>
                    </div>
                </div>

                <!-- Suggested For (Multi-selection + Custom Options with Icons & Max 8 Limit) -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Suggested For (Target Audience)</label>
                            <p class="text-[11px] text-slate-400">Choose or add custom target groups who will benefit most from reading this book</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="toggleAllAudiences(true)" class="text-xs text-brand-600 hover:text-brand-800 font-bold px-2 py-1 rounded hover:bg-brand-50 cursor-pointer">Select All</button>
                            <span class="text-slate-300">|</span>
                            <button type="button" onclick="toggleAllAudiences(false)" class="text-xs text-slate-500 hover:text-slate-700 font-semibold px-2 py-1 rounded hover:bg-slate-100 cursor-pointer">Clear</button>
                        </div>
                    </div>

                    @php 
                        $currentSuggested = (array) old('suggested_for', $book->suggested_for ?? []); 
                        $presetTitles = array_column($suggestedAudiences, 'title');

                        // Extract active titles for checkboxes
                        $currentTitles = array_map(function($item) {
                            if (is_array($item)) return $item['title'] ?? '';
                            return str_contains($item, ':::') ? explode(':::', $item)[0] : $item;
                        }, $currentSuggested);

                        // Extract custom items
                        $customItems = [];
                        foreach ($currentSuggested as $item) {
                            if (is_array($item)) {
                                $t = trim((string) ($item['title'] ?? ''));
                                $ic = trim((string) ($item['icon'] ?? 'fa-solid fa-user-tag'));
                                if ($t && !in_array($t, $presetTitles)) {
                                    $customItems[] = ['title' => $t, 'icon' => $ic ?: 'fa-solid fa-user-tag'];
                                }
                            } elseif (is_string($item)) {
                                if (str_contains($item, ':::')) {
                                    [$t, $ic] = explode(':::', $item, 2);
                                    $t = trim($t);
                                    $ic = trim($ic);
                                } else {
                                    $t = trim($item);
                                    $ic = 'fa-solid fa-user-tag';
                                }
                                if ($t && !in_array($t, $presetTitles)) {
                                    $customItems[] = ['title' => $t, 'icon' => $ic ?: 'fa-solid fa-user-tag'];
                                }
                            }
                        }
                    @endphp

                    <!-- Standard Predefined Audiences with Icons -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                        @foreach($suggestedAudiences as $audience)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-brand-300 hover:bg-brand-50/30 transition cursor-pointer select-none group">
                            <input type="checkbox" name="suggested_for[]" value="{{ $audience['title'] }}"
                                {{ in_array($audience['title'], $currentTitles) ? 'checked' : '' }}
                                class="audience-checkbox w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500 cursor-pointer">
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-700 group-hover:text-slate-900 min-w-0">
                                <i class="{{ $audience['icon'] }} text-brand-600 text-xs shrink-0"></i>
                                <span class="truncate">{{ $audience['title'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <!-- Custom Audiences Section Header & Container -->
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500">Custom Target Audiences</label>
                            <span id="custom-count-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ count($customItems) >= 8 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ count($customItems) }} / 8 Added (Max 8)
                            </span>
                        </div>
                        <div id="custom-audiences-container" class="flex flex-wrap gap-2 min-h-[38px] p-3 rounded-xl bg-slate-50 border border-dashed border-slate-200">
                            @forelse($customItems as $custom)
                                <div class="custom-audience-chip inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-brand-200 shadow-2xs text-brand-800 text-xs font-semibold">
                                    <input type="checkbox" name="suggested_for[]" value="{{ $custom['title'] }}:::{{ $custom['icon'] }}" checked class="audience-checkbox w-3.5 h-3.5 rounded text-brand-600 border-slate-300 focus:ring-brand-500 cursor-pointer">
                                    <i class="{{ $custom['icon'] }} text-brand-600 text-xs"></i>
                                    <span>{{ $custom['title'] }}</span>
                                    <button type="button" onclick="removeCustomAudienceChip(this)" class="text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                            @empty
                                <span id="custom-empty-hint" class="text-xs text-slate-400 italic py-0.5">No custom audiences added yet. You can add up to 8 custom audience roles with custom icons below.</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Add Custom Audience Form: Title Input + Icon Picker + Add Button -->
                    <div class="p-3.5 rounded-2xl bg-slate-50/90 border border-slate-200 space-y-2.5">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                            <!-- Title Input -->
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                    <i class="fa-solid fa-pen-fancy"></i>
                                </span>
                                <input type="text" id="custom-audience-input" placeholder="Type custom audience title (e.g. UPSC Aspirants, Product Managers)..."
                                    onkeydown="if(event.key === 'Enter') { event.preventDefault(); addCustomAudience(); }"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white text-slate-900 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition">
                            </div>

                            <!-- Selected Icon Trigger & Dropdown Popover -->
                            <div class="relative shrink-0">
                                <button type="button" id="icon-picker-btn" onclick="toggleIconPicker()" class="w-full sm:w-auto flex items-center justify-between gap-2.5 px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 hover:border-brand-400 text-slate-700 text-xs font-semibold cursor-pointer shadow-2xs transition">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                                            <i id="selected-icon-preview" class="fa-solid fa-user-tag"></i>
                                        </span>
                                        <span id="selected-icon-label" class="truncate max-w-[90px]">Audience Tag</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                </button>
                                <input type="hidden" id="selected-icon-input" value="fa-solid fa-user-tag">

                                <!-- Icon Picker Grid Dropdown -->
                                <div id="icon-picker-dropdown" class="hidden absolute right-0 bottom-full sm:bottom-auto sm:top-full mb-2 sm:mb-0 sm:mt-2 z-50 w-72 sm:w-80 p-3 bg-white rounded-2xl shadow-xl border border-slate-200 space-y-2">
                                    <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                                        <p class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Select Audience Icon</p>
                                        <button type="button" onclick="toggleIconPicker(false)" class="text-slate-400 hover:text-slate-600 text-xs cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="grid grid-cols-6 gap-1.5 max-h-48 overflow-y-auto p-1">
                                        @foreach($availableIcons as $icon)
                                        <button type="button" onclick="selectAudienceIcon('{{ $icon['class'] }}', '{{ $icon['label'] }}')"
                                            title="{{ $icon['label'] }}"
                                            class="audience-icon-opt w-10 h-10 rounded-xl border border-slate-200 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-600 text-slate-600 flex items-center justify-center text-sm transition cursor-pointer">
                                            <i class="{{ $icon['class'] }}"></i>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Add Button -->
                            <button type="button" id="add-custom-btn" onclick="addCustomAudience()" class="px-4.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shrink-0 cursor-pointer transition flex items-center justify-center gap-1.5 shadow-2xs">
                                <i class="fa-solid fa-plus text-[11px]"></i> Add Option
                            </button>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400">
                            <span><i class="fa-solid fa-circle-info mr-1"></i> Type title, choose an icon, and click Add (Max 8 custom audiences).</span>
                            <span id="max-limit-warning" class="hidden text-rose-600 font-semibold">Maximum limit of 8 reached</span>
                        </div>
                    </div>

                    @error('suggested_for')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- SEO Meta Configuration -->
                <div class="pt-6 border-t border-slate-100 space-y-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-globe text-brand-600 text-sm"></i>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Search Engine Optimization (SEO)</h3>
                    </div>

                    <!-- Meta Title -->
                    <div class="space-y-1.5">
                        <label for="meta_title" class="block text-xs font-semibold text-slate-700">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $book->meta_title) }}" placeholder="e.g. Masterclass in Scalable Distributed Architecture | E-Book"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                        <p class="text-[11px] text-slate-400">Recommended length: 50-60 characters</p>
                    </div>

                    <!-- Meta Description -->
                    <div class="space-y-1.5">
                        <label for="meta_description" class="block text-xs font-semibold text-slate-700">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3"
                            placeholder="Brief search snippet summarizing the book value proposition..."
                            class="w-full p-4 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition leading-relaxed resize-y"
                        >{{ old('meta_description', $book->meta_description) }}</textarea>
                        <p class="text-[11px] text-slate-400">Recommended length: 150-160 characters</p>
                    </div>

                    <!-- Meta Keywords -->
                    <div class="space-y-1.5">
                        <label for="meta_keywords" class="block text-xs font-semibold text-slate-700">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $book->meta_keywords) }}" placeholder="e.g. system design, distributed systems, architecture, algorithms, coding"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 focus:bg-white text-slate-900 text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium">
                        <p class="text-[11px] text-slate-400">Comma-separated keyword phrases</p>
                    </div>
                </div>
            </div>

            <!-- Step 5 Actions -->
            <div class="flex items-center justify-between pt-2">
                <button type="button" onclick="goToStep(4)" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back: Files</span>
                </button>
                <button type="submit" class="px-7 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-bold shadow-md transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-check-double text-xs"></i>
                    <span>Save & Update E-Book</span>
                </button>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 5;

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;

        // Hide all step contents
        for (let i = 1; i <= totalSteps; i++) {
            const content = document.getElementById('step-content-' + i);
            const tab = document.getElementById('step-tab-' + i);
            const num = tab.querySelector('.step-num');

            if (content) {
                content.classList.toggle('hidden', i !== step);
            }

            if (tab) {
                if (i === step) {
                    tab.className = 'step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left bg-brand-50 border border-brand-200 text-brand-700 font-bold';
                    num.className = 'step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center text-xs font-extrabold shrink-0 shadow-2xs';
                } else if (i < step) {
                    tab.className = 'step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left bg-emerald-50/60 border border-emerald-200/70 text-emerald-800 font-bold';
                    num.className = 'step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-extrabold shrink-0 shadow-2xs';
                } else {
                    tab.className = 'step-tab flex flex-col sm:flex-row items-center gap-1.5 sm:gap-3 p-2 sm:p-3 rounded-xl transition cursor-pointer text-left hover:bg-slate-50 text-slate-600';
                    num.className = 'step-num w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-extrabold shrink-0';
                }
            }
        }

        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function nextStep(fromStep) {
        // Validate required inputs on Step 1
        if (fromStep === 1) {
            const title = document.getElementById('title').value.trim();
            const author = document.getElementById('author_name').value.trim();
            const category = document.getElementById('category_id').value;

            if (!title) {
                alert('Please enter the book title.');
                document.getElementById('title').focus();
                return;
            }
            if (!author) {
                alert('Please enter the author name.');
                document.getElementById('author_name').focus();
                return;
            }
            if (!category) {
                alert('Please select a category.');
                document.getElementById('category_id').focus();
                return;
            }
        }

        // Validate required inputs on Step 2
        if (fromStep === 2) {
            const price = document.getElementById('price').value;
            const selling = document.getElementById('selling_price').value;
            if (price === '' || parseFloat(price) < 0) {
                alert('Please enter a valid original price.');
                document.getElementById('price').focus();
                return;
            }
            if (selling === '' || parseFloat(selling) < 0) {
                alert('Please enter a valid selling price.');
                document.getElementById('selling_price').focus();
                return;
            }
        }

        goToStep(fromStep + 1);
    }

    function calculateDiscount() {
        const price = parseFloat(document.getElementById('price').value) || 0;
        const selling = parseFloat(document.getElementById('selling_price').value) || 0;
        const badge = document.getElementById('discount-badge');
        const percentSpan = document.getElementById('discount-percent');
        const savingsSpan = document.getElementById('savings-amount');

        if (price > 0 && price > selling && selling > 0) {
            const discount = Math.round(((price - selling) / price) * 100);
            const savings = (price - selling).toFixed(2);
            percentSpan.textContent = discount;
            savingsSpan.textContent = savings;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function insertSampleHighlights() {
        const sample = `In-depth architectural patterns for scalable systems\nMemory efficiency, CPU cache locality and zero-allocation techniques\nHands-on concurrent data structure implementations\nReal-world production benchmarks and post-mortem analysis`;
        document.getElementById('key_highlights').value = sample;
    }

    function insertSampleTOC() {
        const sample = `Chapter 1: System Foundations & Latency Realities\nChapter 2: Memory Hierarchy & Cache Lines\nChapter 3: Lock-Free & Concurrent Primitives\nChapter 4: Scalable Distributed Consensus\nChapter 5: Production Bottleneck Hunting`;
        document.getElementById('table_of_contents').value = sample;
    }

    function toggleAllAudiences(checked) {
        document.querySelectorAll('.audience-checkbox').forEach(cb => {
            cb.checked = checked;
        });
    }

    function previewCoverImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover-preview-img').src = e.target.result;
                document.getElementById('cover-preview-container').classList.remove('hidden');
                document.getElementById('cover-dropzone').classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeCoverImage() {
        document.getElementById('cover_image').value = '';
        document.getElementById('cover-preview-img').src = '';
        document.getElementById('cover-preview-container').classList.add('hidden');
        document.getElementById('cover-dropzone').classList.remove('hidden');
    }

    let galleryDataTransfer = new DataTransfer();

    function previewGalleryImages(input) {
        const container = document.getElementById('gallery-preview-container');
        const actions = document.getElementById('gallery-actions');
        const warning = document.getElementById('gallery-warning');
        
        if (input.files && input.files.length > 0) {
            // Remove existing server images on first upload
            const existingImages = container.querySelectorAll('.gallery-existing-image');
            existingImages.forEach(el => el.remove());

            container.classList.remove('hidden');
            actions.classList.remove('hidden');
            if (warning) warning.classList.remove('hidden');
            
            Array.from(input.files).forEach(file => {
                if(file.type.startsWith('image/')) {
                    // Check if file already exists in DataTransfer
                    let exists = false;
                    for (let i = 0; i < galleryDataTransfer.files.length; i++) {
                        if (galleryDataTransfer.files[i].name === file.name && galleryDataTransfer.files[i].size === file.size) {
                            exists = true;
                            break;
                        }
                    }
                    if (!exists) {
                        galleryDataTransfer.items.add(file);
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgBox = document.createElement('div');
                            imgBox.className = 'relative rounded-xl overflow-hidden border border-slate-200 bg-white aspect-[10/7] shadow-sm group new-gallery-preview';
                            imgBox.dataset.filename = file.name;
                            imgBox.innerHTML = `
                                <img src="${e.target.result}" alt="Gallery Preview" class="w-full h-full object-cover">
                                <button type="button" onclick="removeSingleGalleryImage('${file.name}', this)" class="absolute top-1 right-1 w-6 h-6 rounded-full bg-rose-500/90 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-rose-600">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            `;
                            // Append right before the dropzone if it exists, otherwise just append
                            const dropzone = document.getElementById('gallery-dropzone-inline');
                            if (dropzone) {
                                container.insertBefore(imgBox, dropzone);
                            } else {
                                container.appendChild(imgBox);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
            
            // Sync with actual input
            document.getElementById('gallery_images').files = galleryDataTransfer.files;
            
            // Hide main dropzone, show inline dropzone
            document.getElementById('gallery-dropzone').classList.add('hidden');
            
            if (!document.getElementById('gallery-dropzone-inline')) {
                const inlineDropzone = document.createElement('div');
                inlineDropzone.id = 'gallery-dropzone-inline';
                inlineDropzone.className = 'border-2 border-dashed border-slate-300 hover:border-indigo-400 bg-white rounded-xl aspect-[10/7] flex flex-col items-center justify-center transition cursor-pointer text-slate-400 hover:text-indigo-500';
                inlineDropzone.onclick = () => document.getElementById('gallery_images').click();
                inlineDropzone.innerHTML = `<i class="fa-solid fa-plus text-xl mb-1"></i><span class="text-[10px] font-bold uppercase tracking-wider">Add More</span>`;
                container.appendChild(inlineDropzone);
            }
        }
    }

    function removeSingleGalleryImage(filename, buttonElement) {
        const newDataTransfer = new DataTransfer();
        
        for (let i = 0; i < galleryDataTransfer.files.length; i++) {
            if (galleryDataTransfer.files[i].name !== filename) {
                newDataTransfer.items.add(galleryDataTransfer.files[i]);
            }
        }
        
        galleryDataTransfer = newDataTransfer;
        document.getElementById('gallery_images').files = galleryDataTransfer.files;
        
        // Remove preview UI
        buttonElement.closest('div').remove();
        
        // If empty, reset UI
        if (galleryDataTransfer.files.length === 0) {
            removeGalleryImages();
        }
    }

    function removeGalleryImages() {
        galleryDataTransfer = new DataTransfer();
        document.getElementById('gallery_images').files = galleryDataTransfer.files;
        document.getElementById('gallery-preview-container').innerHTML = '';
        document.getElementById('gallery-preview-container').classList.add('hidden');
        document.getElementById('gallery-actions').classList.add('hidden');
        const warning = document.getElementById('gallery-warning');
        if (warning) warning.classList.add('hidden');
        document.getElementById('gallery-dropzone').classList.remove('hidden');
    }

    function previewSampleFile(input) {
        if (input.files && input.files[0]) {
            document.getElementById('sample-filename').textContent = input.files[0].name;
            document.getElementById('sample-preview-box').classList.remove('hidden');
            document.getElementById('sample-dropzone').classList.add('hidden');
        }
    }

    function removeSampleFile() {
        document.getElementById('sample_file').value = '';
        document.getElementById('sample-preview-box').classList.add('hidden');
        document.getElementById('sample-dropzone').classList.remove('hidden');
    }

    function previewEbookFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('ebook-filename').textContent = file.name;
            document.getElementById('ebook-preview-box').classList.remove('hidden');
            document.getElementById('ebook-dropzone').classList.add('hidden');

            // Auto-calculate file size if field is empty or default
            const bytes = file.size;
            const sizeStr = bytes >= 1048576 
                ? (bytes / 1048576).toFixed(1) + ' MB' 
                : (bytes / 1024).toFixed(1) + ' KB';
            const sizeInput = document.getElementById('file_size');
            if (sizeInput) {
                sizeInput.value = sizeStr;
            }
        }
    }

    function removeEbookFile() {
        document.getElementById('ebook_file').value = '';
        document.getElementById('ebook-preview-box').classList.add('hidden');
        document.getElementById('ebook-dropzone').classList.remove('hidden');
    }

    function toggleAllAudiences(select) {
        document.querySelectorAll('.audience-checkbox').forEach(cb => {
            cb.checked = select;
        });
    }

    let selectedCustomIcon = 'fa-solid fa-user-tag';
    let selectedCustomIconLabel = 'Audience Tag';

    function toggleIconPicker(show) {
        const dropdown = document.getElementById('icon-picker-dropdown');
        if (!dropdown) return;
        if (show === undefined) {
            dropdown.classList.toggle('hidden');
        } else if (show) {
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    }

    function selectAudienceIcon(cls, label) {
        selectedCustomIcon = cls;
        selectedCustomIconLabel = label;
        const input = document.getElementById('selected-icon-input');
        const preview = document.getElementById('selected-icon-preview');
        const lbl = document.getElementById('selected-icon-label');

        if (input) input.value = cls;
        if (preview) preview.className = cls;
        if (lbl) lbl.textContent = label;
        toggleIconPicker(false);
    }

    document.addEventListener('click', function(e) {
        const btn = document.getElementById('icon-picker-btn');
        const dropdown = document.getElementById('icon-picker-dropdown');
        if (dropdown && !dropdown.classList.contains('hidden')) {
            if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        }
    });

    function updateCustomAudienceCount() {
        const chips = document.querySelectorAll('.custom-audience-chip');
        const count = chips.length;
        const badge = document.getElementById('custom-count-badge');
        const addBtn = document.getElementById('add-custom-btn');
        const warning = document.getElementById('max-limit-warning');

        if (badge) {
            badge.textContent = `${count} / 8 Added (Max 8)`;
            if (count >= 8) {
                badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200';
            } else {
                badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200';
            }
        }

        if (count >= 8) {
            if (addBtn) {
                addBtn.disabled = true;
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            if (warning) warning.classList.remove('hidden');
        } else {
            if (addBtn) {
                addBtn.disabled = false;
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            if (warning) warning.classList.add('hidden');
        }
    }

    function removeCustomAudienceChip(btn) {
        const chip = btn.closest('.custom-audience-chip');
        if (chip) {
            chip.remove();
            const container = document.getElementById('custom-audiences-container');
            if (container && container.querySelectorAll('.custom-audience-chip').length === 0) {
                container.innerHTML = '<span id="custom-empty-hint" class="text-xs text-slate-400 italic py-0.5">No custom audiences added yet. You can add up to 8 custom audience roles with custom icons below.</span>';
            }
            updateCustomAudienceCount();
        }
    }

    function addCustomAudience() {
        const input = document.getElementById('custom-audience-input');
        const container = document.getElementById('custom-audiences-container');
        const emptyHint = document.getElementById('custom-empty-hint');
        const val = input.value.trim();
        const icon = selectedCustomIcon || 'fa-solid fa-user-tag';

        if (!val) {
            input.focus();
            return;
        }

        const currentCount = document.querySelectorAll('.custom-audience-chip').length;
        if (currentCount >= 8) {
            alert('You can create a maximum of 8 custom target audiences.');
            return;
        }

        // Check if already exists in inputs
        const existing = Array.from(document.querySelectorAll('input[name="suggested_for[]"]')).find(cb => {
            const itemVal = cb.value.split(':::')[0].trim().toLowerCase();
            return itemVal === val.toLowerCase();
        });

        if (existing) {
            existing.checked = true;
            input.value = '';
            existing.parentElement.classList.add('ring-2', 'ring-brand-500');
            setTimeout(() => existing.parentElement.classList.remove('ring-2', 'ring-brand-500'), 1500);
            return;
        }

        if (emptyHint) {
            emptyHint.remove();
        }

        const chipValue = `${val}:::${icon}`;
        const chip = document.createElement('div');
        chip.className = 'custom-audience-chip inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-brand-200 shadow-2xs text-brand-800 text-xs font-semibold animate-fadeIn';
        chip.innerHTML = `
            <input type="checkbox" name="suggested_for[]" value="${chipValue.replace(/"/g, '&quot;')}" checked class="audience-checkbox w-3.5 h-3.5 rounded text-brand-600 border-slate-300 focus:ring-brand-500 cursor-pointer">
            <i class="${icon} text-brand-600 text-xs"></i>
            <span>${val}</span>
            <button type="button" onclick="removeCustomAudienceChip(this)" class="text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        `;

        container.appendChild(chip);
        input.value = '';
        updateCustomAudienceCount();
        input.focus();
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateDiscount();
        updateCustomAudienceCount();
    });
</script>
@endpush

@endsection
