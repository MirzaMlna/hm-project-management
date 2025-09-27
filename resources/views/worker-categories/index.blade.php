<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategori Tukang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Tombol Tambah --}}
                <div class="flex justify-end items-end">
                    <x-primary-button class="mt-6 mr-6" onclick="toggleCreateModal()">
                        <i class="bi bi-plus-circle mr-2"></i>Tambah Kategori Tukang
                    </x-primary-button>
                </div>

                {{-- Tabel --}}
                <div class="p-6 text-gray-900">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-sky-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">Kategori</th>
                                    <th scope="col" class="px-6 py-3">Jumlah Tukang</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-900">
                                        <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                            {{ $index + 1 }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $category->category }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{-- Kosong dulu --}}
                                        </td>
                                        <td class="px-6 py-4 space-x-3">
                                            <button
                                                onclick="toggleEditModal({{ $category->id }}, '{{ $category->category }}')">
                                                <i class="bi bi-pencil-square text-yellow-500"></i>
                                            </button>
                                            <form action="{{ route('worker-categories.destroy', $category->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                                    <i class="bi bi-trash3 text-red-500"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Belum ada data kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create --}}
    <div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h3 class="text-lg font-bold mb-4">Tambah Kategori Tukang</h3>
            <form action="{{ route('worker-categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="category" id="category"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h3 class="text-lg font-bold mb-4">Edit Kategori Tukang</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_category" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="category" id="edit_category"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="toggleEditModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        function toggleEditModal(id = null, category = null) {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');

            if (id && category) {
                document.getElementById('edit_category').value = category;
                document.getElementById('editForm').action = `/worker-categories/${id}`;
            }
        }
    </script>
</x-app-layout>
