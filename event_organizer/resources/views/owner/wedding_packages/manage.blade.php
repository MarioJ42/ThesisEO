@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    activeTab: '{{ session('active_tab', 'template') }}',
    isTemplateModalOpen: false,
    isAssignmentModalOpen: false,
    searchQuery: '',
    assignmentForm: { template_id: '', category_name: '', vendors: [], allowed_vendors: [] },
    openAssignmentModal(templateId, categoryName, assignedVendorIds, allowedVendorIds) {
        this.assignmentForm.template_id = templateId;
        this.assignmentForm.category_name = categoryName;
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
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <span class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-md border border-blue-100 shadow-sm">
                        Selling Price: Rp {{ number_format($package->base_price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-md border border-emerald-100 shadow-sm">
                        EO Service: Rp {{ number_format($package->eo_fee ?? 0, 0, ',', '.') }}
                    </span>
                    <span class="text-sm font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-md border border-gray-200 shadow-sm" title="This is the total cost if the client chooses the cheapest vendors in all categories.">
                        Base Cost (Vendors): Rp {{ number_format($minCost, 0, ',', '.') }}
                    </span>

                    @php
                        $totalMinimumCost = $minCost + ($package->eo_fee ?? 0);
                    @endphp

                    @if($package->base_price >= $totalMinimumCost && $minCost > 0)
                        <span class="text-sm font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-md border border-green-100 flex items-center gap-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Safe Margin
                        </span>
                    @elseif($package->base_price >= $minCost && $package->base_price < $totalMinimumCost && $minCost > 0)
                        <span class="text-sm font-semibold text-yellow-600 bg-yellow-50 px-3 py-1 rounded-md border border-yellow-100 flex items-center gap-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Warning: EO Fee Cut!
                        </span>
                    @elseif($package->base_price < $minCost && $minCost > 0)
                        <span class="text-sm font-semibold text-red-600 bg-red-50 px-3 py-1 rounded-md border border-red-100 flex items-center gap-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Below Vendor Cost!
                        </span>
                    @endif
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
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <div class="relative w-full sm:max-w-md">
                    <input type="text" x-model="searchQuery" placeholder="Search by category"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button @click="isTemplateModalOpen = true"
                        class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Category
                </button>
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
                        <tr class="hover:bg-gray-50 transition-colors duration-150"
                            x-show="searchQuery === '' || '{{ strtolower(addslashes($template->category->name ?? 'Unknown')) }}'.includes(searchQuery.toLowerCase())">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $template->session) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $template->category->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <form id="delete-template-form-{{ $template->id }}" action="{{ route('owner.wedding_packages.template.destroy', [$package->id, $template->id]) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteTemplate('{{ $template->id }}')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">Remove</button>
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

            <div class="mb-6 p-5 bg-blue-50/50 border border-blue-100 rounded-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-gray-900">Set Package Pricing & EO Service</h3>
                    <p class="text-xs text-gray-500 mt-1">Determine the final selling price for the client and the dedicated fee/profit for Fenix EO.</p>
                </div>
                <form action="{{ route('owner.wedding_packages.price.update', $package->id) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3 w-full md:w-auto">
                    @csrf @method('PUT')
                    <div class="w-full sm:w-48">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Selling Price (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-bold">Rp</span>
                            <input type="number" name="base_price" value="{{ round($package->base_price) }}" required min="0" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold">
                        </div>
                    </div>
                    <div class="w-full sm:w-48">
                        <label class="block text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-1">EO SERVICE (RP)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-blue-500 font-bold">Rp</span>
                            <input type="number" name="eo_fee" value="{{ round($package->eo_fee ?? 0) }}" required min="0" class="w-full pl-9 pr-3 py-2 border border-blue-300 bg-blue-50 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-semibold text-blue-700">
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-bold transition-colors w-full sm:w-auto h-[38px] flex items-center justify-center whitespace-nowrap shadow-sm">Save</button>
                </form>
            </div>

            <div class="relative w-full sm:max-w-md mb-6">
                <input type="text" x-model="searchQuery" placeholder="Search by category"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
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
                        <tr class="hover:bg-gray-50 transition-colors duration-150"
                            x-show="searchQuery === '' || '{{ strtolower(addslashes($template->category->name ?? 'Unknown')) }}'.includes(searchQuery.toLowerCase())">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $template->category->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 capitalize mt-0.5">{{ str_replace('_', ' ', $template->session) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if($template->is_included)
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Included</span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">Not Included</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $vendorNames ?: '-' }}">
                                {{ $vendorNames ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <button @click="openAssignmentModal({{ $template->id }}, '{{ addslashes($template->category->name ?? 'Unknown') }}', {{ $vendorIds }}, {{ $allowedVendors }})"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors">
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
                            <option value="full_day">Full Day</option>
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
