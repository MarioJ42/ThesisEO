<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix Event Organizer</title>
    <link rel="icon" href="/images/logo-fenix.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
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

<body class="bg-gray-50 text-gray-800 antialiased selection:bg-blue-500 selection:text-white">

    <nav
        class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer" onclick="window.location.href='{{ route('home') }}'">
                    <img src="/images/logo-fenix.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-900' }} font-medium text-sm transition-colors px-1 py-2">Home</a>
                    <a href="{{ route('vendor') }}"
                        class="{{ request()->routeIs('vendor') ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-900' }} font-medium text-sm transition-colors px-1 py-2">Vendor</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <div x-data="{ open: false }" class="relative">

                            <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors focus:outline-none">
                                <span>Hi, {{ Auth::user()->name }}</span>

                                <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <div x-show="open"
                                x-transition.opacity.duration.200ms
                                class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50 flex flex-col"
                                style="display: none;">

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
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-md text-sm font-semibold tracking-wide transition-all shadow-sm">
                            LOG IN
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-24 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @php
                $excludedCategories = [
                    'Baloon & Dove',
                    'Church',
                    'Church Decoration',
                    'Corsage',
                    'Flower Shower',
                    'Genset',
                    'Guest Lunch',
                    'Hair Styling',
                    'Headpiece',
                    'LCD',
                    'LED',
                    'Meal Crew',
                    'Misua & Angco Tea',
                    'Pyramid Fountain & Toast',
                    'Pyramid Fountian & Toast',
                    'Ring Box',
                    'Robe & Veil',
                    'Room Decoration',
                    'Tie',
                ];

                $excludedVendors = ['Pribadi', 'EO'];

                $displayCategories = isset($categories)
                    ? $categories
                        ->filter(function ($category) use ($excludedCategories) {
                            return !in_array($category->name, $excludedCategories);
                        })
                        ->map(function ($category) use ($excludedVendors) {
                            $filteredVendors = $category->vendors
                                ->filter(function ($vendor) use ($excludedVendors) {
                                    return !in_array($vendor->name, $excludedVendors);
                                })
                                ->values();

                            $category->setRelation('vendors', $filteredVendors);
                            return $category;
                        })
                        ->filter(function ($category) {
                            return $category->vendors->count() > 0;
                        })
                        ->values()
                    : collect();
            @endphp

            <div class="text-center max-w-3xl mx-auto mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4 uppercase">
                    OUR PARTNERS
                </h1>
                <p class="text-lg text-gray-500">
                    Discover the finest professionals who help us craft your unforgettable moments.
                </p>
            </div>

            <div x-data="{ selectedCategory: 'All', searchQuery: '' }" class="flex flex-col md:flex-row gap-8 items-start">

                <aside
                    class="w-full md:w-64 flex-shrink-0 sticky top-28 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm max-h-[calc(100vh-8rem)] overflow-y-auto no-scrollbar">

                    <div class="mb-6">
                        <h3 class="font-bold text-gray-900 mb-3 text-lg">Search</h3>
                        <div class="relative">
                            <input type="text" x-model="searchQuery" placeholder="Find vendo."
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm text-gray-700 placeholder-gray-400">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Categories</h3>
                    <ul class="space-y-1">
                        <li>
                            <button @click="selectedCategory = 'All'"
                                :class="selectedCategory === 'All' ? 'text-blue-600 font-bold bg-blue-50' :
                                    'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full text-left px-4 py-2.5 rounded-lg transition-colors text-sm">
                                All
                            </button>
                        </li>
                        @if ($displayCategories->count() > 0)
                            @foreach ($displayCategories as $category)
                                <li>
                                    <button @click="selectedCategory = {{ json_encode($category->name) }}"
                                        :class="selectedCategory === {{ json_encode($category->name) }} ?
                                            'text-blue-600 font-bold bg-blue-50' :
                                            'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="w-full text-left px-4 py-2.5 rounded-lg transition-colors text-sm">
                                        {{ $category->name }}
                                    </button>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </aside>

                <div class="flex-grow w-full">
                    @if ($displayCategories->count() > 0)
                        @foreach ($displayCategories as $category)
                            <div x-data="{
                                categoryName: {{ json_encode($category->name) }},
                                vendorNames: {{ json_encode($category->vendors->map(function ($v) {return strtolower($v->name);})->values()) }}
                            }"
                                x-show="(selectedCategory === 'All' || selectedCategory === categoryName) && (searchQuery === '' || vendorNames.some(name => name.includes(searchQuery.toLowerCase())))"
                                x-transition.opacity.duration.300ms class="mb-12">

                                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">
                                    {{ $category->name }}
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach ($category->vendors as $vendor)
                                        <div x-show="searchQuery === '' || {{ json_encode(strtolower($vendor->name)) }}.includes(searchQuery.toLowerCase())"
                                            class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col h-full hover:-translate-y-1">

                                            <div
                                                class="aspect-[4/3] bg-gray-100 flex items-center justify-center border-b border-gray-100">
                                                <svg class="w-16 h-16 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>

                                            <div class="p-6 flex-grow flex flex-col">
                                                <h3
                                                    class="text-lg font-bold text-gray-900 mb-1 leading-tight group-hover:text-blue-600 transition-colors">
                                                    {{ $vendor->name }}</h3>

                                                @php
                                                    $packages = $vendor->packages ?? collect();
                                                    $minPrice = $packages->isNotEmpty()
                                                        ? $packages->min('price')
                                                        : null;
                                                    $maxPrice = $packages->isNotEmpty()
                                                        ? $packages->max('price')
                                                        : null;
                                                @endphp

                                                <p class="text-xs text-gray-500 mb-6 flex-grow">
                                                    Price Range:<br>
                                                    <span class="text-sm font-semibold text-gray-700 block mt-0.5">
                                                        @if ($minPrice !== null && $maxPrice !== null)
                                                            Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp
                                                            {{ number_format($maxPrice, 0, ',', '.') }}
                                                        @else
                                                            Contact for pricing
                                                        @endif
                                                    </span>
                                                </p>

                                                <button type="button"
                                                    class="w-full py-2.5 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl transition-colors mt-auto flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Add to Your Event
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div
                            class="text-center text-gray-500 py-12 bg-white rounded-xl border border-dashed border-gray-300">
                            <p>No vendors available at the moment.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-gray-900 py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
            <p class="text-gray-400 text-sm">© {{ date('Y') }} Fenix Event Organizer. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0 text-sm">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>

</body>

</html>
