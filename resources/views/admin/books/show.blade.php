@extends('admin.layouts.app')

@section('title', 'E-Book Details: ' . $book->title)

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
                <span class="text-brand-600 font-semibold truncate max-w-[200px]">{{ $book->title }}</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">{{ $book->title }}</h1>
                @if($book->is_featured)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 text-xs font-bold">
                    <i class="fa-solid fa-star text-[10px]"></i> Featured
                </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-2">
                <span class="font-medium text-slate-700"><i class="fa-solid fa-user-pen text-xs text-brand-600 mr-1"></i>{{ $book->author_name }}</span>
                <span class="text-slate-300">•</span>
                @if($book->category)
                <span class="inline-flex items-center gap-1 text-slate-600 font-medium">
                    @if($book->category->icon)
                    <i class="{{ $book->category->icon }} text-brand-600 text-xs"></i>
                    @endif
                    <span>{{ $book->category->title }}</span>
                </span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.books.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to List
            </a>
            <a href="{{ route('admin.books.edit', $book) }}" class="px-4.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-pen-to-square text-xs mr-1"></i> Edit E-Book
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Description, Highlights, Table of Contents -->
        <div class="xl:col-span-2 space-y-6">

            <!-- About The Book -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm font-bold shadow-2xs"><i class="fa-solid fa-align-left"></i></div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">About the Book</h2>
                        <p class="text-[11px] text-slate-400">Comprehensive overview & description</p>
                    </div>
                </div>
                <div class="text-sm text-slate-700 leading-relaxed space-y-3 font-normal">
                    @if($book->description)
                        <p class="whitespace-pre-line">{{ $book->description }}</p>
                    @else
                        <p class="text-slate-400 italic">No description provided for this e-book.</p>
                    @endif
                </div>
            </div>

            <!-- Key Highlights (Bullet Points) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shadow-2xs"><i class="fa-solid fa-list-check"></i></div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Key Highlights</h2>
                            <p class="text-[11px] text-slate-400">Core takeaways & takeaways</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200/70">
                        {{ count($book->highlights_list) }} points
                    </span>
                </div>

                @if(count($book->highlights_list) > 0)
                <ul class="space-y-2.5">
                    @foreach($book->highlights_list as $highlight)
                    <li class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50/80 border border-slate-100 text-sm text-slate-800">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5 shadow-2xs">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="font-medium leading-relaxed">{{ $highlight }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-xs text-slate-400 italic">No key highlights recorded.</p>
                @endif
            </div>

            <!-- Table of Contents -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm font-bold shadow-2xs"><i class="fa-solid fa-bars-staggered"></i></div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Table of Contents</h2>
                        <p class="text-[11px] text-slate-400">Chapters & syllabus covered</p>
                    </div>
                </div>

                @if($book->table_of_contents)
                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200/80 text-sm text-slate-800 whitespace-pre-line leading-relaxed font-normal">
                    {{ $book->table_of_contents }}
                </div>
                @else
                <p class="text-xs text-slate-400 italic">No table of contents outlined.</p>
                @endif
            </div>

            <!-- Specifications Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-sm font-bold shadow-2xs"><i class="fa-solid fa-circle-info"></i></div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">E-Book Specifications</h2>
                        <p class="text-[11px] text-slate-400">Format, length, language & size</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1"><i class="fa-solid fa-file-lines mr-1 text-brand-500"></i> Length</p>
                        <p class="text-sm font-bold text-slate-800">{{ $book->pages ? $book->pages . ' Pages' : '—' }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1"><i class="fa-solid fa-language mr-1 text-emerald-500"></i> Language</p>
                        <p class="text-sm font-bold text-slate-800">{{ $book->language ?: 'English' }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1"><i class="fa-solid fa-file-pdf mr-1 text-rose-500"></i> Format</p>
                        <p class="text-sm font-bold text-slate-800">{{ $book->format ?: 'PDF' }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-center">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1"><i class="fa-solid fa-hard-drive mr-1 text-amber-500"></i> File Size</p>
                        <p class="text-sm font-bold text-slate-800">{{ $book->file_size ?: '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Digital Files / PDF Downloads -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold shadow-2xs"><i class="fa-solid fa-file-arrow-down"></i></div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Digital Files & Downloads</h2>
                        <p class="text-[11px] text-slate-400">Sample preview PDF & full purchased e-book package</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Sample PDF -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 text-base">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-slate-800">Sample Chapter / Preview</h3>
                                <p class="text-[11px] text-slate-500">Free download for previewing</p>
                            </div>
                        </div>
                        @if($book->sample_file_url)
                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                            <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> Available
                            </span>
                            <a href="{{ $book->sample_file_url }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-2xs transition">
                                <i class="fa-solid fa-download text-[11px]"></i> Download Sample
                            </a>
                        </div>
                        @else
                        <div class="pt-2 border-t border-slate-200/60">
                            <span class="text-[11px] text-slate-400 italic flex items-center gap-1">
                                <i class="fa-solid fa-circle-xmark"></i> No sample file uploaded
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Full E-Book PDF -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center shrink-0 text-base">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-slate-800">Complete E-Book File</h3>
                                <p class="text-[11px] text-slate-500">Delivered to customers upon purchase</p>
                            </div>
                        </div>
                        @if($book->ebook_file_url)
                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                            <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> Uploaded
                            </span>
                            <a href="{{ $book->ebook_file_url }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-2xs transition">
                                <i class="fa-solid fa-download text-[11px]"></i> Download E-Book
                            </a>
                        </div>
                        @else
                        <div class="pt-2 border-t border-slate-200/60">
                            <span class="text-[11px] text-slate-400 italic flex items-center gap-1">
                                <i class="fa-solid fa-circle-xmark"></i> No e-book file uploaded
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SEO & Meta Tags Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold shadow-2xs"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Search Engine Optimization (SEO)</h2>
                        <p class="text-[11px] text-slate-400">Metadata for Google & social sharing</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Meta Title</label>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $book->meta_title ?: $book->title }}</p>
                    </div>
                    <div>
                        <label class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Meta Description</label>
                        <p class="text-slate-700 mt-0.5 leading-relaxed">{{ $book->meta_description ?: 'No meta description provided.' }}</p>
                    </div>
                    <div>
                        <label class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Meta Keywords</label>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @if($book->meta_keywords)
                                @foreach(explode(',', $book->meta_keywords) as $keyword)
                                    @if(trim($keyword))
                                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 text-xs font-medium">{{ trim($keyword) }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-slate-400 italic">No keywords specified.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Cover, Pricing, Details, Audience -->
        <div class="space-y-6">

            <!-- Cover & Pricing Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <!-- Cover Image -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-3/4 max-w-[220px] mx-auto shadow-md">
                    @if($book->cover_image_url)
                        <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-brand-600 to-brand-900 flex flex-col items-center justify-center text-white p-4 text-center">
                            <i class="fa-solid fa-book-open text-4xl mb-3 text-brand-200"></i>
                            <span class="text-xs font-bold leading-snug line-clamp-3">{{ $book->title }}</span>
                        </div>
                    @endif
                </div>

                <!-- Price Box -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Selling Price</p>
                    <div class="flex items-center justify-center gap-2.5">
                        <span class="text-2xl font-extrabold text-emerald-700">₹{{ number_format((float) $book->selling_price, 2) }}</span>
                        @if((float) $book->price > (float) $book->selling_price)
                        <span class="text-sm text-slate-400 line-through">₹{{ number_format((float) $book->price, 2) }}</span>
                        @endif
                    </div>
                    @if($book->discount_percentage > 0)
                    <div class="pt-1">
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold">
                            Save {{ $book->discount_percentage }}% OFF
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Meta Details List -->
                <div class="space-y-3 pt-2 border-t border-slate-100 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Status</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $book->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $book->status === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            {{ ucfirst($book->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Slug</span>
                        <span class="font-mono text-slate-700 font-semibold truncate max-w-[150px]">{{ $book->slug }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Created</span>
                        <span class="text-slate-700 font-medium">{{ $book->created_at?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Last Updated</span>
                        <span class="text-slate-700 font-medium">{{ $book->updated_at?->format('M d, Y') ?? '—' }}</span>
                    </div>
                </div>
            </div>

            <!-- Suggested For Audience -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-bold shadow-2xs"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Suggested For</h2>
                        <p class="text-[11px] text-slate-400">Target audience badges</p>
                    </div>
                </div>

                @if(!empty($book->suggested_for_list) && count($book->suggested_for_list) > 0)
                <div class="flex flex-wrap gap-1.5">
                    @foreach($book->suggested_for_list as $item)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sky-50 text-sky-700 border border-sky-200/80 text-xs font-semibold">
                        <i class="{{ $item['icon'] }} text-[11px] text-sky-600"></i> {{ $item['title'] }}
                    </span>
                    @endforeach
                </div>
                @else
                <p class="text-xs text-slate-400 italic">No specific audience selected.</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
