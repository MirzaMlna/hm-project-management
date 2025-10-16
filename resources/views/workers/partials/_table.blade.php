<div class="overflow-x-auto border rounded-lg">
    <table class="min-w-full text-xs">
        <thead class="bg-sky-800 text-white">
            <tr>
                <th class="px-4 py-3">NO</th>
                <th class="px-4 py-3">KATEGORI</th>
                <th class="px-4 py-3">NAMA</th>
                <th class="px-4 py-3">KODE</th>
                <th class="px-4 py-3">GAJI HARIAN (Rp.)</th>
                <th class="px-4 py-3">NO. TELP</th>
                <th class="px-4 py-3">USIA</th>
                <th class="px-4 py-3">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($workers as $index => $worker)
                <tr class="text-start border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $workers->firstItem() + $index }}</td>
                    <td class="px-4 py-2 text-gray-500">{{ $worker->category->category ?? '-' }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $worker->name }}</td>
                    <td class="px-4 py-2">{{ $worker->code }}</td>
                    <td class="px-4 py-2">{{ number_format($worker->daily_salary, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $worker->phone }}</td>
                    <td class="px-4 py-2">
                        {{ $worker->birth_date ? \Carbon\Carbon::parse($worker->birth_date)->age : '-' }}
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-3">
                        <a href="{{ route('workers.show', $worker->id) }}" title="Cetak ID Card"
                            class="text-blue-600 hover:text-blue-900">
                            <i class="bi bi-person-vcard"></i>
                        </a>
                        <a href="{{ route('workers.edit', $worker->id) }}" title="Edit"
                            class="text-yellow-600 hover:text-yellow-900">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button onclick="openDeactivateModal({{ $worker->id }}, '{{ $worker->name }}')"
                            title="Nonaktifkan" class="text-gray-600 hover:text-gray-900">
                            <i class="bi bi-person-slash"></i>
                        </button>
                        <form action="{{ route('workers.destroy', $worker->id) }}" method="POST"
                            onsubmit="return confirm('Hapus data ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus" class="text-red-600 hover:text-red-900">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-4 text-start text-gray-500">
                        Tidak ada tukang ditambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
