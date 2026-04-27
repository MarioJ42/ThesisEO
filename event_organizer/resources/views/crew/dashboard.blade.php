@extends('layouts.guest_rsvp')

@section('content')
<div class="max-w-5xl mx-auto p-6 md:p-12">
    <div class="flex justify-between items-center mb-12 border-b border-gray-200 pb-6">
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Crew Portal</p>
            <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}</h1>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 bg-red-50 px-4 py-2 rounded-lg transition-colors">Logout</button>
        </form>
    </div>

    <h2 class="text-xl font-bold text-gray-900 mb-6">Your Assigned Events</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($events as $event)
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-serif text-2xl font-bold text-gray-900 mb-1">{{ $event->title }}</h3>
                        <p class="text-sm font-semibold text-blue-600">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</p>
                    </div>
                    <span class="bg-gray-900 text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                        {{ $event->pivot->jobdesk ?? 'Crew' }}
                    </span>
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-500 mb-8 mt-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $event->venue ?? 'Venue Location Not Set' }}
                </div>

                <a href="{{ route('crew.rsvp.hub', $event->id) }}" class="flex items-center justify-center gap-2 w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-2xl font-bold transition-all shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Enter Digital Guestbook
                </a>
            </div>
        @empty
            <div class="col-span-full bg-white border border-gray-100 shadow-sm rounded-3xl p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">You don't have any assigned events for today.</p>
                <p class="text-xs text-gray-400 mt-1">Please contact your Project Leader if this is a mistake.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
