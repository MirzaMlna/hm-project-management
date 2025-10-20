<div class="p-6 overflow-x-auto">
    <div class="mb-2 text-sm text-gray-600 italic">
        Menampilkan data bulan
        <span class="font-semibold text-sky-700">
            {{ \Carbon\Carbon::parse($selectedMonth . '-01')->translatedFormat('F Y') }}
        </span>
    </div>

    <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200">
        <thead class="text-xs text-white uppercase bg-sky-700">
            <tr>
                <th class="px-4 py-3 w-12">#</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Jenis</th>
                <th class="px-4 py-3">Supplier</th>
                <th class="px-4 py-3">Jumlah</th>
                <th class="px-4 py-3">Harga Satuan</th>
                <th class="px-4 py-3">Total Harga</th>
                <th class="px-4 py-3">Tanggal Beli</th>
                <th class="px-4 py-3">Nota</th>
                <th class="px-4 py-3">Foto Barang</th>
                <th class="px-4 py-3 w-20">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($itemIns as $in)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $in->item->category->category ?? '-' }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $in->item->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $in->supplier->supplier ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $in->quantity }}</td>
                    <td class="px-4 py-3">Rp{{ number_format($in->unit_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 font-semibold text-sky-700">
                        Rp{{ number_format($in->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($in->purchase_date)->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @if ($in->recipt_photo)
                            <a href="{{ asset('storage/' . $in->recipt_photo) }}" target="_blank"
                                class="text-sky-600 hover:underline">Lihat</a>
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($in->item_in_photo)
                            <a href="{{ asset('storage/' . $in->item_in_photo) }}" target="_blank"
                                class="text-sky-600 hover:underline">Lihat</a>
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
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
                    <td colspan="11" class="text-center py-4 text-gray-500 italic bg-gray-50">
                        Belum ada data barang masuk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $itemIns->links() }}
    </div>
</div>
