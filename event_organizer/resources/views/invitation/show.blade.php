<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Wedding of {{ $guest->event_title }}</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#0b1121] text-white antialiased overflow-x-hidden">

    <div x-data="{ isOpen: {{ $guest->status !== 'pending' ? 'true' : 'false' }} }">

        <section x-show="!isOpen" x-transition.opacity.duration.800ms
            class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-[#0b1121] text-center p-6">

            <p class="text-[10px] uppercase tracking-[0.2em] mb-4 text-gray-400">THE WEDDING OF</p>
            <h1 class="font-serif text-4xl md:text-5xl mb-12">{{ $guest->event_title }}</h1>

            <div class="mb-12">
                <p class="text-gray-400 text-xs mb-2 italic">Dear,</p>
                <h2 class="text-xl font-bold">{{ $guest->name }}</h2>
            </div>

            <button @click="isOpen = true; window.scrollTo(0,0)"
                class="bg-white text-gray-900 px-6 py-2.5 rounded-full font-bold hover:bg-gray-200 transition-all flex items-center gap-2 text-sm shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
                Open Invitation
            </button>
        </section>

        <main x-show="isOpen" x-cloak x-transition.opacity.duration.800ms
            class="min-h-screen bg-[#f8fafc] text-gray-900 flex flex-col items-center py-12">

            <div class="w-full max-w-sm px-6">
                <div class="text-center mb-8">
                    <p class="text-[10px] uppercase tracking-[0.15em] text-gray-500 mb-3">YOU ARE CORDIALLY INVITED TO
                        THE WEDDING OF</p>
                    <h2 class="font-serif text-3xl mb-6">{{ $guest->event_title }}</h2>

                    <p class="font-bold text-lg text-gray-900">
                        {{ \Carbon\Carbon::parse($guest->event_date)->format('l, d F Y') }}</p>
                    <p class="text-gray-500 text-sm italic mt-1">6:00 PM WIB - End</p>
                </div>

                @if (session('success') && $guest->status === 'attending')
                    <div
                        class="bg-[#dcfce7] border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 text-center text-xs font-medium shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div
                    class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 text-center mb-8">

                    @if ($guest->status === 'pending')
                        <h3 class="font-bold text-xl mb-6 text-gray-900">Attendance Confirmation</h3>
                        <div class="flex flex-col gap-3">
                            <form action="{{ route('invitation.rsvp', $guest->barcode_token) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="attending">
                                <button type="submit"
                                    class="w-full bg-[#111827] text-white py-3.5 rounded-xl font-bold shadow-md hover:bg-black transition-all text-sm">
                                    I Will Attend
                                </button>
                            </form>
                            <form action="{{ route('invitation.rsvp', $guest->barcode_token) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="not_attending">
                                <button type="submit"
                                    class="w-full bg-white border border-gray-200 text-gray-500 py-3.5 rounded-xl font-bold hover:bg-gray-50 transition-all text-sm">
                                    I Am Unable to Attend
                                </button>
                            </form>
                        </div>
                    @elseif($guest->status === 'not_attending')
                        <div class="py-6">
                            <h3 class="font-bold text-lg mb-2">Confirmation Received</h3>
                            <p class="text-gray-500 text-sm">Thank you for your confirmation. We are sorry you cannot
                                attend the event.</p>
                        </div>
                    @elseif(in_array($guest->status, ['attending', 'checked_in']))
                        <h3 class="font-bold text-xl mb-2 text-gray-900">Digital Entry Ticket</h3>
                        <p class="text-gray-500 text-[10px] mb-6">Please present this QR Code to the reception staff at
                            the venue.</p>

                        <div class="bg-white p-4 rounded-2xl inline-block border border-gray-100 shadow-sm mb-6">
                            {!! QrCode::size(180)->generate($guest->barcode_token) !!}
                        </div>

                        <div class="space-y-1.5">
                            <p class="text-[11px] font-extrabold text-blue-700 uppercase tracking-widest">TABLE:
                                {{ $guest->table_name ?? 'General' }}</p>
                            <p class="text-sm font-bold text-gray-900">Total Pax: {{ $guest->pax_invited }}</p>
                        </div>
                    @endif

                </div>

                <div class="text-center">
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.1em]">Powered by Fenix Digital
                        Guest Book</p>
                </div>
            </div>

        </main>
    </div>

</body>

</html>
