<div id="importModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button onclick="toggleImportModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
        <h3 class="text-lg font-semibold mb-4">Import Data Barang dari Excel</h3>

        <form method="POST" action="{{ route('items.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Pilih File Excel</label>
                <input type="file" name="file" accept=".xlsx,.xls" required
                    class="w-full border-gray-300 rounded p-2 text-sm">
                <small class="text-gray-500 text-sm">Format kolom wajib:
                    <b>Kategori, Nama_Barang, Satuan, Keterangan</b></small>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="toggleImportModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded">Import</button>
            </div>
        </form>
    </div>
</div>
