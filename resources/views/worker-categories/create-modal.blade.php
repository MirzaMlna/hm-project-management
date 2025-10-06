{{-- Modal Create --}}
<div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div
        class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg p-4 sm:p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="toggleCreateModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

        <h3 class="text-lg font-bold mb-4">Tambah Kategori Tukang</h3>
        <form action="{{ route('worker-categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="category" id="category"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-sky-300">
            </div>
            <div class="flex flex-col sm:flex-row justify-end gap-2">
                <button type="button" onclick="toggleCreateModal()"
                    class="px-4 py-2 bg-gray-400 text-white rounded w-full sm:w-auto">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700 w-full sm:w-auto">Simpan</button>
            </div>
        </form>
    </div>
</div>
