<div x-show="activeTab === 'verification'" x-cloak>
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-900">Verification & Meal Crew Input</h3>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full w-full whitespace-nowrap">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">VENDOR</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">VENDOR'S NAME</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">DEAL PRICE</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">STATUS</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">ACTION</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($slots->whereNotNull('vendor_id') as $slot)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 text-sm font-bold text-gray-800 uppercase">
                            {{ $slot->category_name }}
                            @if ($slot->is_included)
                                <span
                                    class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded font-bold tracking-wider">INCLUDED</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-800">
                            <div class="font-bold text-gray-900">{{ $slot->vendor_name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $slot->package_name ?? ($slot->vendor_category_id == 1 ? 'Venue / Hotel Reservation' : 'Custom Service') }}
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm font-semibold text-gray-900">
                            @if ($slot->is_included)
                                <span
                                    class="inline-flex items-center gap-1 text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md text-xs font-bold border border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Included
                                </span>
                            @else
                                Rp {{ number_format($slot->deal_price, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <span
                                class="px-2.5 py-1 text-xs font-bold rounded-full
                                @if ($slot->status == 'reviewing') bg-yellow-100 text-yellow-800 border border-yellow-200
                                @elseif($slot->status == 'verified' || $slot->status == 'signed') bg-emerald-100 text-emerald-800 border border-emerald-200
                                @elseif($slot->status == 'rejected') bg-red-100 text-red-800 border border-red-200 @endif">
                                {{ ucfirst($slot->status == 'signed' ? 'verified' : $slot->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <form
                                action="{{ route($user->role . '.events.slots.status', ['event' => $event->id, 'slot' => $slot->id]) }}"
                                method="POST" class="flex justify-center items-center gap-2 m-0">
                                @csrf @method('PUT')

                                <select name="vendor_contact_id"
                                    class="w-36 bg-white border border-gray-300 text-gray-900 text-xs rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-colors">
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

                                <div class="flex items-center shadow-sm rounded-md">
                                    <input type="number" name="meal_crew" value="{{ $slot->meal_crew ?? 0 }}"
                                        min="0"
                                        class="w-12 text-center text-xs border border-gray-300 rounded-l-md p-1.5 focus:ring-blue-500 focus:border-blue-500 z-10"
                                        style="border-right: none;">
                                    <span
                                        class="bg-gray-100 text-gray-500 text-xs px-2.5 py-1.5 border border-gray-300 rounded-r-md font-semibold">Pax</span>
                                </div>

                                <select name="status"
                                    class="w-28 bg-white border border-gray-300 text-gray-900 text-xs rounded-md p-1.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-colors">
                                    <option value="reviewing" {{ $slot->status === 'reviewing' ? 'selected' : '' }}>
                                        Reviewing</option>
                                    <option value="verified"
                                        {{ in_array($slot->status, ['verified', 'signed']) ? 'selected' : '' }}>
                                        Verified</option>
                                    <option value="rejected" {{ $slot->status === 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>

                                <button type="submit"
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors shadow-sm">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 bg-gray-50/50">No vendors assigned
                            yet. Please assign vendors in the Vendor Planning tab first.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
