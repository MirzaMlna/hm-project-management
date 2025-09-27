<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tukang
            </h2>
            <a href="{{ route('workers.inactive') }}" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded">
                <span><i class="bi bi-person-slash me-2"></i></span>Tukang Nonaktif
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class=" max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Statistik -->
            <div class="py-6">
                <div class="mx-auto sm:px-4 lg:px-8">
                    <!-- Enhanced Stat Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <!-- Total Workers -->
                        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-blue-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Tukang</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $totalWorkers }}</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="bi bi-people text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Active Workers -->
                        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-green-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tukang Aktif</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $activeWorkers }}</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Salary -->
                        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-amber-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Gaji Harian</p>
                                    <p class="text-2xl font-bold text-gray-800">
                                        Rp{{ number_format($totalDailySalary, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="bg-amber-100 p-3 rounded-full">
                                    <i class="bi bi-wallet2 text-amber-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tabel Tukang -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold"><span><i
                                            class="bi bi-person-check me-2"></i></span>Daftar Tukang Aktif</h3>
                                <a href="{{ route('workers.create') }}"
                                    class="bg-sky-800 hover:bg-sky-700 text-white px-4 py-2 rounded">
                                    <span><i class="bi bi-person-plus me-2"></i></span>Tambah Tukang
                                </a>

                            </div>


                            <div class="overflow-x-auto rounded-lg overflow-hidden border border-gray-200">
                                <table class="min-w-full text-sm ">
                                    <thead class="bg-sky-800 text-white">
                                        <tr>
                                            <th class="px-4 py-2 ">NO</th>
                                            <th class="px-4 py-2 ">KATEGORI</th>
                                            <th class="px-4 py-2 ">NAMA</th>
                                            <th class="px-4 py-2 ">KODE</th>
                                            <th class="px-4 py-2 ">GAJI HARIAN (Rp.)</th>
                                            <th class="px-4 py-2 ">NO. TELP</th>
                                            <th class="px-4 py-2 ">USIA</th>
                                            <th class="px-4 py-2 ">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($workers as $index => $worker)
                                            <tr class="text-center">
                                                <td class="px-4 py-2 ">{{ $workers->firstItem() + $index }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $worker->category ? $worker->category->category : '-' }}
                                                </td>
                                                <td class="px-4 py-2 ">{{ $worker->name }}</td>
                                                <td class="px-4 py-2 ">{{ $worker->code }}</td>
                                                <td class="px-4 py-2 ">
                                                    {{ number_format($worker->daily_salary, 0, ',', '.') }}</td>
                                                <td class="px-4 py-2 ">{{ $worker->phone }}</td>
                                                <td class="px-4 py-2 ">
                                                    @if ($worker->birth_date)
                                                        {{ \Carbon\Carbon::parse($worker->birth_date)->age }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>


                                                <td class="px-4 py-2 flex gap-2 justify-center items-center">
                                                    <a href="{{ route('workers.show', $worker->id) }}"
                                                        title="Cetak ID Card Tukang"
                                                        class="text-blue-600 hover:text-blue-900"><i
                                                            class="bi bi-person-vcard"></i></a>
                                                    <a href="{{ route('workers.edit', $worker->id) }}"
                                                        title="Edit Tukang"
                                                        class="text-yellow-600 hover:text-yellow-900"><i
                                                            class="bi bi-pencil"></i></a>
                                                    <!-- Tombol Nonaktifkan -->
                                                    <button class="text-gray-600 hover:text-gray-900"
                                                        onclick="openDeactivateModal({{ $worker->id }}, '{{ $worker->name }}')"
                                                        title="Nonaktifkan Tukang">
                                                        <i class="bi bi-person-slash"></i>
                                                    </button>

                                                    <form action="{{ route('workers.destroy', $worker->id) }}"
                                                        method="POST" onsubmit="return confirm('Hapus data ini?')"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Hapus Tukang"
                                                            class="text-red-600 hover:text-red-900"><i
                                                                class="bi bi-trash"></i></button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada
                                                    tukang
                                                    ditambahkan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <!-- Modal Nonaktifkan (letakkan modal ini di bawah tabel atau di akhir blade) -->
                                <div id="deactivateModal"
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                                    <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
                                        <h3 class="text-lg mb-4">Nonaktifkan Tukang: <br> <span id="workerNameModal"
                                                class="font-bold"></span></h3>

                                        <form id="deactivateForm" method="POST" action="">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="note"
                                                    class="block text-sm font-medium text-gray-700 mb-1">Catatan (wajib
                                                    diisi):</label>
                                                <textarea id="note" name="note" required class="w-full border border-gray-300 rounded px-3 py-2" rows="3"
                                                    placeholder="Masukkan alasan nonaktifkan tukang..."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-3">
                                                <button type="button" onclick="closeDeactivateModal()"
                                                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                                                <button type="submit"
                                                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Nonaktifkan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $workers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>
<script>
    function openDeactivateModal(workerId, workerName) {
        document.getElementById('deactivateModal').classList.remove('hidden');
        document.getElementById('workerNameModal').textContent = workerName;
        const form = document.getElementById('deactivateForm');
        form.action = '/workers/' + workerId + '/deactivate';
        form.note.value = '';
    }

    function closeDeactivateModal() {
        document.getElementById('deactivateModal').classList.add('hidden');
    }
</script>
