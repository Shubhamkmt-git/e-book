<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal Login - {{ $appSetting->app_name ?? config('app.name', 'E-Book') }}</title>

    @if(!empty($appSetting?->favicon_url))
        <link rel="icon" href="{{ $appSetting->favicon_url }}">
        <link rel="shortcut icon" href="{{ $appSetting->favicon_url }}">
    @endif

    <!-- Google Fonts Poppins & Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, html {
            font-family: 'Poppins', 'Roboto', sans-serif;
            background-color: #f9f9f7;
            background-image: 
                linear-gradient(to right, rgba(0, 0, 0, 0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.035) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Roboto', 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="admin-scope h-full antialiased text-slate-800 flex flex-col justify-between items-center min-h-screen p-4 sm:p-6 selection:bg-brand-600 selection:text-white font-poppins">

    <!-- Top spacer -->
    <div class="w-full"></div>

    <!-- Centered Card Container -->
    <div class="w-full max-w-[430px] my-auto">
        <div class="bg-white rounded-2xl p-8 sm:p-10 shadow-2xl shadow-slate-900/[0.06] border border-slate-200/70">
            
            <!-- Logo & Brand Header -->
            <div class="flex flex-col items-center justify-center mb-7">
                @if(!empty($appSetting?->logo_light_url) || !empty($appSetting?->logo_dark_url))
                    <a href="{{ route('home') }}" target="_blank" title="{{ $appSetting->app_name ?? 'Storefront' }}" class="inline-block mb-3.5 transition hover:opacity-90">
                        <img 
                            src="{{ $appSetting->logo_light_url ?? $appSetting->logo_dark_url }}" 
                            alt="{{ $appSetting->app_name ?? 'Logo' }}" 
                            class="h-12 w-auto max-w-[200px] object-contain"
                        >
                    </a>
                @else
                    <div class="w-14 h-14 rounded-full bg-brand-50 border border-brand-100 flex items-center justify-center shadow-xs mb-3.5">
                        <i class="fa-solid fa-book-open text-2xl text-brand-600"></i>
                    </div>
                @endif
                <p class="text-xs font-semibold text-slate-500 tracking-normal uppercase">{{ $appSetting->app_name ?? config('app.name', 'E-Book Platform') }}</p>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-1">Admin Portal Login</h1>
            </div>

            <!-- Status Alert -->
            @if (session('status'))
                <div class="mb-5 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="mb-5 p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        class="w-full bg-white border border-slate-300 focus:border-brand-600 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600/20 transition duration-150 shadow-2xs"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            placeholder="••••••••"
                            class="w-full bg-white border border-slate-300 focus:border-brand-600 rounded-lg pl-3.5 pr-10 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600/20 transition duration-150 shadow-2xs"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer"
                            aria-label="Toggle password visibility"
                        >
                            <i id="eye-icon" class="fa-regular fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600 focus:ring-offset-0 transition"
                        >
                        <span class="text-xs text-slate-600 font-normal">Remember this device</span>
                    </label>
                </div>

                <!-- Sign In Button -->
                <div class="pt-3">
                    <button 
                        type="submit" 
                        class="w-full rounded-full bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold py-3 px-4 shadow-md shadow-brand-600/25 transition-all duration-150 active:scale-[0.99] cursor-pointer"
                    >
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bottom Footer Copyright -->
    <footer class="w-full text-center py-4">
        <p class="text-xs text-slate-400 font-normal">
            &copy; {{ date('Y') }} {{ $appSetting->app_name ?? config('app.name', 'E-Book Platform') }}. All rights reserved.
        </p>
    </footer>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'fa-regular fa-eye-slash text-sm';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'fa-regular fa-eye text-sm';
            }
        }
    </script>
</body>
</html>
