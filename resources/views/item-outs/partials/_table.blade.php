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
                <th class="px-4 py-3 text-start w-12">#</th>
                <th class="px-4 py-3">Barang</th>
                <th class="px-4 py-3">Titik Pembangunan</th>
                <th class="px-4 py-3 text-start">Jumlah</th>
                <th class="px-4 py-3 text-start">Tanggal Keluar</th>
                <th class="px-4 py-3">Catatan</th>
                <th class="px-4 py-3 text-start w-20">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($itemOuts as $out)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $out->item->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $out->developmentPoint->development_point ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $out->quantity }}</td>
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($out->date_out)->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-4 py-3">{{ $out->note ?? '-' }}</td>
                    <td class="px-4 py-3">
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
