<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Barang Masuk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Header kiri-kanan --}}
                <div class="flex flex-col md:flex-row md:justify-between md:items-center px-6 mt-6 gap-3">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-box-arrow-in-down text-sky-600"></i> Daftar Barang Masuk
                    </h3>

                    <div class="flex items-center gap-3">
                        {{-- Filter Bulan --}}
                        <form method="GET" action="{{ route('item-ins.index') }}" class="flex items-center gap-2">
                            <label for="month" class="text-sm font-medium text-gray-700">Filter Bulan:</label>
                            <input type="month" id="month" name="month" value="{{ $selectedMonth }}"
                                class="border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
                        </form>

                        <x-primary-button onclick="toggleCreateModal()">
                            <i class="bi bi-plus-circle mr-2"></i>Tambah Barang Masuk
                        </x-primary-button>
                    </div>
                </div>


                {{-- Tabel --}}
                <div class="p-6 overflow-x-auto">
                    <div class=" mb-2 text-sm text-gray-600 italic">
                        Menampilkan data bulan
                        <span class="font-semibold text-sky-700">
                            {{ \Carbon\Carbon::parse($selectedMonth . '-01')->translatedFormat('F Y') }}
                        </span>
                    </div>

                    <table class="w-full text-sm text-left text-gray-600 border border-gray-200">
                        <thead class="text-xs text-white uppercase bg-sky-700">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">#</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Supplier</th>
                                <th class="px-4 py-3 text-center">Jumlah</th>
                                <th class="px-4 py-3 text-center">Harga Satuan</th>
                                <th class="px-4 py-3 text-center">Total Harga</th>
                                <th class="px-4 py-3 text-center">Tanggal Beli</th>
                                <th class="px-4 py-3 text-center">Nota</th>
                                <th class="px-4 py-3 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($itemIns as $in)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $in->item->category->category ?? '-' }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $in->item->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $in->supplier->supplier ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $in->quantity }}</td>
                                    <td class="px-4 py-3 text-center">
                                        Rp{{ number_format($in->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center font-semibold text-sky-700">
                                        Rp{{ number_format($in->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ \Carbon\Carbon::parse($in->purchase_date)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($in->recipt_photo)
                                            <a href="{{ asset('storage/' . $in->recipt_photo) }}" target="_blank"
                                                class="text-sky-600 hover:underline">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('item-ins.destroy', $in->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                <i class="bi bi-trash3 text-red-500 hover:text-red-600"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-gray-500 italic bg-gray-50">
                                        Belum ada data barang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $itemIns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <button onclick="toggleCreateModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

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
                <div class="grid grid-cols-2 gap-4 mb-3">
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

                    {{-- Input dari kamera --}}
                    <input type="file" name="recipt_photo" accept="image/*" capture="environment"
                        class="w-full border-gray-300 rounded p-2 mb-2">

                    <div class="text-xs text-gray-500">Bisa pilih dari galeri atau langsung ambil foto dengan kamera.
                    </div>
                </div>


                {{-- Catatan --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Catatan</label>
                    <textarea name="note" rows="2" class="w-full border-gray-300 rounded p-2"
                        placeholder="Tuliskan keterangan tambahan jika perlu..."></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleCreateModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded">Simpan</button>
                </div>
            </form>

        </div>
    </div>

    {{-- Script --}}
    <script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        function updateTotalPrice() {
            const qty = document.querySelector('input[name="quantity"]').value || 0;
            const price = document.querySelector('input[name="unit_price"]').value || 0;
            const total = qty * price;
            document.getElementById('total_price').value = new Intl.NumberFormat('id-ID').format(total);
        }

        // Auto hide alert
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 500);
            }
        }, 4000);

        // Dropdown dinamis: Kategori → Barang
        document.getElementById('categorySelect').addEventListener('change', function() {
            const categoryId = this.value;
            const itemSelect = document.getElementById('itemSelect');
            itemSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
            itemSelect.disabled = true;

            if (categoryId) {
                fetch(`/get-items-by-category/${categoryId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.id;
                                option.textContent = item.name;
                                itemSelect.appendChild(option);
                            });
                            itemSelect.disabled = false;
                        } else {
                            const opt = document.createElement('option');
                            opt.textContent = 'Tidak ada barang di kategori ini';
                            itemSelect.appendChild(opt);
                        }
                    })
                    .catch(() => {
                        const opt = document.createElement('option');
                        opt.textContent = 'Gagal memuat data';
                        itemSelect.appendChild(opt);
                    });
            }
        });
    </script>
</x-app-layout>
