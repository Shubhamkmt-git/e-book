<!-- ==========================================
     PREMIUM AUTHENTICATION SIDE DRAWER
     ========================================== -->
<div 
    id="auth-drawer-backdrop" 
    class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-[100] transition-opacity duration-300 opacity-0 pointer-events-none" 
    onclick="closeAuthDrawer()"
    aria-hidden="true"
></div>

<div 
    id="auth-drawer" 
    class="fixed inset-y-0 right-0 w-full max-w-[440px] bg-white z-[101] shadow-2xl transform translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] flex flex-col justify-between overflow-y-auto border-l border-slate-100"
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="auth-drawer-title"
>
    <!-- Drawer Scrollable Body -->
    <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
        <div>
            <!-- Top Header Section -->
            <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    @if (!empty($appSetting?->logo_light_url) || !empty($appSetting?->logo_dark_url))
                        <img 
                            id="auth-drawer-title"
                            src="{{ $appSetting->logo_light_url ?? $appSetting->logo_dark_url }}" 
                            alt="{{ $appSetting->app_name ?? 'Logo' }}" 
                            class="max-w-[150px] max-h-10 object-contain"
                        >
                    @else
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-brand-500/25">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div>
                                <h3 id="auth-drawer-title" class="font-brand text-2xl text-slate-900 uppercase tracking-wide leading-none">
                                    {{ $appSetting->app_name ?? 'E-Book' }}<span class="text-brand-600">.</span>
                                </h3>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Close Button -->
                <button 
                    type="button" 
                    onclick="closeAuthDrawer()" 
                    class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-all duration-200 hover:rotate-90 cursor-pointer shadow-2xs"
                    aria-label="Close Authentication Drawer"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Header Badge & Intro Text -->
            <div class="mt-5 text-center sm:text-left">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 border border-brand-100/60 text-brand-700 text-[11px] font-semibold tracking-wide mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                    <span id="auth-subheading-badge">Instant &amp; Passwordless Access</span>
                </div>
                <h2 id="auth-heading-title" class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-display">
                    Welcome to {{ $appSetting->app_name ?? 'our digital library' }}
                </h2>
                <p id="auth-heading-desc" class="text-xs text-slate-500 mt-1">
                    Sign in or register effortlessly with a 6-digit email code.
                </p>
            </div>

            <!-- Dynamic Alert Container -->
            <div id="auth-drawer-alert" class="hidden mt-4 rounded-2xl px-4 py-3 text-xs font-medium flex items-start gap-2.5 transition-all duration-200" role="alert"></div>

            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700 flex items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Auth Tabs Switcher (Sign In vs Sign Up) -->
            <div id="auth-tabs-switcher" class="mt-5 p-1 bg-slate-100/90 rounded-2xl border border-slate-200/60 flex items-center shadow-inner">
                <button 
                    id="tab-btn-signin"
                    type="button" 
                    onclick="switchAuthTab('signin')" 
                    class="flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-white text-brand-600 shadow-sm cursor-pointer text-center"
                >
                    Sign In
                </button>
                <button 
                    id="tab-btn-signup"
                    type="button" 
                    onclick="switchAuthTab('signup')" 
                    class="flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 text-slate-500 hover:text-slate-900 cursor-pointer text-center"
                >
                    Sign Up
                </button>
            </div>

            <!-- Continue with Google Button -->
            <div id="auth-social-container" class="mt-5">
                <button 
                    type="button" 
                    id="btn-google-auth"
                    onclick="handleGoogleSignIn(event)" 
                    class="w-full h-12 inline-flex items-center justify-center gap-3 px-4 rounded-2xl bg-white hover:bg-slate-50 active:scale-[0.99] text-slate-700 text-sm font-semibold border border-slate-200 shadow-xs hover:shadow-sm hover:border-slate-300 transition-all duration-150 cursor-pointer"
                >
                    <!-- Google Multi-Color SVG Icon -->
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span id="google-auth-text">Continue with Google</span>
                </button>

                <!-- Divider -->
                <div class="relative flex py-5 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-white px-2">or email code</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>
            </div>

            <!-- ==========================================
                 SIGN IN FORM (EMAIL + OTP LOGIN)
                 ========================================== -->
            <form id="form-signin" onsubmit="handleSendLoginOtp(event)" class="space-y-4">
                @csrf
                <div>
                    <label for="signin-email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </div>
                        <input 
                            type="email" 
                            id="signin-email" 
                            name="email" 
                            placeholder="name@example.com"
                            required
                            class="w-full h-12 pl-11 pr-4 rounded-2xl bg-slate-50/80 text-slate-900 placeholder-slate-400 text-sm border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none transition-all duration-200 font-medium"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    id="btn-signin-submit"
                    class="w-full h-12 inline-flex items-center justify-center gap-2.5 px-6 rounded-2xl bg-brand-600 hover:bg-brand-500 active:scale-[0.99] text-white font-bold text-sm tracking-wide shadow-lg shadow-brand-600/25 hover:shadow-brand-600/35 transition-all duration-200 cursor-pointer"
                >
                    <span>Send Login Code</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>

                <p class="text-center text-xs text-slate-500 pt-1">
                    Don't have an account? 
                    <button type="button" onclick="switchAuthTab('signup')" class="text-brand-600 font-bold hover:underline cursor-pointer">
                        Sign Up
                    </button>
                </p>
            </form>

            <!-- ==========================================
                 SIGN UP FORM (EMAIL + OTP REGISTRATION)
                 ========================================== -->
            <form id="form-signup" onsubmit="handleSendRegistrationOtp(event)" class="hidden space-y-4">
                @csrf
                <!-- Full Name -->
                <div>
                    <label for="signup-name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-sm"></i>
                        </div>
                        <input 
                            type="text" 
                            id="signup-name" 
                            name="name" 
                            placeholder="John Doe"
                            required
                            class="w-full h-12 pl-11 pr-4 rounded-2xl bg-slate-50/80 text-slate-900 placeholder-slate-400 text-sm border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none transition-all duration-200 font-medium"
                        >
                    </div>
                </div>

                <!-- Email Address -->
                <div>
                    <label for="signup-email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </div>
                        <input 
                            type="email" 
                            id="signup-email" 
                            name="email" 
                            placeholder="name@example.com"
                            required
                            class="w-full h-12 pl-11 pr-4 rounded-2xl bg-slate-50/80 text-slate-900 placeholder-slate-400 text-sm border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none transition-all duration-200 font-medium"
                        >
                    </div>
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="signup-phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Mobile Number <span class="text-slate-400 font-normal lowercase">(optional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </div>
                        <input 
                            type="tel" 
                            id="signup-phone" 
                            name="mobile"
                            placeholder="+91 98765 43210"
                            class="w-full h-12 pl-11 pr-4 rounded-2xl bg-slate-50/80 text-slate-900 placeholder-slate-400 text-sm border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none transition-all duration-200 font-medium"
                        >
                    </div>
                </div>

                <!-- Agree to Terms Checkbox -->
                <div class="flex items-start gap-2.5 pt-1">
                    <input type="checkbox" id="terms" required class="mt-1 w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500 cursor-pointer">
                    <label for="terms" class="text-xs text-slate-500 leading-relaxed">
                        I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-brand-600 font-semibold hover:underline">Terms of Service</a> &amp; <a href="{{ route('privacy-policy') }}" target="_blank" class="text-brand-600 font-semibold hover:underline">Privacy Policy</a>
                    </label>
                </div>

                <button 
                    type="submit" 
                    id="btn-signup-submit"
                    class="w-full h-12 inline-flex items-center justify-center gap-2.5 px-6 rounded-2xl bg-brand-600 hover:bg-brand-500 active:scale-[0.99] text-white font-bold text-sm tracking-wide shadow-lg shadow-brand-600/25 hover:shadow-brand-600/35 transition-all duration-200 cursor-pointer"
                >
                    <span>Send Verification Code</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>

                <p class="text-center text-xs text-slate-500 pt-1">
                    Already have an account? 
                    <button type="button" onclick="switchAuthTab('signin')" class="text-brand-600 font-bold hover:underline cursor-pointer">
                        Sign In
                    </button>
                </p>
            </form>

            <!-- ==========================================
                 OTP VERIFICATION FORM (STEP 2: OTP)
                 ========================================== -->
            <form id="form-otp" onsubmit="handleVerifyOtp(event)" class="hidden space-y-4">
                @csrf
                
                <div class="flex items-center justify-between pb-1">
                    <button 
                        type="button" 
                        onclick="backFromOtpForm()" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-600 transition cursor-pointer"
                    >
                        <i class="fa-solid fa-arrow-left text-[11px]"></i>
                        <span id="otp-back-btn-text">Edit Email</span>
                    </button>
                </div>

                <!-- OTP Notice Box -->
                <div class="p-4 rounded-2xl bg-gradient-to-br from-brand-50/80 via-purple-50/50 to-brand-50/80 border border-brand-100/80 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-brand-100 text-brand-600 flex items-center justify-center mx-auto mb-2 text-sm font-bold shadow-xs">
                        <i class="fa-regular fa-envelope-open"></i>
                    </div>
                    <h4 id="otp-header-title" class="text-sm font-bold text-slate-900">Check Your Email</h4>
                    <p class="text-xs text-slate-500 mt-1">
                        We sent a 6-digit verification code to<br>
                        <span id="otp-display-email" class="inline-block mt-1 font-semibold text-brand-700 bg-white/80 border border-brand-200/60 rounded-lg px-2.5 py-0.5 font-mono text-xs"></span>
                    </p>
                </div>

                <!-- 6-Digit OTP Input -->
                <div>
                    <label for="otp-code-input" class="block text-xs font-bold text-slate-700 mb-2 text-center uppercase tracking-wider">
                        Enter 6-Digit Code
                    </label>
                    <input 
                        type="text" 
                        id="otp-code-input" 
                        name="otp" 
                        maxlength="6" 
                        inputmode="numeric" 
                        pattern="[0-9]*"
                        autocomplete="one-time-code"
                        placeholder="••••••"
                        required
                        class="w-full text-center text-3xl tracking-[0.4em] font-mono py-3.5 font-black rounded-2xl bg-slate-50/90 text-slate-900 placeholder-slate-300 border-2 border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 outline-none transition-all duration-200 shadow-inner"
                    >
                </div>

                <!-- Resend OTP Option -->
                <div class="flex items-center justify-between text-xs px-1 pt-1">
                    <span class="text-slate-500">Didn't receive code?</span>
                    <button 
                        type="button" 
                        id="btn-resend-otp" 
                        onclick="handleResendOtp()" 
                        class="font-bold text-brand-600 hover:text-brand-700 hover:underline disabled:text-slate-400 disabled:no-underline disabled:cursor-not-allowed cursor-pointer transition flex items-center gap-1.5"
                    >
                        <i class="fa-solid fa-rotate-right text-[10px]"></i>
                        <span>Resend Code</span>
                    </button>
                </div>

                <button 
                    type="submit" 
                    id="btn-verify-submit"
                    class="w-full h-12 inline-flex items-center justify-center gap-2.5 px-6 rounded-2xl bg-brand-600 hover:bg-brand-500 active:scale-[0.99] text-white font-bold text-sm tracking-wide shadow-lg shadow-brand-600/25 hover:shadow-brand-600/35 transition-all duration-200 cursor-pointer"
                >
                    <span id="btn-verify-text">Verify &amp; Sign In</span>
                    <i class="fa-solid fa-check text-xs"></i>
                </button>
            </form>

        </div>

        <!-- Drawer Footer Trust Elements -->
        <div class="pt-6 mt-6 border-t border-slate-100 text-center">
            <div class="inline-flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                <i class="fa-solid fa-shield-halved text-brand-500"></i>
                <span>256-bit TLS Encrypted &bull; Instant access to purchased e-books</span>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     FIREBASE & AUTH DRAWER SCRIPT
     ========================================== -->
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>

<script>
    const firebaseConfig = {
        apiKey: @json(config('services.firebase.api_key')),
        authDomain: @json(config('services.firebase.auth_domain')),
        projectId: @json(config('services.firebase.project_id')),
        storageBucket: @json(config('services.firebase.storage_bucket')),
        messagingSenderId: @json(config('services.firebase.messaging_sender_id')),
        appId: @json(config('services.firebase.app_id')),
        measurementId: @json(config('services.firebase.measurement_id')),
    };

    let firebaseAuthInstance = null;
    try {
        if (typeof firebase !== 'undefined' && firebaseConfig.apiKey && firebaseConfig.projectId) {
            if (!firebase.apps.length) {
                firebase.initializeApp(firebaseConfig);
            }
            firebaseAuthInstance = firebase.auth();
        }
    } catch (e) {
        console.warn('Firebase initialization warning:', e);
    }

    let registeredEmail = '';
    let authOtpMode = 'signin'; // 'signin' or 'signup'
    let resendTimerInterval = null;
    let resendSecondsLeft = 0;

    function openAuthDrawer(tab = 'signin') {
        const backdrop = document.getElementById('auth-drawer-backdrop');
        const drawer = document.getElementById('auth-drawer');
        
        switchAuthTab(tab);

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

    function switchAuthTab(tab) {
        clearAuthAlert();
        const signinBtn = document.getElementById('tab-btn-signin');
        const signupBtn = document.getElementById('tab-btn-signup');
        const signinForm = document.getElementById('form-signin');
        const signupForm = document.getElementById('form-signup');
        const otpForm = document.getElementById('form-otp');
        const tabsSwitcher = document.getElementById('auth-tabs-switcher');
        const socialContainer = document.getElementById('auth-social-container');
        const headingTitle = document.getElementById('auth-heading-title');
        const headingDesc = document.getElementById('auth-heading-desc');
        const badgeText = document.getElementById('auth-subheading-badge');

        if (tabsSwitcher) tabsSwitcher.classList.remove('hidden');
        if (socialContainer) socialContainer.classList.remove('hidden');
        if (otpForm) otpForm.classList.add('hidden');

        if (tab === 'signin') {
            authOtpMode = 'signin';
            signinBtn.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-white text-brand-600 shadow-sm cursor-pointer text-center';
            signupBtn.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 text-slate-500 hover:text-slate-900 cursor-pointer text-center';
            signinForm.classList.remove('hidden');
            signupForm.classList.add('hidden');
            if (headingTitle) headingTitle.textContent = 'Welcome Back';
            if (headingDesc) headingDesc.textContent = 'Sign in to access all your purchased books & downloads.';
            if (badgeText) badgeText.textContent = 'Quick & Passwordless Login';
        } else {
            authOtpMode = 'signup';
            signupBtn.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-white text-brand-600 shadow-sm cursor-pointer text-center';
            signinBtn.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 text-slate-500 hover:text-slate-900 cursor-pointer text-center';
            signupForm.classList.remove('hidden');
            signinForm.classList.add('hidden');
            if (headingTitle) headingTitle.textContent = 'Create an Account';
            if (headingDesc) headingDesc.textContent = 'Join in seconds. No complex passwords to remember.';
            if (badgeText) badgeText.textContent = 'Fast 6-Digit Email Verification';
        }
    }

    function backFromOtpForm() {
        clearAuthAlert();
        const signupForm = document.getElementById('form-signup');
        const signinForm = document.getElementById('form-signin');
        const otpForm = document.getElementById('form-otp');
        const tabsSwitcher = document.getElementById('auth-tabs-switcher');
        const socialContainer = document.getElementById('auth-social-container');

        if (otpForm) otpForm.classList.add('hidden');
        if (tabsSwitcher) tabsSwitcher.classList.remove('hidden');
        if (socialContainer) socialContainer.classList.remove('hidden');

        if (authOtpMode === 'signup') {
            signupForm.classList.remove('hidden');
            signinForm.classList.add('hidden');
        } else {
            signinForm.classList.remove('hidden');
            signupForm.classList.add('hidden');
        }
    }

    function showAuthAlert(type, message) {
        const alertBox = document.getElementById('auth-drawer-alert');
        if (!alertBox) return;

        alertBox.classList.remove('hidden', 'bg-rose-50', 'border', 'border-rose-200', 'text-rose-700', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
        
        if (type === 'success') {
            alertBox.classList.add('bg-emerald-50', 'border', 'border-emerald-200', 'text-emerald-700');
            alertBox.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-500 mt-0.5"></i> <span>${message}</span>`;
        } else {
            alertBox.classList.add('bg-rose-50', 'border', 'border-rose-200', 'text-rose-700');
            alertBox.innerHTML = `<i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i> <span>${message}</span>`;
        }
    }

    function clearAuthAlert() {
        const alertBox = document.getElementById('auth-drawer-alert');
        if (alertBox) {
            alertBox.classList.add('hidden');
            alertBox.innerHTML = '';
        }
    }

    async function handleSendLoginOtp(event) {
        event.preventDefault();
        clearAuthAlert();

        const form = document.getElementById('form-signin');
        const submitBtn = document.getElementById('btn-signin-submit');
        const emailInput = document.getElementById('signin-email');

        registeredEmail = emailInput.value.trim().toLowerCase();
        authOtpMode = 'signin';

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Sending login code...</span>`;

        try {
            const response = await fetch('{{ route('customer.send-login-otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ email: registeredEmail })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                document.getElementById('form-signin').classList.add('hidden');
                document.getElementById('auth-tabs-switcher').classList.add('hidden');
                document.getElementById('auth-social-container').classList.add('hidden');

                const otpForm = document.getElementById('form-otp');
                otpForm.classList.remove('hidden');

                document.getElementById('otp-back-btn-text').textContent = 'Edit Login Email';
                document.getElementById('otp-header-title').textContent = 'Check Your Email For Login Code';
                document.getElementById('btn-verify-text').textContent = 'Verify & Sign In';
                document.getElementById('otp-display-email').textContent = registeredEmail;
                document.getElementById('otp-code-input').value = '';
                document.getElementById('otp-code-input').focus();

                showAuthAlert('success', data.message || 'Login code sent to your email.');
                startResendCountdown(30);
            } else {
                showAuthAlert('error', data.message || 'Unable to send login code. Please check your email.');
            }
        } catch (error) {
            showAuthAlert('error', 'A network error occurred. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<span>Send Login Code</span> <i class="fa-solid fa-arrow-right text-xs"></i>`;
        }
    }

    async function handleSendRegistrationOtp(event) {
        event.preventDefault();
        clearAuthAlert();

        const form = document.getElementById('form-signup');
        const submitBtn = document.getElementById('btn-signup-submit');
        const emailInput = document.getElementById('signup-email');

        registeredEmail = emailInput.value.trim().toLowerCase();
        authOtpMode = 'signup';

        // Loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Sending code...</span>`;

        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route('customer.send-otp') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                document.getElementById('form-signup').classList.add('hidden');
                document.getElementById('auth-tabs-switcher').classList.add('hidden');
                document.getElementById('auth-social-container').classList.add('hidden');
                
                const otpForm = document.getElementById('form-otp');
                otpForm.classList.remove('hidden');
                
                document.getElementById('otp-back-btn-text').textContent = 'Edit Details';
                document.getElementById('otp-header-title').textContent = 'Check Your Email';
                document.getElementById('btn-verify-text').textContent = 'Verify & Create Account';
                document.getElementById('otp-display-email').textContent = registeredEmail;
                document.getElementById('otp-code-input').value = '';
                document.getElementById('otp-code-input').focus();

                showAuthAlert('success', data.message || 'Verification code sent to your email.');
                startResendCountdown(30);
            } else {
                showAuthAlert('error', data.message || 'Unable to send verification code. Please check your details.');
            }
        } catch (error) {
            showAuthAlert('error', 'A network error occurred. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<span>Send Verification Code</span> <i class="fa-solid fa-arrow-right text-xs"></i>`;
        }
    }

    async function handleVerifyOtp(event) {
        event.preventDefault();
        clearAuthAlert();

        const otpInput = document.getElementById('otp-code-input');
        const verifyBtn = document.getElementById('btn-verify-submit');
        const otpCode = otpInput.value.trim();

        if (otpCode.length !== 6) {
            showAuthAlert('error', 'Please enter a valid 6-digit verification code.');
            return;
        }

        verifyBtn.disabled = true;
        verifyBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Verifying code...</span>`;

        const verifyRoute = authOtpMode === 'signin' 
            ? '{{ route('customer.verify-login-otp') }}' 
            : '{{ route('customer.verify-otp') }}';

        try {
            const response = await fetch(verifyRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    email: registeredEmail,
                    otp: otpCode
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAuthAlert('success', data.message || 'Verified successfully! Signing you in...');
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            } else {
                showAuthAlert('error', data.message || 'Invalid verification code. Please try again.');
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = `<span>${authOtpMode === 'signin' ? 'Verify & Sign In' : 'Verify & Create Account'}</span> <i class="fa-solid fa-check text-xs"></i>`;
            }
        } catch (error) {
            showAuthAlert('error', 'Network error. Please try again.');
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = `<span>Verify &amp; Continue</span> <i class="fa-solid fa-check text-xs"></i>`;
        }
    }

    async function handleResendOtp() {
        if (resendSecondsLeft > 0 || !registeredEmail) return;

        const resendBtn = document.getElementById('btn-resend-otp');
        resendBtn.disabled = true;
        resendBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-[10px]"></i> <span>Sending...</span>`;

        const resendRoute = authOtpMode === 'signin' 
            ? '{{ route('customer.resend-login-otp') }}' 
            : '{{ route('customer.resend-otp') }}';

        try {
            const response = await fetch(resendRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ email: registeredEmail })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAuthAlert('success', data.message || 'New verification code sent!');
                startResendCountdown(30);
            } else {
                showAuthAlert('error', data.message || 'Unable to resend code right now.');
                resendBtn.disabled = false;
                resendBtn.innerHTML = `<i class="fa-solid fa-rotate-right text-[10px]"></i> <span>Resend Code</span>`;
            }
        } catch (err) {
            showAuthAlert('error', 'Network error. Please try again.');
            resendBtn.disabled = false;
            resendBtn.innerHTML = `<i class="fa-solid fa-rotate-right text-[10px]"></i> <span>Resend Code</span>`;
        }
    }

    function startResendCountdown(seconds) {
        const resendBtn = document.getElementById('btn-resend-otp');
        if (!resendBtn) return;

        clearInterval(resendTimerInterval);
        resendSecondsLeft = seconds;
        resendBtn.disabled = true;
        resendBtn.innerHTML = `<i class="fa-solid fa-clock text-[10px]"></i> <span>Resend in ${resendSecondsLeft}s</span>`;

        resendTimerInterval = setInterval(() => {
            resendSecondsLeft--;
            if (resendSecondsLeft <= 0) {
                clearInterval(resendTimerInterval);
                resendBtn.disabled = false;
                resendBtn.innerHTML = `<i class="fa-solid fa-rotate-right text-[10px]"></i> <span>Resend Code</span>`;
            } else {
                resendBtn.innerHTML = `<i class="fa-solid fa-clock text-[10px]"></i> <span>Resend in ${resendSecondsLeft}s</span>`;
            }
        }, 1000);
    }

    async function handleGoogleSignIn(event) {
        event.preventDefault();
        clearAuthAlert();

        const btn = document.getElementById('btn-google-auth');
        const textSpan = document.getElementById('google-auth-text');

        // If Firebase is initialized with live credentials, use Firebase popup sign-in
        if (firebaseAuthInstance) {
            btn.disabled = true;
            if (textSpan) textSpan.textContent = 'Connecting Google account...';

            try {
                const provider = new firebase.auth.GoogleAuthProvider();
                provider.addScope('profile');
                provider.addScope('email');

                const result = await firebaseAuthInstance.signInWithPopup(provider);
                const idToken = await result.user.getIdToken();

                showAuthAlert('success', 'Verifying secure Firebase credentials...');

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
                    showAuthAlert('success', data.message || 'Signed in successfully!');
                    setTimeout(() => {
                        window.location.href = data.redirect_url || window.location.href;
                    }, 600);
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
            return;
        }

        // Fallback: standard Socialite OAuth redirect
        window.location.href = '{{ route('auth.google') }}';
    }

    // Close on Escape key press
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAuthDrawer();
        }
    });

    @if ($errors->any() || session('open_auth_drawer'))
        openAuthDrawer(@json(session('auth_tab', 'signin')));
    @endif
</script>
