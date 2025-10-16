<div id="createModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
        <button onclick="toggleCreateModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

        <h3 class="text-lg font-semibold mb-4">Tambah / Update Stok</h3>

        <form method="POST" action="{{ route('item-stocks.store') }}">
            @csrf

            {{-- Pilih Kategori --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Pilih Kategori</label>
                <select id="categorySelect" class="w-full border-gray-300 rounded p-2" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pilih Barang --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Pilih Barang</label>
                <select name="item_id" id="itemSelect" required class="w-full border-gray-300 rounded p-2" disabled>
                    <option value="">-- Pilih Barang --</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Stok</label>
                <input type="number" name="current_stock" min="0" class="w-full border-gray-300 rounded p-2"
                    required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Stok Minimal (Opsional)</label>
                <input type="number" name="minimum_stock" min="0" class="w-full border-gray-300 rounded p-2">
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-2">
                <button type="button" onclick="toggleCreateModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded w-full sm:w-auto">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded w-full sm:w-auto">Simpan</button>
            </div>
        </form>
    </div>
</div>
