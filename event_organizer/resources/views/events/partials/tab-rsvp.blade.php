<div x-show="activeTab === 'rsvp'" x-cloak>
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h3 class="text-lg font-bold text-gray-900">Guestbook & RSVP</h3>
        <div class="flex gap-2 w-full sm:w-auto">
            <form id="blastWaForm" action="{{ route($user->role . '.events.guests.blast', $event->id) }}" method="POST"
                class="flex-1 sm:flex-none m-0">
                @csrf
                <button type="button" onclick="confirmBlastWA()"
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
        <input type="text" x-model="guestSearch" placeholder="Search guest name or phone"
            class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
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
                                                @elseif($guest->status === 'checked_in') bg-blue-50 text-blue-700 border-blue-200
                                                @elseif($guest->status === 'pending') bg-gray-50 text-gray-700 border-gray-200 @endif">
                                    {{ str_replace('_', ' ', $guest->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('invitation.show', $guest->barcode_token) }}" target="_blank"
                                        class="text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">View</a>

                                    <button
                                        @click="openEditGuestModal({{ $guest->id }}, '{{ addslashes($guest->name) }}', '{{ addslashes($guest->phone_number) }}', {{ $guest->pax_invited }}, '{{ addslashes($guest->table_name) }}', '{{ $guest->status }}')"
                                        class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Edit</button>

                                    <form
                                        action="{{ route($user->role . '.events.guests.destroy', ['event' => $event->id, 'guest' => $guest->id]) }}"
                                        method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" data-form-id="delete-guest-{{ $guest->id }}"
                                            onclick="confirmDelete(this)"
                                            class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
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
