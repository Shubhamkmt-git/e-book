<!-- ==========================================
     GLOBAL QUICK CHECKOUT MODAL (CASHFREE PAYMENTS)
     ========================================== -->
<div id="quick-checkout-modal-backdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeQuickCheckoutModal()"></div>

<div 
    id="quick-checkout-modal" 
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 opacity-0 pointer-events-none transition-all duration-300 transform scale-95"
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="quick-checkout-title"
>
    <div class="relative w-full max-w-[460px] sm:max-w-[480px] bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden flex flex-col">
        
        <!-- Modal Compact Header -->
        <div class="relative bg-gradient-to-r from-brand-900 via-brand-800 to-brand-900 text-white px-5 py-4 shrink-0 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-brand-300 flex items-center justify-center text-sm shadow-inner">
                    <i class="fa-solid fa-shield-halved text-emerald-400 text-xs"></i>
                </div>
                <div>
                    <h3 id="quick-checkout-title" class="font-brand font-bold text-xl sm:text-2xl text-white tracking-wide uppercase leading-tight">
                        Secure Checkout
                    </h3>
                    <p class="text-[11px] text-brand-200/90 font-medium leading-none mt-0.5">Instant DRM-Free PDF &bull; Cashfree Verified</p>
                </div>
            </div>

            <button 
                type="button" 
                onclick="closeQuickCheckoutModal()" 
                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer"
                aria-label="Close Checkout"
            >
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 sm:p-6 space-y-4 bg-slate-50/40">
            
            <!-- Error Banner -->
            <div id="checkout-error-banner" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
                <span id="checkout-error-text">Payment initialization failed.</span>
            </div>

            <!-- Selected Book Card -->
            <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
                <div class="w-12 h-16 rounded-xl overflow-hidden bg-slate-900 shrink-0 shadow-2xs border border-slate-200 relative">
                    <img id="checkout-book-image" src="{{ asset('images/books/algorithms.jpg') }}" alt="Book Cover" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <span id="checkout-book-category" class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block leading-none mb-1">E-Book</span>
                    <h4 id="checkout-book-title" class="font-bold text-sm sm:text-base text-slate-900 truncate leading-tight">Selected Book</h4>
                    <p id="checkout-book-author" class="text-xs text-slate-400 truncate mt-0.5">Author</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span id="checkout-book-price" class="font-brand text-xl text-brand-600 font-bold leading-none">₹499</span>
                        <span id="checkout-book-original-price" class="font-brand text-xs text-slate-400 line-through leading-none hidden">₹799</span>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <form id="quick-checkout-form" method="POST" action="" class="space-y-3.5" onsubmit="handleQuickCheckoutSubmit(event)">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <label for="checkout-customer-name" class="block text-xs font-semibold text-slate-700 mb-1.5">Your Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-sm"></i>
                        </div>
                        <input 
                            type="text" 
                            id="checkout-customer-name" 
                            name="name" 
                            value="{{ auth('customer')->user()?->name ?? '' }}" 
                            placeholder="John Doe"
                            class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-200 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800 font-medium placeholder-slate-400"
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="checkout-customer-email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email Address <span class="text-brand-600 font-normal text-[11px]">(PDF delivery)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </div>
                        <input 
                            type="email" 
                            id="checkout-customer-email" 
                            name="email" 
                            value="{{ auth('customer')->user()?->email ?? '' }}" 
                            placeholder="you@example.com"
                            class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-200 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800 font-medium placeholder-slate-400"
                        >
                    </div>
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="checkout-customer-mobile" class="block text-xs font-semibold text-slate-700 mb-1.5">Mobile Number (UPI / Cards)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </div>
                        <input 
                            type="tel" 
                            id="checkout-customer-mobile" 
                            name="mobile" 
                            value="{{ auth('customer')->user()?->mobile ?? '' }}" 
                            placeholder="9876543210"
                            class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-200 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800 font-medium placeholder-slate-400"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="checkout-submit-btn"
                    class="w-full h-12 sm:h-13 inline-flex items-center justify-center gap-2.5 px-6 rounded-xl sm:rounded-2xl bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl uppercase tracking-wider transition-all shadow-md shadow-brand-600/25 text-center cursor-pointer mt-2"
                >
                    <span id="checkout-submit-btn-text">Proceed to Pay</span>
                    <i class="fa-solid fa-lock text-xs"></i>
                </button>

                <!-- Payment Trust Footer -->
                <div class="pt-2 flex items-center justify-center gap-2 text-[11px] text-slate-400">
                    <i class="fa-solid fa-shield-check text-emerald-500 text-xs"></i>
                    <span>Secured with 256-bit encryption by <strong>Cashfree Payments</strong></span>
                </div>

            </form>

        </div>

    </div>
</div>

<!-- Cashfree Web JS SDK v3 -->
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

<!-- ==========================================
     GLOBAL QUICK CHECKOUT JAVASCRIPT ENGINE
     ========================================== -->
<script>
(function() {
    const isCustomerAuthenticated = @json(auth('customer')->check());
    const cashfreeMode = @json(strtoupper((string) config('services.cashfree.env', 'SANDBOX')) === 'PRODUCTION' ? 'production' : 'sandbox');
    let currentBookKey = '';
    let currentBookPriceText = '';
    let cashfreeInstance = null;

    function getCashfree() {
        if (!cashfreeInstance && typeof Cashfree !== 'undefined') {
            try {
                cashfreeInstance = Cashfree({ mode: cashfreeMode });
            } catch (e) {
                console.warn('Cashfree SDK initialization warning:', e);
            }
        }
        return cashfreeInstance;
    }

    function resetSubmitButton() {
        const btn = document.getElementById('checkout-submit-btn');
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-wait');
            btn.innerHTML = `<span id="checkout-submit-btn-text">Proceed to Pay ${currentBookPriceText ? `(${currentBookPriceText})` : ''}</span> <i class="fa-solid fa-lock text-xs"></i>`;
        }
    }

    function setSubmitLoading(message) {
        const btn = document.getElementById('checkout-submit-btn');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>${message || 'Connecting to Cashfree...'}</span>`;
        }
    }

    function showCheckoutError(msg) {
        const banner = document.getElementById('checkout-error-banner');
        const text = document.getElementById('checkout-error-text');
        if (banner && text) {
            text.textContent = msg || 'An error occurred. Please try again.';
            banner.classList.remove('hidden');
        }
    }

    function hideCheckoutError() {
        const banner = document.getElementById('checkout-error-banner');
        if (banner) {
            banner.classList.add('hidden');
        }
    }

    // Universal Buy Now redirect / checkout handler
    window.initiateBookPurchase = function(bookData, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!bookData) return;

        // If user is not logged in, open the Sign In drawer
        if (!isCustomerAuthenticated) {
            if (typeof openAuthDrawer === 'function') {
                openAuthDrawer('signin');
            }
            return;
        }

        currentBookKey = bookData.slug || bookData.id;
        currentBookPriceText = bookData.price || '';

        // Open the Quick Checkout Modal for instant confirmation and Cashfree drop-in
        openQuickCheckoutModal(bookData);
    };

    window.openQuickCheckoutModal = function(bookData) {
        if (!isCustomerAuthenticated) {
            if (typeof openAuthDrawer === 'function') {
                openAuthDrawer('signin');
            }
            return;
        }

        const backdrop = document.getElementById('quick-checkout-modal-backdrop');
        const modal = document.getElementById('quick-checkout-modal');
        if (!backdrop || !modal) return;

        currentBookKey = bookData.slug || bookData.id;
        currentBookPriceText = bookData.price || '';
        hideCheckoutError();

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

        resetSubmitButton();

        backdrop.classList.remove('pointer-events-none', 'opacity-0');
        backdrop.classList.add('pointer-events-auto', 'opacity-100');

        modal.classList.remove('pointer-events-none', 'opacity-0', 'scale-95');
        modal.classList.add('pointer-events-auto', 'opacity-100', 'scale-100');
        document.body.style.overflow = 'hidden';

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
            resetSubmitButton();
        }, 300);
    };

    window.handleQuickCheckoutSubmit = async function(event) {
        event.preventDefault();
        hideCheckoutError();
        setSubmitLoading('Initiating Cashfree Payment...');

        const form = document.getElementById('quick-checkout-form');
        const formData = new FormData(form);
        const name = formData.get('name') || '';
        const email = formData.get('email') || '';
        const mobile = formData.get('mobile') || '';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        try {
            const response = await fetch(`/ebooks/${encodeURIComponent(currentBookKey)}/purchase`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name, email, mobile })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Payment initiation failed.');
            }

            // If Cashfree session is returned
            if (data.gateway === 'cashfree' && data.payment_session_id) {
                const cf = getCashfree();
                if (cf) {
                    cf.checkout({
                        paymentSessionId: data.payment_session_id,
                        redirectTarget: "_modal"
                    }).then(function(result) {
                        if (result.error) {
                            showCheckoutError(result.error.message || 'Payment cancelled or failed.');
                            resetSubmitButton();
                        } else {
                            setSubmitLoading('Verifying payment...');
                            window.location.href = data.return_url;
                        }
                    });
                    return;
                } else if (data.return_url) {
                    window.location.href = data.return_url;
                    return;
                }
            }

            // Direct fulfillment / fallback
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
                return;
            }

            // Fallback reload
            window.location.reload();
        } catch (err) {
            console.error('Checkout error:', err);
            showCheckoutError(err.message || 'Payment connection error. Please try again.');
            resetSubmitButton();
        }
    };

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQuickCheckoutModal();
        }
    });
})();
</script>
