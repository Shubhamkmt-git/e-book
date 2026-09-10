<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') &bull; {{ $appSetting->app_name ?? config('app.name', 'E-Book') }}</title>
    @if($appSetting->favicon_url)
    <link rel="icon" type="image/png" href="{{ $appSetting->favicon_url }}">
    @endif

    <!-- Google Fonts Poppins and Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, html {
            font-family: 'Poppins', 'Roboto', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Roboto', 'Poppins', sans-serif;
        }
        /* Smooth transitions for collapsible layout */
        #sidebar, #main-wrapper {
            transition: all 0.25s ease-in-out;
        }
    </style>
    @stack('styles')
</head>
<body class="admin-scope h-full antialiased text-slate-800 bg-slate-50 selection:bg-brand-600 selection:text-white flex min-h-screen font-poppins">

    <!-- Sidebar Partial -->
    @include('admin.layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div id="main-wrapper" class="lg:pl-64 flex flex-col flex-1 min-h-screen w-full transition-all duration-300">
        
        <!-- Header Partial -->
        @include('admin.layouts.header')

        <!-- Page Content -->
        <main class="flex-1 pt-24 px-4 pb-4 sm:px-6 sm:pb-6 lg:px-8 lg:pb-8 w-full">
            @yield('content')
        </main>

        <!-- Footer Partial -->
        @include('admin.layouts.footer')
    </div>

    <!-- Universal Toast Notifications -->
    @include('admin.layouts.toast')

    <!-- Scripts -->
    <script>
        // Initialize desktop icon-only mini sidebar from saved preference
        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth >= 1024) {
                const isMini = localStorage.getItem('admin_sidebar_mini') === 'true';
                if (isMini) {
                    applySidebarMini(true);
                }
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                const willBeMini = !sidebar.classList.contains('sidebar-mini');
                applySidebarMini(willBeMini);
                localStorage.setItem('admin_sidebar_mini', willBeMini);
            } else {
                // Mobile slide-over drawer toggle
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }
        }

        function applySidebarMini(isMini) {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('main-wrapper');
            const header = document.getElementById('admin-header');

            if (isMini) {
                sidebar.classList.add('sidebar-mini');
                mainWrapper.classList.remove('lg:pl-64');
                mainWrapper.classList.add('lg:pl-20');
                if (header) {
                    header.classList.remove('lg:left-64');
                    header.classList.add('lg:left-20');
                }
            } else {
                sidebar.classList.remove('sidebar-mini');
                mainWrapper.classList.add('lg:pl-64');
                mainWrapper.classList.remove('lg:pl-20');
                if (header) {
                    header.classList.add('lg:left-64');
                    header.classList.remove('lg:left-20');
                }
            }
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('hidden');
        }

        function toggleSidebarSubmenu(submenuId, button) {
            const submenu = document.getElementById(submenuId);
            if (!submenu) return;
            const isHidden = submenu.classList.contains('hidden');
            submenu.classList.toggle('hidden');
            
            if (button) {
                button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                const arrow = button.querySelector('.submenu-arrow');
                if (arrow) {
                    if (isHidden) {
                        arrow.classList.add('rotate-180');
                    } else {
                        arrow.classList.remove('rotate-180');
                    }
                }
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const profileBtn = e.target.closest('button[onclick="toggleProfileDropdown()"]');
            const dropdown = document.getElementById('profile-dropdown');
            if (!profileBtn && dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });

        // Universal Toggle Record Status Handler for list tables
        window.toggleRecordStatus = function(btn, url) {
            if (!btn || btn.disabled) return;

            const isChecked = btn.getAttribute('aria-checked') === 'true';
            const nextState = !isChecked;
            const knob = btn.querySelector('span');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-wait');

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json().catch(() => ({}));
                if (res.ok && data.success) {
                    const finalState = (typeof data.status === 'string') ? (data.status === 'active') : nextState;
                    btn.setAttribute('aria-checked', finalState ? 'true' : 'false');

                    if (finalState) {
                        btn.classList.remove('bg-slate-300');
                        btn.classList.add('bg-emerald-500');
                        if (knob) {
                            knob.classList.remove('translate-x-0');
                            knob.classList.add('translate-x-5');
                        }
                        btn.title = 'Status: Active (Click to toggle)';
                    } else {
                        btn.classList.remove('bg-emerald-500');
                        btn.classList.add('bg-slate-300');
                        if (knob) {
                            knob.classList.remove('translate-x-5');
                            knob.classList.add('translate-x-0');
                        }
                        btn.title = 'Status: Inactive (Click to toggle)';
                    }

                    if (typeof window.showToast === 'function') {
                        window.showToast(data.message || `Status updated to ${finalState ? 'active' : 'inactive'}.`, 'success');
                    }
                } else {
                    if (typeof window.showToast === 'function') {
                        window.showToast(data.message || 'Unable to update status. Action not allowed.', 'error');
                    }
                }
            })
            .catch(err => {
                console.error('Toggle status error:', err);
                if (typeof window.showToast === 'function') {
                    window.showToast('Server communication error while toggling status.', 'error');
                }
            })
            .finally(() => {
                btn.disabled = false;
                btn.classList.remove('opacity-60', 'cursor-wait');
            });
        };
    </script>
    @stack('scripts')
</body>
</html>
