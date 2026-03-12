<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fenix EO') }} - Admin Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

            @if(Auth::user()->role === 'owner')
            <div x-data="{ open: {{ request()->routeIs('admin.users') || request()->routeIs('admin.vendors') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-4 py-2.5 rounded-md font-medium transition-colors hover:bg-gray-800 hover:text-white mt-2">
                    <span>Master Data</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('admin.users') }}" class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('admin.users') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">User</a>
                    <a href="{{ route('admin.vendors') }}" class="block px-4 py-2 rounded-md transition-colors {{ request()->routeIs('admin.vendors') ? 'bg-gray-950 text-white font-bold' : 'text-sm hover:bg-gray-800 hover:text-white' }}">Vendor</a>
                </div>
            </div>
            @endif

            @if(in_array(Auth::user()->role, ['owner', 'pl']))
            <a href="{{ route('pl.events') }}"
               class="block px-4 py-2.5 mt-2 rounded-md transition-colors {{ request()->routeIs('pl.events') ? 'bg-gray-950 text-white font-bold' : 'hover:bg-gray-800 hover:text-white font-medium' }}">
                Event Arrangement
            </a>
            @endif

        </nav>

        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md transition-colors">
                    Logout
                </button>
            </form>
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

</body>
</html>
