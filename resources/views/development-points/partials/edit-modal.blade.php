<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="toggleEditModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
        <h3 class="text-lg font-semibold mb-4">Edit Titik Pembangunan</h3>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium">Nama Titik</label>
                <input type="text" id="edit_point" name="development_point"
                    class="w-full border rounded p-2 focus:ring focus:ring-amber-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Foto Baru</label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full border rounded p-2 focus:ring focus:ring-amber-300">
                <div class="mt-2" id="previewPhoto"></div>
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
