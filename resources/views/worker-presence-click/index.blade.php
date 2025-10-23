<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Presensi Tukang (Manual Checklist)</h2>

            <div class="flex items-center gap-2">
                {{-- Filter tanggal --}}
                <form method="GET" action="{{ route('worker-presences-click.index') }}" class="flex items-center gap-1">
                    <input type="date" name="date" value="{{ $date }}"
                        class="rounded border-gray-300 focus:border-sky-500 focus:ring-sky-500 text-sm">
                    <button type="submit"
                        class="px-3 py-2 bg-sky-600 text-white rounded hover:bg-sky-700 text-sm flex items-center gap-1">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </form>

                {{-- Tombol Export Excel --}}
                <button onclick="toggleExcelModal()"
                    class="px-3 py-2 bg-green-700 text-white rounded hover:bg-green-800 text-sm flex items-center gap-1">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert sukses/gagal --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div id="alert-error"
                    class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm transition-opacity duration-500">
                    ❌ {{ session('error') }}
                </div>
            @endif

            {{-- Form Presensi --}}
            <form method="POST" action="{{ route('worker-presences-click.save-all') }}">
                @csrf
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-sky-800 text-white">
                            <tr>
                                <th class="p-2 text-center">#</th>
                                <th class="p-2">Kategori</th>
                                <th class="p-2">Nama</th>
                                <th class="p-2 text-center">Presensi 1</th>
                                <th class="p-2 text-center">Presensi 2</th>
                                <th class="p-2 text-center">Jam Lembur</th>
                                <th class="p-2 text-center">Lembur Malam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workers as $index => $worker)
                                @php
                                    $presence = $presences[$worker->id] ?? null;
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2 text-center">{{ $index + 1 }}</td>
                                    <td class="p-2">{{ $worker->category->category ?? '-' }}</td>
                                    <td class="p-2 font-medium">{{ $worker->name }}</td>

                                    {{-- Presensi 1 --}}
                                    <td class="p-2 text-center">
                                        <input type="checkbox" name="presences[{{ $worker->id }}][first_check_in]"
                                            value="1"
                                            class="w-5 h-5 text-sky-600 focus:ring-sky-500 presence-toggle"
                                            {{ $presence?->first_check_in ? 'checked' : '' }}>
                                    </td>

                                    {{-- Presensi 2 --}}
                                    <td class="p-2 text-center">
                                        <input type="checkbox" name="presences[{{ $worker->id }}][second_check_in]"
                                            value="1"
                                            class="w-5 h-5 text-green-600 focus:ring-green-500 presence-toggle"
                                            {{ $presence?->second_check_in ? 'checked' : '' }}>
                                    </td>

                                    {{-- Jam Lembur --}}
                                    <td class="p-2 text-center">
                                        <input type="number" min="0"
                                            name="presences[{{ $worker->id }}][work_longer_count]"
                                            value="{{ $presence?->work_longer_count ?? 0 }}"
                                            class="w-16 border rounded text-center text-xs focus:ring focus:ring-sky-200 overtime-hours"
                                            {{ $presence?->is_overtime ? 'disabled' : '' }}>
                                    </td>

                                    {{-- Lembur Malam --}}
                                    <td class="p-2 text-center">
                                        <input type="checkbox" name="presences[{{ $worker->id }}][is_overtime]"
                                            value="1"
                                            class="w-5 h-5 text-amber-600 focus:ring-amber-500 overtime-toggle"
                                            {{ $presence?->is_overtime ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-5 text-right">
                        <button type="submit"
                            class="px-5 py-2 bg-sky-700 text-white rounded hover:bg-sky-800 text-sm font-semibold">
                            <i class="bi bi-save"></i> Simpan Semua
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 🟩 Modal Export Excel --}}
    <div id="excelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <button onclick="toggleExcelModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Export Presensi ke Excel</h3>
            <form id="export-form" method="POST" action="{{ route('worker-presences-click.export') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Tanggal Mulai</label>
                        <input type="date" name="date_from" id="date_from"
                            value="{{ old('date_from', \Carbon\Carbon::today()->subDays(6)->toDateString()) }}"
                            required class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Tanggal Akhir</label>
                        <input type="date" name="date_to" id="date_to"
                            value="{{ old('date_to', \Carbon\Carbon::today()->toDateString()) }}" required
                            class="mt-1 block w-full rounded border-gray-300">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="submitExport()"
                        class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-600 transition">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Generate Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert + Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 🧭 Modal Excel
        function toggleExcelModal() {
            document.getElementById("excelModal").classList.toggle("hidden");
        }

        // 📅 Validasi Export
        function submitExport() {
            const from = document.getElementById('date_from').value;
            const to = document.getElementById('date_to').value;

            if (!from || !to) return alert('Pilih tanggal mulai dan akhir.');

            const fromDate = new Date(from);
            const toDate = new Date(to);

            if (toDate < fromDate) return alert('Tanggal akhir harus >= tanggal mulai.');

            const diffDays = Math.floor((toDate - fromDate) / (1000 * 60 * 60 * 24)) + 1;
            if (diffDays > 30) return alert('Periode maksimal 30 hari.');

            document.getElementById('export-form').submit();
            toggleExcelModal();
        }

        // 🔁 Auto-hide alert
        setTimeout(() => {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');
            [successAlert, errorAlert].forEach(alert => {
                if (alert) {
                    alert.classList.add('opacity-0');
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);

        // 🟦 Konfirmasi batal presensi
        document.querySelectorAll('.presence-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', async (e) => {
                if (!e.target.checked) {
                    const result = await Swal.fire({
                        icon: 'warning',
                        title: 'Batalkan Presensi?',
                        text: 'Apakah Anda yakin ingin membatalkan presensi ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, batalkan',
                        cancelButtonText: 'Tidak',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280'
                    });

                    if (!result.isConfirmed) e.target.checked = true;
                }
            });
        });

        // 🟩 Otomatis 0 dan disable saat lembur malam aktif
        document.querySelectorAll('.overtime-toggle').forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                const row = e.target.closest('tr');
                const inputJam = row.querySelector('.overtime-hours');

                if (e.target.checked) {
                    inputJam.value = 0;
                    inputJam.disabled = true;
                } else {
                    inputJam.disabled = false;
                }
            });
        });
    </script>
</x-app-layout>
