<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Barang Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses / error --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm transition-opacity duration-500">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Header kiri-kanan --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-6 mt-6 gap-3">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-box-arrow-up text-sky-600"></i> Daftar Barang Keluar
                    </h3>

                    <div class="flex flex-row items-center gap-3 w-full sm:w-auto">
                        {{-- Filter Bulan --}}
                        <form method="GET" action="{{ route('item-outs.index') }}"
                            class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="month" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                Filter Bulan:
                            </label>
                            <input type="month" id="month" name="month"
                                value="{{ $selectedMonth ?? now()->format('Y-m') }}"
                                class="border-gray-300 rounded p-2 text-sm w-full sm:w-auto"
                                onchange="this.form.submit()">
                        </form>

                        {{-- Tombol Tambah --}}
                        <x-primary-button class="w-10 h-10 flex items-center justify-center"
                            onclick="toggleCreateModal()" title="Tambah Barang Keluar">
                            <i class="bi bi-plus-circle text-lg"></i>
                        </x-primary-button>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="p-6 overflow-x-auto">
                    <div class="mb-2 text-sm text-gray-600 italic">
                        Menampilkan data bulan
                        <span class="font-semibold text-sky-700">
                            {{ \Carbon\Carbon::parse(($selectedMonth ?? now()->format('Y-m')) . '-01')->translatedFormat('F Y') }}
                        </span>
                    </div>

                    <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200">
                        <thead class="text-xs text-white uppercase bg-sky-700">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">#</th>
                                <th class="px-4 py-3">Barang</th>
                                <th class="px-4 py-3">Titik Pembangunan</th>
                                <th class="px-4 py-3 text-center">Jumlah</th>
                                <th class="px-4 py-3 text-center">Tanggal Keluar</th>
                                <th class="px-4 py-3">Catatan</th>
                                <th class="px-4 py-3 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($itemOuts as $out)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $out->item->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $out->developmentPoint->development_point ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $out->quantity }}</td>
                                    <td class="px-4 py-3 text-center">
                                        {{ \Carbon\Carbon::parse($out->date_out)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">{{ $out->note ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('item-outs.destroy', $out->id) }}" method="POST"
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
                                    <td colspan="7" class="text-center py-4 text-gray-500 italic bg-gray-50">
                                        Belum ada data barang keluar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $itemOuts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded-lg shadow-lg w-full sm:max-w-md md:max-w-lg lg:max-w-xl p-6 relative">
            <button onclick="toggleCreateModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

            <h3 class="text-lg font-semibold mb-4">Tambah Barang Keluar</h3>

            <form method="POST" action="{{ route('item-outs.store') }}">
                @csrf

                {{-- Pilih Kategori --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Kategori Barang</label>
                    <select id="categorySelect" name="item_category_id" class="w-full border-gray-300 rounded p-2"
                        required>
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

                {{-- Titik Pembangunan --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Titik Pembangunan</label>
                    <select name="development_point_id" required class="w-full border-gray-300 rounded p-2">
                        <option value="">-- Pilih Titik --</option>
                        @foreach ($points as $pt)
                            <option value="{{ $pt->id }}">{{ $pt->development_point }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Jumlah</label>
                    <input type="number" name="quantity" min="1" required
                        class="w-full border-gray-300 rounded p-2">
                </div>

                {{-- Tanggal Keluar --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Tanggal Keluar</label>
                    <input type="date" name="date_out" required class="w-full border-gray-300 rounded p-2"
                        value="{{ \Carbon\Carbon::today()->toDateString() }}">
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

    {{-- Script --}}
    <script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        // Auto hide alert
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 500);
            }
        }, 4000);

        // Dropdown dinamis kategori -> barang
        document.getElementById('categorySelect').addEventListener('change', function() {
            const categoryId = this.value;
            const itemSelect = document.getElementById('itemSelect');
            itemSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
            itemSelect.disabled = true;

            if (categoryId) {
                fetch(`/get-items-by-category/${categoryId}`)
                    .then(res => res.json())
                    .then(data => {
                        console.log(data); // debug hasil fetch
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
