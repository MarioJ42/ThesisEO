<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Update Password</h2>
        <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label class="block mb-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Current Password</label>
            <input type="password" name="current_password" required autocomplete="current-password"
                   class="w-full sm:w-1/2 px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
            @if($errors->updatePassword->get('current_password'))
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row gap-5">
            <div class="w-full sm:w-1/2">
                <label class="block mb-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider">New Password</label>
                <input type="password" name="password" required autocomplete="new-password"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                @if($errors->updatePassword->get('password'))
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div class="w-full sm:w-1/2">
                <label class="block mb-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Confirm New Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                @if($errors->updatePassword->get('password_confirmation'))
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-sm">Update Password</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-emerald-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Password secured.
                </p>
            @endif
        </div>
    </form>
</section>
