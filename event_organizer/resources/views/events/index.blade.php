@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    isModalOpen: false,
    activeTab: 'all',

    isEditModalOpen: false,
    editForm: { id: '', title: '', pl_id: '', package_name: '', event_date: '' },
    openEditModal(id, title, pl_id, package_name, event_date) {
        this.editForm.id = id;
        this.editForm.title = title;
        this.editForm.pl_id = pl_id || '';
        this.editForm.package_name = package_name;
        this.editForm.event_date = event_date;
        this.isEditModalOpen = true;
    }
}">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Event Arrangement</h2>
        <button @click="isModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors shadow-sm">
            Add New Event
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

    @if($user->role === 'owner')
    <div class="bg-white rounded-t-lg shadow-sm border-b border-gray-200">
        <nav class="flex space-x-8 px-6 overflow-x-auto" aria-label="Tabs">
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                All Events
            </button>
            <button @click="activeTab = 'mine'" :class="activeTab === 'mine' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                My Events
            </button>
            <button @click="activeTab = 'pl'" :class="activeTab === 'pl' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                PL Events
            </button>
            <button @click="activeTab = 'unassigned'" :class="activeTab === 'unassigned' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                Drafts / Unassigned
                <span class="bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs">{{ $events->whereNull('pl_id')->count() }}</span>
            </button>
        </nav>
    </div>
    @endif

    <div class="bg-white {{ $user->role === 'owner' ? 'rounded-b-lg border-t-0' : 'rounded-lg' }} shadow-sm border border-gray-200 p-6">
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
                <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search by Event Name or Project Leader..." class="border-gray-300 rounded-md text-sm px-4 py-2 w-full sm:w-64 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
            </div>
        </div>

        <div id="table-container">
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="min-w-full w-full whitespace-nowrap">
                    <thead class="bg-gray-100/75">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Client Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Event Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Package</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Project Leader</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($events as $event)
                        @php
                            $rowCategory = 'unassigned';
                            if ($event->pl_id) {
                                $rowCategory = ($event->pl->role === 'owner') ? 'mine' : 'pl';
                            }
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors"
                            x-show="activeTab === 'all' || activeTab === '{{ $rowCategory }}'"
                            @if($user->role !== 'owner') style="display: table-row;" @endif>

                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $event->client->name ?? 'Unknown Client' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $event->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($event->package)
                                    {{ $event->package->name }}
                                @else
                                    <span class="text-gray-500 italic">Custom</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $event->event_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                @if($event->pl)
                                    {{ $event->pl->name }}
                                @else
                                    <span class="text-gray-400 italic text-xs">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($event->status === 'draft') bg-gray-100 text-gray-700
                                    @elseif($event->status === 'planning') bg-blue-100 text-blue-700
                                    @elseif($event->status === 'ongoing') bg-yellow-100 text-yellow-700
                                    @elseif($event->status === 'completed') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif capitalize">
                                    {{ $event->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEditModal({{ $event->id }}, '{{ addslashes($event->title) }}', '{{ $event->pl_id }}', '{{ $event->package ? addslashes($event->package->name) : 'Custom' }}', '{{ $event->event_date->format('d M Y') }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold transition-colors">Quick Edit</button>
                                    <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold transition-colors">Manage</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">No events found. Click "Add New Event" to create one.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-4">
                <div class="text-sm text-gray-600">
                    Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} entries
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ $events->previousPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors {{ $events->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">Previous</a>
                    <a href="{{ $events->nextPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors {{ !$events->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">Next</a>
                </div>
            </div>
        </div>
    </div>

    <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto" style="display: none;" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl my-8" @click.away="isModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Add New Event</h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route(Auth::user()->role . '.events.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Client Name <span class="text-red-500">*</span></label>
                        <select name="client_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Event Name <span class="text-red-500">*</span></label>
                        <input type="text" name="title" placeholder="e.g. Wedding of Kezia & Clarin" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Event Date <span class="text-red-500">*</span></label>
                        <input type="date" name="event_date" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Wedding Package <span class="text-red-500">*</span></label>
                        <select name="package_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">-- Select Package --</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Event</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto" style="display: none;" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl my-8" @click.away="isEditModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Quick Edit Event</h3>
                <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="'{{ url('/' . Auth::user()->role . '/events') }}/' + editForm.id" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Event Name</label>
                        <input type="text" x-model="editForm.title" readonly class="bg-gray-200 border border-gray-300 text-gray-600 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Package</label>
                            <input type="text" x-model="editForm.package_name" readonly class="bg-gray-200 border border-gray-300 text-gray-600 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Date</label>
                            <input type="text" x-model="editForm.event_date" readonly class="bg-gray-200 border border-gray-300 text-gray-600 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Assign Project Leader</label>
                        <select name="pl_id" x-model="editForm.pl_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">-- Unassigned (Draft) --</option>
                            @foreach($projectLeaders as $pl)
                                <option value="{{ $pl->id }}">{{ $pl->name }} ({{ strtoupper($pl->role) }})</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500">Assigning a PL to a drafted event will automatically change its status to <b>Planning</b>.</p>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update Event</button>
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
