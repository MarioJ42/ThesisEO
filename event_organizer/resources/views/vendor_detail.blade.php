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
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased selection:bg-blue-500 selection:text-white">

    <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer" onclick="window.location.href='{{ route('home') }}'">
                    <img src="/images/logo-fenix2.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">Home</a>
                    <a href="{{ route('vendor') }}" class="text-gray-900 border-b-2 border-gray-900 font-medium text-sm transition-colors px-1 py-2">Vendor</a>
                    @if(Auth::check() && Auth::user()->role === 'klien')
                        <a href="{{ route('client.events.index') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors px-1 py-2">My Events</a>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors focus:outline-none">
                                <span>Hi, {{ Auth::user()->name }}</span>
                                <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50 flex flex-col" style="display: none;" x-cloak>
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
                        <a href="{{ route('login') }}" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-md text-sm font-semibold tracking-wide transition-all shadow-sm">LOG IN</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-24 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="{{ route('vendor') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-900 mb-8 transition-colors group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Partners
            </a>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-8 items-start md:items-center mb-16">
                <div class="w-32 h-32 md:w-40 md:h-40 flex-shrink-0 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center">
                    @if($vendor->logo)
                        <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    @endif
                </div>

                <div class="flex-grow">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">{{ $vendor->name }}</h1>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-gray-500 text-sm mb-6">
                        @if($vendor->instagram && $vendor->instagram !== '-')
                            <p class="flex items-center gap-2"><svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg> {{ $vendor->instagram }}</p>
                        @endif
                        @if($vendor->address && $vendor->address !== '-')
                            <p class="flex items-center gap-2"><svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $vendor->address }}</p>
                        @endif
                    </div>

                    @php
                        $packages = $vendor->packages ?? collect();
                        $minPrice = $packages->isNotEmpty() ? $packages->min('price') : null;
                        $maxPrice = $packages->isNotEmpty() ? $packages->max('price') : null;
                    @endphp

                    <div class="inline-block bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Price Range</p>
                        <p class="text-lg font-extrabold text-gray-900">
                            @if($minPrice !== null && $maxPrice !== null && $minPrice !== $maxPrice)
                                Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                            @elseif($minPrice !== null)
                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                            @else
                                New Collaboration
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-16">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-900">Packages Offered</h2>
                </div>

                @php
                    $vendorCategories = $vendor->categories ? $vendor->categories->keyBy('id') : collect();
                    $groupedPackages = $packages->groupBy('vendor_category_id');
                @endphp

                @if($groupedPackages->isNotEmpty())
                    <div class="space-y-12">
                        @foreach($groupedPackages as $categoryId => $categoryPackages)
                            @php
                                $categoryName = $vendorCategories->has($categoryId) ? $vendorCategories[$categoryId]->name : 'Other Packages';
                            @endphp
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-5 border-b border-gray-100 pb-3">{{ $categoryName }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($categoryPackages as $package)
                                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-shadow group">
                                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $package->name }}</h4>
                                            <p class="text-xl font-extrabold text-gray-900 mb-4 border-b border-gray-50 pb-4">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                            @if(isset($package->description) && $package->description)
                                                <p class="text-sm text-gray-500 leading-relaxed">{{ $package->description }}</p>
                                            @else
                                                <p class="text-sm text-gray-400 italic">No description provided.</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-200 text-gray-500 text-sm">
                        No packages have been added by this vendor yet.
                    </div>
                @endif
            </div>

            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1.5 h-6 bg-gray-900 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-900">Masterpieces & Portfolio</h2>
                </div>

                @php $portfolios = $vendor->portfolios ?? collect(); @endphp
                @if($portfolios->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($portfolios as $portfolio)
                            <div class="group relative rounded-2xl overflow-hidden aspect-[4/5] bg-gray-100 shadow-sm">
                                <img src="{{ asset('storage/' . $portfolio->image_path) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                    <h4 class="text-white font-bold text-xl">{{ $portfolio->title }}</h4>
                                    @if($portfolio->description)
                                        <p class="text-gray-300 text-sm mt-2 line-clamp-3 leading-relaxed">{{ $portfolio->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200 text-gray-500 text-sm">
                        Portfolio is currently empty.
                    </div>
                @endif
            </div>

        </div>
    </main>

    <footer class="bg-gray-900 py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
            <p class="text-gray-400 text-sm">© {{ date('Y') }} Fenix Event Organizer. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
