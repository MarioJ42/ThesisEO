@extends('layouts.guest_rsvp')

@section('content')
<div class="max-w-xl mx-auto p-6" x-data="{ scanning: true }">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">QR Code Scanner</h2>
        <p class="text-sm text-gray-500">Scan the guest's digital ticket for check-in</p>
    </div>

    <div class="relative">
        <div id="reader" class="rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white bg-black aspect-square"></div>
        <div class="absolute inset-0 pointer-events-none border-[1.5rem] border-black/10 rounded-[2.5rem]"></div>
    </div>

    <div class="mt-12 flex flex-col items-center gap-6">
        <div class="flex items-center gap-2 text-emerald-600 font-bold text-sm animate-pulse">
            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
            System Active & Ready
        </div>
        <a href="{{ route('crew.rsvp.hub', $event->id) }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-gray-700 font-bold rounded-2xl shadow-sm border border-gray-100 hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Hub
        </a>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText) {
        html5QrcodeScanner.clear();
        window.location.href = `{{ route('crew.rsvp.checkin.form', $event->id) }}?token=${decodedText}`;
    }

    let config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        rememberLastUsedCamera: true,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
    };

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", config, false
    );
    html5QrcodeScanner.render(onScanSuccess);
</script>

<style>
    #reader__dashboard_section_csr button {
        background-color: #2563eb !important;
        color: white !important;
        border: none !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        font-weight: bold !important;
        margin-top: 10px !important;
        cursor: pointer !important;
    }
    #reader__dashboard_section_csr span {
        color: white !important;
    }
</style>
@endsection
