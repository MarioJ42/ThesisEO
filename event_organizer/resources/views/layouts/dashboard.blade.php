<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fenix Event Organizer</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 text-gray-300 flex flex-col shadow-xl">
        <div class="h-16 flex items-center px-6 bg-gray-950 font-bold text-xl text-white tracking-wider">
            FENIX
        </div>

        <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">

            @php
                $dashboardRoute = Auth::user()->role === 'owner' ? route('owner.dashboard') : route('pl.dashboard');
                $isDashboardActive = request()->routeIs('owner.dashboard') || request()->routeIs('pl.dashboard');
            @endphp
            <a href="{{ $dashboardRoute }}"
                class="block px-4 py-2.5 rounded-md transition-colors {{ $isDashboardActive ? 'bg-gray-950 text-white font-bold' : 'hover:bg-gray-800 hover:text-white font-medium' }}">
                Dashboard
            </a>

            @if (Auth::user()->role === 'owner')
                <div x-data="{
                    open: localStorage.getItem('masterDataOpen') === 'true' || (localStorage.getItem('masterDataOpen') === null && {{ request()->routeIs('owner.users') || request()->routeIs('owner.clients') || request()->routeIs('owner.vendors') || request()->routeIs('owner.vendors.manage') || request()->routeIs('owner.wedding_packages') || request()->routeIs('owner.portfolios.*') ? 'true' : 'false' }})
                }" x-init="$watch('open', val => localStorage.setItem('masterDataOpen', val))">

                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-4 py-2.5 rounded-md font-medium transition-colors hover:bg-gray-800 hover:text-white mt-2">
                        <span>Master Data</span>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1" x-cloak>
                        <a href="{{ route('owner.users') }}"
                            class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('owner.users') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">User</a>
                        <a href="{{ route('owner.clients') }}"
                            class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('owner.clients') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">Clients</a>
                        <a href="{{ route('owner.vendors') }}"
                            class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('owner.vendors') || request()->routeIs('owner.vendors.manage') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">Vendor</a>
                        <a href="{{ route('owner.wedding_packages') }}"
                            class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('owner.wedding_packages') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">Event
                            Package</a>
                        <a href="{{ route('owner.portfolios.index') }}"
                            class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('owner.portfolios.*') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">EO
                            Portfolio</a>
                    </div>
                </div>
            @endif

            @if (in_array(Auth::user()->role, ['owner', 'pl']))
                @php
                    $isEventActive =
                        request()->routeIs('owner.events.index') ||
                        request()->routeIs('pl.events.index') ||
                        request()->routeIs('owner.events.manage') ||
                        request()->routeIs('pl.events.manage');
                @endphp
                <a href="{{ route(Auth::user()->role . '.events.index') }}"
                    class="block px-4 py-2.5 mt-2 rounded-md transition-colors {{ $isEventActive ? 'bg-gray-950 text-white font-bold' : 'hover:bg-gray-800 hover:text-white font-medium' }}">
                    Event Arrangement
                </a>
            @endif

        </nav>

        <div class="p-4 border-t border-gray-800">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <button type="button" onclick="confirmLogout()"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center transition-colors">
                Logout
            </button>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white shadow-sm flex items-center px-8 border-b border-gray-100">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Welcome,
                <a href="{{ route('profile.edit') }}" title="Edit Profile"
                    class="hover:text-blue-600 hover:underline underline-offset-4 transition-all duration-200">
                    {{ Auth::user()->name }}
                </a>
            </h2>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Ready to Leave?',
                text: "Are you sure you want to logout from your account?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                scrollbarPadding: false,
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    @auth
        @if (Auth::user()->must_change_password)
            <div class="fixed inset-0 flex items-center justify-center p-4"
                style="z-index: 99999; background-color: rgba(0, 0, 0, 0.9); backdrop-filter: blur(8px);">
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Security Update</h3>
                    <p class="text-sm text-gray-500 mb-6">For your account's security, please change the default password
                        provided by the administrator.</p>

                    <form action="{{ route('password.force_change') }}" method="POST" class="text-left space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">New
                                Password</label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50"
                                placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Confirm New
                                Password</label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50"
                                placeholder="Re-type new password">
                        </div>
                        <button type="submit" class="w-full pt-2">
                            <div
                                class="w-full py-3 px-4 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm shadow-md transition-colors text-center">
                                Update Password & Re-login
                            </div>
                        </button>
                    </form>
                </div>
            </div>
            <style>
                body {
                    overflow: hidden !important;
                }
            </style>
        @endif
    @endauth

</body>

</html>
