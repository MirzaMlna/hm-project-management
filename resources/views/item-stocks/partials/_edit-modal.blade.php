<div id="editModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
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

            <div class="flex flex-col sm:flex-row justify-end gap-2">
                <button type="button" onclick="toggleEditModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded w-full sm:w-auto">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded w-full sm:w-auto">Update</button>
            </div>
        </form>
    </div>
</div>
