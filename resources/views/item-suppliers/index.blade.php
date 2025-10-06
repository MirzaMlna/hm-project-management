<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pemasok Barang
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Header kiri-kanan --}}
                <div class="flex justify-between items-center px-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-truck text-sky-600"></i> Daftar Pemasok
                    </h3>

                    <x-primary-button onclick="toggleCreateModal()">
                        <i class="bi bi-plus-circle mr-2"></i>Tambah Pemasok
                    </x-primary-button>
                </div>

                {{-- Tabel --}}
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto mt-2">
                        <table class="w-full text-sm text-left text-gray-600 border border-gray-200">
                            <thead class="text-xs text-white uppercase bg-sky-700">
                                <tr>
                                    <th class="px-4 py-3 text-center w-12">#</th>
                                    <th class="px-4 py-3">Kode</th>
                                    <th class="px-4 py-3">Nama Pemasok</th>
                                    <th class="px-4 py-3">Telepon</th>
                                    <th class="px-4 py-3">Alamat</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                    <th class="px-4 py-3 text-center w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $index => $supplier)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 text-center">
                                            {{ $suppliers->firstItem() + $index }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $supplier->code }}
                                        </td>
                                        <td class="px-4 py-3 font-bold text-gray-800">
                                            {{ $supplier->supplier }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $supplier->phone ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $supplier->address ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $supplier->description ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center space-x-2">
                                            <button type="button" class="btn-edit" data-id="{{ $supplier->id }}"
                                                data-supplier="{{ e($supplier->supplier) }}"
                                                data-phone="{{ e($supplier->phone) }}"
                                                data-address="{{ e($supplier->address) }}"
                                                data-description="{{ e($supplier->description) }}">
                                                <i
                                                    class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600"></i>
                                            </button>

                                            <form action="{{ route('item-suppliers.destroy', $supplier->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus pemasok ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">
                                                    <i class="bi bi-trash3 text-red-500 hover:text-red-600"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-gray-500 italic bg-gray-50">
                                            Belum ada pemasok.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                    {{-- PAGINASI --}}
                    @if ($suppliers->hasPages())
                        <div class="mt-4">
                            {{ $suppliers->onEachSide(1)->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="toggleCreateModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Tambah Pemasok</h3>

            <form method="POST" action="{{ route('item-suppliers.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nama Pemasok</label>
                    <input type="text" name="supplier" class="w-full border-gray-300 rounded p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">No. Telepon</label>
                    <input type="text" name="phone" class="w-full border-gray-300 rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Alamat</label>
                    <input type="text" name="address" class="w-full border-gray-300 rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <textarea name="description" class="w-full border-gray-300 rounded p-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="toggleEditModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Edit Pemasok</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nama Pemasok</label>
                    <input id="edit_supplier" type="text" name="supplier" class="w-full border-gray-300 rounded p-2"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">No. Telepon</label>
                    <input id="edit_phone" type="text" name="phone" class="w-full border-gray-300 rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Alamat</label>
                    <input id="edit_address" type="text" name="address"
                        class="w-full border-gray-300 rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <textarea id="edit_description" name="description" class="w-full border-gray-300 rounded p-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleEditModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        function toggleEditModal() {
            document.getElementById('editModal').classList.toggle('hidden');
        }

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                document.getElementById('editForm').action = `/item-suppliers/${id}`;
                document.getElementById('edit_supplier').value = btn.dataset.supplier || '';
                document.getElementById('edit_phone').value = btn.dataset.phone || '';
                document.getElementById('edit_address').value = btn.dataset.address || '';
                document.getElementById('edit_description').value = btn.dataset.description || '';
                toggleEditModal();
            });
        });

        // Auto-hide alert
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
</x-app-layout>
