<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
    <div
        class="bg-white rounded-lg shadow-lg w-full max-w-md md:max-w-lg lg:max-w-xl p-6 relative transform transition duration-200">
        <button onclick="toggleCreateModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

        <h3 class="text-lg font-semibold mb-4">Tambah Kategori Barang</h3>

        <form method="POST" action="{{ route('item-categories.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="category" required
                    class="w-full border-gray-300 rounded focus:ring focus:ring-sky-200 p-2" placeholder="Contoh: Paku">
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
