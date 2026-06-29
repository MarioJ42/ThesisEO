@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    isModalOpen: false,
    isEditModalOpen: false,
    editForm: { id: '', title: '', description: '' },

    openEditModal(id, title, description) {
        this.editForm.id = id;
        this.editForm.title = title;
        this.editForm.description = description === '-' ? '' : description;
        this.isEditModalOpen = true;
    }
}">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Master EO Portfolio</h2>
        <button @click="isModalOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors shadow-sm">
            Add New Portfolio
        </button>
    </div>

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
        <div id="table-container">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($portfolios as $portfolio)
                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="aspect-[4/3] w-full bg-gray-100">
                        <img src="{{ asset('storage/' . $portfolio->image_path) }}" class="w-full h-full object-cover" alt="{{ $portfolio->title }}">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-1 line-clamp-1">{{ $portfolio->title }}</h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $portfolio->description }}</p>

                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <button @click="openEditModal({{ $portfolio->id }}, '{{ addslashes($portfolio->title) }}', '{{ addslashes($portfolio->description) }}')" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                Edit
                            </button>

                            <!-- Form Delete Diubah -->
                            <form id="delete-form-{{ $portfolio->id }}" action="{{ route('owner.portfolios.destroy', $portfolio->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeletePortfolio({{ $portfolio->id }})" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    Data portfolio tidak ditemukan.
                </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $portfolios->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" style="display: none;" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl my-8" @click.away="isModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Add Portfolio</h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('owner.portfolios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea name="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5"></textarea>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Upload Image <span class="text-red-500">*</span></label>
                        <input type="file" name="image" required accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">Max size: 2MB. Format: JPG, JPEG, PNG.</p>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" style="display: none;" x-cloak>
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl my-8" @click.away="isEditModalOpen = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Edit Portfolio</h3>
                <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="'{{ url('/owner/portfolios') }}/' + editForm.id" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" x-model="editForm.title" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea name="description" x-model="editForm.description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5"></textarea>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Replace Image (Optional)</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t gap-2">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        @endif
    });

    function confirmDeletePortfolio(portfolioId) {
        Swal.fire({
            title: 'Hapus Portofolio?',
            text: "Anda yakin ingin menghapus portofolio ini? Tindakan ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + portfolioId).submit();
            }
        });
    }
</script>
@endsection
