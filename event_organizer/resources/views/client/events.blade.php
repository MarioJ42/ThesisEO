<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix Event Organizer</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased"
      x-data="{
          isCreateModalOpen: new URLSearchParams(location.search).has('plan_package'),
          activeTab: 'all',
          selectedPackage: new URLSearchParams(location.search).get('plan_package') || ''
      }"
      x-init="if(isCreateModalOpen) { window.history.replaceState({}, document.title, window.location.pathname); }">

    <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer" onclick="window.location.href='{{ route('home') }}'">
                    <img src="/images/logo-fenix2.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Home</a>
                    <a href="{{ route('vendor') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Vendor</a>
                    <a href="{{ route('client.events.index') }}" class="text-gray-900 border-b-2 border-gray-900 font-medium text-sm transition-colors px-1 py-2">My Events</a>
                </div>

                <div class="flex items-center gap-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors focus:outline-none">
                            <span>Hi, {{ Auth::user()->name }}</span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50 flex flex-col" x-cloak>
                            <a href="{{ route('profile.edit') }}" class="px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile
                            </a>
                            <div class="h-px bg-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0 block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">My Events</h1>
                    <p class="text-gray-500 mt-2">Manage and track the progress of your upcoming moments.</p>
                </div>
                <button @click="isCreateModalOpen = true" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg text-sm font-semibold tracking-wide transition-all shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Plan New Event
                </button>
            </div>

            @if(session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @php
                $counts = [
                    'all' => $events->count(),
                    'draft' => $events->where('status', 'draft')->count(),
                    'planning' => $events->where('status', 'planning')->count(),
                    'ongoing' => $events->where('status', 'ongoing')->count(),
                    'completed' => $events->where('status', 'completed')->count(),
                    'canceled' => $events->where('status', 'canceled')->count(),
                ];
            @endphp

            @if($counts['all'] > 0)
                <div class="mb-8 border-b border-gray-200">
                    <nav class="flex space-x-8 overflow-x-auto no-scrollbar" aria-label="Tabs">
                        @foreach(['all' => 'All Events', 'draft' => 'Draft', 'planning' => 'Planning', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'canceled' => 'Canceled'] as $key => $label)
                            <button @click="activeTab = '{{ $key }}'"
                                :class="activeTab === '{{ $key }}' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors flex items-center gap-2">
                                {{ $label }}
                                <span :class="activeTab === '{{ $key }}' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'" class="py-0.5 px-2 rounded-full text-[10px] font-bold transition-colors">
                                    {{ $counts[$key] }}
                                </span>
                            </button>
                        @endforeach
                    </nav>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div x-show="activeTab === 'all' || activeTab === '{{ $event->status }}'" x-transition.opacity.duration.300ms class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full relative overflow-hidden" x-cloak>
                            <div class="absolute top-0 left-0 w-1 h-full
                                @if($event->status == 'draft') bg-gray-300
                                @elseif($event->status == 'planning') bg-blue-500
                                @elseif($event->status == 'ongoing') bg-yellow-400
                                @elseif($event->status == 'completed') bg-green-500
                                @elseif($event->status == 'canceled') bg-red-500
                                @endif">
                            </div>

                            <div class="flex justify-between items-start mb-4 pl-2">
                                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full
                                    @if($event->status == 'draft') bg-gray-100 text-gray-600
                                    @elseif($event->status == 'planning') bg-blue-50 text-blue-600
                                    @elseif($event->status == 'ongoing') bg-yellow-50 text-yellow-700
                                    @elseif($event->status == 'completed') bg-green-50 text-green-700
                                    @elseif($event->status == 'canceled') bg-red-50 text-red-600
                                    @endif">
                                    {{ $event->status }}
                                </span>
                                <div class="text-xs font-semibold text-gray-400">
                                    {{ \Carbon\Carbon::parse($event->created_at)->diffForHumans() }}
                                </div>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-1 pl-2">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-500 mb-6 pl-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
                            </p>

                            <div class="mt-auto space-y-3 pl-2 bg-gray-50 p-4 rounded-xl">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Package Selected</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $event->package ? $event->package->name : 'Custom Arrangement' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Project Leader</p>
                                    <div class="flex items-center gap-2">
                                        @if($event->pl)
                                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                                {{ substr($event->pl->name, 0, 1) }}
                                            </div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $event->pl->name }}</p>
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">?</div>
                                            <p class="text-sm font-medium text-gray-500 italic">Pending Assignment</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(!in_array($event->status, ['draft', 'canceled']))
                            <div class="mt-6 pl-2 flex flex-col gap-2">
                                <a href="{{ route('client.events.manage', $event->id) }}" class="block w-full py-2.5 bg-white border border-gray-200 text-gray-900 text-center rounded-lg text-sm font-semibold hover:border-gray-900 transition-colors">
                                    View Details
                                </a>
                                <a href="{{ route('client.events.analytics', $event->id) }}" class="block w-full py-2.5 bg-gray-900 text-white text-center rounded-lg text-sm font-semibold hover:bg-black transition-colors shadow-sm">
                                    View RSVP Analytics
                                </a>
                            </div>
                            @endif
                        </div>
                    @endforeach

                    @foreach(['draft', 'planning', 'ongoing', 'completed', 'canceled'] as $status)
                        @if($counts[$status] === 0)
                            <div x-show="activeTab === '{{ $status }}'" class="col-span-full py-16 flex flex-col items-center justify-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200" x-cloak>
                                <p class="text-gray-500 text-sm">You don't have any {{ ucfirst($status) }} events.</p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $events->links() }}
                </div>

            @else
                <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No Events Yet</h3>
                    <p class="text-gray-500 text-sm mb-6 text-center max-w-sm">You haven't planned any events with us yet. Let's create your first unforgettable moment!</p>
                    <button @click="isCreateModalOpen = true" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                        Plan New Event
                    </button>
                </div>
            @endif
        </div>
    </main>

    <div x-show="isCreateModalOpen" x-transition.opacity class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 999; background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px);" x-cloak>
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl" @click.away="isCreateModalOpen = false">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Plan Your Event</h3>
                    <p class="text-xs text-gray-500 mt-1">Fill in the details to start your journey with us.</p>
                </div>
                <button @click="isCreateModalOpen = false" class="text-gray-400 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('client.events.store') }}" method="POST">
                @csrf
                <input type="hidden" name="client_id" value="{{ Auth::id() }}">

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-bold text-gray-900">Event Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="Wedding of Groom & Bride" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-bold text-gray-900">Event Date <span class="text-red-500">*</span></label>
                        <input type="date" name="event_date" required min="{{ \Carbon\Carbon::today()->addDays(21)->format('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-bold text-gray-900">Select Package <span class="text-red-500">*</span></label>
                        <select name="package_id" required x-model="selectedPackage" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors">
                            <option value="" disabled selected>Choose a base arrangement</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }} (Start from Rp {{ number_format($package->base_price, 0, ',', '.') }})</option>
                            @endforeach
                            <option value="custom">Custom Arrangement (Build from scratch)</option>
                        </select>
                        <p class="text-[11px] text-gray-500 mt-2 leading-relaxed">By selecting a package, we will automatically set up standard vendor slots for you. You can customize them later.</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button type="button" @click="isCreateModalOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition-colors shadow-md">Create Event</button>
                </div>
            </form>
        </div>
    </div>

@auth
        @if(Auth::user()->must_change_password)
            <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 99999; background-color: rgba(0, 0, 0, 0.9); backdrop-filter: blur(8px);">
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Security Update</h3>
                    <p class="text-sm text-gray-500 mb-6">For your account's security, please change the default password provided by the administrator.</p>

                    <form action="{{ route('password.force_change') }}" method="POST" class="text-left space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">New Password</label>
                            <input type="password" name="password" required minlength="8" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50" placeholder="Minimum 8 characters">
                            @error('password') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50" placeholder="Re-type new password">
                        </div>
                        <button type="submit" class="w-full pt-2">
                            <div class="w-full py-3 px-4 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm shadow-md transition-colors text-center">
                                Update Password & Re-login
                            </div>
                        </button>
                    </form>
                </div>
            </div>
            <style>
                body { overflow: hidden !important; }
            </style>
        @endif
    @endauth

</body>
</html>
