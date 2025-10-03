<x-app-layout>
    <style>
        #qr-reader video {
            transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Presensi Tukang
            </h2>
            {{-- Tanggal & Jam Sekarang --}}
            <div class="text-center">
                <p class="text-sm font-semibold text-gray-700">
                    <span id="current-time"></span> <br> <span id="current-date"></span>
                </p>
            </div>
        </div>
    </x-slot>



    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kartu Jadwal --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                @php
                    $cards = [
                        [
                            'label' => 'Presensi Pertama',
                            'color' => 'blue',
                            'start' => $worker_presence_schedules->first_check_in_start,
                            'end' => $worker_presence_schedules->first_check_in_end,
                        ],
                        [
                            'label' => 'Presensi Kedua',
                            'color' => 'green',
                            'start' => $worker_presence_schedules->second_check_in_start,
                            'end' => $worker_presence_schedules->second_check_in_end,
                        ],
                        [
                            'label' => 'Presensi Pulang',
                            'color' => 'amber',
                            'start' => $worker_presence_schedules->check_out_start,
                            'end' => $worker_presence_schedules->check_out_end,
                        ],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-{{ $card['color'] }}-500">
                        <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($card['start'])->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($card['end'])->format('H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Grid Scanner & Tabel --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Scanner QR --}}
                <div class="bg-white p-4 rounded-lg shadow col-span-1">
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="bi bi-qr-code-scan mr-2"></i> Scan QR Code
                    </h3>
                    <div id="qr-reader" class="w-full"></div>
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-center">
                        <p class="text-sm text-red-700 font-semibold">
                            Belum Absen: <span class="text-xl font-bold">{{ $notPresentCount }}</span> Tukang
                        </p>
                    </div>

                </div>

                {{-- Tabel Presensi --}}
                <div class="bg-white p-6 rounded-lg shadow col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        {{-- <h3 class="text-lg font-semibold">
                            <i class="bi bi-list-task mr-2"></i> Hasil Scan
                        </h3> --}}
                        <form method="GET" action="{{ route('worker-presences.index') }}" class="mb-4">
                            <div class="flex flex-wrap items-end gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="date"
                                        value="{{ request('date', \Carbon\Carbon::today()->toDateString()) }}"
                                        class="mt-1 block w-full rounded border-gray-300">
                                </div>
                                <div>
                                    <button type="submit"
                                        class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-500">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>


                        <div class="flex flex-wrap items-end mt-2">
                            <button type="submit" onclick="toggleExcelModal()"
                                class="px-4 py-2 !bg-green-700 hover:!bg-green-600 text-white mr-2 rounded">
                                <i class="bi bi-file-earmark-spreadsheet"></i>
                            </button>
                            <button type="submit" onclick="toggleExcelModal()"
                                class="px-4 py-2 !bg-red-700 hover:!bg-red-600 text-white mr-2 rounded">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg overflow-hidden border border-gray-200">
                        <table class="min-w-full text-sm">
                            <thead class="bg-sky-800 text-white">
                                <tr>
                                    <th class="p-2 text-center">No</th>
                                    <th class="p-2">Kategori</th>
                                    <th class="p-2">Nama</th>
                                    <th class="p-2 text-center">Kode</th>
                                    <th class="p-2 text-center">Presensi 1</th>
                                    <th class="p-2 text-center">Presensi 2</th>
                                    <th class="p-2 text-center">Presensi Pulang</th>
                                    <th class="p-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presences as $index => $presence)
                                    <tr class="text-center">
                                        <td class="p-2">{{ $index + 1 }}</td>
                                        <td class="p-2">{{ $presence->worker->category->category ?? '-' }}</td>
                                        <td class="p-2">{{ $presence->worker->name }}</td>
                                        <td class="p-2">{{ $presence->worker->code }}</td>
                                        <td class="p-2">
                                            @if ($presence->first_check_in)
                                                <span class="font-bold text-lg">
                                                    {{ \Carbon\Carbon::parse($presence->first_check_in)->format('H:i') }}
                                                </span><br>
                                                {{ $presence->is_work_earlier ? 'Datang Lebih Awal' : 'Tepat Waktu' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-2">
                                            @if ($presence->second_check_in)
                                                <span class="font-bold text-lg">
                                                    {{ \Carbon\Carbon::parse($presence->second_check_in)->format('H:i') }}
                                                </span><br>
                                                Tepat Waktu
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-2">
                                            @if ($presence->check_out)
                                                <span class="font-bold text-lg">
                                                    {{ \Carbon\Carbon::parse($presence->check_out)->format('H:i') }}
                                                </span><br>
                                                @if ($presence->is_overtime)
                                                    Lembur Malam
                                                @elseif($presence->is_work_longer)
                                                    Pulang Lebih Lambat
                                                @else
                                                    Tepat Waktu
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-2">
                                            <form action="{{ route('worker-presences.destroy', $presence->id) }}"
                                                method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus presensi ini?')">
                                                    <i class="bi bi-trash3 text-red-500"></i>
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
            </div>

        </div>
    </div>

    {{-- Modal Export Excel (tetap pakai modal) --}}
    <div id="excelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <button onclick="toggleExcelModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h3 class="text-lg font-semibold mb-4">Export Presensi ke Excel</h3>
            <form id="export-form" method="POST" action="{{ route('worker-presences.export') }}">
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
                        class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-500">
                        Generate Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleExcelModal() {
            document.getElementById("excelModal").classList.toggle("hidden");
        }

        function submitExport() {
            const from = document.getElementById('date_from').value;
            const to = document.getElementById('date_to').value;
            if (!from || !to) return alert('Pilih tanggal mulai dan akhir.');

            const fromDate = new Date(from);
            const toDate = new Date(to);
            if (toDate < fromDate) return alert('Tanggal akhir harus sama/lebih besar dari tangqrgal mulai.');

            const diffDays = Math.floor((toDate - fromDate) / (1000 * 60 * 60 * 24)) + 1;
            if (diffDays > 30) return alert('Periode maksimal 30 hari.');

            document.getElementById('export-form').submit();
            toggleExcelModal();
        }

        // Update Jam & Tanggal
        function updateDateTime() {
            const now = new Date();
            document.getElementById("current-date").innerText = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById("current-time").innerText = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // QR Scanner langsung aktif
        document.addEventListener("DOMContentLoaded", () => {
            const qrReader = document.getElementById("qr-reader");
            if (!qrReader) return;

            let isCooldown = false;

            new Html5QrcodeScanner("qr-reader", {
                fps: 10,
                qrbox: 250
            }).render((decodedText) => {
                if (isCooldown) return;
                isCooldown = true;

                // 1. Ambil preview data tukang
                fetch(`/presences/preview/${decodedText}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status !== 'success') {
                            Swal.fire({
                                icon: 'error',
                                title: 'QR Tidak Valid',
                                text: data.message
                            });
                            isCooldown = false;
                            return;
                        }

                        // 2. Tampilkan modal konfirmasi
                        Swal.fire({
                            title: 'Konfirmasi Presensi',
                            html: `
        <table class="swal2-table" style="width:100%;text-align:left;border-collapse:collapse;margin-top:10px">
            <tr>
                <th style="padding:4px;border:1px solid #ccc">Foto</th>
                <td style="padding:4px;border:1px solid #ccc">
                    ${data.worker.photo 
                        ? `<img src="${data.worker.photo}" alt="${data.worker.name}"
                                                    style="width:120px;height:120px;object-cover;border-radius:8px;border:2px solid #ccc;box-shadow:0 2px 4px rgba(0,0,0,0.1);">`
                        : `<div style="width:120px;height:120px;display:flex;align-items:center;justify-content:center;
                                                       background:#e5e7eb;border-radius:8px;color:#6b7280;font-style:italic;">
                                                   Tidak ada foto
                                               </div>`}
                </td>
            </tr>
            <tr>
                <th style="padding:4px;border:1px solid #ccc">Nama</th>
                <td style="padding:4px;border:1px solid #ccc">${data.worker.name}</td>
            </tr>
            <tr>
                <th style="padding:4px;border:1px solid #ccc">Kode</th>
                <td style="padding:4px;border:1px solid #ccc">${data.worker.code}</td>
            </tr>
            <tr>
                <th style="padding:4px;border:1px solid #ccc">Kategori</th>
                <td style="padding:4px;border:1px solid #ccc">${data.worker.category}</td>
            </tr>
        </table>
    `,
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'OK Presensi',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // 3. Kalau OK baru simpan ke DB
                                fetch(`/presences/verify/${decodedText}`)
                                    .then(res => res.json())
                                    .then(resp => {
                                        Swal.fire({
                                            icon: resp.status === 'success' ?
                                                'success' : 'error',
                                            title: resp.message
                                        }).then(() => {
                                            if (resp.status === 'success') location
                                                .reload();
                                        });
                                    })
                                    .catch(() => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal menyimpan presensi'
                                        });
                                    })
                                    .finally(() => {
                                        setTimeout(() => {
                                            isCooldown = false;
                                        }, 2000);
                                    });
                            } else {
                                isCooldown = false; // batal
                            }
                        });
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal membaca QR'
                        });
                        isCooldown = false;
                    });
            });
        });
    </script>

</x-app-layout>
