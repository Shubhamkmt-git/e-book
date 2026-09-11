<!-- ==========================================
     GLOBAL QUICK CHECKOUT MODAL (INSTANT BUY NOW)
     ========================================== -->
<div id="quick-checkout-modal-backdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeQuickCheckoutModal()"></div>

<div 
    id="quick-checkout-modal" 
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 opacity-0 pointer-events-none transition-all duration-300 transform scale-95"
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="quick-checkout-title"
>
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-brand-200/80 overflow-hidden flex flex-col">
        
        <!-- Modal Top Brand Header -->
        <div class="relative bg-gradient-to-r from-brand-900 via-brand-800 to-brand-900 text-white p-6 sm:p-7">
            <!-- Decorative Glow -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-brand-500/30 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-brand-300 flex items-center justify-center text-lg shadow-inner">
                        <i class="fa-solid fa-lock text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 id="quick-checkout-title" class="font-brand text-2xl sm:text-3xl text-white tracking-wide uppercase leading-tight">
                            Secure Checkout
                        </h3>
                        <p class="text-xs text-brand-200/90 font-medium">Instant Download & Email Delivery</p>
                    </div>
                </div>

                <button 
                    type="button" 
                    onclick="closeQuickCheckoutModal()" 
                    class="w-9 h-9 rounded-full bg-white/10 hover:bg-white text-white hover:text-slate-900 border border-white/20 flex items-center justify-center transition cursor-pointer"
                    aria-label="Close Checkout"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Selected Book Highlight Card -->
        <div class="p-6 sm:p-7 space-y-6 bg-slate-50/50">
            
            <div class="flex items-center gap-4 p-3.5 rounded-2xl bg-white border border-brand-100 shadow-2xs">
                <div class="w-20 aspect-[10/7] rounded-xl overflow-hidden bg-slate-950 shrink-0 shadow-sm border border-slate-200/80 relative">
                    <img id="checkout-book-image" src="{{ asset('images/books/algorithms.jpg') }}" alt="Book Cover" class="w-full h-full object-cover">
                    <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-r from-black/40 to-transparent pointer-events-none"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <span id="checkout-book-category" class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">E-Book</span>
                    <h4 id="checkout-book-title" class="font-bold text-sm sm:text-base text-slate-900 truncate leading-snug">Algorithms & Elegance</h4>
                    <p id="checkout-book-author" class="text-xs text-slate-400 truncate mt-0.5">Author</p>
                    <div class="flex items-baseline gap-2 mt-1.5">
                        <span id="checkout-book-price" class="font-brand text-2xl text-brand-600 font-bold leading-none">₹499</span>
                        <span id="checkout-book-original-price" class="font-brand text-xs text-slate-400 line-through leading-none hidden">₹799</span>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <form id="quick-checkout-form" method="POST" action="" class="space-y-4">
                @csrf
                
                <div>
                    <label for="checkout-customer-name" class="block text-xs font-semibold text-slate-700 mb-1.5">Your Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-xs"></i>
                        </div>
                        <input 
                            type="text" 
                            id="checkout-customer-name" 
                            name="name" 
                            value="{{ auth('customer')->user()?->name ?? '' }}" 
                            placeholder="e.g. John Doe"
                            required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800"
                        >
                    </div>
                </div>

                <div>
                    <label for="checkout-customer-email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email Address <span class="text-brand-600 font-normal">(PDF will be sent here)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                        <input 
                            type="email" 
                            id="checkout-customer-email" 
                            name="email" 
                            value="{{ auth('customer')->user()?->email ?? '' }}" 
                            placeholder="you@example.com"
                            required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800"
                        >
                    </div>
                </div>

                <div>
                    <label for="checkout-customer-mobile" class="block text-xs font-semibold text-slate-700 mb-1.5">Mobile Number <span class="text-slate-400 font-normal">(For Payment SMS)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </div>
                        <input 
                            type="tel" 
                            id="checkout-customer-mobile" 
                            name="mobile" 
                            value="{{ auth('customer')->user()?->mobile ?? '' }}" 
                            placeholder="9876543210"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="checkout-submit-btn"
                    class="w-full h-12 inline-flex items-center justify-center gap-2 px-6 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl uppercase tracking-wider transition-all duration-200 shadow-lg shadow-brand-600/30 text-center cursor-pointer mt-2"
                >
                    <span id="checkout-submit-btn-text">Proceed to Payment</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>

                <!-- Trust Guarantee Notice -->
                <div class="flex items-center justify-center gap-3 pt-2 text-[11px] text-slate-500">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-lock text-emerald-500 text-[10px]"></i> Easebuzz Secured
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-bolt text-amber-500 text-[10px]"></i> Instant PDF Delivery
                    </span>
                </div>

            </form>

        </div>

    </div>
</div>

<!-- ==========================================
     GLOBAL QUICK CHECKOUT JAVASCRIPT ENGINE
     ========================================== -->
<script>
(function() {
    const isCustomerAuthenticated = @json(auth('customer')->check());

    // Universal Buy Now redirect / checkout handler
    window.initiateBookPurchase = function(bookData, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!bookData) return;

        const bookKey = bookData.slug || bookData.id;
        const initiateUrl = `/ebooks/${encodeURIComponent(bookKey)}/payments/easebuzz`;

        // If customer is already authenticated, submit direct POST without extra prompt
        if (isCustomerAuthenticated) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = initiateUrl;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
            return;
        }

        // Otherwise, open the Quick Checkout Modal for instant guest/customer checkout
        openQuickCheckoutModal(bookData, initiateUrl);
    };

    window.openQuickCheckoutModal = function(bookData, initiateUrl) {
        const backdrop = document.getElementById('quick-checkout-modal-backdrop');
        const modal = document.getElementById('quick-checkout-modal');
        const form = document.getElementById('quick-checkout-form');
        if (!backdrop || !modal || !form) return;

        const bookKey = bookData.slug || bookData.id;
        form.action = initiateUrl || `/ebooks/${encodeURIComponent(bookKey)}/payments/easebuzz`;

        // Update book details in modal
        const titleEl = document.getElementById('checkout-book-title');
        const authorEl = document.getElementById('checkout-book-author');
        const categoryEl = document.getElementById('checkout-book-category');
        const priceEl = document.getElementById('checkout-book-price');
        const origPriceEl = document.getElementById('checkout-book-original-price');
        const imgEl = document.getElementById('checkout-book-image');
        const btnTextEl = document.getElementById('checkout-submit-btn-text');

        if (titleEl) titleEl.textContent = bookData.title || 'Selected E-Book';
        if (authorEl) authorEl.textContent = bookData.author || '';
        if (categoryEl) categoryEl.textContent = bookData.category || 'E-Book';
        if (priceEl) priceEl.textContent = bookData.price || '₹499';
        
        if (origPriceEl) {
            if (bookData.original_price) {
                origPriceEl.textContent = bookData.original_price;
                origPriceEl.classList.remove('hidden');
            } else {
                origPriceEl.classList.add('hidden');
            }
        }

        if (imgEl && bookData.image) {
            imgEl.src = bookData.image;
        }

        if (btnTextEl) {
            btnTextEl.textContent = `Proceed to Payment (${bookData.price || ''})`;
        }

        backdrop.classList.remove('pointer-events-none', 'opacity-0');
        backdrop.classList.add('pointer-events-auto', 'opacity-100');

        modal.classList.remove('pointer-events-none', 'opacity-0', 'scale-95');
        modal.classList.add('pointer-events-auto', 'opacity-100', 'scale-100');

        document.body.style.overflow = 'hidden';

        // Auto-focus first empty input
        setTimeout(() => {
            const nameInput = document.getElementById('checkout-customer-name');
            const emailInput = document.getElementById('checkout-customer-email');
            if (nameInput && !nameInput.value) {
                nameInput.focus();
            } else if (emailInput && !emailInput.value) {
                emailInput.focus();
            }
        }, 150);
    };

    window.closeQuickCheckoutModal = function() {
        const backdrop = document.getElementById('quick-checkout-modal-backdrop');
        const modal = document.getElementById('quick-checkout-modal');
        if (!backdrop || !modal) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');

        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            backdrop.classList.add('pointer-events-none');
            modal.classList.add('pointer-events-none');
            document.body.style.overflow = '';
        }, 300);
    };

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQuickCheckoutModal();
        }
    });

    // Handle submit loading state
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('quick-checkout-form');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('checkout-submit-btn');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-wait');
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-sm"></i> <span>Connecting to Payment...</span>';
                }
            });
        }
    });
})();
</script>
