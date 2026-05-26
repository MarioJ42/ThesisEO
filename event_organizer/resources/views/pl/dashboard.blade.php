@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Project Leader Dashboard</h2>
        <p class="text-gray-500 text-sm mt-1">Select an event below to view its operational analytics and traffic.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">My Managed Projects</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalManagedEvents }} <span class="text-sm font-medium text-gray-500">Events</span></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="mb-4">
            <h3 class="text-lg font-bold text-gray-800">Assigned Events</h3>
            <p class="text-xs text-gray-500">Click analytics to view guest attendance, traffic, and vendor allocations.</p>
        </div>
        <div class="space-y-3 mt-4">
            @forelse($myEvents as $ev)
                <div class="p-4 bg-gray-50 rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center border border-gray-100 gap-4">
                    <div>
                        <p class="text-base font-bold text-gray-900">{{ $ev->title }}</p>
                        <p class="text-xs text-gray-500 font-semibold mt-1">{{ \Carbon\Carbon::parse($ev->event_date)->format('d F Y') }} &bull; <span class="uppercase text-indigo-500">{{ $ev->status }}</span></p>
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="{{ route('pl.events.analytics', $ev->id) }}" class="flex-1 sm:flex-none text-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition-colors shadow-sm w-full sm:w-auto">View Analytics</a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 italic text-center py-8">No active events assigned to you yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
