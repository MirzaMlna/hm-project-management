<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Log Pergerakan Barang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 🔹 Tabel Log --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- 🔹 Filter & Tombol Export --}}
                <div class="flex flex-wrap justify-between items-center px-6 pt-6 gap-3">
                    <form method="GET" action="{{ route('item-logs.index') }}" class="flex items-center gap-2">
                        <label for="month" class="text-sm text-gray-600">Filter Bulan :</label>
                        <select id="month" name="month" onchange="this.form.submit()"
                            class="border-gray-300 rounded-md text-sm p-2">
                            @foreach ($months as $month)
                                <option value="{{ $month['value'] }}"
                                    {{ $selectedMonth == $month['value'] ? 'selected' : '' }}>
                                    {{ $month['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    {{-- 🔹 Tombol Export --}}
                    <a href="{{ route('item-logs.export', ['month' => $selectedMonth]) }}"
                        class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md shadow">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>


                {{-- 🔹 Tabel --}}
                <div class="overflow-x-auto p-6">
                    <div class="mb-2 text-sm text-gray-600 italic">
                        Menampilkan data bulan
                        <span class="font-semibold text-sky-700">
                            {{ \Carbon\Carbon::parse($selectedMonth . '-01')->translatedFormat('F Y') }}
                        </span>
                    </div>

                    <table class="min-w-full text-sm text-left text-gray-600 border">
                        <thead class="bg-gray-100 text-gray-700 uppercase">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3 text-green-700 text-center">Barang Masuk</th>
                                <th class="px-4 py-3 text-red-700 text-center">Barang Keluar</th>
                                <th class="px-4 py-3 text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $index => $log)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        {{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3">{{ $log['kategori'] }}</td>
                                    <td class="px-4 py-3">{{ $log['jenis'] }}</td>
                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($log['tanggal'])->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-green-600 font-semibold text-center">
                                        {{ $log['barang_masuk'] ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-red-600 font-semibold text-center">
                                        {{ $log['barang_keluar'] ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-center">
                                        {{ $log['stok'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                        Tidak ada data log
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 🔹 Pagination --}}
            <div class="mt-4 px-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
