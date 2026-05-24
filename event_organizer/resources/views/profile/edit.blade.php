<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix Event Organizer</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    @php
        $userRole = Auth::user()->role;
        $backUrl = route('home');
        $backText = 'Back to Home';

        if ($userRole === 'owner') {
            $backUrl = route('owner.dashboard');
            $backText = 'Back to Dashboard';
        } elseif ($userRole === 'pl') {
            $backUrl = route('pl.dashboard');
            $backText = 'Back to Dashboard';
        } elseif ($userRole === 'crew_eo') {
            $backUrl = route('crew.dashboard');
            $backText = 'Back to Dashboard';
        } elseif ($userRole === 'klien') {
            $backUrl = route('client.events.index');
            $backText = 'Back to My Events';
        }
    @endphp

    <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer" onclick="window.location.href='{{ $backUrl }}'">
                    <img src="/images/logo-fenix2.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
                </div>

                {{-- Sembunyikan link navbar klien jika yang login adalah manajemen Fenix --}}
                @if($userRole === 'klien')
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Home</a>
                    <a href="{{ route('vendor') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Vendor</a>
                    <a href="{{ route('client.events.index') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">My Events</a>
                </div>
                @endif

                <div class="flex items-center gap-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm font-semibold text-gray-900 hover:text-blue-600 transition-colors focus:outline-none">
                            <span>Hi, {{ Auth::user()->name }}</span>
                            <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>

                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50 flex flex-col" x-cloak>
                            <a href="{{ route('profile.edit') }}" class="px-4 py-2.5 text-sm text-blue-600 bg-blue-50 font-semibold transition-colors flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile
                            </a>
                            <div class="h-px bg-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0 block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-24 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Account Settings</h1>
                    <p class="text-sm text-gray-500 mt-2">Manage your profile details, contact information, and security preferences.</p>
                </div>
                <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-gray-900 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    {{ $backText }}
                </a>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gray-900"></div>
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-3xl p-8 border border-red-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </main>
</body>
</html>
