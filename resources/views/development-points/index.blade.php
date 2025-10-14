<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Titik Pembangunan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-4 sm:p-6">
                {{-- Header --}}
                <div class="flex flex-row justify-between items-center gap-3 mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <i class=""></i>
                    </h3>
                    <x-primary-button onclick="toggleCreateModal()" class="flex items-center gap-2 !w-auto">
                        <i class="bi bi-plus-circle"></i>
                    </x-primary-button>
                </div>


                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded text-gray-700">
                        <thead class="bg-sky-700 text-white uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-3 py-2 text-start w-10">#</th>
                                <th class="px-3 py-2 text-start">Titik</th>
                                <th class="px-3 py-2 text-start">Foto</th>
                                <th class="px-3 py-2 text-start w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($points as $point)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2 text-start">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2 font-semibold">{{ $point->development_point }}</td>
                                    <td class="px-3 py-2 text-start">
                                        @if ($point->photo)
                                            <button type="button"
                                                onclick="showPhoto('{{ asset('storage/' . $point->photo) }}')"
                                                class="text-sky-600 hover:text-sky-800 underline">
                                                Lihat
                                            </button>
                                        @else
                                            <span class="text-gray-500 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-start space-x-2">
                                        <button
                                            onclick="toggleEditModal({{ $point->id }}, '{{ $point->development_point }}', '{{ $point->photo }}')"
                                            class="text-yellow-600 hover:text-yellow-800">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('development-points.destroy', $point->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-start text-gray-500 italic">
                                        Belum ada titik pembangunan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $points->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div
            class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg p-6 relative overflow-y-auto max-h-[90vh]">
            <button onclick="toggleCreateModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Tambah Titik Pembangunan</h3>
            <form method="POST" action="{{ route('development-points.store') }}" class="space-y-3"
                enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Nama Titik</label>
                    <input type="text" name="development_point"
                        class="w-full border rounded p-2 focus:ring focus:ring-sky-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Foto</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full border rounded p-2 focus:ring focus:ring-sky-300">
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 w-full sm:w-auto">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700 w-full sm:w-auto">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div
            class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg p-6 relative overflow-y-auto max-h-[90vh]">
            <button onclick="toggleEditModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Edit Titik Pembangunan</h3>

            <form id="editForm" method="POST" class="space-y-3" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Nama Titik</label>
                    <input type="text" id="edit_point" name="development_point"
                        class="w-full border rounded p-2 focus:ring focus:ring-amber-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Foto Baru</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full border rounded p-2 focus:ring focus:ring-amber-300">
                    <div class="mt-2" id="previewPhoto"></div>
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2">
                    <button type="button" onclick="toggleEditModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 w-full sm:w-auto">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 w-full sm:w-auto">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Lihat Foto --}}
    <div id="photoModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full relative">
            <button onclick="togglePhotoModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">✕</button>
            <div class="p-4">
                <img id="photoPreview" src="" alt="Foto Titik Pembangunan"
                    class="max-h-[70vh] w-full object-contain rounded">
            </div>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        function toggleEditModal(id = null, point = null, photo = null) {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');

            if (id && point) {
                document.getElementById('edit_point').value = point;
                document.getElementById('editForm').action = `/development-points/${id}`;

                const previewDiv = document.getElementById('previewPhoto');
                previewDiv.innerHTML = '';
                if (photo) {
                    previewDiv.innerHTML =
                        `<img src="/storage/${photo}" alt="Foto lama" class="h-16 w-16 object-cover rounded">`;
                }
            }
        }

        function showPhoto(src) {
            document.getElementById('photoPreview').src = src;
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function togglePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }

        // Auto-hide alert
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 500);
            }
        }, 4000);
    </script>
</x-app-layout>
