<div class="bg-white p-6 rounded-xl shadow-md col-span-2">
    {{-- Filter --}}
    <div class="flex flex-wrap justify-between items-end gap-3 mb-4">
        <form method="GET" action="{{ route('worker-presences.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700">Tanggal</label>
                <input type="date" name="date" value="{{ request('date', \Carbon\Carbon::today()->toDateString()) }}"
                    class="mt-1 block w-full rounded border-gray-300 focus:border-sky-500 focus:ring-sky-500 text-xs">
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
        <table class="min-w-full text-xs">
            <thead class="bg-sky-800 text-white">
                <tr>
                    <th class="p-2 text-start">No</th>
                    <th class="p-2">Kategori</th>
                    <th class="p-2">Nama</th>
                    <th class="p-2 text-start">Kode</th>
                    <th class="p-2 text-start">Presensi 1</th>
                    <th class="p-2 text-start">Presensi 2</th>
                    <th class="p-2 text-start">Jam Lembur</th>
                    <th class="p-2 text-start">Lembur Malam</th>
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
                                ✅
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Presensi 2 --}}
                        <td class="p-2">
                            @if ($presence->second_check_in)
                                ✅
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Jam Lembur --}}
                        <td class="p-2">
                            <form action="{{ route('worker-presences.update', $presence->id) }}" method="POST"
                                class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="work_longer_count"
                                    value="{{ old('work_longer_count', $presence->work_longer_count) }}"
                                    class="w-10 border rounded p-1 text-center focus:ring focus:ring-sky-200 text-xs">
                                <button type="submit" class="text-sky-700 hover:text-sky-900">
                                    <i class="bi bi-save"></i>
                                </button>
                            </form>
                        </td>

                        {{-- Lembur Malam --}}
                        <td class="p-2 text-center">
                            <form action="{{ route('worker-presences.update', $presence->id) }}" method="POST"
                                class="flex justify-center items-center">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="work_longer_count"
                                    value="{{ $presence->work_longer_count }}">
                                <input type="checkbox" name="is_overtime" value="1"
                                    {{ $presence->is_overtime ? 'checked' : '' }} onchange="this.form.submit()"
                                    class="rounded border-gray-300 text-sky-600 focus:ring-sky-500 w-5 h-5 cursor-pointer">
                            </form>
                        </td>

                        {{-- Hapus --}}
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
                        <td colspan="9" class="p-4 text-gray-500 text-center">
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
