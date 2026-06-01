@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Owner Analytics Dashboard</h2>
            <p class="text-gray-500 text-sm mt-1">Data overview from <b class="text-gray-800">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</b> to <b class="text-gray-800">{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</b>.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('owner.dashboard') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2 bg-white p-1.5 rounded-lg border border-gray-200 shadow-sm w-full lg:w-auto">
                <input type="date" name="start_date" value="{{ $startDate }}" class="text-sm border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-600 bg-gray-50 py-1.5 px-3 w-full sm:w-auto">
                <span class="text-gray-400 text-xs font-bold uppercase mx-1">To</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="text-sm border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-600 bg-gray-50 py-1.5 px-3 w-full sm:w-auto">
                <button type="submit" class="bg-gray-900 hover:bg-black text-white px-4 py-1.5 rounded-md text-sm font-semibold transition-colors w-full sm:w-auto mt-2 sm:mt-0">
                    Apply Filter
                </button>
            </form>

            <div class="bg-white px-4 py-2.5 rounded-lg shadow-sm border border-gray-200 flex items-center gap-3 shrink-0 self-end sm:self-auto">
                <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold text-gray-700 hidden sm:inline">Live Data Active</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full z-0"></div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 z-10 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Gross Revenue</p>
                <h3 class="text-2xl font-black text-gray-900">Rp {{ number_format($totalRevenueYTD, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full z-0"></div>
            <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 z-10 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Net Profit</p>
                <h3 class="text-2xl font-black text-gray-900">Rp {{ number_format($totalProfitYTD, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full z-0"></div>
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 z-10 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Events Managed</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalEventsYTD }} <span class="text-sm font-medium text-gray-500">Events</span></h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">1 & 2. Financial Trend (Gross vs Net)</h3>
                <p class="text-xs text-gray-500">Monthly breakdown of revenue and profit generation.</p>
            </div>
            <div id="chart-finance" class="w-full h-80"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col h-full">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">3. Upcoming Events</h3>
                <p class="text-xs text-gray-500">Top 5 nearest event timelines & countdowns.</p>
            </div>

            <div class="flex-grow flex flex-col justify-center">
                @php
                    $upcomingEvents = \App\Models\Event::where('event_date', '>=', \Carbon\Carbon::today())
                        ->whereIn('status', ['planning', 'ongoing', 'draft'])
                        ->orderBy('event_date', 'asc')
                        ->limit(5)
                        ->get();
                @endphp

                @if($upcomingEvents->count() > 0)
                    <ul class="divide-y divide-gray-100 flex flex-col">
                        @foreach($upcomingEvents as $evt)
                            @php
                                $daysLeft = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($evt->event_date), false);
                            @endphp
                            <li class="py-3 first:pt-0 last:pb-0 flex justify-between items-center group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors
                                        {{ $daysLeft == 0 ? 'bg-emerald-100 text-emerald-600' : ($daysLeft <= 7 ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-600') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $evt->title }}</h4>
                                        <p class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($evt->event_date)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ml-2">
                                    @if($daysLeft === 0)
                                        <span class="inline-block px-2.5 py-1 bg-emerald-500 text-white rounded-md text-[10px] font-black tracking-wider animate-pulse">TODAY</span>
                                    @else
                                        <span class="text-lg font-black {{ $daysLeft <= 7 ? 'text-orange-500' : 'text-blue-600' }}">{{ $daysLeft }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase block -mt-1">Days</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="flex flex-col items-center justify-center text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-sm font-medium">No upcoming events scheduled.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">4. Event Status Volume</h3>
                <p class="text-xs text-gray-500">Total events grouped by their current phase.</p>
            </div>
            <div id="chart-status" class="w-full h-64 flex justify-center items-center"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">5. Top Project Leaders</h3>
                <p class="text-xs text-gray-500">Based on total events handled.</p>
            </div>
            <div id="chart-pl" class="w-full h-64"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">6. Top Vendor Partners</h3>
                <p class="text-xs text-gray-500">Most frequently hired vendors.</p>
            </div>
            <div id="chart-vendors" class="w-full h-64"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawMonthly = @json($monthlyFinances);
    const months = rawMonthly.map(item => item.month);
    const grossData = rawMonthly.map(item => item.gross);
    const netData = rawMonthly.map(item => item.net);

    const rawStatus = @json($eventStatuses);
    const statusLabels = Object.keys(rawStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1));
    const statusData = Object.values(rawStatus);

    const rawPl = @json($plPerformances);
    const plLabels = rawPl.map(item => item.name);
    const plData = rawPl.map(item => item.total_events);

    const rawVendors = @json($topVendors);
    const vendorLabels = rawVendors.map(item => item.name);
    const vendorData = rawVendors.map(item => item.jobs_assigned);

    new ApexCharts(document.querySelector("#chart-finance"), {
        series: [{ name: 'Gross Revenue', data: grossData }, { name: 'Net Profit', data: netData }],
        chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#3b82f6', '#10b981'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: months, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { formatter: (value) => "Rp " + (value / 1000000).toFixed(1) + "M" } },
        tooltip: { y: { formatter: (value) => "Rp " + value.toLocaleString('id-ID') } }
    }).render();

    new ApexCharts(document.querySelector("#chart-status"), {
        series: statusData.length > 0 ? statusData : [1],
        labels: statusLabels.length > 0 ? statusLabels : ['No Data'],
        chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
        colors: ['#64748b', '#3b82f6', '#eab308', '#22c55e', '#ef4444'],
        dataLabels: { enabled: true, formatter: function (val, opts) { return opts.w.config.series[opts.seriesIndex] } },
        legend: { position: 'bottom' }
    }).render();

    new ApexCharts(document.querySelector("#chart-pl"), {
        series: [{ name: 'Events Handled', data: plData }],
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#6366f1'],
        plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true } },
        dataLabels: { enabled: true },
        xaxis: { categories: plLabels },
        legend: { show: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-vendors"), {
        series: [{ name: 'Jobs Assigned', data: vendorData }],
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#f43f5e'],
        plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true } },
        dataLabels: { enabled: true },
        xaxis: { categories: vendorLabels },
        legend: { show: false }
    }).render();
});
</script>
@endsection
