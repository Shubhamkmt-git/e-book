<!-- ==========================================
     FIREBASE AUTHENTICATION SIDE DRAWER
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

            <!-- Firebase Google Sign-In -->
            <div class="mt-5">
                <button 
                    type="button" 
                    id="btn-google-auth"
                    onclick="handleGoogleSignIn(event)" 
                    class="w-full h-11 inline-flex items-center justify-center gap-2.5 px-4 rounded-xl bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 shadow-2xs transition-all cursor-pointer"
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
                <div class="relative flex py-4 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-[10px] font-medium uppercase tracking-wider text-slate-400">or</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>
            </div>

            <!-- ==========================================
                 FIREBASE EMAIL AUTHENTICATION FORM
                 ========================================== -->
            <form id="form-firebase-auth" onsubmit="handleFirebaseEmailAuth(event)" class="space-y-3.5">
                @csrf
                <div>
                    <label for="firebase-email" class="block text-xs font-medium text-slate-700 mb-1">Email address</label>
                    <input 
                        type="email" 
                        id="firebase-email" 
                        name="email" 
                        placeholder="you@example.com"
                        required
                        class="w-full h-10 px-3.5 rounded-xl bg-slate-50/50 text-slate-900 placeholder-slate-400 text-xs border border-slate-200 focus:bg-white focus:border-brand-600 focus:ring-2 focus:ring-brand-600/10 focus:outline-none transition font-medium"
                    >
                </div>

                <button 
                    type="submit" 
                    id="btn-firebase-email-submit"
                    class="w-full h-10 inline-flex items-center justify-center gap-2 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-semibold text-xs transition cursor-pointer shadow-xs"
                >
                    <span>Send Firebase Sign-in Link</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </form>
        </div>

        <!-- Minimal Footer -->
        <div class="pt-4 border-t border-slate-100 text-center">
            <p class="text-[11px] text-slate-400">
                Powered by Firebase Auth &bull; 256-bit Encrypted
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
        console.warn('Firebase initialization notice:', e);
    }

    function openAuthDrawer() {
        const backdrop = document.getElementById('auth-drawer-backdrop');
        const drawer = document.getElementById('auth-drawer');
        
        clearAuthAlert();

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

    // Firebase Passwordless Email Link Auth
    async function handleFirebaseEmailAuth(event) {
        event.preventDefault();
        clearAuthAlert();

        const emailInput = document.getElementById('firebase-email');
        const submitBtn = document.getElementById('btn-firebase-email-submit');
        const email = emailInput.value.trim().toLowerCase();

        if (!firebaseAuthInstance) {
            showAuthAlert('warning', 'Firebase credentials not attached yet. Please set FIREBASE_API_KEY and FIREBASE_PROJECT_ID in .env');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Sending link...</span>`;

        const actionCodeSettings = {
            url: window.location.origin + window.location.pathname,
            handleCodeInApp: true,
        };

        try {
            await firebaseAuthInstance.sendSignInLinkToEmail(email, actionCodeSettings);
            window.localStorage.setItem('emailForSignIn', email);
            showAuthAlert('success', 'Sign-in link sent via Firebase! Check your email to sign in.');
            emailInput.value = '';
        } catch (error) {
            showAuthAlert('error', error.message || 'Failed to send Firebase sign-in link.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<span>Send Firebase Sign-in Link</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>`;
        }
    }

    // Check if returning from a Firebase Email Link
    document.addEventListener('DOMContentLoaded', async () => {
        if (firebaseAuthInstance && firebaseAuthInstance.isSignInWithEmailLink(window.location.href)) {
            let email = window.localStorage.getItem('emailForSignIn');
            if (!email) {
                email = window.prompt('Please provide your email for confirmation:');
            }
            if (email) {
                try {
                    const result = await firebaseAuthInstance.signInWithEmailLink(email, window.location.href);
                    window.localStorage.removeItem('emailForSignIn');
                    const idToken = await result.user.getIdToken();

                    const response = await fetch('{{ route('customer.firebase-auth') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ id_token: idToken })
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        window.location.href = data.redirect_url || window.location.origin;
                    }
                } catch (e) {
                    console.error('Firebase email link sign-in error:', e);
                }
            }
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAuthDrawer();
        }
    });

    @if ($errors->any() || session('open_auth_drawer'))
        openAuthDrawer();
    @endif
</script>
