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
                    @include('worker-presences._table-row', ['presence' => $presence, 'index' => $index])
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
