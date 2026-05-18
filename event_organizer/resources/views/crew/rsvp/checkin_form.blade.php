@extends('layouts.guest_rsvp')

@section('content')
<div class="relative max-w-xl mx-auto p-8 min-h-screen flex flex-col justify-center"
     x-data="checkinForm(@js($guest->pax_invited), @js($allGuests))">

    <a href="{{ route('crew.rsvp.search', $event->id) }}" class="absolute top-8 right-8 text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors tracking-wider">
        &larr; Back
    </a>

    <div class="text-center mb-10">
        <h2 class="text-3xl font-serif text-gray-900 mb-2">Welcome</h2>
        <h3 class="text-5xl font-serif text-gray-900 tracking-tight">Mr. / Mrs. / Ms.</h3>
        <h4 class="text-3xl font-bold text-blue-600 mt-3">{{ $guest->name }}</h4>
    </div>

    <form action="{{ route('crew.rsvp.checkin.process', [$event->id, $guest->id]) }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-50">

            <div class="flex justify-between items-center mb-8">
                <div class="flex flex-col">
                    <span class="font-bold text-gray-900 text-lg">Person(s)</span>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Invited: {{ $guest->pax_invited }} Pax</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="button" @click="if(pax > 1) pax--" class="w-10 h-10 rounded-xl border border-gray-200 shadow-sm flex items-center justify-center font-bold text-gray-500 hover:bg-gray-50 transition-colors">-</button>
                    <input type="hidden" name="pax_actual" x-model="pax">
                    <span class="text-2xl font-bold w-6 text-center text-gray-900" x-text="pax"></span>
                    <button type="button" @click="pax++" class="w-10 h-10 rounded-xl border border-gray-200 shadow-sm flex items-center justify-center font-bold text-gray-500 hover:bg-gray-50 transition-colors">+</button>
                </div>
            </div>

            <div class="flex justify-between items-center mb-8">
                <span class="font-bold text-gray-900 text-lg">Gift(s) from me:</span>
                <div class="flex items-center gap-4">
                    <button type="button" @click="if(gift > 0) gift--" class="w-10 h-10 rounded-xl border border-gray-200 shadow-sm flex items-center justify-center font-bold text-gray-500 hover:bg-gray-50 transition-colors">-</button>
                    <input type="hidden" name="angpao_count" x-model="gift">
                    <span class="text-2xl font-bold w-6 text-center text-gray-900" x-text="gift"></span>
                    <button type="button" @click="gift++" class="w-10 h-10 rounded-xl border border-gray-200 shadow-sm flex items-center justify-center font-bold text-gray-500 hover:bg-gray-50 transition-colors">+</button>
                </div>
            </div>

            <div class="mb-8">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">Type:</p>
                <div class="flex gap-4">
                    <input type="hidden" name="angpao_type" x-model="type">
                    <button type="button" @click="type = 'fisik'" :class="type === 'fisik' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'" class="flex-1 py-3.5 rounded-xl font-bold text-sm transition-all">Physical</button>
                    <button type="button" @click="type = 'digital'" :class="type === 'digital' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'" class="flex-1 py-3.5 rounded-xl font-bold text-sm transition-all">Digital</button>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <p class="text-sm font-bold text-gray-900 mb-4">Gift(s) on behalf of the other guests:</p>

                <div class="space-y-3 mb-4">
                    <template x-for="(t, index) in titipan" :key="index">
                        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <span class="font-bold text-sm text-gray-800" x-text="t.name"></span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400 font-bold mr-2">GIFT:</span>
                                <button type="button" @click="if(t.qty > 1) t.qty--" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center font-bold text-gray-500">-</button>
                                <span class="font-bold text-gray-900 w-4 text-center" x-text="t.qty"></span>
                                <button type="button" @click="t.qty++" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center font-bold text-gray-500">+</button>
                                <button type="button" @click="removeTitipan(index)" class="ml-2 text-red-400 hover:text-red-600 font-bold p-1">&times;</button>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="showSearch = true" class="w-full py-4 border-2 border-dashed border-gray-200 rounded-2xl text-gray-500 font-bold hover:bg-gray-50 hover:border-gray-300 transition-all text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Add Guests
                </button>
                <input type="hidden" name="titipan_data" :value="JSON.stringify(titipan)">
            </div>
        </div>

        <button type="submit" class="w-full bg-gray-900 text-white py-5 rounded-2xl font-bold text-lg shadow-xl hover:bg-black hover:-translate-y-0.5 transition-all">
            Continue
        </button>
    </form>

    <div x-show="showSearch" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl flex flex-col max-h-[80vh]" @click.away="showSearch = false">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-900">Search Guest for Gift</h3>
                <button @click="showSearch = false" class="text-gray-400 hover:text-gray-900">&times;</button>
            </div>
            <div class="p-4">
                <input type="text" x-model="searchQuery" placeholder="Type name or phone number..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="overflow-y-auto flex-1 p-4 pt-0">
                <template x-for="g in filteredGuests" :key="g.id">
                    <div @click="addTitipan(g)" class="p-4 hover:bg-blue-50 cursor-pointer rounded-xl border border-transparent hover:border-blue-100 transition-colors mb-2 bg-white shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                        <p class="font-bold text-gray-900" x-text="g.name"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="g.phone_number || 'No Phone'"></p>
                    </div>
                </template>
                <div x-show="filteredGuests.length === 0" class="text-center py-8 text-gray-400 text-sm">
                    No guests found.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkinForm(initialPax, allGuestsList) {
        return {
            pax: initialPax,
            gift: 1,
            type: 'fisik',
            showSearch: false,
            searchQuery: '',
            allGuests: allGuestsList,
            titipan: [],

            get filteredGuests() {
                if (this.searchQuery === '') return [];
                return this.allGuests.filter(g =>
                    g.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (g.phone_number && g.phone_number.includes(this.searchQuery))
                ).slice(0, 5);
            },

            addTitipan(guest) {
                if (!this.titipan.find(t => t.id === guest.id)) {
                    this.titipan.push({ id: guest.id, name: guest.name, qty: 1 });
                }
                this.searchQuery = '';
                this.showSearch = false;
            },

            removeTitipan(index) {
                this.titipan.splice(index, 1);
            }
        }
    }
</script>
@endsection
