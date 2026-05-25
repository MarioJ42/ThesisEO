@extends('layouts.guest_rsvp')

@section('content')
<div class="relative max-w-xl mx-auto p-8 min-h-screen flex flex-col justify-center" x-data="createGuestForm({{ $event->id }})">

    <a href="{{ route('crew.rsvp.hub', $event->id) }}" class="absolute top-8 right-8 text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors tracking-wider">
        &larr; Back to Hub
    </a>

    <div class="text-center mb-10">
        <h2 class="text-3xl font-serif text-gray-900 mb-2">Registration</h2>
        <h3 class="text-5xl font-serif text-gray-900 tracking-tight">Add New Guest</h3>
    </div>

    <form action="{{ route('crew.rsvp.guests.store', $event->id) }}" method="POST" class="space-y-6" @submit="submitForm($event)">
        @csrf
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-50">

            <div class="space-y-5">
                <div>
                    <label class="block mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Guest Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Full Name"
                           class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-lg font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none">
                </div>

                <div>
                    <label class="block mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">WhatsApp Number</label>
                    <input type="text" name="phone_number" placeholder="08123456789"
                           class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-lg font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pax Invited <span class="text-red-500">*</span></label>
                        <input type="number" name="pax_invited" value="1" min="1" required
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-lg font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none">
                    </div>
                    <div>
                        <label class="block mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Table Name</label>
                        <input type="text" name="table_name" placeholder="Optional"
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-lg font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Guest Side</label>
                    <select name="side" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-lg font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none appearance-none">
                        <option value="General">General</option>
                        <option value="Groom">Groom Side</option>
                        <option value="Bride">Bride Side</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50">
                <p class="text-xs text-center text-gray-400 font-medium italic">
                    Note: After saving, you will be redirected to the check-in form to process gifts.
                </p>
            </div>
        </div>

        <button type="submit" class="w-full bg-gray-900 text-white py-5 rounded-2xl font-bold text-lg shadow-xl hover:bg-black hover:-translate-y-0.5 transition-all">
            Save & Continue Check-in
        </button>
    </form>
</div>

<script>
    function createGuestForm(eventId) {
        return {
            submitForm(event) {
                event.preventDefault();
                if (navigator.onLine) {
                    event.target.submit();
                } else {
                    const formData = new FormData(event.target);
                    const newGuest = {
                        name: formData.get('name'),
                        phone_number: formData.get('phone_number'),
                        pax_invited: formData.get('pax_invited'),
                        table_name: formData.get('table_name'),
                        side: formData.get('side'),
                        timestamp: new Date().toISOString()
                    };

                    const storageKey = 'offline_new_guests_' + eventId;
                    let offlineData = JSON.parse(localStorage.getItem(storageKey)) || [];
                    offlineData.push(newGuest);
                    localStorage.setItem(storageKey, JSON.stringify(offlineData));
                    window.location.href = `/crew/events/${eventId}/rsvp`;
                }
            }
        }
    }
</script>
@endsection
