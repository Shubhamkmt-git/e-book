<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'E-Book Store & Digital Library') &bull; {{ config('app.name', 'E-Book') }}</title>

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen selection:bg-brand-600 selection:text-white">

    <!-- Frontend Nav Component -->
    @include('frontend.layouts.navbar')

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Frontend Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold">
                        <i class="fa-solid fa-book-open text-base"></i>
                    </div>
                    <span class="font-bold text-lg text-white">E-Book Store</span>
                </div>

                <div class="flex items-center gap-6 text-sm">
                    <a href="#browse" class="hover:text-white transition">Books</a>
                    <a href="#categories" class="hover:text-white transition">Categories</a>
                    <a href="#authors" class="hover:text-white transition">Authors</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-brand-400 transition">Admin Portal</a>
                </div>

                <div class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'E-Book Platform') }}. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
