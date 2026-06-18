@extends('layouts.guest_rsvp')

@section('content')
<div class="min-h-screen bg-[#f4f4f6] flex flex-col items-center pt-20 px-6 pb-20" x-data="summaryLogic(@js($allGuests), {{ $event->id }})">

    <div class="w-full max-w-3xl bg-transparent" x-show="activeGuest" x-cloak>

        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif text-gray-800 mb-4">Welcome</h2>
            <h1 class="text-4xl md:text-6xl font-serif text-black tracking-tight mb-8" x-text="'Mr. / Mrs. / Ms. ' + activeGuest.name"></h1>

            <div class="flex flex-col items-center gap-3">
                <span class="bg-[#d1e7dd] text-[#0f5132] font-bold px-4 py-1.5 rounded-full text-xs tracking-wider">
                    CHECKED IN
                </span>
                <span class="text-gray-700 font-medium text-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="formatTime(activeGuest.check_in_time)"></span>
                </span>
            </div>
        </div>

        <div class="w-full max-w-2xl mx-auto space-y-12">

            <div>
                <div class="flex items-center gap-2 mb-6 border-b border-gray-300 pb-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <h3 class="text-gray-800 font-medium text-sm">Guest Information</h3>
                </div>

                <div class="grid grid-cols-2 gap-y-6">
                    <div>
                        <p class="text-[13px] text-gray-600 mb-1">Name</p>
                        <p class="text-[15px] text-gray-900" x-text="activeGuest.name"></p>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-600 mb-1">Phone</p>
                        <p class="text-[15px] text-gray-900" x-text="activeGuest.phone_number || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-600 mb-1">Side</p>
                        <p class="text-[15px] text-gray-900 capitalize" x-text="activeGuest.side || 'Bride'"></p>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-600 mb-1">Table</p>
                        <p class="text-[15px] text-gray-900 uppercase" x-text="activeGuest.table_name || 'GENERAL'"></p>
                    </div>
                    <div>
                        <p class="text-[13px] text-gray-600 mb-1">Attendees</p>
                        <p class="text-[15px] text-gray-900"><span x-text="activeGuest.pax_actual || activeGuest.pax_invited"></span> pax</p>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-6 border-b border-gray-300 pb-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    <h3 class="text-gray-800 font-medium text-sm">Gift Summary</h3>
                </div>

                <div class="flex flex-col items-center justify-center pt-4">
                    <span class="text-2xl font-bold text-gray-900 mb-1" x-text="activeGuest.angpao_count || 0"></span>
                    <span class="text-xs text-gray-500">Total Gift(s) Brought</span>

                    <a href="{{ route('crew.rsvp.hub', $event->id) }}" class="mt-8 bg-black text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800 transition-colors shadow-sm">
                        Back to Homepage
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function summaryLogic(allGuestsList, eventId) {
        return {
            allGuests: allGuestsList,
            activeGuest: null,

            init() {
                const urlParams = new URLSearchParams(window.location.search);
                let guestId = urlParams.get('guest_id') || '{{ session('guest_id') }}';

                let found = null;
                let foundOfflineCheckinData = null;

                if (guestId) {
                    found = this.allGuests.find(g => g.id == guestId);
                }

                if (!found) {
                    const offlineGuests = JSON.parse(localStorage.getItem('offline_new_guests_' + eventId)) || [];
                    if (guestId) found = offlineGuests.find(g => String(g.id) === String(guestId));
                }

                if (found) {
                    const offlineCheckins = JSON.parse(localStorage.getItem('offline_checkin_' + eventId)) || [];
                    foundOfflineCheckinData = offlineCheckins.find(c => String(c.guest_id) === String(found.id));

                    if (foundOfflineCheckinData) {
                        found.status = 'checked_in';
                        found.check_in_time = foundOfflineCheckinData.timestamp;
                        found.pax_actual = foundOfflineCheckinData.pax_actual;
                        found.angpao_count = foundOfflineCheckinData.angpao_count;
                        if (foundOfflineCheckinData.titipan_data && foundOfflineCheckinData.titipan_data.length > 0) {
                             let totalTitipanQty = 0;
                             foundOfflineCheckinData.titipan_data.forEach(t => {
                                 totalTitipanQty += t.qty;
                             });
                             found.angpao_count = parseInt(found.angpao_count) + parseInt(totalTitipanQty);
                        }
                    }
                }

                if (found) {
                    this.activeGuest = found;
                } else {
                    alert('Guest data not found!');
                    window.location.href = `/crew/events/${eventId}/rsvp`;
                }
            },

            formatTime(isoString) {
                if (!isoString) return 'Just now';
                try {
                    const date = new Date(isoString);
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');
                    return `${hours}:${minutes}:${seconds}`;
                } catch(e) {
                    return 'Just now';
                }
            }
        }
    }
</script>
@endsection
