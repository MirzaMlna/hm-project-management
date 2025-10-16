<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
        <button onclick="toggleCreateModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

        <h3 class="text-lg font-semibold mb-4">Tambah Barang Keluar</h3>

        <form method="POST" action="{{ route('item-outs.store') }}">
            @csrf

            {{-- 🔹 Pilih Kategori --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kategori Barang</label>
                <select id="categorySelect" name="item_category_id" class="w-full border-gray-300 rounded p-2" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 🔹 Pilih Barang --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Barang</label>
                <select name="item_id" id="itemSelect" required class="w-full border-gray-300 rounded p-2" disabled>
                    <option value="">-- Pilih Barang --</option>
                </select>
            </div>

            {{-- 🔹 Titik Pembangunan --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Titik Pembangunan</label>
                <select name="development_point_id" required class="w-full border-gray-300 rounded p-2">
                    <option value="">-- Pilih Titik --</option>
                    @foreach ($points as $pt)
                        <option value="{{ $pt->id }}">{{ $pt->development_point }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 🔹 Jumlah --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Jumlah</label>
                <input type="number" name="quantity" min="1" required
                    class="w-full border-gray-300 rounded p-2">
            </div>

            {{-- 🔹 Tanggal Keluar --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tanggal Keluar</label>
                <input type="date" name="date_out" required class="w-full border-gray-300 rounded p-2"
                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
            </div>

            {{-- 🔹 Catatan --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Catatan</label>
                <textarea name="note" rows="2" class="w-full border-gray-300 rounded p-2"
                    placeholder="Tuliskan keterangan tambahan jika perlu..."></textarea>
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
