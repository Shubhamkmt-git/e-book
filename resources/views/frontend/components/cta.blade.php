<!-- Dynamic CTA Component -->
<section class="py-16 sm:py-20 bg-slate-50">
    <div class="w-[96%] max-w-[96%] mx-auto px-2 sm:px-4">
        
        <!-- Minimal CTA Card -->
        <div class="relative overflow-hidden rounded-3xl border p-8 sm:p-12 lg:p-14 text-center shadow-[0_20px_50px_-20px_rgba(122,88,169,0.12)]"
             style="@if(!empty($cta) && $cta->bg_image) background-image: url('{{ $cta->bg_image_url }}'); background-size: cover; background-position: center; @else background: linear-gradient(135deg, #f5f3ff 0%, #fff 50%, #ede9fe 100%); @endif border-color: rgb(196 181 253 / 0.8);">
            
            @if(!empty($cta) && $cta->bg_image)
                <div class="absolute inset-0 bg-slate-950/60 rounded-3xl"></div>
            @endif

            <div class="relative z-10 max-w-2xl mx-auto">
                @if(!empty($cta) && $cta->label)
                    <!-- Top Tag -->
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full border text-xs font-bold uppercase tracking-wider mb-4
                                {{ $cta->bg_image ? 'bg-white/20 border-white/30 text-white backdrop-blur-sm' : 'bg-brand-100/80 border-brand-200 text-brand-700' }}">
                        <i class="fa-solid fa-sparkles text-xs"></i>
                        <span>{{ $cta->label }}</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-brand-100/80 border border-brand-200 text-brand-700 text-xs font-bold uppercase tracking-wider mb-4">
                        <i class="fa-solid fa-sparkles text-brand-600 text-xs"></i>
                        <span>Exclusive Reader Gift</span>
                    </div>
                @endif

                <!-- Main Heading -->
                @if(!empty($cta) && $cta->title)
                    <h2 class="font-brand text-4xl sm:text-5xl lg:text-6xl tracking-wide uppercase leading-[0.95]
                               {{ $cta->bg_image ? 'text-white' : 'text-slate-900' }}">
                        {{ $cta->title }}
                    </h2>
                @else
                    <h2 class="font-brand text-4xl sm:text-5xl lg:text-6xl text-slate-900 tracking-wide uppercase leading-[0.95]">
                        Get 3 Free Bestseller <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-600">E-Books Today</span>
                    </h2>
                @endif

                <!-- Description / Subtitle -->
                @if(!empty($cta) && $cta->subtitle)
                    <p class="text-sm sm:text-base mt-3.5 font-normal leading-relaxed
                              {{ $cta->bg_image ? 'text-white/80' : 'text-slate-600' }}">
                        {{ $cta->subtitle }}
                    </p>
                @else
                    <p class="text-sm sm:text-base text-slate-600 mt-3.5 font-normal leading-relaxed">
                        Join over 120,000+ passionate readers. Receive hand-picked book summaries, author releases, and special reader discounts directly in your inbox.
                    </p>
                @endif

                <!-- Clean Email Form -->
                <form class="mt-8 flex flex-col sm:flex-row items-center gap-3 max-w-md mx-auto" onsubmit="event.preventDefault();">
                    <input 
                        type="email" 
                        placeholder="Enter your email address..."
                        class="w-full bg-white text-slate-900 placeholder-slate-400 px-5 py-3 rounded-full text-sm border border-slate-200/90 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 focus:outline-none shadow-xs font-medium"
                        required
                    >
                    <button 
                        type="submit"
                        class="w-full sm:w-auto h-11 inline-flex items-center justify-center px-7 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase shadow-md shadow-brand-600/25 transition-all duration-150 whitespace-nowrap cursor-pointer transform hover:-translate-y-0.5"
                    >
                        <span>Claim Books</span>
                    </button>
                </form>


            </div>

        </div>

    </div>
</section>
