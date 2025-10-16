<div id="createModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
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
                <label class="block text-sm font-medium mb-1">Jenis Barang</label>
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
