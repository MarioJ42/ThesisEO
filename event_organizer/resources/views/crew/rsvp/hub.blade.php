@extends('layouts.guest_rsvp')

@section('content')
<div class="relative w-full h-screen overflow-hidden bg-gray-900" x-data="{ menuOpen: false }">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-50"
         style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2000&auto=format&fit=crop');">
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
        <p class="text-sm md:text-base uppercase tracking-[0.3em] font-semibold text-gray-300 mb-4 drop-shadow-md">The Wedding Of</p>
        <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl text-white mb-6 drop-shadow-xl">{{ $event->title }}</h1>
        <p class="text-lg md:text-xl font-medium text-gray-200 drop-shadow-md mb-2">
            {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
        </p>
        <p class="text-sm md:text-base text-gray-400 drop-shadow-md">{{ $event->venue ?? 'Venue Location' }}</p>

        <h2 class="font-serif text-3xl md:text-4xl mt-16 tracking-widest uppercase drop-shadow-xl text-white/90">Welcome Guests!</h2>
    </div>

    <div class="absolute bottom-8 left-8 z-50">
        <button @click="menuOpen = !menuOpen"
                class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/50 text-white flex items-center justify-center hover:bg-white/40 transition-all shadow-lg hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>

        <div x-show="menuOpen"
             @click.away="menuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-16 left-0 w-64 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-100 p-3 flex flex-col gap-2" x-cloak>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider px-2 pt-1 pb-2">Crew RSVP Action</p>

            <a href="{{ route('crew.rsvp.scan', $event->id) }}" class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-blue-50 text-gray-800 hover:text-blue-700 rounded-xl transition-colors font-semibold text-sm text-left">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                Scan QR Code
            </a>
            <a href="{{ route('crew.rsvp.search', $event->id) }}" class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-emerald-50 text-gray-800 hover:text-emerald-700 rounded-xl transition-colors font-semibold text-sm text-left">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Search Name / Phone
            </a>
            <a href="{{ route('crew.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-red-50 text-gray-800 hover:text-red-700 rounded-xl transition-colors font-semibold text-sm text-left mt-2 border-t border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
