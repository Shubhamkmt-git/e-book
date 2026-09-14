<!-- ==========================================
     FIREBASE PHONE OTP AUTHENTICATION SIDE DRAWER
     ========================================== -->
<div 
    id="auth-drawer-backdrop" 
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-[100] transition-opacity duration-250 opacity-0 pointer-events-none" 
    onclick="closeAuthDrawer()"
    aria-hidden="true"
></div>

<div 
    id="auth-drawer" 
    class="fixed inset-y-0 right-0 w-full max-w-[400px] bg-white z-[101] shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col justify-between overflow-y-auto"
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="auth-drawer-logo"
>
    <!-- Drawer Body -->
    <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between">
        <div>
            <!-- Header: Logo & Close Button -->
            <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                <div class="flex items-center">
                    @if (!empty($appSetting?->logo_light_url) || !empty($appSetting?->logo_dark_url))
                        <img 
                            id="auth-drawer-logo"
                            src="{{ $appSetting->logo_light_url ?? $appSetting->logo_dark_url }}" 
                            alt="{{ $appSetting->app_name ?? 'Logo' }}" 
                            class="max-w-[140px] max-h-9 object-contain"
                        >
                    @else
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <span id="auth-drawer-logo" class="font-bold text-lg text-slate-900 tracking-tight">
                                {{ $appSetting->app_name ?? 'E-Book' }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Close Button -->
                <button 
                    type="button" 
                    onclick="closeAuthDrawer()" 
                    class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors cursor-pointer"
                    aria-label="Close Drawer"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Dynamic Alert Container -->
            <div id="auth-drawer-alert" class="hidden mt-4 rounded-xl px-3.5 py-2.5 text-xs font-medium transition-all" role="alert"></div>

            @if ($errors->any())
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs text-rose-700" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Tab Mode Switcher -->
            <div class="mt-4 flex p-1 bg-slate-100 rounded-xl">
                <button 
                    type="button" 
                    id="tab-signin" 
                    onclick="setAuthMode('signin')" 
                    class="flex-1 py-1.5 text-xs font-semibold rounded-lg transition-all text-slate-900 bg-white shadow-2xs cursor-pointer"
                >
                    Sign In
                </button>
                <button 
                    type="button" 
                    id="tab-signup" 
                    onclick="setAuthMode('signup')" 
                    class="flex-1 py-1.5 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-900 cursor-pointer"
                >
                    Create Account
                </button>
            </div>

            <!-- Firebase Google Quick Sign-In -->
            <div class="mt-4">
                <button 
                    type="button" 
                    id="btn-google-auth"
                    onclick="handleGoogleSignIn(event)" 
                    class="w-full h-10 inline-flex items-center justify-center gap-2.5 px-4 rounded-xl bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 shadow-2xs transition-all cursor-pointer"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span id="google-auth-text">Continue with Google</span>
                </button>

                <!-- Minimal Divider -->
                <div class="relative flex py-3.5 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-[10px] font-medium uppercase tracking-wider text-slate-400">or with phone OTP</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>
            </div>

            <!-- ==========================================
                 STEP 1: DETAILS & PHONE NUMBER
                 ========================================== -->
            <div id="auth-phone-step" class="space-y-3">
                <!-- Signup-Only Fields: Name & Email -->
                <div id="field-signup-name" class="hidden">
                    <label for="customer-name" class="block text-xs font-medium text-slate-700 mb-1">Full Name</label>
                    <input 
                        type="text" 
                        id="customer-name" 
                        name="name" 
                        placeholder="John Doe"
                        class="w-full h-10 px-3.5 rounded-xl bg-slate-50/50 text-slate-900 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-600 focus:ring-2 focus:ring-brand-600/10 focus:outline-none transition font-medium"
                    >
                </div>

                <div id="field-signup-email" class="hidden">
                    <label for="customer-email" class="block text-xs font-medium text-slate-700 mb-1">Email Address</label>
                    <input 
                        type="email" 
                        id="customer-email" 
                        name="email" 
                        placeholder="you@example.com"
                        class="w-full h-10 px-3.5 rounded-xl bg-slate-50/50 text-slate-900 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-600 focus:ring-2 focus:ring-brand-600/10 focus:outline-none transition font-medium"
                    >
                </div>

                <!-- Common Field: Mobile Number -->
                <div>
                    <label for="customer-phone" class="block text-xs font-medium text-slate-700 mb-1">Mobile Number</label>
                    <div class="flex items-center gap-2">
                        <div class="h-10 px-3 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold flex items-center justify-center border border-slate-200 select-none">
                            <span id="country-code">+91</span>
                        </div>
                        <input 
                            type="tel" 
                            id="customer-phone" 
                            name="phone" 
                            placeholder="98765 43210"
                            required
                            maxlength="15"
                            class="flex-1 h-10 px-3.5 rounded-xl bg-slate-50/50 text-slate-900 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-600 focus:ring-2 focus:ring-brand-600/10 focus:outline-none transition font-medium"
                        >
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">We will send a 6-digit OTP code via SMS</p>
                </div>

                <!-- Invisible Recaptcha Container for Firebase -->
                <div id="recaptcha-container" class="my-1"></div>

                <button 
                    type="button" 
                    id="btn-send-phone-otp"
                    onclick="handleSendPhoneOtp(event)"
                    class="w-full h-10 inline-flex items-center justify-center gap-2 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-semibold text-xs transition cursor-pointer shadow-xs"
                >
                    <span id="send-otp-text">Send OTP</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>

                <!-- Bottom Toggle Helper Text -->
                <div class="text-center pt-2">
                    <p id="toggle-signin-prompt" class="text-xs text-slate-500">
                        Don't have an account? 
                        <button type="button" onclick="setAuthMode('signup')" class="text-brand-600 font-semibold hover:underline cursor-pointer">Sign Up</button>
                    </p>
                    <p id="toggle-signup-prompt" class="hidden text-xs text-slate-500">
                        Already have an account? 
                        <button type="button" onclick="setAuthMode('signin')" class="text-brand-600 font-semibold hover:underline cursor-pointer">Sign In</button>
                    </p>
                </div>
            </div>

            <!-- ==========================================
                 STEP 2: OTP VERIFICATION
                 ========================================== -->
            <div id="auth-otp-step" class="hidden space-y-4">
                <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase font-medium">OTP Sent to</span>
                        <span id="otp-sent-number" class="text-xs font-semibold text-slate-800 tracking-wide"></span>
                    </div>
                    <button 
                        type="button" 
                        onclick="resetToPhoneStep()" 
                        class="text-xs font-semibold text-brand-600 hover:text-brand-700 cursor-pointer underline"
                    >
                        Change
                    </button>
                </div>

                <div>
                    <label for="otp-code-input" class="block text-xs font-medium text-slate-700 mb-1">Enter 6-digit OTP Code</label>
                    <input 
                        type="text" 
                        id="otp-code-input" 
                        name="otp_code" 
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;"
                        maxlength="6"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        class="w-full h-12 text-center text-xl font-bold tracking-[0.4em] rounded-xl bg-slate-50/50 text-slate-900 placeholder-slate-300 border border-slate-200 focus:bg-white focus:border-brand-600 focus:ring-2 focus:ring-brand-600/10 focus:outline-none transition"
                    >
                </div>

                <button 
                    type="button" 
                    id="btn-verify-otp"
                    onclick="handleVerifyPhoneOtp(event)"
                    class="w-full h-10 inline-flex items-center justify-center gap-2 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-semibold text-xs transition cursor-pointer shadow-xs"
                >
                    <span id="verify-otp-text">Verify & Sign In</span>
                    <i class="fa-solid fa-check text-[10px]"></i>
                </button>

                <div class="text-center pt-1">
                    <button 
                        type="button" 
                        id="btn-resend-otp"
                        onclick="handleSendPhoneOtp(event)"
                        disabled
                        class="text-xs text-slate-400 hover:text-slate-600 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                    >
                        Resend OTP in <span id="resend-timer">30</span>s
                    </button>
                </div>
            </div>
        </div>

        <!-- Minimal Footer -->
        <div class="pt-4 border-t border-slate-100 text-center">
            <p class="text-[11px] text-slate-400">
                Secured by Firebase Phone Auth &bull; 256-bit Encrypted
            </p>
        </div>
    </div>
</div>

<!-- ==========================================
     FIREBASE SDK & CLIENT AUTH SCRIPT
     ========================================== -->
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>

<script>
    const firebaseConfig = {
        apiKey: @json(config('services.firebase.api_key')),
        authDomain: @json(config('services.firebase.auth_domain')),
        projectId: @json(config('services.firebase.project_id')),
    };

    let authMode = 'signin';
    let firebaseAuthInstance = null;
    let confirmationResult = null;
    let resendInterval = null;

    function setAuthMode(mode) {
        authMode = mode;
        clearAuthAlert();

        const tabSignIn = document.getElementById('tab-signin');
        const tabSignUp = document.getElementById('tab-signup');
        const nameField = document.getElementById('field-signup-name');
        const emailField = document.getElementById('field-signup-email');
        const signinPrompt = document.getElementById('toggle-signin-prompt');
        const signupPrompt = document.getElementById('toggle-signup-prompt');
        const sendBtnText = document.getElementById('send-otp-text');

        if (mode === 'signup') {
            tabSignUp.classList.add('text-slate-900', 'bg-white', 'shadow-2xs');
            tabSignUp.classList.remove('text-slate-500');

            tabSignIn.classList.remove('text-slate-900', 'bg-white', 'shadow-2xs');
            tabSignIn.classList.add('text-slate-500');

            nameField.classList.remove('hidden');
            emailField.classList.remove('hidden');
            signinPrompt.classList.add('hidden');
            signupPrompt.classList.remove('hidden');
            if (sendBtnText) sendBtnText.textContent = 'Send OTP';
        } else {
            tabSignIn.classList.add('text-slate-900', 'bg-white', 'shadow-2xs');
            tabSignIn.classList.remove('text-slate-500');

            tabSignUp.classList.remove('text-slate-900', 'bg-white', 'shadow-2xs');
            tabSignUp.classList.add('text-slate-500');

            nameField.classList.add('hidden');
            emailField.classList.add('hidden');
            signinPrompt.classList.remove('hidden');
            signupPrompt.classList.add('hidden');
            if (sendBtnText) sendBtnText.textContent = 'Send OTP';
        }
    }

    try {
        if (typeof firebase !== 'undefined' && firebaseConfig.apiKey && firebaseConfig.projectId) {
            if (!firebase.apps.length) {
                firebase.initializeApp(firebaseConfig);
            }
            firebaseAuthInstance = firebase.auth();
        }
    } catch (e) {
        console.warn('Firebase initialization notice:', e);
    }

    function initRecaptcha() {
        if (!firebaseAuthInstance) return;
        if (!window.recaptchaVerifier) {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': function(response) {
                    // reCAPTCHA solved
                },
                'expired-callback': function() {
                    // Response expired
                }
            });
        }
    }

    function openAuthDrawer(mode = 'signin') {
        const backdrop = document.getElementById('auth-drawer-backdrop');
        const drawer = document.getElementById('auth-drawer');
        
        clearAuthAlert();
        setAuthMode(mode);

        backdrop.classList.remove('pointer-events-none', 'opacity-0');
        backdrop.classList.add('opacity-100');

        drawer.classList.remove('translate-x-full');
        drawer.classList.add('translate-x-0');

        document.body.classList.add('overflow-hidden');
    }

    function closeAuthDrawer() {
        const backdrop = document.getElementById('auth-drawer-backdrop');
        const drawer = document.getElementById('auth-drawer');

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0', 'pointer-events-none');

        drawer.classList.remove('translate-x-0');
        drawer.classList.add('translate-x-full');

        document.body.classList.remove('overflow-hidden');
    }

    function showAuthAlert(type, message) {
        const alertBox = document.getElementById('auth-drawer-alert');
        if (!alertBox) return;

        alertBox.classList.remove('hidden', 'bg-rose-50', 'border', 'border-rose-200', 'text-rose-700', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700', 'bg-amber-50', 'border-amber-200', 'text-amber-700');
        
        if (type === 'success') {
            alertBox.classList.add('bg-emerald-50', 'border', 'border-emerald-200', 'text-emerald-700');
        } else if (type === 'warning') {
            alertBox.classList.add('bg-amber-50', 'border', 'border-amber-200', 'text-amber-700');
        } else {
            alertBox.classList.add('bg-rose-50', 'border', 'border-rose-200', 'text-rose-700');
        }
        alertBox.textContent = message;
    }

    function clearAuthAlert() {
        const alertBox = document.getElementById('auth-drawer-alert');
        if (alertBox) {
            alertBox.classList.add('hidden');
            alertBox.innerHTML = '';
        }
    }

    function resetToPhoneStep() {
        clearAuthAlert();
        document.getElementById('auth-phone-step').classList.remove('hidden');
        document.getElementById('auth-otp-step').classList.add('hidden');
        if (resendInterval) clearInterval(resendInterval);
    }

    function startResendTimer() {
        const resendBtn = document.getElementById('btn-resend-otp');
        const timerSpan = document.getElementById('resend-timer');
        let timeLeft = 30;

        resendBtn.disabled = true;
        if (resendInterval) clearInterval(resendInterval);

        resendInterval = setInterval(() => {
            timeLeft--;
            if (timerSpan) timerSpan.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(resendInterval);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend OTP';
            }
        }, 1000);
    }

    // Google Sign-In with Firebase
    async function handleGoogleSignIn(event) {
        event.preventDefault();
        clearAuthAlert();

        const btn = document.getElementById('btn-google-auth');
        const textSpan = document.getElementById('google-auth-text');

        if (!firebaseAuthInstance) {
            showAuthAlert('warning', 'Firebase credentials not attached yet. Please set FIREBASE_API_KEY and FIREBASE_PROJECT_ID in .env');
            return;
        }

        btn.disabled = true;
        if (textSpan) textSpan.textContent = 'Connecting...';

        try {
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('profile');
            provider.addScope('email');

            const result = await firebaseAuthInstance.signInWithPopup(provider);
            const idToken = await result.user.getIdToken();

            const response = await fetch('{{ route('customer.firebase-auth') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    id_token: idToken,
                    name: result.user.displayName || '',
                    mobile: result.user.phoneNumber || ''
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAuthAlert('success', 'Signed in successfully!');
                setTimeout(() => {
                    window.location.href = data.redirect_url || window.location.href;
                }, 500);
            } else {
                showAuthAlert('error', data.message || 'Firebase sign-in verification failed.');
            }
        } catch (err) {
            if (err.code !== 'auth/popup-closed-by-user') {
                showAuthAlert('error', err.message || 'Google sign-in encountered an error.');
            }
        } finally {
            btn.disabled = false;
            if (textSpan) textSpan.textContent = 'Continue with Google';
        }
    }

    // Send Firebase Phone SMS OTP
    async function handleSendPhoneOtp(event) {
        if (event) event.preventDefault();
        clearAuthAlert();

        const phoneInput = document.getElementById('customer-phone');
        const btn = document.getElementById('btn-send-phone-otp');
        const textSpan = document.getElementById('send-otp-text');

        if (authMode === 'signup') {
            const nameInput = document.getElementById('customer-name');
            const emailInput = document.getElementById('customer-email');

            if (!nameInput.value.trim()) {
                showAuthAlert('error', 'Please enter your full name.');
                nameInput.focus();
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim() || !emailRegex.test(emailInput.value.trim())) {
                showAuthAlert('error', 'Please enter a valid email address.');
                emailInput.focus();
                return;
            }
        }

        const rawPhone = phoneInput.value.trim().replace(/[^0-9+]/g, '');
        if (!rawPhone || rawPhone.length < 8) {
            showAuthAlert('error', 'Please enter a valid mobile number.');
            phoneInput.focus();
            return;
        }

        const countryCode = document.getElementById('country-code').textContent.trim() || '+91';
        const formattedPhone = rawPhone.startsWith('+') ? rawPhone : (countryCode + rawPhone.replace(/^0+/, ''));

        if (!firebaseAuthInstance) {
            showAuthAlert('warning', 'Firebase credentials not attached yet. Please set FIREBASE_API_KEY and FIREBASE_PROJECT_ID in .env');
            return;
        }

        btn.disabled = true;
        if (textSpan) textSpan.textContent = 'Sending OTP...';

        try {
            initRecaptcha();
            confirmationResult = await firebaseAuthInstance.signInWithPhoneNumber(formattedPhone, window.recaptchaVerifier);

            // Switch to OTP entry step
            document.getElementById('otp-sent-number').textContent = formattedPhone;
            document.getElementById('auth-phone-step').classList.add('hidden');
            document.getElementById('auth-otp-step').classList.remove('hidden');
            document.getElementById('otp-code-input').focus();
            startResendTimer();
            showAuthAlert('success', '6-digit OTP code sent via SMS to ' + formattedPhone);
        } catch (error) {
            console.error('Firebase Phone Auth error:', error);
            if (window.recaptchaVerifier) {
                window.recaptchaVerifier.render().then(widgetId => {
                    if (typeof grecaptcha !== 'undefined') grecaptcha.reset(widgetId);
                });
            }
            showAuthAlert('error', error.message || 'Failed to send OTP. Please verify phone number and Firebase setup.');
        } finally {
            btn.disabled = false;
            if (textSpan) textSpan.textContent = 'Send OTP';
        }
    }

    // Verify Firebase Phone SMS OTP
    async function handleVerifyPhoneOtp(event) {
        if (event) event.preventDefault();
        clearAuthAlert();

        const otpInput = document.getElementById('otp-code-input');
        const btn = document.getElementById('btn-verify-otp');
        const textSpan = document.getElementById('verify-otp-text');
        const nameInput = document.getElementById('customer-name');
        const emailInput = document.getElementById('customer-email');

        const otpCode = otpInput.value.trim();
        if (!otpCode || otpCode.length < 6) {
            showAuthAlert('error', 'Please enter the 6-digit OTP code received on your phone.');
            return;
        }

        if (!confirmationResult) {
            showAuthAlert('error', 'OTP session expired. Please request a new OTP code.');
            resetToPhoneStep();
            return;
        }

        btn.disabled = true;
        if (textSpan) textSpan.textContent = 'Verifying OTP...';

        try {
            const userCredential = await confirmationResult.confirm(otpCode);
            const idToken = await userCredential.user.getIdToken();

            const response = await fetch('{{ route('customer.firebase-auth') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    id_token: idToken,
                    name: nameInput ? nameInput.value.trim() : '',
                    email: emailInput ? emailInput.value.trim() : '',
                    mobile: userCredential.user.phoneNumber || ''
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAuthAlert('success', data.message || 'Signed in successfully!');
                setTimeout(() => {
                    window.location.href = data.redirect_url || window.location.href;
                }, 500);
            } else {
                showAuthAlert('error', data.message || 'OTP verification failed on server.');
            }
        } catch (error) {
            console.error('OTP confirmation error:', error);
            showAuthAlert('error', error.message || 'Invalid or expired OTP code.');
        } finally {
            btn.disabled = false;
            if (textSpan) textSpan.textContent = 'Verify & Sign In';
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAuthDrawer();
        }
    });

    @if ($errors->any() || session('open_auth_drawer'))
        openAuthDrawer();
    @endif
</script>
