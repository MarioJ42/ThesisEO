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
            </nav>
        </div>

        <div class="bg-white rounded-b-lg shadow-sm border border-gray-200 border-t-0 p-6">

            <div x-show="activeTab === 'overview'" x-cloak>
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
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Vendor
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Note
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Status
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase w-1/3">
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
                                                        <div class="flex items-center justify-between bg-white border border-green-200 p-2 rounded-lg"
                                                            x-data="{ editPrice: false }">
                                                            <div class="flex flex-col w-full pr-4">
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $slot->vendor_name }}</span>
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
                                                                    $upgradeFee = max(0, $currentDealPrice - $baseCost);
                                                                @endphp
                                                                <span
                                                                    class="text-[11px] text-gray-600">{{ $selectedPkg->name ?? 'Package Selected' }}</span>

                                                                <div x-show="!editPrice"
                                                                    class="flex items-center gap-2 mt-0.5">
                                                                    @if ($upgradeFee > 0)
                                                                        <span
                                                                            class="text-[10px] font-bold text-red-500">Upgrade
                                                                            +Rp
                                                                            {{ number_format($upgradeFee, 0, ',', '.') }}</span>
                                                                    @else
                                                                        <span
                                                                            class="text-[10px] font-bold text-green-600">Standard
                                                                            (No Add. Fee)</span>
                                                                    @endif
                                                                    <span
                                                                        class="text-[10px] text-gray-500 border-l border-gray-300 pl-2">Deal:
                                                                        Rp
                                                                        {{ number_format($currentDealPrice, 0, ',', '.') }}</span>
                                                                    <button @click="editPrice = true"
                                                                        class="text-blue-500 hover:text-blue-700 ml-1"
                                                                        title="Edit Deal Price">
                                                                        <svg class="w-3 h-3 inline" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                                            </path>
                                                                        </svg>
                                                                    </button>
                                                                </div>

                                                                <form x-show="editPrice"
                                                                    action="{{ route($user->role . '.events.slots.price', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                    method="POST" class="flex items-center gap-2 mt-1"
                                                                    x-cloak>
                                                                    @csrf @method('PUT')
                                                                    <div class="relative w-full max-w-[150px]">
                                                                        <span
                                                                            class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-500 font-bold">Rp</span>
                                                                        <input type="number" name="deal_price"
                                                                            value="{{ round($currentDealPrice) }}"
                                                                            required min="0"
                                                                            class="w-full pl-6 pr-2 py-1 border border-gray-300 rounded text-[10px] focus:ring-blue-500 focus:border-blue-500 font-semibold">
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="bg-blue-600 hover:bg-blue-700 text-white rounded text-[10px] font-bold px-2 py-1 transition-colors">Save</button>
                                                                    <button type="button" @click="editPrice = false"
                                                                        class="text-gray-500 hover:text-gray-700 text-[10px] px-1 font-semibold">Cancel</button>
                                                                </form>
                                                            </div>
                                                            <div class="flex items-center divide-x divide-gray-300">
                                                                <form
                                                                    action="{{ route($user->role . '.events.slots.remove', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                                                    method="POST">
                                                                    @csrf @method('PUT')
                                                                    <button type="submit"
                                                                        class="text-orange-500 hover:text-orange-700 text-xs font-bold px-2">Unassign</button>
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

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(button) {
            const formId = button.getAttribute('data-form-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this slot?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endsection
