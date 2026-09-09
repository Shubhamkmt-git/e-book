<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Sign In &bull; {{ config('app.name', 'E-Book') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-white selection:bg-brand-600 selection:text-white">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">
        
        <!-- Left Column: Premium Brand Showcase / Visual Banner -->
        <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 bg-gradient-to-br from-brand-900 via-brand-700 to-brand-600 relative flex-col justify-between p-12 lg:p-16 text-white overflow-hidden">
            
            <!-- Ambient Background Effects -->
            <div class="absolute inset-0 bg-grid-pattern opacity-30 pointer-events-none"></div>
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-brand-950/40 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Header in Left Banner -->
            <div class="relative z-10 flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg shadow-black/10">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-xl tracking-tight text-white leading-none">E-Book CMS</div>
                    <div class="text-xs text-brand-200 mt-1">Management Portal</div>
                </div>
            </div>

            <!-- Middle Feature Showcase -->
            <div class="relative z-10 my-auto py-12 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-medium text-brand-100 mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Admin Control Center</span>
                </div>

                <h2 class="text-3xl xl:text-4xl font-extrabold tracking-tight text-white leading-tight mb-4">
                    Streamlined Control Over Your E-Book Library & Content.
                </h2>
                <p class="text-base text-brand-100/90 leading-relaxed mb-8">
                    Manage authors, published catalogs, user subscriptions, and deep reading metrics in one centralized, secure interface.
                </p>

                <!-- Key Highlights Cards -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center text-white mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="font-semibold text-sm text-white">Role-Based Security</div>
                        <div class="text-xs text-brand-200 mt-1">Encrypted sessions & authentication</div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center text-white mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="font-semibold text-sm text-white">Instant Publishing</div>
                        <div class="text-xs text-brand-200 mt-1">Fast live catalog synchronization</div>
                    </div>
                </div>
            </div>

            <!-- Left Footer Note -->
            <div class="relative z-10 text-xs text-brand-200/80 flex items-center justify-between">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'E-Book') }} Platform</span>
                <span>Protected by SSL Encryption</span>
            </div>
        </div>

        <!-- Right Column: Login Form Container -->
        <div class="lg:col-span-6 xl:col-span-5 flex flex-col justify-between p-8 sm:p-12 lg:p-16 bg-white overflow-y-auto">
            
            <!-- Mobile Brand Header (Visible only on small screens) -->
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-md shadow-brand-600/25">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-lg text-slate-900 leading-none">E-Book Admin</div>
                    <div class="text-xs text-slate-500 mt-0.5">Management Portal</div>
                </div>
            </div>

            <div class="w-full max-w-md mx-auto my-auto py-6">
                
                <!-- Heading -->
                <div class="mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Sign In to Your Account
                    </h1>
                    <p class="text-sm text-slate-500 mt-2">
                        Welcome back! Please enter your administrative credentials to continue.
                    </p>
                </div>

                <!-- Session Alert -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Validation Error Alert -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                value="{{ old('email', 'admin@ebook.com') }}"
                                placeholder="name@company.com"
                                class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-300 focus:border-brand-600 rounded-xl pl-11 pr-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-brand-600/10 transition duration-150 ease-in-out shadow-xs"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <a href="#" class="text-xs font-medium text-brand-600 hover:text-brand-700 hover:underline">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="current-password" 
                                required 
                                placeholder="••••••••"
                                value="password"
                                class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-300 focus:border-brand-600 rounded-xl pl-11 pr-11 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-brand-600/10 transition duration-150 ease-in-out shadow-xs"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                                aria-label="Toggle password visibility"
                            >
                                <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600 focus:ring-offset-0 transition"
                            >
                            <span class="text-xs sm:text-sm text-slate-600 font-medium">Remember for 30 days</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full group rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold py-3 px-4 shadow-lg shadow-brand-600/25 hover:shadow-brand-600/35 transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Sign In</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Default Demo Credentials Card -->
                <div class="mt-8 p-4 rounded-2xl bg-brand-50/70 border border-brand-100/90 text-center">
                    <p class="text-xs font-medium text-slate-600 mb-1">Quick Demo Credentials</p>
                    <div class="flex items-center justify-center gap-2 text-xs font-mono text-brand-900 font-semibold">
                        <span>admin@ebook.com</span>
                        <span class="text-slate-400">&bull;</span>
                        <span>password</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="w-full max-w-md mx-auto text-center pt-6">
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'E-Book') }}. All rights reserved.
                </p>
            </div>
        </div>

    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>
</html>
