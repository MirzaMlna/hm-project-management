<div class="bg-white p-6 rounded-xl shadow-md col-span-2">
    {{-- Filter --}}
    <div class="flex flex-wrap justify-between items-end gap-3 mb-4">
        <form method="GET" action="{{ route('worker-presences.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="date" value="{{ request('date', \Carbon\Carbon::today()->toDateString()) }}"
                    class="mt-1 block w-full rounded border-gray-300 focus:border-sky-500 focus:ring-sky-500 text-sm">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700 transition flex items-center gap-2">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <button onclick="toggleExcelModal()"
            class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-600 transition flex items-center gap-2">
            <i class="bi bi-file-earmark-spreadsheet"></i>
        </button>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-sky-800 text-white">
                <tr>
                    <th class="p-2 text-start">No</th>
                    <th class="p-2">Kategori</th>
                    <th class="p-2">Nama</th>
                    <th class="p-2 text-start">Kode</th>
                    <th class="p-2 text-start">Presensi 1</th>
                    <th class="p-2 text-start">Presensi 2</th>
                    <th class="p-2 text-start">Presensi Pulang</th>
                    <th class="p-2 text-start">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presences as $index => $presence)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $index + 1 }}</td>
                        <td class="p-2">{{ $presence->worker->category->category ?? '-' }}</td>
                        <td class="p-2 font-medium text-gray-800">{{ $presence->worker->name }}</td>
                        <td class="p-2">{{ $presence->worker->code }}</td>

                        {{-- Presensi 1 --}}
                        <td class="p-2">
                            @if ($presence->first_check_in)
                                <span class="font-bold text-sky-700 text-lg">
                                    {{ \Carbon\Carbon::parse($presence->first_check_in)->format('H:i') }}
                                </span><br>
                                <span class="text-xs text-gray-600">
                                    {{ $presence->is_work_earlier ? 'Lebih Awal' : 'Tepat Waktu' }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Presensi 2 --}}
                        <td class="p-2">
                            @if ($presence->second_check_in)
                                <span class="font-bold text-green-700 text-lg">
                                    {{ \Carbon\Carbon::parse($presence->second_check_in)->format('H:i') }}
                                </span><br>
                                <span class="text-xs text-gray-600">Tepat Waktu</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Presensi Pulang --}}
                        <td class="p-2">
                            @if ($presence->check_out)
                                <span class="font-bold text-amber-700 text-lg">
                                    {{ \Carbon\Carbon::parse($presence->check_out)->format('H:i') }}
                                </span><br>
                                <span class="text-xs text-gray-600">
                                    @if ($presence->is_overtime)
                                        Lembur
                                    @elseif($presence->is_work_longer)
                                        Pulang Lambat
                                    @else
                                        Tepat Waktu
                                    @endif
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="p-2 text-center">
                            <form action="{{ route('worker-presences.destroy', $presence->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus presensi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-gray-500 text-center">
                            Belum ada presensi hari ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">
            {{ $presences->links() }}
        </div>
    </div>
</div>
