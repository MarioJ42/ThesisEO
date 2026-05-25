@extends('layouts.guest_rsvp')

@section('content')
    <div class="relative w-full h-screen overflow-hidden bg-gray-900" x-data="hubLogic({{ $event->id }})">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-50"
            style="background-image: url('{{ $event->cover_image ? asset('storage/' . $event->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2000&auto=format&fit=crop' }}');">
        </div>

        <div class="absolute top-8 right-8 z-50 flex items-center gap-3">
            <p x-show="isSyncing" class="text-white text-xs font-bold animate-pulse bg-black/50 px-3 py-1.5 rounded-full" x-cloak>Syncing...</p>
            <button x-show="pendingCount > 0" @click="syncData()" x-cloak
                class="px-4 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold rounded-full shadow-[0_0_15px_rgba(234,179,8,0.5)] flex items-center gap-2 transition-transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span x-text="pendingCount" class="text-lg"></span>
            </button>
        </div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
            <p class="text-sm md:text-base uppercase tracking-[0.3em] font-semibold text-gray-300 mb-4 drop-shadow-md">The Wedding Of</p>
            <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl text-white mb-6 drop-shadow-xl">{{ $event->title }}</h1>

            <h2 class="font-serif text-3xl md:text-4xl mt-16 tracking-widest uppercase drop-shadow-xl text-white/90">Welcome Guests!</h2>
        </div>

        <div class="absolute bottom-8 left-8 z-50">
            <button @click="menuOpen = !menuOpen"
                class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/50 text-white flex items-center justify-center hover:bg-white/40 transition-all shadow-lg hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </button>

            <div x-show="menuOpen" @click.away="menuOpen = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="absolute bottom-16 left-0 w-64 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-100 p-3 flex flex-col gap-2"
                x-cloak>

                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider px-2 pt-1 pb-2">Crew RSVP</p>

                <a href="{{ route('crew.rsvp.scan', $event->id) }}"
                    class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-blue-50 text-gray-800 hover:text-blue-700 rounded-xl transition-colors font-semibold text-sm text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    Scan QR Code
                </a>
                <a href="{{ route('crew.rsvp.search', $event->id) }}"
                    class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-emerald-50 text-gray-800 hover:text-emerald-700 rounded-xl transition-colors font-semibold text-sm text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search Name / Phone
                </a>
                <a href="{{ route('crew.rsvp.guests.create', $event->id) }}"
                    class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-emerald-50 text-gray-800 hover:text-emerald-700 rounded-xl transition-colors font-semibold text-sm text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m2 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Input New Guest
                </a>
                <a href="{{ route('crew.dashboard') }}"
                    class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-red-50 text-gray-800 hover:text-red-700 rounded-xl transition-colors font-semibold text-sm text-left mt-2 border-t border-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>

            </div>
        </div>
    </div>

    <script>
        function hubLogic(eventId) {
            return {
                menuOpen: false,
                pendingCount: 0,
                isSyncing: false,

                init() {
                    const checkins = JSON.parse(localStorage.getItem('offline_checkin_' + eventId)) || [];
                    const newGuests = JSON.parse(localStorage.getItem('offline_new_guests_' + eventId)) || [];
                    this.pendingCount = checkins.length + newGuests.length;
                },

                syncData() {
                    if(!navigator.onLine) {
                        return;
                    }

                    const checkins = JSON.parse(localStorage.getItem('offline_checkin_' + eventId)) || [];
                    const newGuests = JSON.parse(localStorage.getItem('offline_new_guests_' + eventId)) || [];

                    if(checkins.length === 0 && newGuests.length === 0) return;

                    this.isSyncing = true;

                    fetch(`/crew/events/${eventId}/rsvp/sync`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ checkins: checkins, new_guests: newGuests })
                    })
                    .then(response => response.json())
                    .then(result => {
                        this.isSyncing = false;
                        if(result.success) {
                            localStorage.removeItem('offline_checkin_' + eventId);
                            localStorage.removeItem('offline_new_guests_' + eventId);
                            this.pendingCount = 0;
                        } else {
                        }
                    })
                    .catch(error => {
                        this.isSyncing = false;
                    });
                }
            }
        }
    </script>
@endsection
