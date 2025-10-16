<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
        <button onclick="toggleEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
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
                <input id="edit_address" type="text" name="address" class="w-full border-gray-300 rounded p-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea id="edit_description" name="description" class="w-full border-gray-300 rounded p-2" rows="3"></textarea>
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
