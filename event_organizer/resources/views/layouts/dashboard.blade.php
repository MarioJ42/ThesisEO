<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fenix EO') }} - Admin Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
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
                    open: localStorage.getItem('masterDataOpen') === 'true' || (localStorage.getItem('masterDataOpen') === null && {{ request()->routeIs('owner.users') || request()->routeIs('owner.vendors') || request()->routeIs('owner.vendors.manage') ? 'true' : 'false' }})
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
                        <a href="{{ route('owner.vendors') }}"
                            class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('owner.vendors') || request()->routeIs('owner.vendors.manage') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">Vendor</a>
                    </div>
                </div>
            @endif

            @if (in_array(Auth::user()->role, ['owner', 'pl']))
                @php
                    $isEventActive = request()->routeIs('owner.events.index') || request()->routeIs('pl.events.index') || request()->routeIs('owner.events.manage') || request()->routeIs('pl.events.manage');
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
                Welcome, {{ Auth::user()->name }}
            </h2>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

</body>

</html>
