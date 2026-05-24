<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix EO - Digital Guestbook</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="text-gray-900 antialiased">
    <main class="w-full min-h-screen">
        @yield('content')
    </main>
    @auth
        @if (Auth::user()->must_change_password)
            <div class="fixed inset-0 flex items-center justify-center p-4"
                style="z-index: 99999; background-color: rgba(0, 0, 0, 0.9); backdrop-filter: blur(8px);">
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Security Update</h3>
                    <p class="text-sm text-gray-500 mb-6">For your account's security, please change the default password
                        provided by the administrator.</p>

                    <form action="{{ route('password.force_change') }}" method="POST" class="text-left space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">New
                                Password</label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50"
                                placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Confirm New
                                Password</label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50"
                                placeholder="Re-type new password">
                        </div>
                        <button type="submit" class="w-full pt-2">
                            <div
                                class="w-full py-3 px-4 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm shadow-md transition-colors text-center">
                                Update Password & Re-login
                            </div>
                        </button>
                    </form>
                </div>
            </div>
            <style>
                body {
                    overflow: hidden !important;
                }
            </style>
        @endif
    @endauth
</body>

</html>
