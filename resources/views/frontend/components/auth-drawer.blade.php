<!-- ==========================================
     AUTHENTICATION SIDE DRAWER (SIGN IN / SIGN UP)
     ========================================== -->
<div id="auth-drawer-backdrop" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeAuthDrawer()"></div>

<div 
    id="auth-drawer" 
    class="fixed inset-y-0 right-0 w-full max-w-md bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col justify-between overflow-y-auto"
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="auth-drawer-title"
>
    <!-- Drawer Content -->
    <div class="p-6 sm:p-8">
        
        <!-- Drawer Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-brand-600/25">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <h3 id="auth-drawer-title" class="font-brand text-2xl text-slate-900 uppercase tracking-wide leading-none">
                        E-Book<span class="text-brand-600">.</span>
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium">Your Digital Reading Gateway</p>
                </div>
            </div>

            <!-- Close Button -->
            <button 
                type="button" 
                onclick="closeAuthDrawer()" 
                class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer"
                aria-label="Close Authentication Drawer"
            >
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Auth Tabs Switcher (Sign In vs Sign Up) -->
        <div class="mt-6 p-1 bg-slate-100 rounded-full flex items-center">
            <button 
                id="tab-btn-signin"
                type="button" 
                onclick="switchAuthTab('signin')" 
                class="flex-1 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-white text-brand-600 shadow-xs cursor-pointer"
            >
                Sign In
            </button>
            <button 
                id="tab-btn-signup"
                type="button" 
                onclick="switchAuthTab('signup')" 
                class="flex-1 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 text-slate-500 hover:text-slate-900 cursor-pointer"
            >
                Sign Up
            </button>
        </div>

        <!-- Continue with Google Button -->
        <div class="mt-6">
            <a 
                href="{{ route('auth.google') }}" 
                class="w-full h-11 inline-flex items-center justify-center gap-3 px-4 rounded-full bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold border border-slate-200/90 shadow-xs hover:shadow-sm transition-all duration-150 cursor-pointer"
            >
                <!-- Google Multi-Color SVG Icon -->
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Continue with Google</span>
            </a>

            <!-- Divider -->
            <div class="relative flex py-5 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">or continue with email</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>
        </div>

        <!-- ==========================================
             SIGN IN FORM
             ========================================== -->
        <form id="form-signin" action="{{ route('customer.login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="signin-email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope text-xs"></i>
                    </div>
                    <input 
                        type="email" 
                        id="signin-email" 
                        name="email" 
                        placeholder="you@example.com"
                        required
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition font-medium"
                    >
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="signin-password" class="block text-xs font-semibold text-slate-700">Password</label>
                    <a href="#" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700 hover:underline">Forgot?</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                    <input 
                        type="password" 
                        id="signin-password" 
                        name="password" 
                        autocomplete="current-password"
                        placeholder="••••••••"
                        required
                        class="w-full pl-9 pr-10 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition font-medium"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility('signin-password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                    >
                        <i class="fa-regular fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                    <span class="text-xs text-slate-500 font-medium">Remember me for 30 days</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full h-11 inline-flex items-center justify-center px-6 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 shadow-md shadow-brand-600/25 cursor-pointer mt-2"
            >
                <span>Sign In</span>
            </button>

            <p class="text-center text-xs text-slate-500 pt-2">
                Don't have an account? 
                <button type="button" onclick="switchAuthTab('signup')" class="text-brand-600 font-bold hover:underline cursor-pointer">
                    Sign Up
                </button>
            </p>
        </form>

        <!-- ==========================================
             SIGN UP FORM
             ========================================== -->
        <form id="form-signup" action="{{ route('customer.register') }}" method="POST" class="hidden space-y-3.5">
            @csrf
            <!-- Full Name -->
            <div>
                <label for="signup-name" class="block text-xs font-semibold text-slate-700 mb-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user text-xs"></i>
                    </div>
                    <input 
                        type="text" 
                        id="signup-name" 
                        name="name" 
                        placeholder="John Doe"
                        required
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition font-medium"
                    >
                </div>
            </div>

            <!-- Email Address -->
            <div>
                <label for="signup-email" class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope text-xs"></i>
                    </div>
                    <input 
                        type="email" 
                        id="signup-email" 
                        name="email" 
                        placeholder="you@example.com"
                        required
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition font-medium"
                    >
                </div>
            </div>

            <!-- Mobile Number -->
            <div>
                <label for="signup-phone" class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                    <input 
                        type="tel" 
                        id="signup-phone" 
                        name="mobile"
                        placeholder="+91 98765 43210"
                        required
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition font-medium"
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="signup-password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                    <input 
                        type="password" 
                        id="signup-password" 
                        name="password" 
                        autocomplete="new-password"
                        placeholder="At least 8 characters"
                        required
                        class="w-full pl-9 pr-10 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition font-medium"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility('signup-password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                    >
                        <i class="fa-regular fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="signup-confirm-password" class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                    </div>
                    <input 
                        type="password" 
                        id="signup-confirm-password" 
                        name="password_confirmation" 
                        autocomplete="new-password"
                        placeholder="Re-enter password"
                        required
                        class="w-full pl-9 pr-10 py-2.5 rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition font-medium"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility('signup-confirm-password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                    >
                        <i class="fa-regular fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Agree to Terms Checkbox -->
            <div class="flex items-start gap-2 pt-1">
                <input type="checkbox" id="terms" required class="mt-0.5 w-3.5 h-3.5 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                <label for="terms" class="text-[11px] text-slate-500 leading-tight">
                    I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-brand-600 hover:underline">Terms of Service</a> &amp; <a href="{{ route('privacy-policy') }}" target="_blank" class="text-brand-600 hover:underline">Privacy Policy</a>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full h-11 inline-flex items-center justify-center px-6 rounded-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-brand text-xl tracking-wider uppercase transition-all duration-200 shadow-md shadow-brand-600/25 cursor-pointer mt-2"
            >
                <span>Create Account</span>
            </button>

            <p class="text-center text-xs text-slate-500 pt-1">
                Already have an account? 
                <button type="button" onclick="switchAuthTab('signin')" class="text-brand-600 font-bold hover:underline cursor-pointer">
                    Sign In
                </button>
            </p>
        </form>

    </div>

    <!-- Drawer Footer -->
    <div class="p-6 bg-slate-50/80 border-t border-slate-100 text-center">
        <p class="text-[11px] text-slate-400">
            Protected with 256-bit encryption • Instant access to your digital library
        </p>
    </div>
</div>

<!-- ==========================================
     AUTH DRAWER SCRIPT
     ========================================== -->
<script>
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
        const signinBtn = document.getElementById('tab-btn-signin');
        const signupBtn = document.getElementById('tab-btn-signup');
        const signinForm = document.getElementById('form-signin');
        const signupForm = document.getElementById('form-signup');

        if (tab === 'signin') {
            signinBtn.className = 'flex-1 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-white text-brand-600 shadow-xs cursor-pointer';
            signupBtn.className = 'flex-1 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 text-slate-500 hover:text-slate-900 cursor-pointer';
            signinForm.classList.remove('hidden');
            signupForm.classList.add('hidden');
        } else {
            signupBtn.className = 'flex-1 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 bg-white text-brand-600 shadow-xs cursor-pointer';
            signinBtn.className = 'flex-1 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 text-slate-500 hover:text-slate-900 cursor-pointer';
            signupForm.classList.remove('hidden');
            signinForm.classList.add('hidden');
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash text-xs text-brand-600';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye text-xs text-slate-400';
        }
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
