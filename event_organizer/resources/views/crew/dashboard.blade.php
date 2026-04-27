@extends('layouts.guest_rsvp')

@section('content')
<div class="max-w-6xl mx-auto p-6 md:p-10">

    <div class="flex justify-between items-center mb-10 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Crew Fenix Event Organizer</p>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h1>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="text-sm font-bold text-red-500 hover:text-white bg-red-50 hover:bg-red-500 px-5 py-2.5 rounded-xl transition-all shadow-sm">Logout</button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl font-medium shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-10">

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Event
                    </h2>
                    <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold">{{ $vacantSlots->count() }} Slots</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($vacantSlots as $slot)
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] hover:shadow-lg transition-all relative overflow-hidden group">
                            <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                            <div class="mb-3">
                                <span class="bg-gray-100 text-gray-600 text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider">{{ $slot->session }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 leading-tight mb-1">{{ $slot->jobdesk }}</h3>
                            <p class="text-sm font-semibold text-blue-600 mb-4">{{ $slot->title }}</p>

                            <div class="flex items-center gap-2 text-xs text-gray-500 font-medium bg-gray-50 p-2.5 rounded-lg mb-4">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($slot->event_date)->format('D, d M Y') }}
                            </div>

                            <form action="{{ route('crew.jobs.apply', $slot->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="w-full bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white py-2.5 rounded-xl text-sm font-bold transition-colors">
                                    Apply for this Job
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-10 text-center">
                            <p class="text-gray-500 font-medium">Waiting for upcoming events</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($pendingRequests->count() > 0)
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        My Applications (Waiting Approval)
                    </h2>
                    <div class="space-y-3">
                        @foreach($pendingRequests as $req)
                            <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $req->jobdesk }} <span class="text-gray-400 font-normal mx-1">at</span> <span class="text-blue-600">{{ $req->title }}</span></h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($req->event_date)->format('d M Y') }} &bull; <span class="capitalize">{{ $req->session }}</span></p>
                                </div>
                                <span class="bg-orange-100 text-orange-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider animate-pulse">Requested</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <div>
            <div class="bg-gray-900 rounded-3xl p-6 shadow-xl sticky top-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    My Assignments
                </h2>

                <div class="space-y-4">
                    @forelse($myEvents as $event)
                        <div class="bg-white/10 border border-white/10 rounded-2xl p-5 hover:bg-white/15 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</p>
                                    <h3 class="font-serif text-xl font-bold text-white leading-tight">{{ $event->title }}</h3>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1 mb-5">
                                <p class="text-sm font-medium text-gray-300">Role: <span class="text-emerald-400 font-bold">{{ $event->jobdesk }}</span></p>
                                <p class="text-sm font-medium text-gray-300">Fee: <span class="text-white font-bold">Rp {{ number_format($event->fee, 0, ',', '.') }}</span></p>
                            </div>

                            <a href="{{ route('crew.rsvp.hub', $event->event_id) }}" class="flex items-center justify-center gap-2 w-full bg-emerald-500 hover:bg-emerald-400 text-gray-900 py-3 rounded-xl font-bold transition-all shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                Enter Workspace
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-gray-400 font-medium text-sm">You have no verified assignments yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
