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
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form :action="'{{ url('/' . $user->role . '/events/' . $event->id . '/slots') }}/' + form.slot_id + '/price'" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between p-4 rounded-lg border border-blue-100" :class="(form.deal_price - form.net_price) >= 0 ? 'bg-blue-50' : 'bg-red-50 border-red-100'">
                    <span class="text-xs font-bold uppercase tracking-wider" :class="(form.deal_price - form.net_price) >= 0 ? 'text-blue-800' : 'text-red-800'">Margin / Profit</span>
                    <span class="text-lg font-black" :class="(form.deal_price - form.net_price) >= 0 ? 'text-green-600' : 'text-red-600'" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(form.deal_price - form.net_price)"></span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Net Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-bold">Rp</span>
                            <input type="number" name="net_price" x-model.number="form.net_price" required min="0" class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold bg-gray-50">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Deal Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-bold">Rp</span>
                            <input type="number" name="deal_price" x-model.number="form.deal_price" required min="0" class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold bg-gray-50">
                        </div>
                    </div>
                </div>

                <template x-if="form.base_cost > 0 && form.deal_price > form.base_cost">
                    <div class="mt-4 text-[11px] text-orange-700 font-semibold bg-orange-50 p-3 rounded-lg border border-orange-100 flex gap-2 items-start">
                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Client upgraded vendor. Additional fee to client: <strong>+Rp <span x-text="new Intl.NumberFormat('id-ID').format(form.deal_price - form.base_cost)"></span></strong></span>
                    </div>
                </template>
            </div>

            <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" @click="isOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save Pricing</button>
            </div>
        </form>
    </div>
</div>

<div x-show="isGuestModalOpen"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto"
    style="display: none;" x-cloak>
    <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl my-8" @click.away="isGuestModalOpen = false">
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Add New Guest</h3>
            <button @click="isGuestModalOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route($user->role . '.events.guests.store', $event->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp Number</label>
                    <input type="text" name="phone_number" placeholder="08123456789" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax Invited <span class="text-red-500">*</span></label>
                        <input type="number" name="pax_invited" value="1" min="1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                    <div class="w-1/2">
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table Name</label>
                        <input type="text" name="table_name" placeholder="Optional" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-colors">
                        <option value="pending">Pending</option>
                        <option value="attending">Attending</option>
                        <option value="not_attending">Not Attending</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" @click="isGuestModalOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save Guest</button>
            </div>
        </form>
    </div>
</div>

<div x-data="advancedEditGuestForm(@js($guests))"
    @open-edit-guest.window="initForm($event.detail)"
    x-show="isOpen"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto"
    style="display: none;" x-cloak>
    <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl my-8" @click.away="if(!showSearch) isOpen = false">
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Edit Guest Details</h3>
            <button @click="isOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form :action="'{{ url('/' . $user->role . '/events/' . $event->id . '/guests') }}/' + form.id" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                <div class="space-y-4">
                    <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Guest Profile</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="form.name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp Number</label>
                            <input type="text" name="phone_number" x-model="form.phone_number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table Name</label>
                            <input type="text" name="table_name" x-model="form.table_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax Invited <span class="text-red-500">*</span></label>
                            <input type="number" name="pax_invited" x-model="form.pax_invited" min="1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                            <select name="status" x-model="form.status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-colors">
                                <option value="pending">Pending</option>
                                <option value="attending">Attending</option>
                                <option value="not_attending">Not Attending</option>
                                <option value="checked_in">Checked In</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Check-in Details</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax Actual</label>
                            <input type="number" name="pax_actual" x-model="form.pax_actual" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Gift Count</label>
                            <input type="number" name="angpao_count" x-model="form.angpao_count" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Gift Type</label>
                            <div class="flex gap-4">
                                <input type="hidden" name="angpao_type" x-model="form.angpao_type">
                                <button type="button" @click="form.angpao_type = 'fisik'" :class="form.angpao_type === 'fisik' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'" class="flex-1 py-2.5 rounded-lg font-bold text-sm transition-all border border-transparent">Physical</button>
                                <button type="button" @click="form.angpao_type = 'digital'" :class="form.angpao_type === 'digital' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'" class="flex-1 py-2.5 rounded-lg font-bold text-sm transition-all border border-transparent">Digital</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Gift(s) on behalf of (Titipan)</h4>
                    <div class="space-y-2">
                        <template x-for="(t, index) in titipan" :key="index">
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-200">
                                <span class="font-bold text-sm text-gray-800" x-text="t.name"></span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Gift:</span>
                                    <button type="button" @click="if(t.qty > 1) t.qty--" class="w-7 h-7 rounded-md bg-white border border-gray-300 flex items-center justify-center font-bold text-gray-500 text-xs">-</button>
                                    <span class="font-bold text-gray-900 w-4 text-center text-sm" x-text="t.qty"></span>
                                    <button type="button" @click="t.qty++" class="w-7 h-7 rounded-md bg-white border border-gray-300 flex items-center justify-center font-bold text-gray-500 text-xs">+</button>
                                    <button type="button" @click="removeTitipan(index)" class="ml-2 text-red-500 hover:text-red-700 font-bold px-1">&times;</button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="showSearch = true" class="w-full py-3.5 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 font-bold hover:bg-gray-50 hover:border-gray-400 transition-all text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Add Guest Titipan
                    </button>
                    <input type="hidden" name="titipan_data" :value="JSON.stringify(titipan)">
                </div>

            </div>
            <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" @click="isOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save Changes</button>
            </div>
        </form>

        <div x-show="showSearch" class="absolute inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 rounded-xl" x-cloak>
            <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl flex flex-col max-h-[85%]" @click.away="showSearch = false">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-900 text-sm">Search Guest</h3>
                    <button @click="showSearch = false" class="text-gray-400 hover:text-gray-900">&times;</button>
                </div>
                <div class="p-3 border-b border-gray-100">
                    <input type="text" x-model="searchQuery" placeholder="Type name or phone number..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="overflow-y-auto flex-1 p-3">
                    <template x-for="g in filteredGuests" :key="g.id">
                        <div @click="addTitipan(g)" class="p-3 hover:bg-blue-50 cursor-pointer rounded-lg border border-transparent hover:border-blue-100 transition-colors mb-1.5 bg-white shadow-sm">
                            <p class="font-bold text-gray-900 text-sm" x-text="g.name"></p>
                            <p class="text-[11px] text-gray-500 mt-0.5" x-text="g.phone_number || 'No Phone'"></p>
                        </div>
                    </template>
                    <div x-show="filteredGuests.length === 0" class="text-center py-6 text-gray-400 text-xs font-medium">
                        No guests found.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function advancedEditGuestForm(allGuests) {
        return {
            isOpen: false,
            form: { id: '', name: '', phone_number: '', pax_invited: 1, pax_actual: 0, table_name: '', status: 'attending', angpao_count: 0, angpao_type: 'fisik' },
            titipan: [],
            searchQuery: '',
            allGuests: allGuests,
            showSearch: false,

            initForm(detail) {
                this.form = detail;
                this.titipan = [];
                this.searchQuery = '';
                this.showSearch = false;
                this.isOpen = true;
            },

            get filteredGuests() {
                if (this.searchQuery === '') return [];
                return this.allGuests.filter(g =>
                    g.id !== this.form.id &&
                    (g.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (g.phone_number && g.phone_number.includes(this.searchQuery)))
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
