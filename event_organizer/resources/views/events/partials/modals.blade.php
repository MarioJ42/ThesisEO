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

<div x-show="isEditGuestModalOpen"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto"
    style="display: none;" x-cloak>
    <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl my-8" @click.away="isEditGuestModalOpen = false">
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Edit Guest</h3>
            <button @click="isEditGuestModalOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form :action="'{{ url('/' . $user->role . '/events/' . $event->id . '/guests') }}/' + guestForm.id" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="guestForm.name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp Number</label>
                    <input type="text" name="phone_number" x-model="guestForm.phone_number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax Invited <span class="text-red-500">*</span></label>
                        <input type="number" name="pax_invited" x-model="guestForm.pax_invited" min="1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                    <div class="w-1/2">
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table Name</label>
                        <input type="text" name="table_name" x-model="guestForm.table_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                    <select name="status" x-model="guestForm.status" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-colors">
                        <option value="pending">Pending</option>
                        <option value="attending">Attending</option>
                        <option value="not_attending">Not Attending</option>
                        <option value="checked_in">Checked In</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" @click="isEditGuestModalOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Update Guest</button>
            </div>
        </form>
    </div>
</div>
