@extends('layouts.dashboard')

@section('content')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="max-w-7xl mx-auto" x-data="{
        isModalOpen: false,
        activeTab: localStorage.getItem('manageEventTab') || 'overview',
        isEditModalOpen: false,
        editForm: { id: '', title: '', pl_id: '', package_name: '', event_date: '', status: '' },
        openEditModal(id, title, pl_id, package_name, event_date, status) {
            this.editForm.id = id;
            this.editForm.title = title;
            this.editForm.pl_id = pl_id || '';
            this.editForm.package_name = package_name;
            this.editForm.event_date = event_date;
            this.editForm.status = status;
            this.isEditModalOpen = true;
        },
        isPriceModalOpen: false,
        priceForm: { slot_id: '', vendor_name: '', package_name: '', base_cost: 0, net_price: 0, deal_price: 0 },
        openPriceModal(slot_id, vendor_name, package_name, base_cost, net_price, deal_price) {
            this.priceForm.slot_id = slot_id;
            this.priceForm.vendor_name = vendor_name;
            this.priceForm.package_name = package_name;
            this.priceForm.base_cost = base_cost;
            this.priceForm.net_price = net_price;
            this.priceForm.deal_price = deal_price;
            this.isPriceModalOpen = true;
        },
        isGuestModalOpen: false,
        isEditGuestModalOpen: false,
        guestSearch: '',
        guestForm: { id: '', name: '', phone_number: '', pax_invited: 1, table_name: '', status: 'attending' },
        openEditGuestModal(id, name, phone, pax, table, status) {
            this.guestForm.id = id;
            this.guestForm.name = name;
            this.guestForm.phone_number = phone;
            this.guestForm.pax_invited = pax;
            this.guestForm.table_name = table;
            this.guestForm.status = status;
            this.isEditGuestModalOpen = true;
        }
    }" x-init="$watch('activeTab', value => localStorage.setItem('manageEventTab', value))">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Client: <span
                        class="font-semibold text-gray-700">{{ $event->client->name }}</span> | Date: <span
                        class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                </p>
            </div>
            <a href="{{ route($user->role . '.events.index') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-semibold transition-colors">
                Back to Events
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-t-lg shadow-sm border-b border-gray-200">
            <nav class="flex space-x-8 px-6 overflow-x-auto" aria-label="Tabs">
                <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Overview
                </button>
                <button @click="activeTab = 'planning'"
                    :class="activeTab === 'planning' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Vendor Planning
                </button>
                <button @click="activeTab = 'verification'"
                    :class="activeTab === 'verification' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                    Vendor Verification
                    @php $unverifiedCount = $slots->where('status', 'reviewing')->count(); @endphp
                    @if ($unverifiedCount > 0)
                        <span
                            class="bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-bold">{{ $unverifiedCount }}</span>
                    @endif
                </button>
                <button @click="activeTab = 'rsvp'"
                    :class="activeTab === 'rsvp' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                    RSVP & Guestbook
                </button>
            </nav>
        </div>

        <div class="bg-white rounded-b-lg shadow-sm border border-gray-200 border-t-0 p-6">

            <div x-show="activeTab === 'overview'" x-cloak>
                @php
                    $totalDealPrice = 0;
                    $totalNetPrice = 0;
                    foreach ($slots as $s) {
                        if ($s->vendor_package_id) {
                            $pkg = collect($vendorPackages[$s->vendor_id] ?? [])->firstWhere(
                                'id',
                                $s->vendor_package_id,
                            );
                            $dPrice = $s->deal_price > 0 ? $s->deal_price : $pkg->price ?? 0;
                            $nPrice = $s->net_price > 0 ? $s->net_price : $pkg->net_price ?? 0;
                            $totalDealPrice += $dPrice;
                            $totalNetPrice += $nPrice;
                        }
                    }
                    $vendorMargin = $totalDealPrice - $totalNetPrice;
                    $eoFee = $event->package ? $event->package->eo_fee : 0;
                    $grandProfit = $vendorMargin + $eoFee;
                @endphp

                <div class="mb-8 bg-blue-50/50 border border-blue-100 rounded-xl p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3">
                        <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2 m-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Financial & Profit Summary
                        </h3>
                        <div
                            class="text-sm text-blue-800 bg-white/60 px-4 py-1.5 rounded-lg border border-blue-200/50 shadow-sm whitespace-nowrap">
                            <span class="font-semibold text-gray-500">Package:</span>
                            <span
                                class="font-bold ml-1">{{ $event->package ? $event->package->name : 'Custom Package' }}</span>
                            @if ($event->package)
                                <span class="mx-2 text-blue-300">|</span>
                                <span class="font-bold text-emerald-600">Rp
                                    {{ number_format($event->package->base_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-50">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Selling Price</p>
                            <p class="text-lg font-extrabold text-gray-900">Rp
                                {{ number_format($totalDealPrice, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-50">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Total Vendor's
                                Prices</p>
                            <p class="text-lg font-extrabold text-red-600">Rp
                                {{ number_format($totalNetPrice, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-50">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Margin</p>
                            <p class="text-lg font-extrabold text-green-600">Rp
                                {{ number_format($vendorMargin, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-50 relative overflow-hidden">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">EO Service</p>
                            <p class="text-lg font-extrabold text-green-600">Rp {{ number_format($eoFee, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="mt-4 bg-blue-600 rounded-lg p-5 flex flex-col sm:flex-row justify-between sm:items-center text-white shadow-md gap-2">
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-100">Grand Profit Estimation</p>
                        <p class="text-3xl font-black tracking-tight">Rp {{ number_format($grandProfit, 0, ',', '.') }}</p>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-6">Verified Vendor</h3>

                @foreach (['morning' => 'Morning Session', 'evening' => 'Reception'] as $sessionKey => $sessionTitle)
                    @php $sessionVerifiedSlots = $verifiedSlots->where('session', $sessionKey); @endphp
                    @if ($sessionVerifiedSlots->count() > 0)
                        <div class="mb-8">
                            <h4 class="font-bold text-gray-800 bg-gray-100 px-4 py-2 rounded-t-lg border border-gray-200">
                                {{ $sessionTitle }}</h4>
                            <div class="overflow-x-auto border border-gray-200 rounded-b-lg border-t-0">
                                <table class="min-w-full w-full whitespace-nowrap">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Vendor
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">
                                                Vendor's Name</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Note
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">PIC
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Phone
                                            </th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Meal
                                                Crew</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($sessionVerifiedSlots as $slot)
                                            <tr class="hover:bg-blue-50/30 transition-colors">
                                                <td class="px-4 py-4 text-sm font-bold text-gray-800 uppercase">
                                                    {{ $slot->category_name }}
                                                    @if ($slot->is_included)
                                                        <span
                                                            class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded font-bold tracking-wider">INCLUDED</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 text-sm font-bold text-gray-800">
                                                    {{ $slot->vendor_name }}
                                                    @if ($slot->vendor_package_id)
                                                        <div class="text-[11px] font-normal text-gray-500 mt-0.5">
                                                            {{ $slot->package_name }}</div>
                                                    @else
                                                        <div class="text-[11px] italic font-normal text-red-500 mt-0.5">No
                                                            Package Selected</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 text-sm text-blue-700">
                                                    {{ $slot->role_detail !== '-' ? $slot->role_detail : '' }}</td>
                                                <td class="px-4 py-4 text-sm text-gray-700">
                                                    {{ $slot->contact_name ?? 'N/A' }}</td>
                                                <td class="px-4 py-4 text-sm text-gray-700">
                                                    {{ $slot->contact_phone ?? 'N/A' }}</td>
                                                <td class="px-4 py-4 text-sm text-center text-gray-700">
                                                    {{ $slot->meal_crew ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if ($verifiedSlots->count() === 0)
                    <div class="px-6 py-8 text-center text-gray-500 border border-gray-200 rounded-lg bg-gray-50">No vendors
                        verified yet.</div>
                @endif
            </div>

            <div x-show="activeTab === 'planning'" x-cloak>
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $event->package ? $event->package->name : 'Custom Package' }}</h3>
                </div>

                <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-5">
                    <h4 class="font-bold text-gray-800 mb-2">Add Custom Slot (Add-on)</h4>
                    <form action="{{ route($user->role . '.events.slots.custom', $event->id) }}" method="POST"
                        class="flex flex-col sm:flex-row gap-4 items-end">
                        @csrf
                        <div class="w-full sm:w-1/4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Session</label>
                            <select name="session" required
                                class="w-full border-gray-300 text-sm rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="morning">Morning Session</option>
                                <option value="evening">Reception</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-1/4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                            <select name="vendor_category_id" required
                                class="w-full border-gray-300 text-sm rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-2/4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Note</label>
                            <input type="text" name="role_detail" required
                                class="w-full border-gray-300 text-sm rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <button type="submit"
                                class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-bold w-full sm:w-auto whitespace-nowrap">Add
                                Slot</button>
                        </div>
                    </form>
                </div>

                @foreach (['morning' => 'Morning Session', 'evening' => 'Reception'] as $sessionKey => $sessionTitle)
                    @php $sessionSlots = $sessionKey == 'morning' ? $morningSlots : $eveningSlots; @endphp

                    @if ($sessionSlots->count() > 0)
                        <div class="mb-8">
                            <h4 class="font-bold text-gray-800 bg-gray-100 px-4 py-2 rounded-t-lg border border-gray-200">
                                {{ $sessionTitle }}</h4>
                            <div class="overflow-x-auto border border-gray-200 rounded-b-lg border-t-0">
                                <table class="min-w-full w-full whitespace-nowrap">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">
                                                Vendor
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Note
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">
                                                Status
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase w-1/3">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($sessionSlots as $slot)
                                            <tr class="hover:bg-blue-50/30">
                                                <td class="px-4 py-4 text-sm font-bold text-gray-800 uppercase">
                                                    {{ $slot->category_name }}
                                                    @if ($slot->is_included)
                                                        <span
                                                            class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded font-bold tracking-wider">INCLUDED</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 text-sm text-blue-700">
                                                    {{ $slot->role_detail !== '-' ? $slot->role_detail : '' }}</td>
                                                <td class="px-4 py-4 text-sm">
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if ($slot->status == 'unassigned') bg-gray-100 text-gray-600
                                        @elseif($slot->status == 'reviewing') bg-yellow-100 text-yellow-800
                                        @elseif($slot->status == 'verified' || $slot->status == 'signed') bg-green-100 text-green-800
                                        @elseif($slot->status == 'rejected') bg-red-100 text-red-800 @endif">
                                                        {{ ucfirst($slot->status == 'signed' ? 'verified' : $slot->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-sm">
                                                    @if ($slot->vendor_id && $slot->vendor_package_id)
                                                        @php
                                                            $selectedPkg = collect(
                                                                $vendorPackages[$slot->vendor_id] ?? [],
                                                            )->firstWhere('id', $slot->vendor_package_id);
                                                            $baseCost =
                                                                $slot->is_included &&
                                                                isset($baseCosts[$slot->vendor_category_id])
                                                                    ? $baseCosts[$slot->vendor_category_id]
                                                                    : 0;
                                                            $currentDealPrice =
                                                                $slot->deal_price > 0
                                                                    ? $slot->deal_price
                                                                    : $selectedPkg->price ?? 0;
                                                            $currentNetPrice =
                                                                $slot->net_price > 0
                                                                    ? $slot->net_price
                                                                    : $selectedPkg->net_price ?? 0;
                                                            $profit = $currentDealPrice - $currentNetPrice;
                                                            $upgradeFee = max(0, $currentDealPrice - $baseCost);
                                                        @endphp
                                                        <div
                                                            class="flex flex-col sm:flex-row sm:items-center justify-between bg-white border border-gray-200 p-3 rounded-xl shadow-sm gap-3">
                                                            <div class="flex flex-col">
                                                                <span
                                                                    class="font-bold text-gray-900">{{ $slot->vendor_name }}</span>
                                                                <span
                                                                    class="text-[11px] text-gray-500 font-medium">{{ $selectedPkg->name ?? 'Package Selected' }}</span>
                                                                <div class="flex items-center gap-2 mt-1.5">
                                                                    <span
                                                                        class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $profit >= 0 ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                                                        Margin: Rp
                                                                        {{ number_format($profit, 0, ',', '.') }}
                                                                    </span>
                                                                    @if ($upgradeFee > 0)
                                                                        <span
                                                                            class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 border border-orange-200">
                                                                            Upgrade: +Rp
                                                                            {{ number_format($upgradeFee, 0, ',', '.') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-2">
                                                                <button
                                                                    @click="$dispatch('open-price-modal', { slot_id: '{{ $slot->id }}', vendor_name: @js($slot->vendor_name), package_name: @js($selectedPkg->name ?? ''), base_cost: {{ $baseCost }}, net_price: {{ round($currentNetPrice) }}, deal_price: {{ round($currentDealPrice) }} })"
                                                                    class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-lg transition-colors shadow-sm"
                                                                    title="Manage Pricing">
                                                                    <svg class="w-3.5 h-3.5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                                        </path>
                                                                    </svg>
                                                                    Pricing
                                                                </button>
                                                                <form
                                                                    action="{{ route($user->role . '.events.slots.remove', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                    method="POST" class="inline">
                                                                    @csrf @method('PUT')
                                                                    <button type="submit"
                                                                        class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-red-700 bg-red-50 border border-red-200 hover:bg-red-100 rounded-lg transition-colors shadow-sm"
                                                                        title="Unassign Vendor">
                                                                        <svg class="w-3.5 h-3.5" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12"></path>
                                                                        </svg>
                                                                        Remove
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @elseif($slot->vendor_id && !$slot->vendor_package_id)
                                                        <div class="flex flex-col gap-2 w-full">
                                                            <div
                                                                class="flex items-center justify-between bg-gray-50 border border-gray-200 p-2 rounded-lg">
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $slot->vendor_name }}</span>
                                                                <form
                                                                    action="{{ route($user->role . '.events.slots.remove', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                    method="POST">
                                                                    @csrf @method('PUT')
                                                                    <button type="submit"
                                                                        class="text-orange-500 hover:text-orange-700 text-[10px] font-bold px-1">Change
                                                                        Vendor</button>
                                                                </form>
                                                            </div>
                                                            <form
                                                                action="{{ route($user->role . '.events.slots.assign', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                method="POST" class="flex gap-2 w-full h-[36px]">
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
                                                                <select name="vendor_package_id" required
                                                                    class="w-full border-gray-300 text-xs rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500 h-full">
                                                                    <option value="" disabled selected>Select Package
                                                                    </option>
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
                                                                            {{ $pkg->name }} {{ $upgradeText }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button type="submit"
                                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-bold px-3 h-full flex items-center justify-center flex-shrink-0 transition-colors">Save</button>
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
                                                        <div class="flex gap-2 items-stretch w-full h-[36px]">
                                                            <form
                                                                action="{{ route($user->role . '.events.slots.assign', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                method="POST" class="flex gap-2 flex-grow h-full">
                                                                @csrf @method('PUT')
                                                                <select name="vendor_id" required
                                                                    class="w-full border-gray-300 text-sm rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500 h-full">
                                                                    <option value="" disabled selected>Select Vendor
                                                                    </option>
                                                                    @foreach ($availableVendors as $v)
                                                                        <option value="{{ $v->id }}">
                                                                            {{ $v->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <button type="submit"
                                                                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-bold w-20 h-full flex items-center justify-center flex-shrink-0 transition-colors">Assign</button>
                                                            </form>
                                                            @if ($slot->status === 'unassigned')
                                                                <button type="button" onclick="confirmDelete(this)"
                                                                    data-form-id="delete-slot-{{ $slot->id }}"
                                                                    class="bg-red-500 hover:bg-red-600 text-white rounded-md text-xs font-bold w-20 h-full flex items-center justify-center transition-colors">Delete</button>
                                                                <form id="delete-slot-{{ $slot->id }}"
                                                                    action="{{ route($user->role . '.events.slots.destroy', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                    method="POST" class="hidden">
                                                                    @csrf @method('DELETE')
                                                                </form>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div x-show="activeTab === 'verification'" x-cloak>
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Verification & Meal Crew Input</h3>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full w-full whitespace-nowrap">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">VENDOR</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">VENDOR'S NAME
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">STATUS</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($slots->whereNotNull('vendor_id') as $slot)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm font-bold text-gray-800 uppercase">
                                        {{ $slot->category_name }}
                                        @if ($slot->is_included)
                                            <span
                                                class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded font-bold tracking-wider">INCLUDED</span>
                                        @endif
                                        @if ($slot->role_detail && $slot->role_detail !== '-')
                                            <div class="text-[11px] font-normal text-blue-600 mt-0.5 capitalize">
                                                {{ $slot->role_detail }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-gray-800">
                                        {{ $slot->vendor_name }}
                                        @if ($slot->vendor_package_id)
                                            <div class="text-[11px] font-normal text-gray-500 mt-0.5">
                                                {{ $slot->package_name }}</div>
                                        @else
                                            <div class="text-[11px] italic font-normal text-red-500 mt-0.5">No Package
                                                Selected</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if ($slot->status == 'reviewing') bg-yellow-100 text-yellow-800
                                    @elseif($slot->status == 'verified' || $slot->status == 'signed') bg-green-100 text-green-800
                                    @elseif($slot->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-600 @endif">
                                            {{ ucfirst($slot->status == 'signed' ? 'verified' : $slot->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <form
                                            action="{{ route($user->role . '.events.slots.status', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                            method="POST" class="flex justify-center items-center gap-2">
                                            @csrf @method('PUT')

                                            <select name="vendor_contact_id"
                                                class="w-36 bg-white border border-gray-300 text-gray-900 text-xs rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">-- Select PIC --</option>
                                                @if (isset($vendorContacts[$slot->vendor_id]))
                                                    @foreach ($vendorContacts[$slot->vendor_id] as $contact)
                                                        <option value="{{ $contact->id }}"
                                                            {{ $slot->vendor_contact_id == $contact->id ? 'selected' : '' }}>
                                                            {{ $contact->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <div class="flex items-center">
                                                <input type="number" name="meal_crew"
                                                    value="{{ $slot->meal_crew ?? 0 }}" min="0"
                                                    class="w-12 text-center text-xs border-gray-300 rounded-l-md p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                                <span
                                                    class="bg-gray-100 text-gray-500 text-xs px-2 py-1.5 border border-l-0 border-gray-300 rounded-r-md">Pax</span>
                                            </div>
                                            <select name="status"
                                                class="w-28 bg-white border border-gray-300 text-gray-900 text-xs rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="reviewing"
                                                    {{ $slot->status === 'reviewing' ? 'selected' : '' }}>Reviewing
                                                </option>
                                                <option value="verified"
                                                    {{ in_array($slot->status, ['verified', 'signed']) ? 'selected' : '' }}>
                                                    Verified</option>
                                                <option value="rejected"
                                                    {{ $slot->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            <button type="submit"
                                                class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No vendors assigned.
                                        Fill the slots in the Vendor Planning tab first.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="activeTab === 'rsvp'" x-cloak>
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-bold text-gray-900">Guestbook & RSVP</h3>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <form action="{{ route($user->role . '.events.guests.blast', $event->id) }}" method="POST"
                            class="flex-1 sm:flex-none m-0"
                            onsubmit="return confirm('Are you sure you want to blast WhatsApp reminders to all attending guests? This process will run in the background.')">
                            @csrf
                            <button type="submit"
                                class="w-full bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-4 py-2 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Blast WA Reminders
                            </button>
                        </form>
                        <button @click="isGuestModalOpen = true"
                            class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add Guest
                        </button>
                    </div>
                </div>

                @php
                    $totalGuests = $guests->count();
                    $totalPax = $guests->sum('pax_invited');
                    $attendingGuests = $guests->where('status', 'attending')->count();
                    $notAttendingGuests = $guests->where('status', 'not_attending')->count();
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider mb-1">Total Invitations</p>
                        <p class="text-3xl font-black text-gray-900">{{ $totalGuests }}</p>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100 shadow-sm">
                        <p class="text-[11px] text-blue-600 font-bold uppercase tracking-wider mb-1">Total Pax</p>
                        <p class="text-3xl font-black text-blue-900">{{ $totalPax }}</p>
                    </div>
                    <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
                        <p class="text-[11px] text-emerald-600 font-bold uppercase tracking-wider mb-1">Attending</p>
                        <p class="text-3xl font-black text-emerald-900">{{ $attendingGuests }}</p>
                    </div>
                    <div class="bg-red-50 p-5 rounded-2xl border border-red-100 shadow-sm">
                        <p class="text-[11px] text-red-600 font-bold uppercase tracking-wider mb-1">Not Attending</p>
                        <p class="text-3xl font-black text-red-900">{{ $notAttendingGuests }}</p>
                    </div>
                </div>

                <div class="relative w-full mb-4">
                    <input type="text" x-model="guestSearch" placeholder="Search guest name or phone..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="min-w-full w-full whitespace-nowrap">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th
                                        class="px-5 py-4 text-left text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Guest Name</th>
                                    <th
                                        class="px-5 py-4 text-left text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Contact</th>
                                    <th
                                        class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Pax</th>
                                    <th
                                        class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Table</th>
                                    <th
                                        class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($guests as $guest)
                                    <tr class="hover:bg-gray-50/50 transition-colors"
                                        x-show="guestSearch === '' || '{{ strtolower(addslashes($guest->name . ' ' . $guest->phone_number)) }}'.includes(guestSearch.toLowerCase())">
                                        <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $guest->name }}</td>
                                        <td class="px-5 py-4 text-sm text-gray-500">{{ $guest->phone_number ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm text-center font-extrabold text-blue-600">
                                            {{ $guest->pax_invited }}</td>
                                        <td class="px-5 py-4 text-sm text-center text-gray-600">
                                            {{ $guest->table_name ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm text-center">
                                            <span
                                                class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border
                                                @if ($guest->status === 'attending') bg-emerald-50 text-emerald-700 border-emerald-200
                                                @elseif($guest->status === 'not_attending') bg-red-50 text-red-700 border-red-200
                                                @elseif($guest->status === 'checked_in') bg-blue-50 text-blue-700 border-blue-200 @endif">
                                                {{ str_replace('_', ' ', $guest->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-center">
                                            <div class="flex justify-center gap-2">
                                                <button
                                                    @click="openEditGuestModal({{ $guest->id }}, '{{ addslashes($guest->name) }}', '{{ addslashes($guest->phone_number) }}', {{ $guest->pax_invited }}, '{{ addslashes($guest->table_name) }}', '{{ $guest->status }}')"
                                                    class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Edit</button>
                                                <form
                                                    action="{{ route($user->role . '.events.guests.destroy', ['event' => $event->id, 'guest' => $guest->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        data-form-id="delete-guest-{{ $guest->id }}"
                                                        onclick="confirmDelete(this)"
                                                        class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Del</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm font-medium text-gray-500">Your guestbook is empty.</p>
                                                <p class="text-xs mt-1">Start adding guests to manage your invitations.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div x-data="{
            isOpen: false,
            form: { slot_id: '', vendor_name: '', package_name: '', base_cost: 0, net_price: 0, deal_price: 0 }
        }" @open-price-modal.window="form = $event.detail; isOpen = true;" x-show="isOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto"
            style="display: none;" x-cloak>
            <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl my-8" @click.away="isOpen = false">
                <div class="flex justify-between items-center p-5 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" x-text="form.vendor_name"></h3>
                        <p class="text-xs font-medium text-gray-500 mt-0.5" x-text="form.package_name"></p>
                    </div>
                    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form
                    :action="'{{ url('/' . $user->role . '/events/' . $event->id . '/slots') }}/' + form.slot_id + '/price'"
                    method="POST">
                    @csrf @method('PUT')
                    <div class="p-6 space-y-5">

                        <div class="flex items-center justify-between p-4 rounded-lg border border-blue-100"
                            :class="(form.deal_price - form.net_price) >= 0 ? 'bg-blue-50' : 'bg-red-50 border-red-100'">
                            <span class="text-xs font-bold uppercase tracking-wider"
                                :class="(form.deal_price - form.net_price) >= 0 ? 'text-blue-800' : 'text-red-800'">Margin /
                                Profit</span>
                            <span class="text-lg font-black"
                                :class="(form.deal_price - form.net_price) >= 0 ? 'text-green-600' : 'text-red-600'"
                                x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(form.deal_price - form.net_price)"></span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Net
                                    Price (Modal Vendor)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-bold">Rp</span>
                                    <input type="number" name="net_price" x-model.number="form.net_price" required
                                        min="0"
                                        class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold bg-gray-50">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Deal
                                    Price (Harga Jual)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-bold">Rp</span>
                                    <input type="number" name="deal_price" x-model.number="form.deal_price" required
                                        min="0"
                                        class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold bg-gray-50">
                                </div>
                            </div>
                        </div>

                        <template x-if="form.base_cost > 0 && form.deal_price > form.base_cost">
                            <div
                                class="mt-4 text-[11px] text-orange-700 font-semibold bg-orange-50 p-3 rounded-lg border border-orange-100 flex gap-2 items-start">
                                <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Client upgraded vendor. Additional fee to client: <strong>+Rp <span
                                            x-text="new Intl.NumberFormat('id-ID').format(form.deal_price - form.base_cost)"></span></strong></span>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                        <button type="button" @click="isOpen = false"
                            class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save
                            Pricing</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="isGuestModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto"
            style="display: none;" x-cloak>
            <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl my-8"
                @click.away="isGuestModalOpen = false">
                <div class="flex justify-between items-center p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Add New Guest</h3>
                    <button @click="isGuestModalOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route($user->role . '.events.guests.store', $event->id) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp
                                Number</label>
                            <input type="text" name="phone_number" placeholder="08123456789"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax
                                    Invited <span class="text-red-500">*</span></label>
                                <input type="number" name="pax_invited" value="1" min="1" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            </div>
                            <div class="w-1/2">
                                <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table
                                    Name</label>
                                <input type="text" name="table_name" placeholder="Optional"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                            <select name="status"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                <option value="attending">Attending</option>
                                <option value="not_attending">Not Attending</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                        <button type="button" @click="isGuestModalOpen = false"
                            class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save
                            Guest</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="isEditGuestModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto"
            style="display: none;" x-cloak>
            <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl my-8"
                @click.away="isEditGuestModalOpen = false">
                <div class="flex justify-between items-center p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Edit Guest</h3>
                    <button @click="isEditGuestModalOpen = false"
                        class="text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form :action="'{{ url('/' . $user->role . '/events/' . $event->id . '/guests') }}/' + guestForm.id"
                    method="POST">
                    @csrf @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="guestForm.name" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp
                                Number</label>
                            <input type="text" name="phone_number" x-model="guestForm.phone_number"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax
                                    Invited <span class="text-red-500">*</span></label>
                                <input type="number" name="pax_invited" x-model="guestForm.pax_invited" min="1"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            </div>
                            <div class="w-1/2">
                                <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table
                                    Name</label>
                                <input type="text" name="table_name" x-model="guestForm.table_name"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                            <select name="status" x-model="guestForm.status"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                <option value="attending">Attending</option>
                                <option value="not_attending">Not Attending</option>
                                <option value="checked_in">Checked In</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                        <button type="button" @click="isEditGuestModalOpen = false"
                            class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Update
                            Guest</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let scrollpos = localStorage.getItem('manageEventScroll');
                if (scrollpos) {
                    window.scrollTo(0, parseInt(scrollpos));
                    localStorage.removeItem('manageEventScroll');
                }
            });

            window.addEventListener("beforeunload", function() {
                localStorage.setItem('manageEventScroll', window.scrollY);
            });

            function confirmDelete(button) {
                const formId = button.getAttribute('data-form-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (document.getElementById(formId)) {
                            document.getElementById(formId).submit();
                        } else if (button.closest('form')) {
                            button.closest('form').submit();
                        }
                    }
                });
            }
        </script>
    @endsection
