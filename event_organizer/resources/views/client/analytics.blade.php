<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RSVP Analytics - Fenix EO</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer" onclick="window.location.href='{{ route('home') }}'">
                    <img src="/images/logo-fenix2.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Home</a>
                    <a href="{{ route('client.events.index') }}" class="text-gray-900 border-b-2 border-gray-900 font-medium text-sm transition-colors px-1 py-2">My Events</a>
                </div>
                <div class="flex items-center gap-4 text-sm font-semibold text-gray-700">
                    <span>Hi, {{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-24 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <div class="flex-1"></div>
                <div class="flex-1 flex justify-end">
                    <a href="{{ route('client.events.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Events
                    </a>
                </div>
            </div>

            <div class="max-w-7xl mx-auto bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden mb-8">
                <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight mt-2">RSVP & Guestbook Insights</h1>
                <p class="text-sm text-gray-500 mt-1">Real-time attendance & operational metrics for <b class="text-gray-800">{{ $event->title }}</b></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full z-0"></div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 z-10 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div class="z-10">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Physical Gifts</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ $giftFisik }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full z-0"></div>
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 z-10 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="z-10">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Digital Envelopes</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ $giftDigital }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center gap-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full z-0"></div>
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 z-10 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path></svg>
                    </div>
                    <div class="z-10">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">On Behalf Gifts (Titipan)</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ $totalTitipan }} <span class="text-sm font-medium text-gray-500">Records</span></h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800">8. Guest Turnout Report</h3>
                        <p class="text-xs text-gray-500">Check-in success rate vs invited guests.</p>
                    </div>
                    <div id="chart-turnout" class="w-full h-64 flex justify-center items-center"></div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800">9. Catering Capacity Efficiency</h3>
                        <p class="text-xs text-gray-500">Expected pax from invitation vs actual at gate.</p>
                    </div>
                    <div id="chart-pax" class="w-full h-64"></div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800">10. Gift Category Overview</h3>
                        <p class="text-xs text-gray-500">Physical vs digital gift proportions.</p>
                    </div>
                    <div id="chart-gifts" class="w-full h-64 flex justify-center items-center"></div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
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
</body>
</html>
