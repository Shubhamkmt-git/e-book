<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') &bull; {{ config('app.name', 'E-Book') }}</title>

    <!-- Google Fonts Poppins as fallback/fast CDN link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Smooth transitions for collapsible layout */
        #sidebar, #main-wrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 selection:bg-brand-600 selection:text-white flex min-h-screen">

    <!-- Sidebar Partial -->
    @include('admin.layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div id="main-wrapper" class="lg:pl-64 flex flex-col flex-1 min-h-screen w-full">
        
        <!-- Header Partial -->
        @include('admin.layouts.header')

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>

        <!-- Footer Partial -->
        @include('admin.layouts.footer')
    </div>

    <!-- Scripts -->
    <script>
        // Check saved desktop sidebar preference
        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth >= 1024) {
                const isCollapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';
                if (isCollapsed) {
                    applySidebarState(true);
                }
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('main-wrapper');
            const backdrop = document.getElementById('sidebar-backdrop');
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                const willCollapse = !sidebar.classList.contains('lg:-translate-x-full');
                applySidebarState(willCollapse);
                localStorage.setItem('admin_sidebar_collapsed', willCollapse);
            } else {
                // Mobile behavior
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }
        }

        function applySidebarState(collapse) {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('main-wrapper');

            if (collapse) {
                sidebar.classList.add('lg:-translate-x-full');
                mainWrapper.classList.remove('lg:pl-64');
                mainWrapper.classList.add('lg:pl-0');
            } else {
                sidebar.classList.remove('lg:-translate-x-full');
                mainWrapper.classList.add('lg:pl-64');
                mainWrapper.classList.remove('lg:pl-0');
            }
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const profileBtn = e.target.closest('button[onclick="toggleProfileDropdown()"]');
            const dropdown = document.getElementById('profile-dropdown');
            if (!profileBtn && dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
