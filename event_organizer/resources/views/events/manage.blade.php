@extends('layouts.dashboard')

@section('content')
    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- Logika Pengecekan Fenix EO Digital Guestbook --}}
    @php
        $hasFenixGuestbook = $verifiedSlots->contains(function ($slot) {
            return stripos($slot->category_name, 'Guest Book') !== false &&
                   stripos($slot->vendor_name, 'Fenix EO') !== false;
        });
    @endphp

    <div class="max-w-7xl mx-auto" x-data="{
        isModalOpen: false,
        activeTab: localStorage.getItem('manageEventTab') || 'overview',
        isEditModalOpen: false,
        editForm: { id: '', title: '', pl_id: '', package_name: '', event_date: '', status: '' },
        openEditModal(id, title, pl_id, package_name, event_date, status) {
            this.editForm.id = id;
            this.editForm.title = title;
            this.editForm.pl_id = pl_id || '';
            this.editForm.package_name = package_name;
            this.editForm.event_date = event_date;
            this.editForm.status = status;
            this.isEditModalOpen = true;
        },
        isPriceModalOpen: false,
        priceForm: { slot_id: '', vendor_name: '', package_name: '', base_cost: 0, net_price: 0, deal_price: 0 },
        openPriceModal(slot_id, vendor_name, package_name, base_cost, net_price, deal_price) {
            this.priceForm.slot_id = slot_id;
            this.priceForm.vendor_name = vendor_name;
            this.priceForm.package_name = package_name;
            this.priceForm.base_cost = base_cost;
            this.priceForm.net_price = net_price;
            this.priceForm.deal_price = deal_price;
            this.isPriceModalOpen = true;
        },
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
    }" x-init="
        if (activeTab === 'rsvp' && !{{ $hasFenixGuestbook ? 'true' : 'false' }}) {
            activeTab = 'overview';
        }
        $watch('activeTab', value => localStorage.setItem('manageEventTab', value))
    ">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Client: <span class="font-semibold text-gray-700">{{ $event->client->name }}</span> | Date: <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                @if($hasFenixGuestbook)
                    <a href="{{ route($user->role . '.events.analytics', $event->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-bold transition-colors shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Analytics
                    </a>
                @endif

                <a href="{{ route($user->role . '.events.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-bold transition-colors">
                    Back to Events
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-t-lg shadow-sm border-b border-gray-200">
            <nav class="flex space-x-8 px-6 overflow-x-auto" aria-label="Tabs">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Overview
                </button>
                <button @click="activeTab = 'planning'" :class="activeTab === 'planning' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Vendor Planning
                </button>
                <button @click="activeTab = 'verification'" :class="activeTab === 'verification' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                    Vendor Verification
                </button>

                {{-- Tab RSVP hanya muncul jika Fenix EO adalah vendor Guest Book --}}
                @if($hasFenixGuestbook)
                <button @click="activeTab = 'rsvp'" :class="activeTab === 'rsvp' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                    RSVP & Guestbook
                </button>
                @endif

                <button @click="activeTab = 'crew'" :class="activeTab === 'crew' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Crew Management
                </button>
            </nav>
        </div>

        <div class="bg-white rounded-b-lg shadow-sm border border-gray-200 border-t-0 p-6">
            <div x-show="activeTab === 'overview'" x-cloak>
                @include('events.partials.tab-overview')
            </div>

            <div x-show="activeTab === 'planning'" x-cloak>
                @include('events.partials.tab-planning')
            </div>

            <div x-show="activeTab === 'verification'" x-cloak>
                @include('events.partials.tab-verification')
            </div>

            {{-- Konten RSVP hanya di-render jika Fenix EO adalah vendor Guest Book --}}
            @if($hasFenixGuestbook)
            <div x-show="activeTab === 'rsvp'" x-cloak>
                @include('events.partials.tab-rsvp')
            </div>
            @endif

            <div x-show="activeTab === 'crew'" x-cloak>
                @include('events.partials.tab-crew')
            </div>
        </div>

        @include('events.partials.modals')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let scrollpos = localStorage.getItem('manageEventScroll');
                if (scrollpos) {
                    window.scrollTo(0, parseInt(scrollpos));
                    localStorage.removeItem('manageEventScroll');
                }
            });

            window.addEventListener("beforeunload", function() {
                localStorage.setItem('manageEventScroll', window.scrollY);
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

            function confirmBlastWA() {
                Swal.fire({
                    title: 'Blast WA Reminders?',
                    text: "Reminder messages will be sent to all guests with 'Attending' status. This process will run in the background (Background Job).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Blast Now!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                        cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('blastWaForm').submit();

                        Swal.fire({
                            title: 'Processing...',
                            text: 'Inserting data into the queue...',
                            allowOutsideClick: false,
                            heightAuto: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                });
            }
        </script>
    </div>
@endsection
