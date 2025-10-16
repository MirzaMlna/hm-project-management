<div id="editModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
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
                <label class="block text-sm font-medium mb-1">Jenis Barang</label>
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
