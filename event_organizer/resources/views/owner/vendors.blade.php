@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    isModalOpen: false,
    isEditModalOpen: false,
    editForm: { id: '', name: '', categories: [], is_active: '1', address: '', instagram: '' },

    openEditModal(id, name, categoryIds, is_active, address, instagram) {
        this.editForm.id = id;
        this.editForm.name = name;
        this.editForm.categories = categoryIds.map(String);
        this.editForm.is_active = is_active.toString();
        this.editForm.address = address || '';
        this.editForm.instagram = instagram || '';
        this.isEditModalOpen = true;
    }
}">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Master Vendor</h2>
        <button @click="isModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors shadow-sm">
            Add New Vendor
        </button>
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

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4 w-full">
            <div class="flex items-center text-sm text-gray-600">
                <span class="mr-2">Show entries:</span>
                <select id="perPageSelect" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5 pl-3 pr-8 shadow-sm">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <div>
                <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search by name or category..." class="border-gray-300 rounded-md text-sm px-4 py-2 w-full sm:w-64 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
            </div>
        </div>

        <div id="table-container">
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="min-w-full w-full whitespace-nowrap">
                    <thead class="bg-gray-100/75">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Vendor Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($vendors as $vendor)
                        @php
                            $catString = $vendor->categories->pluck('name')->join(', ');
                            $catIds = $vendor->categories->pluck('id')->toJson();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $vendor->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $catString ?: '-' }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $vendor->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEditModal({{ $vendor->id }}, '{{ addslashes($vendor->name) }}', {{ $catIds }}, {{ $vendor->is_active ? '1' : '0' }}, '{{ addslashes($vendor->address) }}', '{{ addslashes($vendor->instagram) }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold transition-colors">Quick Edit</button>

                                    <a href="{{ route('owner.vendors.manage', $vendor->id) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold transition-colors">Manage</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">Data vendor tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-4">
                <div class="text-sm text-gray-600">
                    Showing {{ $vendors->firstItem() ?? 0 }} to {{ $vendors->lastItem() ?? 0 }} of {{ $vendors->total() }} entries
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ $vendors->previousPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors {{ $vendors->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">Previous</a>
                    <a href="{{ $vendors->nextPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors {{ !$vendors->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">Next</a>
                </div>
            </div>
        </div>
    </div>

    <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto" style="display: none;" x-cloak>
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl my-8" @click.away="isModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Add New Vendor</h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('owner.vendors.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Vendor Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Categories <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 p-3 bg-gray-50 border border-gray-200 rounded-lg max-h-40 overflow-y-auto">
                            @foreach($masterCategories as $cat)
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 h-4 w-4">
                                <span class="ml-2 text-sm text-gray-700 truncate">{{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-200">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Instagram Username</label>
                        <div class="flex mb-4">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                                @
                            </span>
                            <input type="text" name="instagram" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5" placeholder="username_ig">
                        </div>

                        <label class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                        <textarea name="address" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Vendor address (Optional)"></textarea>
                    </div>
                    <p class="text-xs text-gray-500 italic mt-2">* Contacts, Portfolios, and Packages can be added later via the "Manage" button.</p>
                </div>

                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Vendor</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto" style="display: none;" x-cloak>
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl my-8" @click.away="isEditModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Quick Edit Vendor</h3>
                <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="'{{ url('/owner/vendors') }}/' + editForm.id" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Vendor Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="editForm.name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Categories <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 p-3 bg-gray-50 border border-gray-200 rounded-lg max-h-40 overflow-y-auto">
                            @foreach($masterCategories as $cat)
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" x-model="editForm.categories" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 h-4 w-4">
                                <span class="ml-2 text-sm text-gray-700 truncate">{{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-200">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Instagram Username</label>
                        <div class="flex mb-4">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                                @
                            </span>
                            <input type="text" name="instagram" x-model="editForm.instagram" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5" placeholder="username_ig">
                        </div>

                        <label class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                        <textarea name="address" x-model="editForm.address" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Vendor address (Optional)"></textarea>
                    </div>

                    <div class="pt-2 border-t border-gray-200">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Vendor Status</label>
                        <select name="is_active" x-model="editForm.is_active" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="1">Active (Available for Events)</option>
                            <option value="0">Inactive (Hidden/Suspended)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const perPageSelect = document.getElementById('perPageSelect');
        const tableContainer = document.getElementById('table-container');

        function fetchUpdatedData() {
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput.value);
            url.searchParams.set('per_page', perPageSelect.value);

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    tableContainer.innerHTML = doc.getElementById('table-container').innerHTML;
                    window.history.pushState({}, '', url);
                });
        }

        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(fetchUpdatedData, 300);
        });

        perPageSelect.addEventListener('change', fetchUpdatedData);
    });
</script>
@endsection
