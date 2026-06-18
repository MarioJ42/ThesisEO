@extends('layouts.guest_rsvp')

@section('content')
<div class="max-w-6xl mx-auto p-8" x-data="{ search: '' }">

    <div class="mb-12">
        <h1 class="font-serif text-4xl md:text-5xl text-gray-900 mb-4">Search Guest</h1>
        <p class="text-lg font-bold text-gray-800">{{ $event->title }}</p>
        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</p>
    </div>

    <div class="relative mb-12 max-w-xl">
        <input type="text" x-model="search" placeholder="Search by name or phone number..."
               class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-full text-sm focus:ring-gray-300 focus:border-gray-400 shadow-sm transition-all">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 pb-20">
        @foreach($guests as $guest)
            <a href="{{ route('crew.rsvp.checkin.form', [$event->id, $guest->barcode_token ?? $guest->id]) }}"
               x-show="search === '' || '{{ strtolower(addslashes($guest->name . ' ' . $guest->phone_number)) }}'.includes(search.toLowerCase())"
               class="block bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all cursor-pointer">

                <div class="flex items-start gap-4">
                    <div class="mt-1">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $guest->name }}</h3>
                        <p class="text-sm font-semibold text-gray-700">{{ $guest->phone_number ?? '-' }}</p>

                        @php
                            $isConfirmed = in_array($guest->status, ['attending', 'checked_in']);
                            $displayPax = ($isConfirmed && $guest->pax_actual > 0) ? $guest->pax_actual : $guest->pax_invited;
                        @endphp

                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-bold text-gray-900">{{ $displayPax }}</span> pax
                            @if($isConfirmed)
                                <span class="text-[10px] text-gray-400 ml-1">(Invited: {{ $guest->pax_invited }})</span>
                            @endif
                        </p>

                        <p class="text-[11px] font-bold text-pink-600 mt-4 tracking-wider uppercase">Table: {{ $guest->table_name ?? '-' }}</p>
                        <p class="text-[11px] font-bold text-blue-600 mt-1 tracking-wider uppercase">Side: {{ $guest->side ?? 'General' }}</p>

                        @if($guest->status === 'checked_in')
                            <p class="text-[11px] font-bold text-emerald-600 mt-1 uppercase tracking-wider">Status: CHECKED_IN</p>
                        @elseif($guest->status === 'not_attending')
                            <p class="text-[11px] font-bold text-red-600 mt-1 uppercase tracking-wider">Status: DECLINED</p>
                        @elseif($guest->status === 'pending')
                            <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-wider">Status: PENDING</p>
                        @else
                            <p class="text-[11px] font-bold text-orange-600 mt-1 uppercase tracking-wider">Status: {{ strtoupper($guest->status) }}</p>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="fixed bottom-8 right-8 z-50">
        <a href="{{ route('crew.rsvp.hub', $event->id) }}" class="flex items-center gap-2 bg-gray-900 hover:bg-black text-white px-6 py-3 rounded-full font-bold shadow-xl transition-transform hover:scale-105">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Hub
        </a>
    </div>

</div>
@endsection
