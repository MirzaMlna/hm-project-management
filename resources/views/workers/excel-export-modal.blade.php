<!-- Modal Import Excel -->
<div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white rounded-xl shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
        <h3 class="text-lg mb-4 font-semibold">Import Tukang dari Excel</h3>
        <form action="{{ route('workers.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File Excel</label>
                <input type="file" name="file" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-amber-200">
            </div>
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button type="submit"
                    class="px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-700 w-full sm:w-auto">Upload</button>
                <button type="button" onclick="closeImportModal()"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 w-full sm:w-auto">Batal</button>
            </div>
        </form>
    </div>
</div>
