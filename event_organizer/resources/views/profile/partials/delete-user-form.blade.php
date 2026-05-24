<section x-data="{ confirmingUserDeletion: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <header class="mb-6">
        <h2 class="text-xl font-bold text-red-600">Delete Account</h2>
        <p class="mt-1 text-sm text-gray-500">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
    </header>

    <button @click="confirmingUserDeletion = true" class="px-6 py-2.5 text-sm font-bold text-red-600 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-colors shadow-sm">
        Delete My Account
    </button>

    <div x-show="confirmingUserDeletion" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto" style="display: none;" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl my-8" @click.away="confirmingUserDeletion = false">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-red-600">Delete Account</h3>
                <button @click="confirmingUserDeletion = false" class="text-gray-400 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="p-6 space-y-4">
                    <p class="text-sm font-medium text-gray-600 leading-relaxed">
                        Are you sure you want to delete your account? Once deleted, all data will be lost. Please enter your password to confirm.
                    </p>

                    <div>
                        <label class="block mb-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Password</label>
                        <input type="password" name="password" required placeholder="Enter your password"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500 bg-gray-50/50">
                        @if($errors->userDeletion->get('password'))
                            <p class="mt-2 text-xs text-red-600 font-medium">{{ $errors->userDeletion->first('password') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end p-6 border-t border-gray-100 gap-3 bg-gray-50/50 rounded-b-2xl">
                    <button type="button" @click="confirmingUserDeletion = false" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-sm">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>
</section>
