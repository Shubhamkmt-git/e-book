<!-- ==========================================
     GLOBAL QUICK CHECKOUT MODAL (INSTANT BUY NOW)
     ========================================== -->
<div id="quick-checkout-modal-backdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeQuickCheckoutModal()"></div>

@php
    $easebuzzActive = $appSetting->isEasebuzzEnabled();
    $razorpayActive = $appSetting->isRazorpayEnabled();
    $hasGateway = $easebuzzActive || $razorpayActive;
    $defaultGateway = $razorpayActive ? 'razorpay' : ($easebuzzActive ? 'easebuzz' : '');
@endphp

<div 
    id="quick-checkout-modal" 
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 opacity-0 pointer-events-none transition-all duration-300 transform scale-95"
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="quick-checkout-title"
>
    <div class="relative w-full max-w-[440px] bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden flex flex-col">
        
        <!-- Modal Compact Header -->
        <div class="relative bg-gradient-to-r from-brand-900 via-brand-800 to-brand-900 text-white px-5 py-3.5 shrink-0 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-brand-300 flex items-center justify-center text-xs shadow-inner">
                    <i class="fa-solid fa-shield-halved text-emerald-400 text-[11px]"></i>
                </div>
                <div>
                    <h3 id="quick-checkout-title" class="font-brand text-xl sm:text-2xl text-white tracking-wide uppercase leading-tight">
                        Secure Checkout
                    </h3>
                    <p class="text-[10px] text-brand-200/90 font-medium leading-none">Instant DRM-Free PDF Delivery</p>
                </div>
            </div>

            <button 
                type="button" 
                onclick="closeQuickCheckoutModal()" 
                class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer"
                aria-label="Close Checkout"
            >
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Modal Body (No Scroll, Compact & Clean) -->
        <div class="p-4 sm:p-5 space-y-3.5 bg-slate-50/40">
            
            <!-- Compact Selected Book Card -->
            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                <div class="w-11 h-14 rounded-lg overflow-hidden bg-slate-900 shrink-0 shadow-2xs border border-slate-200 relative">
                    <img id="checkout-book-image" src="{{ asset('images/books/algorithms.jpg') }}" alt="Book Cover" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <span id="checkout-book-category" class="text-[9px] font-bold text-brand-600 uppercase tracking-wider block leading-none mb-0.5">E-Book</span>
                    <h4 id="checkout-book-title" class="font-bold text-xs sm:text-sm text-slate-900 truncate leading-tight">Algorithms &amp; Elegance</h4>
                    <p id="checkout-book-author" class="text-[11px] text-slate-400 truncate">Author</p>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span id="checkout-book-price" class="font-brand text-lg text-brand-600 font-bold leading-none">₹499</span>
                        <span id="checkout-book-original-price" class="font-brand text-[11px] text-slate-400 line-through leading-none hidden">₹799</span>
                    </div>
                </div>
            </div>

            @if(! $hasGateway)
                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs text-center">
                    <p class="font-bold"><i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Checkout Unavailable</p>
                    <p class="text-[11px] text-slate-600 mt-0.5">Payment gateways are currently paused. Please contact support.</p>
                </div>
            @endif

            <!-- Checkout Form -->
            <form id="quick-checkout-form" method="POST" action="" class="space-y-2.5">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <label for="checkout-customer-name" class="block text-[11px] font-semibold text-slate-700 mb-1">Your Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-[11px]"></i>
                        </div>
                        <input 
                            type="text" 
                            id="checkout-customer-name" 
                            name="name" 
                            value="{{ auth('customer')->user()?->name ?? '' }}" 
                            placeholder="John Doe"
                            required
                            class="w-full pl-8.5 pr-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:border-brand-500 focus:ring-1 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800 font-medium"
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="checkout-customer-email" class="block text-[11px] font-semibold text-slate-700 mb-1">
                        Email Address <span class="text-brand-600 font-normal">(PDF delivery)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-[11px]"></i>
                        </div>
                        <input 
                            type="email" 
                            id="checkout-customer-email" 
                            name="email" 
                            value="{{ auth('customer')->user()?->email ?? '' }}" 
                            placeholder="you@example.com"
                            required
                            class="w-full pl-8.5 pr-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:border-brand-500 focus:ring-1 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800 font-medium"
                        >
                    </div>
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="checkout-customer-mobile" class="block text-[11px] font-semibold text-slate-700 mb-1">Mobile Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-[11px]"></i>
                        </div>
                        <input 
                            type="tel" 
                            id="checkout-customer-mobile" 
                            name="mobile" 
                            value="{{ auth('customer')->user()?->mobile ?? '' }}" 
                            placeholder="9876543210"
                            class="w-full pl-8.5 pr-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:border-brand-500 focus:ring-1 focus:ring-brand-500/20 outline-none transition bg-white text-slate-800 font-medium"
                        >
                    </div>
                </div>

                <!-- Payment Gateway Selector with Official Logos -->
                @if($easebuzzActive && $razorpayActive)
                    <div class="space-y-1 pt-0.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Payment Method</label>
                        <div class="grid grid-cols-2 gap-2">
                            
                            <!-- Razorpay Official Logo Card -->
                            <label class="relative flex flex-col justify-between p-2.5 rounded-xl border-2 cursor-pointer transition has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50/40 border-slate-200 bg-white hover:border-slate-300">
                                <input type="radio" name="payment_gateway_choice" value="razorpay" class="sr-only" checked onchange="updateGatewayChoice('razorpay')">
                                <div class="flex items-center justify-between">
                                    <div class="h-4.5 flex items-center">
                                        <!-- Razorpay Official SVG Wordmark & Emblem -->
                                        <svg class="h-4 w-auto" viewBox="0 0 120 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.5 0L5.2 13.8L9.8 18.4L20 0H14.5Z" fill="#0284C7"/>
                                            <path d="M5.2 13.8L0 21.6L8.4 28H13.6L5.2 13.8Z" fill="#0284C7"/>
                                            <path d="M9.8 18.4L2.8 28H8L15 18.4H9.8Z" fill="#0C2340"/>
                                            <text x="24" y="20" font-family="'Poppins', sans-serif" font-weight="700" font-size="15" fill="#0c2340">Razorpay</text>
                                        </svg>
                                    </div>
                                    <span class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center peer-checked:border-sky-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-600"></span>
                                    </span>
                                </div>
                                <span class="text-[9px] text-slate-400 mt-1 block leading-none">UPI, Cards, NetBanking</span>
                            </label>

                            <!-- Easebuzz Official Logo Card -->
                            <label class="relative flex flex-col justify-between p-2.5 rounded-xl border-2 cursor-pointer transition has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/40 border-slate-200 bg-white hover:border-slate-300">
                                <input type="radio" name="payment_gateway_choice" value="easebuzz" class="sr-only" onchange="updateGatewayChoice('easebuzz')">
                                <div class="flex items-center justify-between">
                                    <div class="h-4.5 flex items-center">
                                        <!-- Easebuzz Official SVG Logo & Wordmark -->
                                        <svg class="h-4 w-auto" viewBox="0 0 120 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="3" width="22" height="22" rx="5" fill="#FF5722"/>
                                            <path d="M7 8H17V10.5H10V12.5H16V15H10V17.5H17V20H7V8Z" fill="white"/>
                                            <text x="28" y="19" font-family="'Poppins', sans-serif" font-weight="700" font-size="14" fill="#1E293B">ease<tspan fill="#FF5722">buzz</tspan></text>
                                        </svg>
                                    </div>
                                    <span class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center peer-checked:border-orange-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                    </span>
                                </div>
                                <span class="text-[9px] text-slate-400 mt-1 block leading-none">UPI, Cards, Wallets</span>
                            </label>

                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="checkout-submit-btn"
                    {{ ! $hasGateway ? 'disabled' : '' }}
                    class="w-full h-11 inline-flex items-center justify-center gap-2 px-5 rounded-xl bg-brand-600 hover:bg-brand-500 active:bg-brand-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-brand text-lg uppercase tracking-wider transition-all shadow-md shadow-brand-600/25 text-center cursor-pointer mt-1"
                >
                    <span id="checkout-submit-btn-text">Proceed to Payment</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>

                <!-- Trust Guarantee Notice -->
                <div class="flex items-center justify-center gap-2.5 pt-0.5 text-[10px] text-slate-400">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-lock text-emerald-500 text-[9px]"></i> 256-Bit SSL Encrypted
                    </span>
                    <span>&bull;</span>
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-bolt text-amber-500 text-[9px]"></i> Instant Download
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
    const easebuzzEnabled = @json($easebuzzActive);
    const razorpayEnabled = @json($razorpayActive);
    let selectedGateway = @json($defaultGateway);
    let currentBookKey = '';
    let currentBookPriceText = '';

    window.updateGatewayChoice = function(gateway) {
        selectedGateway = gateway;
        updateFormAction();
    };

    function updateFormAction() {
        const form = document.getElementById('quick-checkout-form');
        if (!form || !currentBookKey) return;

        const gateway = selectedGateway || (razorpayEnabled ? 'razorpay' : 'easebuzz');
        form.action = `/ebooks/${encodeURIComponent(currentBookKey)}/payments/${gateway}`;
    }

    function resetSubmitButton() {
        const btn = document.getElementById('checkout-submit-btn');
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-wait');
            btn.innerHTML = `<span id="checkout-submit-btn-text">Proceed to Payment ${currentBookPriceText ? `(${currentBookPriceText})` : ''}</span> <i class="fa-solid fa-arrow-right text-xs"></i>`;
        }
    }

    function setSubmitLoading(message) {
        const btn = document.getElementById('checkout-submit-btn');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>${message || 'Processing...'}</span>`;
        }
    }

    // Launch direct seamless Razorpay Checkout in modal
    function launchRazorpayCheckout(orderData) {
        if (typeof Razorpay === 'undefined') {
            alert('Razorpay SDK failed to load. Please check your internet connection.');
            resetSubmitButton();
            return;
        }

        const options = {
            key: orderData.razorpay_key,
            amount: orderData.amount,
            currency: orderData.currency || 'INR',
            name: orderData.app_name || 'E-Book CMS',
            description: orderData.book_title || 'E-Book Publication',
            image: orderData.app_logo || '',
            order_id: orderData.razorpay_order_id,
            handler: function (response) {
                setSubmitLoading('Verifying payment & preparing your e-book...');

                // Hidden form post to callback URL
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = orderData.callback_url;

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const fields = {
                    '_token': csrfToken,
                    'razorpay_payment_id': response.razorpay_payment_id,
                    'razorpay_order_id': response.razorpay_order_id,
                    'razorpay_signature': response.razorpay_signature
                };

                for (const [k, v] of Object.entries(fields)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = k;
                    input.value = v;
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
            },
            prefill: {
                name: orderData.customer_name || 'Customer',
                email: orderData.customer_email || '',
                contact: orderData.customer_mobile || ''
            },
            theme: {
                color: '#0284c7'
            },
            modal: {
                ondismiss: function() {
                    resetSubmitButton();
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function (resp) {
            alert('Payment Failed: ' + (resp.error.description || 'Transaction unsuccessful.'));
            resetSubmitButton();
        });
        rzp.open();
    }

    // Universal Buy Now redirect / checkout handler
    window.initiateBookPurchase = function(bookData, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!bookData) return;

        currentBookKey = bookData.slug || bookData.id;
        currentBookPriceText = bookData.price || '';
        const gateway = selectedGateway || (razorpayEnabled ? 'razorpay' : 'easebuzz');

        // If customer is already authenticated and only Easebuzz exists, direct POST
        if (isCustomerAuthenticated && easebuzzEnabled && !razorpayEnabled) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/ebooks/${encodeURIComponent(currentBookKey)}/payments/easebuzz`;

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

        // Open the Quick Checkout Modal
        openQuickCheckoutModal(bookData);
    };

    window.openQuickCheckoutModal = function(bookData) {
        const backdrop = document.getElementById('quick-checkout-modal-backdrop');
        const modal = document.getElementById('quick-checkout-modal');
        const form = document.getElementById('quick-checkout-form');
        if (!backdrop || !modal || !form) return;

        currentBookKey = bookData.slug || bookData.id;
        currentBookPriceText = bookData.price || '';
        updateFormAction();

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

    // Handle form submit
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('quick-checkout-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const gateway = selectedGateway || (razorpayEnabled ? 'razorpay' : 'easebuzz');

                if (gateway === 'razorpay') {
                    e.preventDefault();
                    setSubmitLoading('Initializing Razorpay...');

                    const formData = new FormData(form);
                    const url = `/ebooks/${encodeURIComponent(currentBookKey)}/payments/razorpay`;

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        return response.json().then(json => {
                            if (!response.ok) {
                                throw new Error(json.message || 'Payment initiation failed.');
                            }
                            return json;
                        });
                    })
                    .then(data => {
                        if (data.status === 'mock_redirect' && data.redirect_url) {
                            window.location.href = data.redirect_url;
                            return;
                        }

                        if (data.status === 'success' && data.razorpay_order_id) {
                            closeQuickCheckoutModal();
                            launchRazorpayCheckout(data);
                        } else {
                            throw new Error(data.message || 'Invalid response from payment gateway.');
                        }
                    })
                    .catch(err => {
                        alert(err.message || 'Unable to start Razorpay payment. Please try again.');
                        resetSubmitButton();
                    });
                } else {
                    // Easebuzz standard post redirect
                    setSubmitLoading('Connecting to Easebuzz...');
                }
            });
        }
    });
})();
</script>
