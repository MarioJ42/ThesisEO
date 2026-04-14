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
                    <img src="/images/logo-fenix2.png" alt="Fenix Logo" class="w-24 h-24 object-contain">
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

    <main class="pt-20">

        <section id="home" class="relative min-h-[80vh] flex items-center justify-center bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <span class="text-blue-600 font-semibold tracking-wider text-sm uppercase mb-4 block">
                    Crafting Unforgettable Moments
                </span>
                <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                    Your Dream Event, <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500">Perfectly
                        Executed.</span>
                </h1>
                <p class="mt-4 text-lg md:text-xl text-gray-500 max-w-2xl mx-auto mb-10">
                    Fenix Event Organizer is dedicated to turning your visions into reality with meticulous planning and
                    luxurious touches.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#packages"
                        class="bg-gray-900 hover:bg-gray-800 text-white px-8 py-3.5 rounded-md font-semibold transition-colors shadow-lg">View
                        Packages</a>
                    <a href="#contact"
                        class="bg-white hover:bg-gray-50 text-gray-900 border border-gray-200 px-8 py-3.5 rounded-md font-semibold transition-colors">Contact
                        Us</a>
                </div>
            </div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-50 rounded-full blur-3xl opacity-50 -z-10">
            </div>
        </section>

        <section id="about" class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                    <div class="aspect-[4/5]">
                        <img src="/images/landing-page.jpg" alt="Fenix Team Office"
                            class="w-full h-full rounded-2xl object-cover shadow-inner">
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">About Fenix EO</h2>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Established with a passion for celebration, Fenix Event Organizer has been the architect
                            behind countless successful weddings, corporate gatherings, and private parties.
                        </p>
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            We believe that luxury lies in the details. Our minimalist approach ensures that the focus
                            remains on you and your guests, creating an atmosphere that is both elegant and warmly
                            inviting.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="packages" class="py-24 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Signature Packages</h2>
                    <p class="text-gray-500">Choose a package that fits your needs, or contact us to build a custom arrangement tailored just for you.</p>
                </div>

                <div x-data="{
                    scrollNext() { this.$refs.slider.scrollBy({ left: 350, behavior: 'smooth' }); },
                    scrollPrev() { this.$refs.slider.scrollBy({ left: -350, behavior: 'smooth' }); }
                }" class="relative">

                    @if(count($packages) > 3)
                    <button @click="scrollPrev()" class="hidden md:flex absolute top-1/2 -left-4 -translate-y-1/2 z-10 w-12 h-12 bg-white border border-gray-200 rounded-full items-center justify-center shadow-lg hover:bg-gray-50 text-gray-800 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="scrollNext()" class="hidden md:flex absolute top-1/2 -right-4 -translate-y-1/2 z-10 w-12 h-12 bg-white border border-gray-200 rounded-full items-center justify-center shadow-lg hover:bg-gray-50 text-gray-800 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    @endif

                    <div x-ref="slider" class="flex overflow-x-auto snap-x snap-mandatory gap-8 pb-12 pt-4 no-scrollbar">
                        @foreach($packages as $package)
                            @if($package->name === 'Full Day Wedding')
                                <div class="snap-center shrink-0 w-full md:w-[calc(33.333%-1.33rem)] border-2 border-gray-900 rounded-2xl p-8 shadow-lg bg-gray-900 text-white transform md:-translate-y-4 cursor-pointer relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                                        Most Popular
                                    </div>
                                    <h3 class="text-xl font-bold mb-2">{{ $package->name }}</h3>
                                    <p class="text-gray-400 text-sm mb-6 h-10">Comprehensive planning for your unforgettable grand wedding.</p>
                                    <div class="mb-6">
                                        <span class="text-3xl font-extrabold">Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                                    </div>
                                    <ul class="space-y-3 mb-8 text-sm text-gray-300 min-h-[280px]">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Morning Session Set
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tea Pai Properties
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 50 Balloons & Dove
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Meeting
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> All Vendor Coordinating
                                        </li>
                                    </ul>
                                    <button type="button" onclick="promptLogin()" class="w-full py-3 px-4 bg-white hover:bg-gray-100 text-gray-900 font-bold rounded-md transition-colors">Plan This Event</button>
                                </div>
                            @else
                                <div class="snap-center shrink-0 w-full md:w-[calc(33.333%-1.33rem)] border border-gray-100 rounded-2xl p-8 hover:shadow-xl transition-shadow duration-300 bg-white group cursor-pointer relative overflow-hidden">
                                    @if($package->name === 'Dreamy Full Day Wedding')
                                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                                        DREAM WEDDING
                                    </div>
                                    @endif
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>

                                    @if($package->name === 'Dreamy Full Day Wedding')
                                        <p class="text-gray-500 text-sm mb-6 h-10">Comprehensive planning for your unforgettable magical grand wedding.</p>
                                    @else
                                        <p class="text-gray-500 text-sm mb-6 h-10">Tailored arrangement for your special day.</p>
                                    @endif

                                    <div class="mb-6">
                                        <span class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                                    </div>

                                    <ul class="space-y-3 mb-8 text-sm text-gray-600 min-h-[280px]">
                                        @if($package->name === 'Holy Matrimony')
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Morning Session Set
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Meeting
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> All Vendor Coordinating
                                            </li>
                                        @elseif($package->name === 'Half Day Wedding')
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Morning Session Set
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tea Pai Properties
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Meeting
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> All Vendor Coordinating
                                            </li>
                                        @elseif($package->name === 'Dreamy Full Day Wedding')
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Morning Session Set
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tea Pai Properties
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 50 Balloons & Dove
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 1 Wedding Hand Bouquet
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 1 Corsage For Groom
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 10 Corsages For Family
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Meeting
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> All Vendor Coordinating
                                            </li>
                                        @else
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Essential Vendor Management
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> On-the-day Coordination
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Customized Planning
                                            </li>
                                        @endif
                                    </ul>
                                    <button type="button" onclick="promptLogin()" class="w-full py-3 px-4 bg-gray-50 hover:bg-gray-900 hover:text-white text-gray-900 font-semibold rounded-md transition-colors border border-gray-200 group-hover:border-gray-900">Plan This Event</button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>
        </section>

        <section id="portfolio" class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Our Masterpieces</h2>
                        <p class="text-gray-500">A glimpse into the magical moments we've created.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($portfolios as $portfolio)
                        <div
                            class="relative group overflow-hidden rounded-xl aspect-square shadow-sm cursor-pointer bg-gray-200">
                            <img src="{{ asset('storage/' . $portfolio->image_path) }}" alt="{{ $portfolio->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                                <h3 class="text-xl font-bold text-white mb-1">{{ $portfolio->title }}</h3>
                                @if($portfolio->description && $portfolio->description !== '-')
                                    <p class="text-sm text-gray-300 line-clamp-2">{{ $portfolio->description }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full text-center text-gray-500 py-12 bg-gray-100 rounded-xl border border-dashed border-gray-300">
                            Belum ada portofolio yang ditambahkan.
                        </div>
                    @endforelse
                </div>

            </div>
        </section>

        <section id="contact" class="py-24 bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Let's Talk About Your Event</h2>
                        <p class="text-gray-600 mb-10">Visit our office for a consultation or reach out to us through
                            our social channels. We're ready to listen to your ideas.</p>

                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100 mt-1">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-bold text-gray-900">Office Location</h4>
                                    <p class="text-gray-600 mt-1 text-sm">Ruko Royal Dharmahusada CC<br>Surabaya,Indonesia
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100 mt-1">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-bold text-gray-900">WhatsApp</h4>
                                    <p class="text-gray-600 mt-1 text-sm">+62 858-5578-8100</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100 mt-1">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-bold text-gray-900">Instagram</h4>
                                    <p class="text-gray-600 mt-1 text-sm">@fenixorganizer</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-200 rounded-2xl h-[400px] flex items-center justify-center text-gray-500 font-medium">
                        [ Google Maps Embed Placeholder ]
                    </div>
                </div>
            </div>
        </section>

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function promptLogin() {
            Swal.fire({
                title: 'Do you already have an account?',
                text: 'You need an account to plan an event.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Log In',
                cancelButtonText: 'No, I need one',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Contact Us',
                        text: 'To create a new event and get an account, please contact Michael H via WhatsApp at +6285855788100.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>
</body>

</html>
