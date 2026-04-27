<div x-show="isGuestModalOpen"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto"
    style="display: none;" x-cloak>
    <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl my-8" @click.away="isGuestModalOpen = false">
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Add New Guest</h3>
            <button @click="isGuestModalOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <form action="{{ route($user->role . '.events.guests.store', $event->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest Name
                        <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp
                        Number</label>
                    <input type="text" name="phone_number" placeholder="08123456789"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax
                            Invited <span class="text-red-500">*</span></label>
                        <input type="number" name="pax_invited" value="1" min="1" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                    <div class="w-1/2">
                        <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table
                            Name</label>
                        <input type="text" name="table_name" placeholder="Optional"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                    <select name="status"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-colors">
                        <option value="pending">Pending</option>
                        <option value="attending">Attending</option>
                        <option value="not_attending">Not Attending</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end p-5 border-t border-gray-100 gap-3 bg-gray-50 rounded-b-xl">
                <button type="button" @click="isGuestModalOpen = false"
                    class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save
                    Guest</button>
            </div>
        </form>
    </div>
</div>
