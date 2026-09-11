<!-- Frontend Plain Brand Color Footer (#7A58A9) -->
<footer class="bg-brand-600 text-white border-t border-brand-700 pt-12 pb-8">
    <div class="w-[96%] max-w-[96%] mx-auto px-4 sm:px-6">
        
        <!-- Main Columned Grid (Brand, Quick Links, Top Genres) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12 pb-10 border-b border-white/15">
            
            <!-- Column 1: Brand Info & Socials (Col Span 5 on lg) -->
            <div class="sm:col-span-2 lg:col-span-5 space-y-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    @if (!empty($appSetting?->logo_dark_url))
                        <img
                            src="{{ $appSetting->logo_dark_url }}"
                            alt="{{ $appSetting->app_name ?? 'Logo' }}"
                            class="max-w-[160px] max-h-10 object-contain"
                        >
                    @elseif (!empty($appSetting?->logo_light_url))
                        <img
                            src="{{ $appSetting->logo_light_url }}"
                            alt="{{ $appSetting->app_name ?? 'Logo' }}"
                            class="max-w-[160px] max-h-10 object-contain"
                        >
                    @else
                        <div class="w-10 h-10 rounded-2xl bg-white text-brand-600 flex items-center justify-center font-bold shadow-md shadow-brand-900/20 shrink-0">
                            <i class="fa-solid fa-book-open text-base text-brand-600"></i>
                        </div>
                        <span class="font-extrabold text-xl text-white font-roboto tracking-tight">
                            {{ $appSetting->app_name ?? config('app.name', 'E-Book') }}
                        </span>
                    @endif
                </a>

                <p class="text-xs sm:text-sm text-brand-100 leading-relaxed max-w-sm font-normal">
                    {{ $appSetting->app_short_description ?: 'Discover and download premium digital e-books, technical guides, and curated publications instantly.' }}
                </p>

                <!-- Support Email / Contact -->
                @if(!empty($appSetting?->contact_email))
                    <div class="pt-1 text-xs text-brand-100">
                        <span class="text-brand-200">Support: </span>
                        <a href="mailto:{{ $appSetting->contact_email }}" class="text-white hover:text-brand-200 transition-colors underline underline-offset-2 font-medium">
                            {{ $appSetting->contact_email }}
                        </a>
                    </div>
                @endif

                <!-- Social Media Pills -->
                <div class="flex items-center gap-2 pt-2">
                    @if(!empty($appSetting?->twitter_url))
                        <a href="{{ $appSetting->twitter_url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 border border-white/20 flex items-center justify-center text-xs transition shadow-2xs" title="Twitter / X" aria-label="Twitter">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    @else
                        <a href="#" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 border border-white/20 flex items-center justify-center text-xs transition shadow-2xs" title="Twitter / X" aria-label="Twitter">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    @endif

                    @if(!empty($appSetting?->linkedin_url))
                        <a href="{{ $appSetting->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 border border-white/20 flex items-center justify-center text-xs transition shadow-2xs" title="LinkedIn" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    @else
                        <a href="#" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 border border-white/20 flex items-center justify-center text-xs transition shadow-2xs" title="LinkedIn" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    @endif

                    @if(!empty($appSetting?->facebook_url))
                        <a href="{{ $appSetting->facebook_url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 border border-white/20 flex items-center justify-center text-xs transition shadow-2xs" title="Facebook" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    @endif

                    @if(!empty($appSetting?->instagram_url))
                        <a href="{{ $appSetting->instagram_url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 border border-white/20 flex items-center justify-center text-xs transition shadow-2xs" title="Instagram" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    @endif

                    @if(!empty($appSetting?->youtube_url))
                        <a href="{{ $appSetting->youtube_url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 border border-white/20 flex items-center justify-center text-xs transition shadow-2xs" title="YouTube" aria-label="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Column 2: Quick Links (Col Span 3 on lg) -->
            <div class="lg:col-span-3 space-y-3.5">
                <h3 class="text-xs font-bold text-white uppercase tracking-wider font-roboto">Quick Links</h3>
                <ul class="space-y-2 text-xs sm:text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                            <span>All E-Books</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#browse" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                            <span>Trending Titles</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#faqs" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                            <span>FAQs & Support</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy-policy') }}" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                            <span>Privacy Policy</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                            <span>Terms of Service</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Top Genres (Col Span 4 on lg) -->
            <div class="lg:col-span-4 space-y-3.5">
                <h3 class="text-xs font-bold text-white uppercase tracking-wider font-roboto">Top Genres</h3>
                <ul class="space-y-2 text-xs sm:text-sm">
                    @if(!empty($footerCategories) && $footerCategories->count() > 0)
                        @foreach($footerCategories as $genre)
                            <li>
                                <a href="{{ route('categories.show', $genre->slug) }}" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                                    <span>{{ $genre->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ route('categories.index') }}" class="text-brand-100 hover:text-white transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-angle-right text-[10px] text-brand-200"></i>
                                <span>Browse All Categories</span>
                            </a>
                        </li>
                    @endif
                    <li class="pt-1">
                        <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-white hover:text-brand-200 transition-colors inline-flex items-center gap-1">
                            <span>Explore all genres</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Payment Methods & Security Trust Row -->
        <div class="py-6 border-b border-white/15 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 text-center sm:text-left">
                <span class="text-xs font-semibold text-brand-100 flex items-center gap-1.5 uppercase tracking-wider">
                    <i class="fa-solid fa-lock text-brand-200 text-xs"></i>
                    <span>Secure Payments:</span>
                </span>
                
                <!-- Payment Badges / Official SVG Icons -->
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-2.5">
                    
                    <!-- UPI Official Logo -->
                    <span class="inline-flex items-center justify-center h-7 px-2.5 rounded-lg bg-white shadow-2xs" title="UPI - Unified Payments Interface">
                        <img src="{{ asset('images/payments/upi.svg') }}" alt="UPI" class="h-4 w-auto object-contain">
                    </span>

                    <!-- Visa Official Logo -->
                    <span class="inline-flex items-center justify-center h-7 px-2.5 rounded-lg bg-white shadow-2xs" title="Visa">
                        <img src="{{ asset('images/payments/visa.svg') }}" alt="Visa" class="h-3.5 w-auto object-contain">
                    </span>

                    <!-- Mastercard Official Logo -->
                    <span class="inline-flex items-center justify-center h-7 px-2.5 rounded-lg bg-white shadow-2xs" title="Mastercard">
                        <img src="{{ asset('images/payments/mastercard.svg') }}" alt="Mastercard" class="h-4 w-auto object-contain">
                    </span>

                    <!-- RuPay Official Logo -->
                    <span class="inline-flex items-center justify-center h-7 px-2.5 rounded-lg bg-white shadow-2xs" title="RuPay">
                        <img src="{{ asset('images/payments/rupay.svg') }}" alt="RuPay" class="h-3.5 w-auto object-contain">
                    </span>

                    <!-- Google Pay Official Logo -->
                    <span class="inline-flex items-center justify-center h-7 px-2.5 rounded-lg bg-white shadow-2xs" title="Google Pay">
                        <img src="{{ asset('images/payments/gpay.svg') }}" alt="Google Pay" class="h-3.5 w-auto object-contain">
                    </span>

                    <!-- Paytm Official Logo -->
                    <span class="inline-flex items-center justify-center h-7 px-2.5 rounded-lg bg-white shadow-2xs" title="Paytm">
                        <img src="{{ asset('images/payments/paytm.svg') }}" alt="Paytm" class="h-3.5 w-auto object-contain">
                    </span>

                    <!-- PhonePe Official Logo -->
                    <span class="inline-flex items-center justify-center h-7 px-2.5 rounded-lg bg-white shadow-2xs" title="PhonePe">
                        <img src="{{ asset('images/payments/phonepe.svg') }}" alt="PhonePe" class="h-4 w-auto object-contain">
                    </span>

                    <!-- Net Banking -->
                    <span class="inline-flex items-center gap-1.5 h-7 px-2.5 rounded-lg bg-white/15 text-white border border-white/20 text-xs font-semibold" title="Net Banking">
                        <i class="fa-solid fa-building-columns text-[10px]"></i>
                        <span>NetBanking</span>
                    </span>

                    <!-- Credit / Debit Cards -->
                    <span class="inline-flex items-center gap-1.5 h-7 px-2.5 rounded-lg bg-white/15 text-white border border-white/20 text-xs font-semibold" title="Debit & Credit Cards">
                        <i class="fa-solid fa-credit-card text-[10px]"></i>
                        <span>All Cards</span>
                    </span>

                </div>
            </div>

            <!-- Trust Badge -->
            <div class="flex items-center gap-2 text-xs text-brand-100 shrink-0">
                <i class="fa-solid fa-shield-halved text-brand-200"></i>
                <span class="text-[11px] font-medium">256-Bit SSL Encrypted • Powered by Easebuzz</span>
            </div>
        </div>

        <!-- Bottom Minimal Row: Copyright & Security -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-brand-100">
            <p>&copy; {{ date('Y') }} <span class="text-white font-bold">{{ $appSetting->app_name ?? config('app.name', 'E-Book') }}</span>. All rights reserved.</p>
            
            <div class="flex items-center gap-3 text-[11px] text-brand-200">
                <span class="inline-flex items-center gap-1.5 text-white">
                    <i class="fa-solid fa-circle-check text-emerald-300 text-[10px]"></i> 100% Verified Downloads
                </span>
                <span class="text-brand-300">•</span>
                <span>DRM-Free Reads</span>
            </div>
        </div>

    </div>
</footer>
