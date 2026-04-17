<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix Event Organizer</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer"
                    onclick="window.location.href='{{ route('home') }}'">
                    <img src="/images/logo-fenix2.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Home</a>
                    <a href="{{ route('vendor') }}"
                        class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Vendor</a>
                    <a href="{{ route('client.events.index') }}"
                        class="text-gray-900 border-b-2 border-gray-900 font-medium text-sm transition-colors px-1 py-2">My
                        Events</a>
                </div>

                <div class="flex items-center gap-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors focus:outline-none">
                            <span>Hi, {{ Auth::user()->name }}</span>
                            <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition.opacity.duration.200ms
                            class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50 flex flex-col"
                            x-cloak>
                            <a href="{{ route('profile.edit') }}"
                                class="px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <div class="h-px bg-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0 block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <div class="flex-1"></div>
                <div class="flex-1"></div>
                <div class="flex-1 flex justify-end">
                    <a href="{{ route('client.events.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Events
                    </a>
                </div>
            </div>

            <div
                class="max-w-3xl mx-auto bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden mb-12">
                <div class="absolute top-0 left-0 w-full h-2 bg-gray-900"></div>

                <div class="text-center mt-2">
                    <span
                        class="inline-block px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full mb-4
                        @if ($event->status == 'draft') bg-gray-100 text-gray-600
                        @elseif($event->status == 'planning') bg-blue-50 text-blue-600
                        @elseif($event->status == 'ongoing') bg-yellow-50 text-yellow-700
                        @elseif($event->status == 'completed') bg-green-50 text-green-700
                        @elseif($event->status == 'canceled') bg-red-50 text-red-600 @endif">
                        {{ $event->status }}
                    </span>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-4">{{ $event->title }}
                    </h1>

                    <div class="mb-5 flex justify-center">
                        <a href="{{ route('client.events.billing', $event->id) }}"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-black shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Go to Billing & Payment
                        </a>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-6 text-gray-500 font-medium text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
                        </span>
                        <span class="hidden sm:block text-gray-300">•</span>
                        <span class="flex items-center gap-2 text-gray-900">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $event->package ? $event->package->name : 'Custom Arrangement' }}
                        </span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8 items-start" x-data="{ searchCategory: '' }">

                <div class="w-full lg:w-1/4">
                    <div
                        class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm sticky top-28 max-h-[calc(100vh-8rem)] overflow-y-auto no-scrollbar">
                        <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider mb-5">Category
                            Checklist</h3>
                        <ul class="space-y-4">
                            @foreach ($morningSlots->concat($eveningSlots) as $sidebarSlot)
                                @php
                                    $statusColor = 'text-gray-400';
                                    if (in_array($sidebarSlot->status, ['verified', 'signed'])) {
                                        $statusColor = 'text-emerald-600 font-bold';
                                    } elseif ($sidebarSlot->vendor_id) {
                                        $statusColor = 'text-blue-600 font-semibold';
                                    }
                                @endphp
                                <li class="text-sm {{ $statusColor }} flex items-center gap-3">
                                    @if (in_array($sidebarSlot->status, ['verified', 'signed']))
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @elseif($sidebarSlot->vendor_id)
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex-shrink-0"></div>
                                    @endif
                                    <span class="truncate">{{ $sidebarSlot->category_name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="w-full lg:w-3/4 sticky top-28 max-h-[calc(100vh-8rem)] overflow-y-auto no-scrollbar pb-10">
                    <div class="mb-8 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" x-model="searchCategory" placeholder="Search category name"
                            class="w-full pr-4 py-3.5 bg-white border border-gray-100 rounded-2xl text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-colors"
                            style="padding-left: 3rem;">
                    </div>

                    @foreach (['morning' => 'Morning Session', 'evening' => 'Reception'] as $sessionKey => $sessionTitle)
                        @php $sessionSlots = $sessionKey == 'morning' ? $morningSlots : $eveningSlots; @endphp

                        @if ($sessionSlots->count() > 0)
                            <div class="mb-12">
                                <div class="flex items-center gap-4 mb-6">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $sessionTitle }}</h3>
                                    <div class="flex-grow h-px bg-gray-200"></div>
                                </div>

                                <div class="space-y-4">
                                    @foreach ($sessionSlots as $slot)
                                        <div x-show="searchCategory === '' || '{{ strtolower(addslashes($slot->category_name)) }}'.includes(searchCategory.toLowerCase())"
                                            class="bg-white rounded-2xl border transition-all duration-300 overflow-hidden shadow-sm hover:shadow-md
                                        @if ($slot->vendor_id && $slot->vendor_package_id) border-emerald-200
                                        @elseif($slot->vendor_id && !$slot->vendor_package_id) border-blue-300 ring-2 ring-blue-50
                                        @else border-gray-200 @endif">

                                            <div
                                                class="p-6 flex flex-col md:flex-row gap-6 items-start md:items-center">
                                                <div class="w-full md:w-5/12">
                                                    <div class="flex items-center gap-2 mb-1.5">
                                                        <h4
                                                            class="font-extrabold text-gray-900 uppercase tracking-wide text-sm">
                                                            {{ $slot->category_name }}</h4>
                                                        @if ($slot->is_included)
                                                            <span
                                                                class="text-[9px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-extrabold tracking-wider">INCLUDED</span>
                                                        @endif
                                                    </div>
                                                    @if ($slot->role_detail && $slot->role_detail !== '-')
                                                        <p class="text-xs text-gray-500 leading-relaxed">
                                                            {{ $slot->role_detail }}</p>
                                                    @endif
                                                </div>

                                                <div
                                                    class="w-full md:w-7/12 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6">
                                                    @if ($slot->vendor_id && $slot->vendor_package_id)
                                                        <div
                                                            class="flex items-start justify-between bg-emerald-50/50 p-4 rounded-xl border border-emerald-100">
                                                            <div>
                                                                <p
                                                                    class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">
                                                                    Assigned Vendor</p>
                                                                <p class="font-bold text-gray-900 text-lg">
                                                                    {{ $slot->vendor_name }}</p>
                                                                <p class="text-sm text-gray-600 mt-0.5">
                                                                    {{ $slot->package_name }}</p>

                                                                @php
                                                                    $baseCost =
                                                                        $slot->is_included &&
                                                                        isset($baseCosts[$slot->vendor_category_id])
                                                                            ? $baseCosts[$slot->vendor_category_id]
                                                                            : 0;
                                                                    $upgradeFee = max(0, $slot->deal_price - $baseCost);
                                                                @endphp

                                                                <div
                                                                    class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border {{ $upgradeFee > 0 ? 'border-red-100' : 'border-emerald-100' }}">
                                                                    @if ($upgradeFee > 0)
                                                                        <svg class="w-4 h-4 text-red-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                                                                            </path>
                                                                        </svg>
                                                                        <span
                                                                            class="text-[11px] font-bold text-red-600">Upgrade
                                                                            +Rp
                                                                            {{ number_format($upgradeFee, 0, ',', '.') }}</span>
                                                                    @else
                                                                        <svg class="w-4 h-4 text-emerald-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="M5 13l4 4L19 7">
                                                                            </path>
                                                                        </svg>
                                                                        <span
                                                                            class="text-[11px] font-bold text-emerald-600">Standard
                                                                            (Free)</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            @if (in_array($slot->status, ['verified', 'signed']))
                                                                <div
                                                                    class="flex items-center gap-1.5 bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                    <span
                                                                        class="text-[11px] font-bold uppercase tracking-wider">Verified</span>
                                                                </div>
                                                            @else
                                                                <form
                                                                    action="{{ route('client.events.slots.remove', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                    method="POST">
                                                                    @csrf @method('PUT')
                                                                    <button type="submit"
                                                                        class="text-xs font-semibold text-gray-400 hover:text-red-600 transition-colors underline underline-offset-2">Change</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    @elseif($slot->vendor_id && !$slot->vendor_package_id)
                                                        <div class="flex flex-col gap-3">
                                                            <div
                                                                class="flex justify-between items-center bg-blue-50 px-4 py-2.5 rounded-lg border border-blue-100">
                                                                <span
                                                                    class="text-sm font-bold text-blue-900 flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    {{ $slot->vendor_name }}
                                                                </span>

                                                                @if (!in_array($slot->status, ['verified', 'signed']))
                                                                    <form
                                                                        action="{{ route('client.events.slots.remove', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                        method="POST">
                                                                        @csrf @method('PUT')
                                                                        <button type="submit"
                                                                            class="text-[11px] font-semibold text-blue-600 hover:text-red-600 transition-colors underline">Cancel</button>
                                                                    </form>
                                                                @endif
                                                            </div>

                                                            <form
                                                                action="{{ route('client.events.slots.assign', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                method="POST">
                                                                @csrf @method('PUT')
                                                                @php
                                                                    $packages = collect(
                                                                        $vendorPackages[$slot->vendor_id] ?? [],
                                                                    )->where(
                                                                        'vendor_category_id',
                                                                        $slot->vendor_category_id,
                                                                    );
                                                                    $baseCost =
                                                                        $slot->is_included &&
                                                                        isset($baseCosts[$slot->vendor_category_id])
                                                                            ? $baseCosts[$slot->vendor_category_id]
                                                                            : 0;
                                                                @endphp
                                                                <label
                                                                    class="block text-xs font-bold text-gray-700 mb-1.5">Select
                                                                    a Package</label>
                                                                <div class="flex gap-2">
                                                                    <select name="vendor_package_id" required
                                                                        class="flex-grow bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 shadow-sm">
                                                                        <option value="" disabled selected>Choose
                                                                            the best option</option>
                                                                        @foreach ($packages as $pkg)
                                                                            @php
                                                                                $upgradeFee = max(
                                                                                    0,
                                                                                    $pkg->price - $baseCost,
                                                                                );
                                                                                $upgradeText =
                                                                                    $upgradeFee > 0
                                                                                        ? '(+Rp ' .
                                                                                            number_format(
                                                                                                $upgradeFee,
                                                                                                0,
                                                                                                ',',
                                                                                                '.',
                                                                                            ) .
                                                                                            ')'
                                                                                        : '(Free)';
                                                                            @endphp
                                                                            <option value="{{ $pkg->id }}">
                                                                                {{ $pkg->name }}
                                                                                {{ $upgradeText }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <button type="submit"
                                                                        class="bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-sm font-bold px-6 transition-colors shadow-sm">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @else
                                                        @php
                                                            $cat = $categories
                                                                ->where('id', $slot->vendor_category_id)
                                                                ->first();
                                                            $availableVendors = $cat ? $cat->vendors : collect();
                                                            if (
                                                                $slot->is_included &&
                                                                isset($allowedVendors[$slot->vendor_category_id]) &&
                                                                $allowedVendors[$slot->vendor_category_id]->isNotEmpty()
                                                            ) {
                                                                $allowedIds = $allowedVendors[$slot->vendor_category_id]
                                                                    ->pluck('vendor_id')
                                                                    ->toArray();
                                                                $availableVendors = $availableVendors->whereIn(
                                                                    'id',
                                                                    $allowedIds,
                                                                );
                                                            }
                                                        @endphp
                                                        <form
                                                            action="{{ route('client.events.slots.assign', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                            method="POST">
                                                            @csrf @method('PUT')
                                                            <label
                                                                class="block text-xs font-bold text-gray-500 mb-1.5">Available
                                                                Vendors</label>
                                                            <div class="flex gap-2">
                                                                <select name="vendor_id" required
                                                                    class="flex-grow bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:bg-white focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-colors">
                                                                    <option value="" disabled selected>Browse our
                                                                        partners</option>
                                                                    @foreach ($availableVendors as $v)
                                                                        <option value="{{ $v->id }}">
                                                                            {{ $v->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <button type="submit"
                                                                    class="bg-white border border-gray-300 text-gray-900 hover:bg-gray-50 rounded-xl text-sm font-bold px-6 transition-colors shadow-sm">Select</button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </main>

</body>

</html>
