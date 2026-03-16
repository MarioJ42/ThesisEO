@extends('layouts.dashboard')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{
        loggedInUserId: {{ Auth::id() }},
        isModalOpen: false,
        password: '',
        password_confirmation: '',

        isEditModalOpen: false,
        editForm: { id: '', name: '', email: '', phone: '', role: '', is_active: '1' },
        editPassword: '',
        editPasswordConfirmation: '',

        openEditModal(id, name, email, phone, role, is_active) {
            this.editForm.id = id;
            this.editForm.name = name;
            this.editForm.email = email;
            this.editForm.phone = phone || '';
            this.editForm.role = role.toLowerCase();
            this.editForm.is_active = is_active.toString();
            this.editPassword = '';
            this.editPasswordConfirmation = '';
            this.isEditModalOpen = true;
        }
    }">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Master User</h2>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('owner.users') }}"
                class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4 w-full">
                <div class="flex items-center gap-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="mr-2">Show entries:</span>
                        <select name="per_page" onchange="this.form.submit()"
                            class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5 pl-3 pr-8 shadow-sm">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <button type="button" @click="isModalOpen = true"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors shadow-sm">
                        Add New Account
                    </button>
                </div>
                <div>
                    <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search by name..."
                        class="border-gray-300 rounded-md text-sm px-4 py-2 w-full sm:w-64 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                </div>
            </form>

            <div id="table-container">
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full w-full whitespace-nowrap">
                        <thead class="bg-gray-100/75">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Email</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Role</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 uppercase">{{ $user->role }}</td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <button
                                            @click="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->phone) }}', '{{ $user->role }}', {{ $user->is_active ? '1' : '0' }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-md text-xs font-semibold transition-colors">Edit</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">Data pengguna
                                        tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                        entries
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ $users->previousPageUrl() }}"
                            class="px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors {{ $users->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">Previous</a>
                        <a href="{{ $users->nextPageUrl() }}"
                            class="px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors {{ !$users->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">Next</a>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black bg-opacity-50 p-4"
            style="display: none;">

            <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl" @click.away="isModalOpen = false">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Add New Account</h3>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('owner.users.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                            <input type="text" name="name" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Email Address</label>
                            <input type="email" name="email" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Phone Number (Optional)</label>
                            <input type="text" name="phone"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                            <select name="role" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="owner">Owner</option>
                                <option value="pl">PL (Project Leader)</option>
                                <option value="klien">Klien</option>
                                <option value="crew_rsvp">Crew RSVP</option>
                                <option value="crew_eo">Crew EO</option>
                            </select>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" name="password" x-model="password" required minlength="8"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-4">

                            <label class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                            <input type="password" name="password_confirmation" x-model="password_confirmation" required
                                minlength="8"
                                class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                :class="(password_confirmation.length > 0 && password !== password_confirmation) ?
                                'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300'">

                            <p x-show="password_confirmation.length > 0 && password !== password_confirmation"
                                class="mt-1 text-xs text-red-600" x-cloak>
                                Password and Confirm Password do not match!
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end p-4 border-t gap-2">
                        <button type="button" @click="isModalOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none">Cancel</button>
                        <button type="submit" :disabled="password !== password_confirmation"
                            :class="(password !== password_confirmation) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg focus:outline-none transition-colors">
                            Save Account
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="isEditModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black bg-opacity-50 p-4"
            style="display: none;">

            <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl" @click.away="isEditModalOpen = false">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900"
                        x-text="editForm.id === loggedInUserId ? 'Edit My Profile' : 'Edit User Status'"></h3>
                    <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form :action="'{{ url('/owner/users') }}/' + editForm.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">

                        <div x-show="editForm.id !== loggedInUserId" x-cloak class="space-y-4">
                            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                                <span class="font-medium">Information:</span> You are updating the access status for
                                <strong x-text="editForm.name"></strong>.
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Account Status</label>
                                <select name="is_active" x-model="editForm.is_active"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="1">Active (Can Login)</option>
                                    <option value="0">Inactive (Access Revoked)</option>
                                </select>
                            </div>
                        </div>

                        <div x-show="editForm.id === loggedInUserId" x-cloak class="space-y-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                                <input type="text" name="name" x-model="editForm.name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Email Address</label>
                                <input type="email" name="email" x-model="editForm.email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Phone Number</label>
                                <input type="text" name="phone" x-model="editForm.phone" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                <select name="role" x-model="editForm.role"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="owner">Owner</option>
                                    <option value="pl">PL (Project Leader)</option>
                                    <option value="klien">Klien</option>
                                    <option value="crew_rsvp">Crew RSVP</option>
                                    <option value="crew_eo">Crew EO</option>
                                </select>
                            </div>

                            <div class="pt-4 mt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-3">* Leave the password field blank if you do not wish
                                    to change your password.</p>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900">New Password</label>
                                    <input type="password" name="password" x-model="editPassword" minlength="8"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-4">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Confirm New
                                        Password</label>
                                    <input type="password" name="password_confirmation"
                                        x-model="editPasswordConfirmation" minlength="8"
                                        class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        :class="(editPasswordConfirmation.length > 0 && editPassword !==
                                            editPasswordConfirmation) ?
                                        'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300'">

                                    <p x-show="editPasswordConfirmation.length > 0 && editPassword !== editPasswordConfirmation"
                                        class="mt-1 text-xs text-red-600" x-cloak>
                                        Password and Confirm Password do not match!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end p-4 border-t gap-2">
                        <button type="button" @click="isEditModalOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none">Cancel</button>
                        <button type="submit"
                            :disabled="editForm.id === loggedInUserId && editPassword !== editPasswordConfirmation"
                            :class="(editForm.id === loggedInUserId && editPassword !== editPasswordConfirmation) ?
                            'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg focus:outline-none transition-colors">
                            Update Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const perPageSelect = document.querySelector('select[name="per_page"]');
            const tableContainer = document.getElementById('table-container');

            function fetchUpdatedData() {
                const url = new URL(window.location.href);
                url.searchParams.set('search', searchInput.value);
                if (perPageSelect) {
                    url.searchParams.set('per_page', perPageSelect.value);
                }

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
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
        });
    </script>
@endsection
