<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix Event Organizer</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer"
                    onclick="window.location.href='{{ route('home') }}'">
                    <img src="/images/logo-fenix2.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Home</a>
                    <a href="{{ route('vendor') }}"
                        class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Vendor</a>
                    <a href="{{ route('client.events.index') }}"
                        class="text-gray-900 border-b-2 border-gray-900 font-medium text-sm transition-colors px-1 py-2">My
                        Events</a>
                </div>

                <div class="flex items-center gap-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors focus:outline-none">
                            <span>Hi, {{ Auth::user()->name }}</span>
                            <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms
                            class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50 flex flex-col"
                            x-cloak>
                            <a href="{{ route('profile.edit') }}"
                                class="px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <div class="h-px bg-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0 block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-24 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <div class="flex-1"></div>
                <div class="flex-1"></div>
                <div class="flex-1 flex justify-end">
                    <a href="{{ route('client.events.manage', $event->id) }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Event Arrangement
                    </a>
                </div>
            </div>

            <div
                class="max-w-3xl mx-auto bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden mb-12">
                <div class="absolute top-0 left-0 w-full h-2 bg-gray-900"></div>

                <div class="text-center mt-2">
                    <span
                        class="inline-block px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full mb-4 bg-blue-50 text-blue-600 border border-blue-100">
                        Digital Guestbook
                    </span>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-4">{{ $event->title }}
                    </h1>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-6 text-gray-500 font-medium text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
                        </span>
                        <span class="hidden sm:block text-gray-300">•</span>
                        <span class="flex items-center gap-2 text-gray-900">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $event->package ? $event->package->name : 'Custom Arrangement' }}
                        </span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div x-data="{
                isGuestModalOpen: false,
                isEditGuestModalOpen: false,
                guestSearch: '',
                guestForm: { id: '', name: '', phone_number: '', pax_invited: 1, table_name: '', status: 'attending' },
                openEditGuestModal(id, name, phone, pax, table, status) {
                    this.guestForm.id = id;
                    this.guestForm.name = name;
                    this.guestForm.phone_number = phone;
                    this.guestForm.pax_invited = pax;
                    this.guestForm.table_name = table;
                    this.guestForm.status = status;
                    this.isEditGuestModalOpen = true;
                }
            }">

                @php
                    $eventDate = \Carbon\Carbon::parse($event->event_date)->startOfDay();
                    $today = \Carbon\Carbon::now()->startOfDay();
                    $daysUntilEvent = $today->diffInDays($eventDate, false);
                    $isRsvpLocked = $daysUntilEvent <= 7;
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold text-gray-900">Digital Guestbook</h2>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Powered by Fenix EO
                        </span>
                    </div>

                    @if (!$isRsvpLocked)
                        <button @click="isGuestModalOpen = true"
                            class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Guest
                        </button>
                    @endif
                </div>

                @if ($isRsvpLocked)
                    <div
                        class="mb-6 bg-red-50 border border-red-200 p-5 rounded-2xl flex items-start gap-4 text-red-800 shadow-sm">
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <div>
                            <h4 class="font-bold text-base mb-1">RSVP is Locked</h4>
                            <p class="text-sm">Your event is in <strong>{{ $daysUntilEvent }} days</strong>. Guest
                                data has been finalized and submitted to the Fenix EO production team. You can no longer
                                add, edit, or remove guests.</p>
                        </div>
                    </div>
                @else
                    <div
                        class="mb-6 bg-amber-50 border border-amber-200 p-4 rounded-xl flex items-center gap-3 text-amber-800">
                        <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium">Guest input will be automatically locked <strong>7 days</strong>
                            before the event
                            ({{ \Carbon\Carbon::parse($event->event_date)->subDays(7)->format('d M Y') }}).</p>
                    </div>
                @endif

                @php
                    $totalGuests = $guests->count();
                    $totalPax = $guests->sum('pax_invited');
                    $attendingGuests = $guests->where('status', 'attending')->count();
                    $notAttendingGuests = $guests->where('status', 'not_attending')->count();
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider mb-1">Total Invitations
                        </p>
                        <p class="text-3xl font-black text-gray-900">{{ $totalGuests }}</p>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100 shadow-sm">
                        <p class="text-[11px] text-blue-600 font-bold uppercase tracking-wider mb-1">Total Pax</p>
                        <p class="text-3xl font-black text-blue-900">{{ $totalPax }}</p>
                    </div>
                    <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
                        <p class="text-[11px] text-emerald-600 font-bold uppercase tracking-wider mb-1">Attending</p>
                        <p class="text-3xl font-black text-emerald-900">{{ $attendingGuests }}</p>
                    </div>
                    <div class="bg-red-50 p-5 rounded-2xl border border-red-100 shadow-sm">
                        <p class="text-[11px] text-red-600 font-bold uppercase tracking-wider mb-1">Not Attending</p>
                        <p class="text-3xl font-black text-red-900">{{ $notAttendingGuests }}</p>
                    </div>
                </div>

                <div class="relative w-full mb-4">
                    <input type="text" x-model="guestSearch" placeholder="Search guest name or phone"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="min-w-full w-full whitespace-nowrap">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th
                                        class="px-5 py-4 text-left text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Guest Name</th>
                                    <th
                                        class="px-5 py-4 text-left text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Contact</th>
                                    <th
                                        class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Pax</th>
                                    <th
                                        class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Table</th>
                                    <th
                                        class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    @if (!$isRsvpLocked)
                                        <th
                                            class="px-5 py-4 text-center text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">
                                            Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($guests as $guest)
                                    <tr class="hover:bg-gray-50/50 transition-colors"
                                        x-show="guestSearch === '' || '{{ strtolower(addslashes($guest->name . ' ' . $guest->phone_number)) }}'.includes(guestSearch.toLowerCase())">
                                        <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $guest->name }}</td>
                                        <td class="px-5 py-4 text-sm text-gray-500">{{ $guest->phone_number ?? '-' }}
                                        </td>
                                        <td class="px-5 py-4 text-sm text-center font-extrabold text-blue-600">
                                            {{ $guest->pax_invited }}</td>
                                        <td class="px-5 py-4 text-sm text-center text-gray-600">
                                            {{ $guest->table_name ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm text-center">
                                            <span
                                                class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border
                                                @if ($guest->status === 'attending') bg-emerald-50 text-emerald-700 border-emerald-200
                                                @elseif($guest->status === 'not_attending') bg-red-50 text-red-700 border-red-200
                                                @elseif($guest->status === 'checked_in') bg-blue-50 text-blue-700 border-blue-200 @endif">
                                                {{ str_replace('_', ' ', $guest->status) }}
                                            </span>
                                        </td>
                                        @if (!$isRsvpLocked)
                                            <td class="px-5 py-4 text-sm text-center">
                                                <div class="flex justify-center gap-2">
                                                    <button
                                                        @click="openEditGuestModal({{ $guest->id }}, '{{ addslashes($guest->name) }}', '{{ addslashes($guest->phone_number) }}', {{ $guest->pax_invited }}, '{{ addslashes($guest->table_name) }}', '{{ $guest->status }}')"
                                                        class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Edit</button>
                                                    <form
                                                        action="{{ route('client.events.guests.destroy', ['event' => $event->id, 'guest' => $guest->id]) }}"
                                                        method="POST" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            data-form-id="delete-guest-{{ $guest->id }}"
                                                            onclick="confirmDelete(this)"
                                                            class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isRsvpLocked ? 5 : 6 }}" class="px-5 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm font-medium text-gray-500">Your guestbook is empty.
                                                </p>
                                                @if (!$isRsvpLocked)
                                                    <p class="text-xs mt-1">Start adding guests to manage your
                                                        invitations.</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="isGuestModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
                    style="display: none;" x-cloak>
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl my-8"
                        @click.away="isGuestModalOpen = false">
                        <div class="flex justify-between items-center p-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900">Add New Guest</h3>
                            <button @click="isGuestModalOpen = false"
                                class="text-gray-400 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <form action="{{ route('client.events.guests.store', $event->id) }}" method="POST">
                            @csrf
                            <div class="p-6 space-y-5">
                                <div>
                                    <label
                                        class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest
                                        Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                </div>
                                <div>
                                    <label
                                        class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp
                                        Number</label>
                                    <input type="text" name="phone_number" placeholder="08123456789"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <label
                                            class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax
                                            Invited <span class="text-red-500">*</span></label>
                                        <input type="number" name="pax_invited" value="1" min="1"
                                            required
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                    </div>
                                    <div class="w-1/2">
                                        <label
                                            class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table
                                            Name</label>
                                        <input type="text" name="table_name"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                                    <select name="status"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50 appearance-none">
                                        <option value="attending">Attending</option>
                                        <option value="not_attending">Not Attending</option>
                                    </select>
                                </div>
                            </div>
                            <div
                                class="flex justify-end p-6 border-t border-gray-100 gap-3 bg-gray-50/50 rounded-b-2xl">
                                <button type="button" @click="isGuestModalOpen = false"
                                    class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                                <button type="submit"
                                    class="px-6 py-2.5 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-sm">Save
                                    Guest</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="isEditGuestModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
                    style="display: none;" x-cloak>
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl my-8"
                        @click.away="isEditGuestModalOpen = false">
                        <div class="flex justify-between items-center p-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900">Edit Guest</h3>
                            <button @click="isEditGuestModalOpen = false"
                                class="text-gray-400 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <form :action="'{{ url('/client/events/' . $event->id . '/guests') }}/' + guestForm.id"
                            method="POST">
                            @csrf @method('PUT')
                            <div class="p-6 space-y-5">
                                <div>
                                    <label
                                        class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Guest
                                        Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" x-model="guestForm.name" required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                </div>
                                <div>
                                    <label
                                        class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp
                                        Number</label>
                                    <input type="text" name="phone_number" x-model="guestForm.phone_number"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <label
                                            class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Pax
                                            Invited <span class="text-red-500">*</span></label>
                                        <input type="number" name="pax_invited" x-model="guestForm.pax_invited"
                                            min="1" required
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                    </div>
                                    <div class="w-1/2">
                                        <label
                                            class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Table
                                            Name</label>
                                        <input type="text" name="table_name" x-model="guestForm.table_name"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Status</label>
                                    <select name="status" x-model="guestForm.status"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50 appearance-none">
                                        <option value="attending">Attending</option>
                                        <option value="not_attending">Not Attending</option>
                                        <option value="checked_in">Checked In</option>
                                    </select>
                                </div>
                            </div>
                            <div
                                class="flex justify-end p-6 border-t border-gray-100 gap-3 bg-gray-50/50 rounded-b-2xl">
                                <button type="button" @click="isEditGuestModalOpen = false"
                                    class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                                <button type="submit"
                                    class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">Update
                                    Guest</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let scrollpos = localStorage.getItem('guestbookScroll');
            if (scrollpos) {
                window.scrollTo(0, parseInt(scrollpos));
                localStorage.removeItem('guestbookScroll');
            }
        });

        window.addEventListener("beforeunload", function() {
            localStorage.setItem('guestbookScroll', window.scrollY);
        });

        function confirmDelete(button) {
            const formId = button.getAttribute('data-form-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (document.getElementById(formId)) {
                        document.getElementById(formId).submit();
                    } else if (button.closest('form')) {
                        button.closest('form').submit();
                    }
                }
            });
        }
    </script>
</body>

</html>
