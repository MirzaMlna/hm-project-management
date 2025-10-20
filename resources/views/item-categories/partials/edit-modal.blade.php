<div id="editModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
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
