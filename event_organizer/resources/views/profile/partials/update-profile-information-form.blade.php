<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
        <p class="mt-1 text-sm text-gray-500">Update your account's email address and phone number. Account name cannot be changed.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label class="block mb-1.5 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Full Name</label>
            <input type="text" value="{{ old('name', $user->name) }}" disabled
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-100 text-gray-400 cursor-not-allowed font-medium">
            <input type="hidden" name="name" value="{{ $user->name }}">
        </div>

        <div class="flex flex-col sm:flex-row gap-5">
            <div class="w-full sm:w-1/2">
                <label class="block mb-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                @if($errors->get('email'))
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="w-full sm:w-1/2">
                <label class="block mb-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" required placeholder="08123456789"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                @if($errors->get('phone'))
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $errors->first('phone') }}</p>
                @endif
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl">
                <p class="text-sm text-amber-800">
                    Your email address is unverified.
                    <button form="send-verification" class="font-bold underline hover:text-amber-900 transition-colors">
                        Click here to re-send the verification email.
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-bold text-emerald-600">A new verification link has been sent to your email address.</p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">Save Changes</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-emerald-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Profile updated.
                </p>
            @endif
        </div>
    </form>
</section>
