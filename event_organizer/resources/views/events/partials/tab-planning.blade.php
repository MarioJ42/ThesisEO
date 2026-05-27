<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div x-show="activeTab === 'planning'" x-cloak>
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-900">
            {{ $event->package ? $event->package->name : 'Custom Package' }}</h3>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
            <h4 class="font-bold text-gray-800 mb-2">Add Custom Slot (Add-on)</h4>
            <form action="{{ route($user->role . '.events.slots.custom', $event->id) }}" method="POST"
                class="flex flex-col sm:flex-row gap-4 items-end">
                @csrf
                <div class="w-full sm:w-1/4">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Session</label>
                    <select name="session" required
                        class="w-full border-gray-300 text-sm rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="morning">Morning Session</option>
                        <option value="evening">Reception</option>
                    </select>
                </div>
                <div class="w-full sm:w-1/4">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                    <select name="vendor_category_id" required
                        class="w-full border-gray-300 text-sm rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="" disabled selected>Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-2/4">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Note</label>
                    <input type="text" name="role_detail" placeholder="Optional"
                        class="w-full border-gray-300 text-sm rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                </div>
                <div>
                    <button type="submit"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-bold w-full sm:w-auto whitespace-nowrap">Add
                        Slot</button>
                </div>
            </form>
        </div>

        <div class="bg-blue-50/50 border border-blue-200 rounded-lg p-5" x-data="{ checkVendorId: '' }">
            <h4 class="font-bold text-blue-900 mb-2">Cek Ketersediaan Jadwal Vendor</h4>
            <div class="flex flex-col sm:flex-row gap-4 items-end h-[calc(105px-1.5rem)]">
                <div class="flex-grow w-full">
                    <label class="block text-xs font-semibold text-blue-800 mb-1">Pilih Vendor Partner</label>
                    <select x-model="checkVendorId" class="w-full border-gray-300 text-sm rounded-md p-2 bg-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Pilih salah satu vendor...</option>
                        @foreach ($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach ($category->vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button"
                        @click="$dispatch('open-vendor-calendar', { id: checkVendorId })"
                        :disabled="!checkVendorId"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-bold w-full sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition-all h-[38px] flex items-center justify-center whitespace-nowrap">
                        Lihat Kalender
                    </button>
                </div>
            </div>
        </div>
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
                                        @if (($slot->vendor_id && $slot->vendor_package_id) || ($slot->vendor_id && $slot->vendor_category_id == 1))
                                            <div class="flex flex-col gap-3">
                                                <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 flex justify-between items-start">
                                                    <div>
                                                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">Assigned Vendor</p>
                                                        <h5 class="text-sm font-bold text-gray-900 mb-1">
                                                            {{ $slot->vendor_name }}
                                                        </h5>

                                                        @if($slot->vendor_category_id == 1)
                                                            <p class="text-xs text-gray-500 mb-2 italic">Venue / Hotel Reservation</p>
                                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-gray-200 shadow-sm">
                                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                                <span class="text-[10px] font-bold text-gray-700 uppercase">Direct Payment to Hotel</span>
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-gray-500 mb-2">{{ $slot->package_name }}</p>

                                                            @php
                                                                $baseCost = $slot->is_included && isset($baseCosts[$slot->vendor_category_id]) ? $baseCosts[$slot->vendor_category_id] : 0;
                                                                $upgradeFee = max(0, $slot->deal_price - $baseCost);
                                                            @endphp

                                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-white border {{ $upgradeFee > 0 ? 'border-red-100' : 'border-green-100' }}">
                                                                <span class="text-[10px] font-bold {{ $upgradeFee > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                                    @if ($upgradeFee > 0)
                                                                        Upgrade +Rp {{ number_format($upgradeFee, 0, ',', '.') }}
                                                                    @else
                                                                        Standard (Free)
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="text-right">
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold
                                                            @if ($slot->status === 'unassigned') bg-gray-100 text-gray-700
                                                            @elseif($slot->status === 'reviewing') bg-yellow-100 text-yellow-700
                                                            @elseif($slot->status === 'verified') bg-blue-100 text-blue-700
                                                            @elseif($slot->status === 'rejected') bg-red-100 text-red-700
                                                            @elseif($slot->status === 'signed') bg-green-100 text-green-700
                                                            @endif capitalize">
                                                            {{ $slot->status }}
                                                        </span>

                                                        @if($slot->vendor_category_id != 1)
                                                        <div class="mt-2 text-xs font-bold text-gray-900">
                                                            Deal: Rp {{ number_format($slot->deal_price, 0, ',', '.') }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-between bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                                                    <div class="flex flex-wrap gap-2">
                                                        <button @click="openStatusModal({{ $slot->id }}, '{{ $slot->status }}', '{{ $slot->vendor_contact_id }}', {{ $slot->meal_crew ?? 0 }})"
                                                            class="text-[11px] font-bold text-gray-600 hover:text-blue-600 flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                            Update Status
                                                        </button>

                                                        @if($slot->vendor_category_id != 1)
                                                        <div class="w-px h-3 bg-gray-300 my-auto hidden sm:block"></div>
                                                        <button @click="openPriceModal({{ $slot->id }}, '{{ addslashes($slot->vendor_name) }}', '{{ addslashes($slot->package_name) }}', {{ $baseCost ?? 0 }}, {{ $slot->net_price ?? 0 }}, {{ $slot->deal_price ?? 0 }})"
                                                            class="text-[11px] font-bold text-gray-600 hover:text-green-600 flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            Negotiate Price
                                                        </button>
                                                        @endif
                                                    </div>

                                                    <form id="remove-vendor-form-{{ $slot->id }}" action="{{ route($user->role . '.events.slots.remove', ['event' => $event->id, 'slot' => $slot->id]) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <button type="button" class="text-[11px] font-bold text-red-500 hover:text-red-700 flex items-center gap-1" onclick="confirmRemoveVendor({{ $slot->id }})">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                                                        class="w-full border-gray-300 text-xs rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500 h-full bg-white">
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
                                                        class="w-full border-gray-300 text-sm rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500 h-full bg-white">
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

<div x-data="{
        isOpen: false,
        vendorId: null,
        calendar: null,
        initCalendar() {
            const calendarEl = document.getElementById('modalCalendar');
            if (!calendarEl) return;

            if (this.calendar) {
                this.calendar.destroy();
            }

            this.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
                height: 'auto',
                locale: 'id',
                events: `/vendor/${this.vendorId}/calendar-events`
            });

            setTimeout(() => {
                this.calendar.render();
            }, 150);
        }
     }"
     @open-vendor-calendar.window="isOpen = true; vendorId = $event.detail.id; $nextTick(() => initCalendar())"
     x-show="isOpen"
     style="display: none;"
     class="fixed inset-0 z-[150] flex items-center justify-center overflow-y-auto bg-gray-900/50 backdrop-blur-sm"
     x-cloak>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 p-6 z-10 animate-fade-in" @click.outside="isOpen = false">
        <button @click="isOpen = false" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <h3 class="text-xl font-bold text-gray-900 mb-4">Kalender Vendor</h3>

        <div class="border-t border-gray-100 pt-4">
            <div id="modalCalendar"></div>
        </div>
    </div>
    <script>
    function confirmRemoveVendor(slotId) {
        Swal.fire({
            title: 'Remove Vendor?',
            text: "Are you sure you want to remove this vendor from the slot? You will need to select a vendor again.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Remove!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl px-6 py-2 font-bold',
                cancelButton: 'rounded-xl px-6 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('remove-vendor-form-' + slotId).submit();
            }
        });
    }
</script>
</div>
