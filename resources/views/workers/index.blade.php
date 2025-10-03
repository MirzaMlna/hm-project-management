<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tukang
            </h2>
            <a href="{{ route('workers.inactive') }}">
                <x-primary-button class="!bg-gray-500 hover:!bg-gray-600 !text-white">
                    <i class="bi bi-person-slash me-2"></i> Tukang Nonaktif
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert --}}
            @if (session('success'))
                <div class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <!-- Total Workers -->
                <div
                    class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Tukang</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalWorkers }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="bi bi-people text-blue-600 text-2xl"></i>
                    </div>
                </div>

                <!-- Active Workers -->
                <div
                    class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Tukang Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $activeWorkers }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="bi bi-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>

                <!-- Daily Salary -->
                <div
                    class="bg-white rounded-xl shadow-md p-5 border-l-4 border-amber-500 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Gaji Harian</p>
                        <p class="text-2xl font-bold text-gray-800">
                            Rp{{ number_format($totalDailySalary, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-amber-100 p-3 rounded-full">
                        <i class="bi bi-wallet2 text-amber-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Tabel Tukang -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <i class="bi bi-person-check"></i> Daftar Tukang Aktif
                    </h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <button onclick="openImportModal()"
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded flex items-center gap-2">
                            <i class="bi bi-upload"></i>
                        </button>
                        <a href="{{ route('workers.create') }}"
                            class="px-4 py-2 bg-sky-800 hover:bg-sky-700 text-white rounded flex items-center gap-2">
                            <i class="bi bi-person-plus"></i>
                        </a>
                        <a href="{{ route('workers.printAll') }}"
                            class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded flex items-center gap-2">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm">
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
                                <tr class="text-center border-b">
                                    <td class="px-4 py-2">{{ $workers->firstItem() + $index }}</td>
                                    <td class="px-4 py-2">{{ $worker->category->category ?? '-' }}</td>
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
                                        <button
                                            onclick="openDeactivateModal({{ $worker->id }}, '{{ $worker->name }}')"
                                            title="Nonaktifkan" class="text-gray-600 hover:text-gray-900">
                                            <i class="bi bi-person-slash"></i>
                                        </button>
                                        <form action="{{ route('workers.destroy', $worker->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus data ini?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-4 text-center text-gray-500">Tidak ada tukang
                                        ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $workers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nonaktifkan -->
    <div id="deactivateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <h3 class="text-lg mb-4 font-semibold">Nonaktifkan Tukang</h3>
            <p class="mb-2">Nama: <span id="workerNameModal" class="font-bold text-red-600"></span></p>
            <form id="deactivateForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan <span
                            class="text-red-500">*</span></label>
                    <textarea id="note" name="note" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-red-200" rows="3"
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
    <!-- Modal Import Excel -->
    <div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <h3 class="text-lg mb-4 font-semibold">Import Tukang dari Excel</h3>
            <form action="{{ route('workers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File Excel</label>
                    <input type="file" name="file" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-amber-200">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeImportModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-700">Upload</button>
                </div>
            </form>
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

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }
</script>
