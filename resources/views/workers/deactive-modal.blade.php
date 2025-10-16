<!-- Modal Nonaktifkan -->
<div id="deactivateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white rounded-xl shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
        <h3 class="text-lg mb-4 font-semibold">Nonaktifkan Tukang</h3>
        <p class="mb-2">Nama: <span id="workerNameModal" class="font-bold text-red-600"></span></p>
        <form id="deactivateForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan <span
                        class="text-red-500">*</span></label>
                <textarea id="note" name="note" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-red-200" rows="3"
                    placeholder="Masukkan alasan nonaktifkan tukang..."></textarea>
            </div>
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button type="button" onclick="closeDeactivateModal()"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 w-full sm:w-auto">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 w-full sm:w-auto">Nonaktifkan</button>
            </div>
        </form>
    </div>
</div>
