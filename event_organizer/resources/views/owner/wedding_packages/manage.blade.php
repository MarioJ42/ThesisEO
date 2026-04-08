@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    activeTab: '{{ session('active_tab', 'template') }}',
    isTemplateModalOpen: false,
    isAssignmentModalOpen: false,

    assignmentForm: { template_id: '', category_name: '', is_included: '0', vendors: [], allowed_vendors: [] },

    openAssignmentModal(templateId, categoryName, isIncluded, assignedVendorIds, allowedVendorIds) {
        this.assignmentForm.template_id = templateId;
        this.assignmentForm.category_name = categoryName;
        this.assignmentForm.is_included = isIncluded.toString();
        this.assignmentForm.vendors = assignedVendorIds.map(String);
        this.assignmentForm.allowed_vendors = allowedVendorIds.map(String);
        this.isAssignmentModalOpen = true;
    }
}">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.wedding_packages') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $package->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-sm font-semibold text-green-600">
                        Rp {{ number_format($package->base_price, 0, ',', '.') }}
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
            <button @click="activeTab = 'template'" :class="activeTab === 'template' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Template Event
            </button>
            <button @click="activeTab = 'assignment'" :class="activeTab === 'assignment' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Vendor Assignment
            </button>
        </nav>
    </div>

    <div class="bg-white rounded-b-lg shadow-sm border border-t-0 border-gray-200 p-6">

        <div x-show="activeTab === 'template'" x-cloak>
            <div class="flex justify-end items-center mb-4">
                <button @click="isTemplateModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-semibold transition-colors">+ Add Category</button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $sessionOrder = ['morning' => 1, 'evening' => 2, 'full_day' => 3];
                        @endphp
                        @forelse($package->templates->sortBy(fn($t) => $sessionOrder[$t->session] ?? 4) as $template)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">{{ $template->session }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $template->category->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <form id="delete-template-form-{{ $template->id }}" action="{{ route('owner.wedding_packages.template.destroy', [$package->id, $template->id]) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteTemplate('{{ $template->id }}')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2 py-1 rounded">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                No categories added to this template yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'assignment'" x-cloak>
            <div class="overflow-x-auto rounded-lg border border-gray-200 mt-2">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category / Session</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Is Included?</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Vendors</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $sessionOrder = ['morning' => 1, 'evening' => 2, 'full_day' => 3];
                        @endphp
                        @forelse($package->templates->sortBy(fn($t) => $sessionOrder[$t->session] ?? 4) as $template)
                        @php
                            $assignedVendors = $package->vendors->where('pivot.vendor_category_id', $template->vendor_category_id);
                            $vendorNames = $assignedVendors->pluck('name')->join(', ');
                            $vendorIds = $assignedVendors->pluck('id')->toJson();

                            $allowedVendors = \Illuminate\Support\Facades\DB::table('category_vendor')
                                ->where('category_id', $template->vendor_category_id)
                                ->pluck('vendor_id')
                                ->map(fn($id) => (string)$id)
                                ->values()
                                ->toJson();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $template->category->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ $template->session }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if($template->is_included)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-700">Included</span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">Not Included</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $vendorNames ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <button @click="openAssignmentModal({{ $template->id }}, '{{ addslashes($template->category->name ?? 'Unknown') }}', {{ $template->is_included ? '1' : '0' }}, {{ $vendorIds }}, {{ $allowedVendors }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded">
                                    Set Vendor
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Set up the template first to assign vendors.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div x-show="isTemplateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl" @click.away="isTemplateModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Add Category to Template</h3>
                <button @click="isTemplateModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('owner.wedding_packages.template.store', $package->id) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Vendor Category</label>
                        <select name="vendor_category_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            @foreach($masterCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Session</label>
                        <select name="session" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="morning">Morning Procession</option>
                            <option value="evening">Reception (Evening)</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isTemplateModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Add to Template</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="isAssignmentModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 overflow-y-auto" x-cloak>
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl my-8" @click.away="isAssignmentModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Configure: <span x-text="assignmentForm.category_name"></span></h3>
                <button @click="isAssignmentModalOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form :action="'{{ url('/owner/event-packages') }}/{{ $package->id }}/template/' + assignmentForm.template_id + '/assign'" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Is this category included in the package?</label>
                        <select name="is_included" x-model="assignmentForm.is_included" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            <option value="1">Included</option>
                            <option value="0">Not Included</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Select Vendors (Multiple allowed)</label>
                        <div class="grid grid-cols-2 gap-2 p-3 bg-gray-50 border border-gray-200 rounded-lg max-h-60 overflow-y-auto">
                            @foreach($masterVendors as $vendorItem)
                            <label x-show="assignmentForm.allowed_vendors.includes('{{ $vendorItem->id }}')" class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="vendors[]" value="{{ $vendorItem->id }}" x-model="assignmentForm.vendors" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 h-4 w-4">
                                <span class="ml-2 text-sm text-gray-700 truncate">{{ $vendorItem->name }}</span>
                            </label>
                            @endforeach
                            <div x-show="assignmentForm.allowed_vendors.length === 0" class="col-span-2 text-sm text-red-500 italic">
                                No vendors available for this category.
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">* Leave unchecked if no specific vendor is assigned yet.</p>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isAssignmentModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDeleteTemplate(templateId) {
        Swal.fire({
            title: 'Remove Category?',
            text: "Are you sure you want to remove this category from the package template?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            scrollbarPadding: false,
            heightAuto: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-template-form-' + templateId).submit();
            }
        });
    }
</script>
@endsection
