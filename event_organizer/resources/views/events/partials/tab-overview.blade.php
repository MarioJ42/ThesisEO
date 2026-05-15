<div x-show="activeTab === 'overview'" x-cloak>
    @php
        $basePackagePrice = $event->package ? $event->package->base_price : 0;
        $eoFee = $event->package ? $event->package->eo_fee : 0;

        $additionalSellingPrice = 0;
        $totalNetPrice = 0;

        foreach ($slots as $s) {

            if ($s->vendor_package_id || $s->vendor_id) {
                $pkg = collect($vendorPackages[$s->vendor_id] ?? [])->firstWhere('id', $s->vendor_package_id);

                $dPrice = $s->deal_price > 0 ? $s->deal_price : ($pkg->price ?? 0);
                $nPrice = (isset($s->net_price) && $s->net_price > 0) ? $s->net_price : ($pkg->net_price ?? 0);

                $totalNetPrice += $nPrice;

                if ($event->package && $s->is_included) {
                    $baseAllowance = $baseCosts[$s->vendor_category_id] ?? 0;
                    if ($dPrice > $baseAllowance) {
                        $upgradeFee = $dPrice - $baseAllowance;
                        $additionalSellingPrice += $upgradeFee;
                    }
                } else {
                    $additionalSellingPrice += $dPrice;
                }
            }
        }

        $sellingPrice = $basePackagePrice + $additionalSellingPrice;
        $grandProfit = $sellingPrice - $totalNetPrice;
        $vendorMargin = $grandProfit - $eoFee;
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
                <span class="font-bold ml-1">{{ $event->package ? $event->package->name : 'Custom Package' }}</span>
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
                    {{ number_format($sellingPrice, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-50">
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Total Vendor's Prices</p>
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
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Vendor</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Vendor's Name</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Note</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">PIC</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Phone</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Meal Crew</th>
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
