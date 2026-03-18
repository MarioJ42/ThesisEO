@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    activeTab: '{{ session('active_tab', 'contacts') }}',

    isContactModalOpen: false,
    isEditContactModalOpen: false,
    contactForm: { id: '', name: '', phone: '', is_primary: '0', is_active: '1' },

    openEditContactModal(id, name, phone, is_primary, is_active) {
        this.contactForm.id = id;
        this.contactForm.name = name;
        this.contactForm.phone = phone;
        this.contactForm.is_primary = is_primary.toString();
        this.contactForm.is_active = is_active.toString();
        this.isEditContactModalOpen = true;
    },

    isPackageModalOpen: false,
    isEditPackageModalOpen: false,
    packageForm: { id: '', name: '', min_price: '', max_price: '', details: '' },

    openEditPackageModal(id, name, min_price, max_price, details) {
        this.packageForm.id = id;
        this.packageForm.name = name;
        this.packageForm.min_price = min_price;
        this.packageForm.max_price = max_price;
        this.packageForm.details = details;
        this.isEditPackageModalOpen = true;
    }
}">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.vendors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $vendor->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $vendor->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ $vendor->categories->pluck('name')->join(', ') ?: 'No Category' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
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
            <button @click="activeTab = 'contacts'" :class="activeTab === 'contacts' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Contact Persons (PIC)
            </button>
            <button @click="activeTab = 'packages'" :class="activeTab === 'packages' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Packages & Pricelist
            </button>
            <button @click="activeTab = 'portfolios'" :class="activeTab === 'portfolios' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Portfolios
            </button>
        </nav>
    </div>

    <div class="bg-white rounded-b-lg shadow-sm border border-t-0 border-gray-200 p-6 min-h-[400px]">

        <div x-show="activeTab === 'contacts'" x-cloak>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">PIC Management</h3>
                <button @click="isContactModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-semibold transition-colors">+ Add PIC</button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($vendor->contacts as $contact)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contact->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contact->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if($contact->is_primary)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">Primary</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Alternative</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $contact->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $contact->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEditContactModal({{ $contact->id }}, '{{ addslashes($contact->name) }}', '{{ addslashes($contact->phone) }}', {{ $contact->is_primary ? '1' : '0' }}, {{ $contact->is_active ? '1' : '0' }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded">Edit</button>

                                    <form id="delete-form-{{ $contact->id }}" action="{{ route('owner.vendors.contacts.destroy', $contact->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $contact->id }}')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2 py-1 rounded">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No PIC contacts have been added yet. Please click the "+ Add PIC" button.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'packages'" x-cloak>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Packages & Pricelist</h3>
                <button @click="isPackageModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-semibold transition-colors">+ Add Package</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($vendor->packages as $package)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-md font-bold text-gray-900">{{ $package->name }}</h4>
                        <div class="flex gap-2">
                            <button @click="openEditPackageModal({{ $package->id }}, '{{ addslashes($package->name) }}', '{{ $package->min_price }}', '{{ $package->max_price }}', '{{ addslashes($package->details) }}')" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-1.5 rounded transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form id="delete-package-form-{{ $package->id }}" action="{{ route('owner.vendors.packages.destroy', $package->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDeletePackage('{{ $package->id }}')" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-1.5 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-emerald-600 mb-3">
                        Rp {{ number_format($package->min_price, 0, ',', '.') }} - Rp {{ number_format($package->max_price, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-600 whitespace-pre-wrap">{{ $package->details ?: 'No details provided.' }}</div>
                </div>
                @empty
                <div class="col-span-full border-2 border-dashed border-gray-200 rounded-lg p-10 text-center text-gray-500">
                    There are no packages/pricelists added yet. Please click the "+ Add Package" button.
                </div>
                @endforelse
            </div>
        </div>

        <div x-show="activeTab === 'portfolios'" x-cloak>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Portfolios</h3>
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-semibold transition-colors">+ Upload Photo</button>
            </div>
            <div class="border-2 border-dashed border-gray-200 rounded-lg p-10 text-center text-gray-500">
                Fitur Portfolios sedang dalam pengembangan...
            </div>
        </div>

    </div>

    <div x-show="isContactModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl" @click.away="isContactModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Add New PIC</h3>
                <button @click="isContactModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('owner.vendors.contacts.store', $vendor->id) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">PIC Name</label>
                        <input type="text" name="name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Phone Number</label>
                        <input type="text" name="phone" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                            <select name="is_primary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full py-2.5 pl-3 pr-10">
                                <option value="0">Alternative</option>
                                <option value="1">Primary Contact</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                            <select name="is_active" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full py-2.5 pl-3 pr-10">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isContactModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save PIC</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="isEditContactModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl" @click.away="isEditContactModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Edit PIC</h3>
                <button @click="isEditContactModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form :action="'{{ url('/owner/vendors/contacts') }}/' + contactForm.id" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">PIC Name</label>
                        <input type="text" name="name" x-model="contactForm.name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Phone Number</label>
                        <input type="text" name="phone" x-model="contactForm.phone" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                            <select name="is_primary" x-model="contactForm.is_primary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full py-2.5 pl-3 pr-10">
                                <option value="0">Alternative</option>
                                <option value="1">Primary Contact</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                            <select name="is_active" x-model="contactForm.is_active" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full py-2.5 pl-3 pr-10">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isEditContactModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update PIC</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="isPackageModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-cloak>
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl" @click.away="isPackageModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Add New Package</h3>
                <button @click="isPackageModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('owner.vendors.packages.store', $vendor->id) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Package Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Min Price (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="min_price" required min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Max Price (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="max_price" required min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Specifications / Details</label>
                        <textarea name="details" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"></textarea>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isPackageModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="isEditPackageModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-cloak>
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl" @click.away="isEditPackageModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Edit Package</h3>
                <button @click="isEditPackageModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form :action="'{{ url('/owner/vendors/packages') }}/' + packageForm.id" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Package Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="packageForm.name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Min Price (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="min_price" x-model="packageForm.min_price" required min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Max Price (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="max_price" x-model="packageForm.max_price" required min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Specifications / Details</label>
                        <textarea name="details" x-model="packageForm.details" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"></textarea>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isEditPackageModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(contactId) {
        Swal.fire({
            title: 'Delete PIC Contact?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            scrollbarPadding: false,
            heightAuto: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + contactId).submit();
            }
        });
    }

    function confirmDeletePackage(packageId) {
        Swal.fire({
            title: 'Delete Package?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            scrollbarPadding: false,
            heightAuto: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-package-form-' + packageId).submit();
            }
        });
    }
</script>
@endsection
