@extends('layouts.dashboard')

@section('content')
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="max-w-7xl mx-auto" x-data="{
        isModalOpen: false,
        activeTab: localStorage.getItem('manageEventTab') || 'overview',
        isEditModalOpen: false,
        editForm: { id: '', title: '', pl_id: '', package_name: '', event_date: '', status: '' },
        openEditModal(id, title, pl_id, package_name, event_date, status) { /* ... isi fungsi sama ... */ },

        isPriceModalOpen: false,
        priceForm: { slot_id: '', vendor_name: '', package_name: '', base_cost: 0, net_price: 0, deal_price: 0 },
        openPriceModal(slot_id, vendor_name, package_name, base_cost, net_price, deal_price) { /* ... isi fungsi sama ... */ },

        isGuestModalOpen: false,
        isEditGuestModalOpen: false,
        guestSearch: '',
        guestForm: { id: '', name: '', phone_number: '', pax_invited: 1, table_name: '', status: 'attending' },
        openEditGuestModal(id, name, phone, pax, table, status) { /* ... isi fungsi sama ... */ }
    }" x-init="$watch('activeTab', value => localStorage.setItem('manageEventTab', value))">

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

            <div x-show="activeTab === 'rsvp'" x-cloak>
                @include('events.partials.tab-rsvp')
            </div>

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
