<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-lg shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
        <button onclick="toggleCreateModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

        <h3 class="text-lg font-semibold mb-4">Tambah Barang Masuk</h3>

        <form method="POST" action="{{ route('item-ins.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Pilih Kategori --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kategori Barang</label>
                <select id="categorySelect" class="w-full border-gray-300 rounded p-2" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pilih Barang --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Barang</label>
                <select name="item_id" id="itemSelect" required class="w-full border-gray-300 rounded p-2" disabled>
                    <option value="">-- Pilih Barang --</option>
                </select>
            </div>

            {{-- Supplier --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Supplier</label>
                <select name="supplier_id" required class="w-full border-gray-300 rounded p-2">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach ($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->supplier }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah & Harga --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Jumlah</label>
                    <input type="number" name="quantity" min="1" required
                        class="w-full border-gray-300 rounded p-2" oninput="updateTotalPrice()">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Harga Satuan (Rp)</label>
                    <input type="number" name="unit_price" step="0.01" min="0" required
                        class="w-full border-gray-300 rounded p-2" oninput="updateTotalPrice()">
                </div>
            </div>

            {{-- Total Harga --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Total Harga</label>
                <input type="text" id="total_price" readonly
                    class="w-full border-gray-300 bg-gray-100 rounded p-2 text-gray-700 font-semibold">
            </div>

            {{-- Tanggal Pembelian --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tanggal Pembelian</label>
                <input type="date" name="purchase_date" required class="w-full border-gray-300 rounded p-2"
                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
            </div>

            {{-- Foto Nota --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Foto Nota (opsional)</label>
                <input type="file" name="recipt_photo" accept="image/*" capture="environment"
                    class="w-full border-gray-300 rounded p-2 mb-2">
                <p class="text-xs text-gray-500">Bisa pilih dari galeri atau langsung ambil foto dengan kamera.</p>
            </div>

            {{-- Foto Barang Masuk --}}
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Foto Barang Masuk (opsional)</label>
                <input type="file" name="item_in_photo" accept="image/*" capture="environment"
                    class="w-full border-gray-300 rounded p-2 mb-2">
                <p class="text-xs text-gray-500">Bisa pilih dari galeri atau langsung ambil foto dengan kamera.</p>
            </div>

            {{-- Catatan --}}
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
