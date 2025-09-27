{{-- Modal Create --}}
    <div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h3 class="text-lg font-bold mb-4">Tambah Kategori Tukang</h3>
            <form action="{{ route('worker-categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="category" id="category"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>