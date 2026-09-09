@php
    $testimonials = $testimonials ?? [
        [
            'name' => 'Sophia Martinez',
            'role' => 'Software Architect',
            'rating' => 5,
            'book_read' => 'Algorithms & Elegance',
            'quote' => 'The digital reading experience is buttery smooth. Being able to read technical diagrams seamlessly across both my tablet and laptop made studying on the go effortless.',
        ],
        [
            'name' => 'Dr. Raghavan Iyer',
            'role' => 'Physics Professor',
            'rating' => 5,
            'book_read' => 'Quantum Frontiers',
            'quote' => 'Instant offline access to high-quality academic publications and scientific literature has completely transformed how I review new research and prepare lectures.',
        ],
        [
            'name' => 'Elena Rostova',
            'role' => 'Product Designer',
            'rating' => 5,
            'book_read' => 'Whispers of the Nebula',
            'quote' => 'The typography and dark mode reading view are so easy on the eyes during late-night reading sessions. Easily the cleanest digital library platform I have used.',
        ],
    ];
@endphp

<!-- Minimal & Clean Testimonials Section -->
<section id="testimonials" class="py-16 sm:py-20 bg-slate-50">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Clean Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 sm:mb-12 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Reader Reviews</span>
                <h2 class="font-brand text-4xl sm:text-5xl text-slate-900 tracking-wide uppercase mt-1">What Readers Are Saying</h2>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 self-start sm:self-auto">
                <div class="flex items-center text-amber-400">
                    <i class="fa-solid fa-star text-xs"></i>
                    <i class="fa-solid fa-star text-xs"></i>
                    <i class="fa-solid fa-star text-xs"></i>
                    <i class="fa-solid fa-star text-xs"></i>
                    <i class="fa-solid fa-star text-xs"></i>
                </div>
                <span class="font-bold text-slate-800">4.9 / 5.0</span>
                <span class="text-slate-400 font-normal">(18,500+ reviews)</span>
            </div>
        </div>

        <!-- Minimal Review Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($testimonials as $testimonial)
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/80 hover:border-brand-200 hover:shadow-sm transition-all duration-200 flex flex-col justify-between">
                    
                    <div>
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-1 text-amber-400 mb-4">
                            @for ($i = 0; $i < $testimonial['rating']; $i++)
                                <i class="fa-solid fa-star text-[11px]"></i>
                            @endfor
                        </div>

                        <!-- Review Text -->
                        <p class="text-slate-600 text-sm leading-relaxed font-normal mb-5">
                            “{{ $testimonial['quote'] }}”
                        </p>
                    </div>

                    <!-- Author Info & Book Read -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-slate-900 leading-tight truncate">
                                {{ $testimonial['name'] }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5 truncate">
                                {{ $testimonial['role'] }}
                            </p>
                        </div>

                        <!-- Book Tag -->
                        <span class="text-[11px] font-medium text-brand-600 bg-brand-50/80 px-2.5 py-1 rounded-md shrink-0 truncate max-w-[140px]" title="{{ $testimonial['book_read'] }}">
                            {{ $testimonial['book_read'] }}
                        </span>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
