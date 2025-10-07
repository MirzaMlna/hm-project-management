<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategori Barang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses/gagal --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="alert-error"
                    class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm transition-opacity duration-500">
                    ❌ {{ session('error') }}
                </div>
            @endif


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Header + Tombol Tambah --}}
                <div class="flex justify-between items-center px-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-list"></i> Kategori Barang
                    </h3>
                    <x-primary-button onclick="toggleCreateModal()">
                        <i class="bi bi-plus-circle"></i>
                    </x-primary-button>
                </div>


                {{-- Tabel --}}
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto rounded">
                        <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200">
                            <thead class="text-xs text-white uppercase bg-sky-700">
                                <tr>
                                    <th class="px-4 py-3 text-start w-12">#</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3 text-start">Jumlah Jenis</th>
                                    <th class="px-4 py-3 text-start w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 text-start font-medium text-gray-800">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $category->category }}
                                        </td>
                                        <td class="px-4 py-3 text-start font-semibold text-gray-800">
                                            {{ $category->items_count ?? 0 }}
                                        </td>
                                        <td class="px-4 py-3 text-start space-x-3">
                                            <button
                                                onclick="toggleEditModal({{ $category->id }}, '{{ $category->category }}')"
                                                class="text-yellow-500 hover:text-yellow-600">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('item-categories.destroy', $category->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-start py-4 text-gray-500 italic bg-gray-50">
                                            Belum ada data kategori.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
        <div
            class="bg-white rounded-lg shadow-lg w-full max-w-md md:max-w-lg lg:max-w-xl p-6 relative transform transition duration-200">
            <button onclick="toggleCreateModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

            <h3 class="text-lg font-semibold mb-4">Tambah Kategori Barang</h3>

            <form method="POST" action="{{ route('item-categories.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="category" required
                        class="w-full border-gray-300 rounded focus:ring focus:ring-sky-200 p-2"
                        placeholder="Contoh: Paku">
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 w-full sm:w-auto">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-sky-600 hover:bg-sky-700 text-white w-full sm:w-auto">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
        <div
            class="bg-white rounded-lg shadow-lg w-full max-w-md md:max-w-lg lg:max-w-xl p-6 relative transform transition duration-200">
            <button onclick="toggleEditModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

            <h3 class="text-lg font-semibold mb-4">Edit Kategori Barang</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" id="edit_category" name="category" required
                        class="w-full border-gray-300 rounded focus:ring focus:ring-amber-200 p-2">
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2">
                    <button type="button" onclick="toggleEditModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 w-full sm:w-auto">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-amber-500 hover:bg-amber-600 text-white w-full sm:w-auto">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Modal + Auto Hide Alert --}}
    <script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        function toggleEditModal(id = null, category = null) {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');

            if (id && category) {
                document.getElementById('edit_category').value = category;
                document.getElementById('editForm').action = `/item-categories/${id}`;
            }
        }

        // Hilangkan alert setelah 5 detik (5000 ms)
        setTimeout(() => {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');

            [successAlert, errorAlert].forEach(alert => {
                if (alert) {
                    alert.classList.add('opacity-0'); // efek fade
                    setTimeout(() => alert.remove(), 500); // hapus setelah animasi
                }
            });
        }, 5000);
    </script>
</x-app-layout>
