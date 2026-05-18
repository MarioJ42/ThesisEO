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
                <input type="text" name="role_detail" placeholder="Optional"
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
                                            @php
                                                $selectedPkg = collect(
                                                    $vendorPackages[$slot->vendor_id] ?? [],
                                                )->firstWhere('id', $slot->vendor_package_id);
                                                $baseCost =
                                                    $slot->is_included && isset($baseCosts[$slot->vendor_category_id])
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
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
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
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                                                        )->where('vendor_category_id', $slot->vendor_category_id);
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
                                                                $upgradeFee = max(0, $pkg->price - $baseCost);
                                                                $upgradeText =
                                                                    $upgradeFee > 0
                                                                        ? '(+Rp ' .
                                                                            number_format($upgradeFee, 0, ',', '.') .
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
                                                $cat = $categories->where('id', $slot->vendor_category_id)->first();
                                                $availableVendors = $cat ? $cat->vendors : collect();
                                                if (
                                                    $slot->is_included &&
                                                    isset($allowedVendors[$slot->vendor_category_id]) &&
                                                    $allowedVendors[$slot->vendor_category_id]->isNotEmpty()
                                                ) {
                                                    $allowedIds = $allowedVendors[$slot->vendor_category_id]
                                                        ->pluck('vendor_id')
                                                        ->toArray();
                                                    $availableVendors = $availableVendors->whereIn('id', $allowedIds);
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
