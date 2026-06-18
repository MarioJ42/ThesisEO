<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix Event Organizer</title>
    <link rel="icon" href="/images/logo-fenix1.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased selection:bg-gray-900 selection:text-white"
      x-data="paymentHandler('{{ route('client.events.pay', $event->id) }}', '{{ csrf_token() }}')">

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

    <main class="pt-32 pb-24 min-h-screen relative">
        <div x-show="isLoading" class="fixed inset-0 z-[100] bg-white/60 backdrop-blur-sm flex flex-col items-center justify-center" x-cloak>
            <div class="w-12 h-12 border-4 border-gray-200 border-t-gray-900 rounded-full animate-spin mb-4"></div>
            <p class="text-sm font-bold text-gray-900 tracking-wider uppercase">Processing Payment...</p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <div class="flex-1"></div>
                <div class="flex-1"></div>
                <div class="flex-1 flex justify-end">
                    <a href="{{ route('client.events.manage', $event->id) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Event Arrangement
                    </a>
                </div>
            </div>

            <div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden mb-12">
                <div class="absolute top-0 left-0 w-full h-2 bg-gray-900"></div>

                <div class="text-center mt-2">
                    <span class="inline-block px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full mb-4 bg-blue-50 text-blue-600">
                        Billing & Payment
                    </span>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-3">{{ $event->title }}</h1>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-6 text-gray-500 font-medium text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
                        </span>
                        <span class="hidden sm:block text-gray-300">•</span>
                        <span class="flex items-center gap-2 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Verified Vendors Only
                        </span>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)]">
                        <p class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-2">Total Amount</p>
                        <h2 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h2>
                    </div>
                    <div class="bg-emerald-50 rounded-3xl p-6 border border-emerald-100 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)]">
                        <p class="text-xs font-extrabold text-emerald-600 uppercase tracking-widest mb-2">Total Paid</p>
                        <h2 class="text-2xl font-bold text-emerald-700">Rp {{ number_format($successfulPayments, 0, ',', '.') }}</h2>
                    </div>
                    <div class="bg-blue-50 rounded-3xl p-6 border border-blue-100 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)]">
                        <p class="text-xs font-extrabold text-blue-600 uppercase tracking-widest mb-2">Remaining Balance</p>
                        <h2 class="text-2xl font-bold text-blue-700">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</h2>
                    </div>
                </div>

                @php
                    $dpAmount = $totalPrice * 0.5;
                    $termin2Amount = $totalPrice * 0.2;
                    $termin3Amount = $totalPrice * 0.1;

                    $hasPaidDp = $successfulPayments >= ($dpAmount - 1);
                    $hasPaidT2 = $successfulPayments >= ($dpAmount + $termin2Amount - 1);
                    $hasPaidT3 = $successfulPayments >= ($dpAmount + $termin2Amount + $termin3Amount - 1);
                    $isFullyPaid = $remainingBalance <= 0;

                    $pendingPayment = $payments->where('status', 'pending')->first();
                @endphp

                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-12 overflow-hidden">
                    <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Payment Action</h3>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Select your preferred payment milestone to continue.</p>
                        </div>
                    </div>

                    <div class="p-8 bg-gray-50/50">
                        @if($isFullyPaid)
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">All Payments Settled!</h3>
                                <p class="text-gray-500 text-sm">Thank you. Your event is fully funded and ready to go.</p>
                            </div>
                        @elseif($pendingPayment)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 animate-pulse"></span>
                                        <h4 class="font-bold text-yellow-800">Pending Payment Detected</h4>
                                    </div>
                                    <p class="text-sm text-yellow-700 font-medium">You have an unfinished payment for <strong>{{ strtoupper(str_replace('_', ' ', $pendingPayment->payment_type)) }} (Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }})</strong>.</p>
                                </div>
                                <button @click="pay('{{ $pendingPayment->payment_type }}', {{ $pendingPayment->amount }})" class="w-full md:w-auto px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold text-sm rounded-xl shadow-sm transition-colors whitespace-nowrap">
                                    Continue Payment
                                </button>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                @if(!$hasPaidDp)
                                    <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:border-gray-900 transition-colors shadow-sm">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-extrabold text-gray-900 mb-1">Down Payment (50%)</h4>
                                                <p class="text-xs text-gray-500 font-medium">Required to secure vendors.</p>
                                            </div>
                                            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded">REQUIRED</span>
                                        </div>
                                        <h2 class="text-xl font-bold text-gray-900 mb-5">Rp {{ number_format($dpAmount, 0, ',', '.') }}</h2>
                                        <button @click="pay('dp', {{ $dpAmount }})" class="w-full py-3 bg-gray-900 hover:bg-black text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                                            Pay Down Payment
                                        </button>
                                    </div>
                                @else
                                    @if(!$hasPaidT2)
                                        <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:border-gray-900 transition-colors shadow-sm flex flex-col h-full">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h4 class="font-extrabold text-gray-900 mb-1">Termin 2 (20%)</h4>
                                                    <p class="text-xs text-gray-500 font-medium">Optional milestone payment.</p>
                                                </div>
                                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded">OPTIONAL</span>
                                            </div>
                                            <h2 class="text-xl font-bold text-gray-900 mb-5">Rp {{ number_format($termin2Amount, 0, ',', '.') }}</h2>
                                            <button @click="pay('termin_2', {{ $termin2Amount }})" class="w-full mt-auto py-3 bg-white border-2 border-gray-200 hover:border-gray-900 text-gray-900 font-bold text-sm rounded-xl transition-all">
                                                Pay Termin 2
                                            </button>
                                        </div>
                                    @elseif(!$hasPaidT3)
                                        <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:border-gray-900 transition-colors shadow-sm flex flex-col h-full">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h4 class="font-extrabold text-gray-900 mb-1">Termin 3 (10%)</h4>
                                                    <p class="text-xs text-gray-500 font-medium">Optional milestone payment.</p>
                                                </div>
                                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded">OPTIONAL</span>
                                            </div>
                                            <h2 class="text-xl font-bold text-gray-900 mb-5">Rp {{ number_format($termin3Amount, 0, ',', '.') }}</h2>
                                            <button @click="pay('termin_3', {{ $termin3Amount }})" class="w-full mt-auto py-3 bg-white border-2 border-gray-200 hover:border-gray-900 text-gray-900 font-bold text-sm rounded-xl transition-all">
                                                Pay Termin 3
                                            </button>
                                        </div>
                                    @endif

                                    <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:border-gray-900 transition-colors shadow-sm flex flex-col h-full">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-extrabold text-gray-900 mb-1">Full Settlement</h4>
                                                <p class="text-xs text-gray-500 font-medium">Pay all remaining balance.</p>
                                            </div>
                                            <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded">RECOMMENDED</span>
                                        </div>
                                        <h2 class="text-xl font-bold text-gray-900 mb-5">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</h2>
                                        <button @click="pay('settlement', {{ $remainingBalance }})" class="w-full mt-auto py-3 bg-gray-900 hover:bg-black text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                                            Pay Settlement
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-12">
                    <div class="flex items-center gap-4 mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Payment History</h3>
                        <div class="flex-grow h-px bg-gray-200"></div>
                    </div>

                    @if($payments->count() > 0)
                        <div class="space-y-4">
                            @foreach($payments as $payment)
                                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                                            @if($payment->status == 'success') bg-emerald-50 text-emerald-500
                                            @elseif($payment->status == 'pending') bg-yellow-50 text-yellow-500
                                            @else bg-red-50 text-red-500 @endif">

                                            @if($payment->status == 'success')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @elseif($payment->status == 'pending')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-extrabold text-gray-900 uppercase tracking-wide text-sm mb-1">{{ str_replace('_', ' ', $payment->payment_type) }}</h4>
                                            <p class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($payment->created_at)->format('d F Y, H:i') }} • ID: {{ $payment->midtrans_order_id ?? 'Manual' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <h3 class="text-lg font-bold text-gray-900 mb-1">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h3>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest
                                            @if($payment->status == 'success') text-emerald-600
                                            @elseif($payment->status == 'pending') text-yellow-600
                                            @else text-red-600 @endif">
                                            {{ $payment->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-3xl border border-dashed border-gray-300 p-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">No Transaction History</h4>
                            <p class="text-sm text-gray-500 font-medium">Your payment records will appear here.</p>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Invoice Breakdown</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                            <div>
                                <p class="font-bold text-gray-800">Base Package</p>
                                <p class="text-xs text-gray-500">{{ $billingDetails['basePackageName'] }}</p>
                            </div>
                            <p class="font-bold text-gray-900">Rp {{ number_format($billingDetails['basePackagePrice'], 0, ',', '.') }}</p>
                        </div>

                        @foreach($billingDetails['additionalItems'] as $item)
                        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                            <div class="flex flex-col">
                               <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-0.5">{{ \Carbon\Carbon::parse($item->verified_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                <p class="font-bold text-gray-800">
                                    {{ $item->category_name }}
                                    <span class="text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded ml-1 uppercase align-middle">{{ $item->type }}</span>
                                </p>
                                <p class="text-xs text-gray-500">{{ $item->vendor_name }}</p>
                            </div>
                            <p class="font-bold text-red-500">+ Rp {{ number_format($item->added_cost, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                        <p class="font-bold text-gray-900 uppercase tracking-wider text-sm">Total Final Amount</p>
                        <p class="text-xl font-black text-gray-900">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentHandler', (routeUrl, csrfToken) => ({
                isLoading: false,

                async pay(type, amount) {
                    this.isLoading = true;

                    try {
                        const response = await fetch(routeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                payment_type: type,
                                amount: amount
                            })
                        });

                        const data = await response.json();

                        if (data.snap_token) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) { window.location.reload(); },
                                onPending: function(result) { window.location.reload(); },
                                onError: function(result) { alert("Payment failed!"); window.location.reload(); },
                                onClose: function() { window.location.reload(); }
                            });
                        } else {
                            console.error("Midtrans Error Response:", data);
                            alert("Error: " + (data.message || data.error || "Gagal mendapatkan token. Cek console Inspect Element!"));
                        }
                    } catch (error) {
                        console.error("Fetch Error:", error);
                        alert('Connection error. Please try again.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }))
        })
    </script>
</body>
</html>
