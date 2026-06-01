@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
        <div>
            @if($role === 'owner')
                <a href="{{ route('owner.events.manage', $event->id) }}" class="text-xs font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider transition-colors">&larr; Back to Event Manager</a>
            @elseif($role === 'pl')
                <a href="{{ route('pl.events.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider transition-colors">&larr; Back to Dashboard</a>
            @else
                <a href="{{ route('client.events.manage', $event->id) }}" class="text-xs font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider transition-colors">&larr; Back to Event Manager</a>
            @endif

            <h1 class="text-3xl font-black text-gray-900 mt-2">Event Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time metrics for <b>{{ $event->title }}</b></p>
        </div>
        <div>
            @php $routePrefix = ($role === 'klien') ? 'client' : $role; @endphp
            <a href="{{ route($routePrefix . '.events.manage', $event->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-bold transition-colors shadow-sm inline-flex items-center gap-2">
                &larr; Back to Manage Event
            </a>
        </div>
    </div>

    @if(in_array($role, ['owner', 'pl']))
    <div class="grid grid-cols-1 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">7. Peak Hour Traffic</h3>
                <p class="text-xs text-gray-500">Guest arrival times based on check-in records.</p>
            </div>
            <div id="chart-traffic" class="w-full h-64"></div>
        </div>
    </div>
    @endif

    <div class="mb-4">
        <h3 class="text-xl font-bold text-gray-900">RSVP & Guestbook Insights</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full z-0"></div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 z-10 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Physical Gifts</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $giftFisik }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full z-0"></div>
            <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 z-10 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Digital Envelopes</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $giftDigital }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full z-0"></div>
            <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 z-10 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path></svg>
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Titipan Gifts</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalTitipan }} <span class="text-sm font-medium text-gray-500">Records</span></h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">8. Guest Turnout</h3>
                <p class="text-xs text-gray-500">Check-in success rate vs invited guests.</p>
            </div>
            <div id="chart-turnout" class="w-full h-64 flex justify-center items-center"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">9. Catering Efficiency</h3>
                <p class="text-xs text-gray-500">Invited pax capacity vs actual at gate.</p>
            </div>
            <div id="chart-pax" class="w-full h-64"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">10. Gift Category Overview</h3>
                <p class="text-xs text-gray-500">Physical vs digital gift proportions.</p>
            </div>
            <div id="chart-gifts" class="w-full h-64 flex justify-center items-center"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if(in_array($role, ['owner', 'pl']))
    const rawTraffic = @json($trafficData);
    const timelineLabels = rawTraffic.map(item => item.time_slot);
    const trafficCounts = rawTraffic.map(item => parseInt(item.total));

    new ApexCharts(document.querySelector("#chart-traffic"), {
        series: [{ name: 'Check-ins', data: trafficCounts.length > 0 ? trafficCounts : [0] }],
        chart: { type: 'area', height: 260, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#4f46e5'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4 },
        xaxis: { categories: timelineLabels.length > 0 ? timelineLabels : ['No Arrivals'] },
        yaxis: { labels: { formatter: (value) => Math.round(value) + " Pax" } }
    }).render();
    @endif

    new ApexCharts(document.querySelector("#chart-turnout"), {
        series: [@json($totalCheckedIn), @json($totalAttendingNoCheckin), @json($totalPending), @json($totalDeclined)],
        labels: ['Checked In', 'Confirmed Attending', 'Pending Response', 'Declined'],
        chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
        colors: ['#10b981', '#3b82f6', '#64748b', '#ef4444'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-pax"), {
        series: [{ name: 'Pax', data: [@json($paxExpected), @json($paxActual)] }],
        chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#f59e0b'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '45%', distributed: true } },
        xaxis: { categories: ['Expected Pax', 'Actual Pax'] },
        legend: { show: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-gifts"), {
        series: [@json($giftFisik), @json($giftDigital)],
        labels: ['Physical Gift', 'Digital Envelope'],
        chart: { type: 'pie', height: 280, fontFamily: 'inherit' },
        colors: ['#6366f1', '#10b981'],
        legend: { position: 'bottom' }
    }).render();

});
</script>
@endsection
