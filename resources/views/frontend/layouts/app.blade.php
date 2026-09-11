<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'E-Book Store & Digital Library') &bull; {{ config('app.name', 'E-Book') }}</title>

    <!-- Dynamic Favicon from App Settings -->
    @if(!empty($appSetting?->favicon_url))
        <link rel="icon" href="{{ $appSetting->favicon_url }}">
        <link rel="apple-touch-icon" href="{{ $appSetting->favicon_url }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📚</text></svg>">
    @endif

    <!-- Google Fonts Poppins & Bebas Neue -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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

    <!-- Main Page Content (Offset for Fixed Nav) -->
    <main class="flex-1 pt-20">
        @yield('content')
    </main>

    <!-- Frontend Footer Component -->
    @include('frontend.layouts.footer')

    <!-- Global Auth Side Drawer Component -->
    @include('frontend.components.auth-drawer')

    <!-- Global Wishlist Side Drawer Component -->
    @include('frontend.components.wishlist-drawer')

    <!-- Global Quick Checkout Modal Component -->
    @include('frontend.components.quick-checkout-modal')

    <!-- Global Top-Right Recent Sales Toast Notification -->
    @include('frontend.components.recent-sales-toast')

    @stack('scripts')
</body>
</html>
