<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Stok Barang
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

            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Header kiri-kanan --}}
                <div class="flex justify-between items-center px-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-boxes text-sky-600"></i> Daftar Stok Barang
                    </h3>

                    <x-primary-button onclick="toggleCreateModal()">
                        <i class="bi bi-plus-circle mr-2"></i>Tambah / Update Stok
                    </x-primary-button>
                </div>

                {{-- Loop per kategori --}}
                <div class="p-6 text-gray-900 space-y-8">
                    @forelse ($categories as $category)
                        @php
                            $categoryStocks = $stocks->filter(
                                fn($s) => $s->item && $s->item->item_category_id == $category->id,
                            );
                        @endphp

                        <div class="rounded border border-gray-200 shadow-sm">
                            {{-- Header kategori --}}
                            <div class="bg-sky-700 text-white px-4 py-2 rounded-t font-semibold">
                                {{ strtoupper($category->category) }}
                            </div>

                            {{-- Tabel stok --}}
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-center w-12">#</th>
                                            <th class="px-4 py-3">Barang</th>
                                            <th class="px-4 py-3">Satuan</th>
                                            <th class="px-4 py-3 text-center">Stok Saat Ini</th>
                                            <th class="px-4 py-3 text-center">Minimal</th>
                                            <th class="px-4 py-3 text-center">Terakhir Diperbarui</th>
                                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categoryStocks as $index => $stock)
                                            <tr
                                                class="bg-white border-b border-gray-200 text-gray-900 hover:bg-gray-50">
                                                <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-3 font-semibold">{{ $stock->item->name ?? '-' }}</td>
                                                <td class="px-4 py-3">{{ $stock->item->unit ?? '-' }}</td>
                                                <td class="px-4 py-3 text-center">{{ $stock->current_stock }}</td>
                                                <td class="px-4 py-3 text-center">{{ $stock->minimum_stock ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    {{ $stock->last_updated ? \Carbon\Carbon::parse($stock->last_updated)->translatedFormat('d M Y H:i') : '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-center space-x-2">
                                                    <button type="button" class="btn-edit"
                                                        data-id="{{ $stock->id }}"
                                                        data-current="{{ $stock->current_stock }}"
                                                        data-minimum="{{ $stock->minimum_stock }}">
                                                        <i
                                                            class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600"></i>
                                                    </button>

                                                    <form action="{{ route('item-stocks.destroy', $stock->id) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus stok ini?')">
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
                                                <td colspan="7"
                                                    class="text-center py-4 text-gray-500 italic bg-gray-50">
                                                    Tidak ada stok dalam kategori ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-10">
                            Belum ada kategori barang yang terdaftar.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="toggleCreateModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

            <h3 class="text-lg font-semibold mb-4">Tambah / Update Stok</h3>

            <form method="POST" action="{{ route('item-stocks.store') }}">
                @csrf

                {{-- Pilih Kategori --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Pilih Kategori</label>
                    <select id="categorySelect" class="w-full border-gray-300 rounded p-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Barang --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Pilih Barang</label>
                    <select name="item_id" id="itemSelect" required class="w-full border-gray-300 rounded p-2" disabled>
                        <option value="">-- Pilih Barang --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Stok</label>
                    <input type="number" name="current_stock" min="0" class="w-full border-gray-300 rounded p-2"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Stok Minimal (Opsional)</label>
                    <input type="number" name="minimum_stock" min="0"
                        class="w-full border-gray-300 rounded p-2">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    {{-- Modal Edit --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="toggleEditModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Edit Stok Barang</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Stok Saat Ini</label>
                    <input id="edit_current" type="number" name="current_stock" min="0"
                        class="w-full border-gray-300 rounded p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Stok Minimal</label>
                    <input id="edit_minimum" type="number" name="minimum_stock" min="0"
                        class="w-full border-gray-300 rounded p-2">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleEditModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script --}}
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
                document.getElementById('editForm').action = `/item-stocks/${id}`;
                document.getElementById('edit_current').value = btn.dataset.current;
                document.getElementById('edit_minimum').value = btn.dataset.minimum;
                toggleEditModal();
            });
        });

        // Auto hide alert
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);

        // Dropdown dinamis: Kategori → Barang
        document.getElementById('categorySelect').addEventListener('change', function() {
            const categoryId = this.value;
            const itemSelect = document.getElementById('itemSelect');
            itemSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
            itemSelect.disabled = true;

            if (categoryId) {
                fetch(`/get-items-by-category/${categoryId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.id;
                                option.textContent = item.name;
                                itemSelect.appendChild(option);
                            });
                            itemSelect.disabled = false;
                        } else {
                            const opt = document.createElement('option');
                            opt.textContent = 'Tidak ada barang di kategori ini';
                            itemSelect.appendChild(opt);
                        }
                    })

                    .catch(() => {
                        const opt = document.createElement('option');
                        opt.textContent = 'Gagal memuat data';
                        itemSelect.appendChild(opt);
                    });
            }
        });
    </script>
</x-app-layout>
