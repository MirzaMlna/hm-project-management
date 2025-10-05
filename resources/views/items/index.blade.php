<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jenis Barang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Tombol Tambah --}}
                <div class="flex justify-end items-end">
                    <x-primary-button class="mt-6 mr-6" onclick="toggleCreateModal()">
                        <i class="bi bi-plus-circle mr-2"></i>Tambah Barang
                    </x-primary-button>
                </div>

                {{-- Loop per kategori --}}
                <div class="p-6 text-gray-900 space-y-8">
                    @forelse ($categories as $category)
                        <div class="rounded border border-gray-200 shadow-sm">
                            {{-- Header kategori --}}
                            <div class="bg-sky-700 text-white px-4 py-2 rounded-t font-semibold">
                                {{ strtoupper($category->category) }}
                            </div>

                            {{-- Tabel barang --}}
                            <div class="overflow-x-auto mt-2">
                                <table class="min-w-full border border-gray-200 rounded text-sm text-gray-700">
                                    <thead class="bg-sky-700 text-white uppercase text-xs tracking-wider">
                                        <tr>
                                            <th class="px-4 py-3 text-center border border-gray-300 w-12">#</th>
                                            <th class="px-4 py-3 border border-gray-300">Kode</th>
                                            <th class="px-4 py-3 border border-gray-300">Jenis</th>
                                            <th class="px-4 py-3 border border-gray-300 text-center">Satuan</th>
                                            <th class="px-4 py-3 border border-gray-300 text-center">Foto</th>
                                            <th class="px-4 py-3 border border-gray-300 text-center w-24">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $categoryItems = $items->where('item_category_id', $category->id);
                                        @endphp

                                        @forelse ($categoryItems as $index => $item)
                                            <tr
                                                class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} border-t border-gray-200 hover:bg-gray-100">
                                                <td
                                                    class="px-4 py-3 text-center border border-gray-200 font-medium text-gray-800">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="px-4 py-3 border border-gray-200">{{ $item->code }}</td>
                                                <td
                                                    class="px-4 py-3 border border-gray-200 font-semibold text-gray-900">
                                                    {{ $item->name }}
                                                </td>
                                                <td class="px-4 py-3 text-center border border-gray-200">
                                                    {{ $item->unit }}</td>
                                                <td class="px-4 py-3 text-center border border-gray-200">
                                                    @if ($item->photo)
                                                        <img src="{{ asset('storage/' . $item->photo) }}"
                                                            class="w-12 h-12 rounded object-cover border mx-auto">
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center border border-gray-200 space-x-2">
                                                    <button type="button" class="btn-edit"
                                                        data-id="{{ $item->id }}" data-name="{{ e($item->name) }}"
                                                        data-unit="{{ e($item->unit) }}"
                                                        data-category="{{ $item->item_category_id }}"
                                                        data-description="{{ e($item->description) }}"
                                                        data-photo="{{ $item->photo ? asset('storage/' . $item->photo) : '' }}">
                                                        <i
                                                            class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600"></i>
                                                    </button>

                                                    <form action="{{ route('items.destroy', $item->id) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
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
                                                <td colspan="6"
                                                    class="text-center py-4 text-gray-500 italic bg-gray-50 border-t border-gray-200">
                                                    Tidak ada barang dalam kategori ini.
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

    {{-- Modal Edit --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button type="button" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                onclick="document.getElementById('editModal').classList.add('hidden')">✕</button>

            <h3 class="text-lg font-semibold mb-4">Edit Barang</h3>

            <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select id="edit_category" name="item_category_id" required
                        class="w-full border-gray-300 rounded p-2">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nama Barang</label>
                    <input id="edit_name" type="text" name="name" class="w-full border-gray-300 rounded p-2"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Satuan</label>
                    <input id="edit_unit" type="text" name="unit" class="w-full border-gray-300 rounded p-2"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Foto Saat Ini</label>
                    <div class="flex items-center gap-3">
                        <img id="edit_photo_preview" src=""
                            class="w-12 h-12 rounded object-cover border hidden">
                        <span id="edit_no_photo" class="text-gray-400">-</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Ganti Foto (Opsional)</label>
                    <input type="file" name="photo" class="w-full border-gray-300 rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <textarea id="edit_description" name="description" class="w-full border-gray-300 rounded p-2" rows="3"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded"
                        onclick="document.getElementById('editModal').classList.add('hidden')">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>


    {{-- Modal Tambah --}}
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="toggleCreateModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Tambah Barang</h3>

            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="item_category_id" required class="w-full border-gray-300 rounded p-2">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nama Barang</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Satuan</label>
                    <input type="text" name="unit" class="w-full border-gray-300 rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Foto (Opsional)</label>
                    <input type="file" name="photo" class="w-full border-gray-300 rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <textarea name="description" class="w-full border-gray-300 rounded p-2" rows="3"></textarea>
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

    <script>
        // Buka modal tambah
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        // Inisialisasi tombol edit
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name || '';
                const unit = btn.dataset.unit || '';
                const catId = btn.dataset.category || '';
                const desc = btn.dataset.description || '';
                const photo = btn.dataset.photo || '';

                // Set action form
                document.getElementById('editForm').action = "{{ url('items') }}/" + id;

                // Isi field
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_unit').value = unit;
                document.getElementById('edit_category').value = catId;
                document.getElementById('edit_description').value = desc;

                // Preview foto
                const img = document.getElementById('edit_photo_preview');
                const noPhoto = document.getElementById('edit_no_photo');
                if (photo) {
                    img.src = photo;
                    img.classList.remove('hidden');
                    noPhoto.classList.add('hidden');
                } else {
                    img.classList.add('hidden');
                    noPhoto.classList.remove('hidden');
                }

                // Tampilkan modal
                document.getElementById('editModal').classList.remove('hidden');
            });
        });

        // Hilangkan alert otomatis
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>

</x-app-layout>
