<x-app-layout>
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

            {{-- Tabel Presensi --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold"><span><i class="bi bi-scan"></i></span> Hasil Scan</h3>
                    <div class="flex justify-end">
                        <x-primary-button onclick="toggleQrModal()" class="mr-2">
                            <i class="bi bi-qr-code-scan mr-2"></i> Scan QR Code
                        </x-primary-button>
                        <x-primary-button onclick="toggleExcelModal()" class="!bg-green-700 hover:!bg-green-600 mr-2">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                        </x-primary-button>

                        <x-primary-button onclick="" class="!bg-red-700 hover:!bg-red-600">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </x-primary-button>
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
                                            <span
                                                class="font-bold text-lg">{{ \Carbon\Carbon::parse($presence->first_check_in)->format('H:i') }}</span><br>
                                            {{ $presence->is_work_earlier ? 'Datang Lebih Awal' : 'Tepat Waktu' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-2">
                                        @if ($presence->second_check_in)
                                            <span
                                                class="font-bold text-lg">{{ \Carbon\Carbon::parse($presence->second_check_in)->format('H:i') }}</span><br>
                                            Tepat Waktu
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-2">
                                        @if ($presence->check_out)
                                            <span
                                                class="font-bold text-lg">{{ \Carbon\Carbon::parse($presence->check_out)->format('H:i') }}</span><br>
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
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus presensi ini?')">
                                                <i class="bi bi-trash3 text-red-500"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-gray-500 text-center">Belum ada presensi hari
                                        ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Export Excel --}}
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

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Kategori</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded border-gray-300">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Periode maksimal 30 hari.</p>
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


    {{-- Modal Scan QR --}}
    <div id="qrModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            {{-- Tombol Close --}}
            <button onclick="toggleQrModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                ✕
            </button>
            <h3 class="text-lg font-semibold mb-4">Scan QR Code</h3>
            <div id="qr-reader" style="width:100%;"></div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle Modal QR
        function toggleQrModal() {
            document.getElementById("qrModal").classList.toggle("hidden");
        }

        function toggleExcelModal() {
            document.getElementById("excelModal").classList.toggle("hidden");
        }

        function submitExport() {
            const from = document.getElementById('date_from').value;
            const to = document.getElementById('date_to').value;

            if (!from || !to) {
                alert('Pilih tanggal mulai dan akhir.');
                return;
            }

            const fromDate = new Date(from);
            const toDate = new Date(to);

            if (toDate < fromDate) {
                alert('Tanggal akhir harus sama atau setelah tanggal mulai.');
                return;
            }

            const diffTime = toDate - fromDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
            if (diffDays > 30) {
                alert('Periode maksimal 30 hari.');
                return;
            }

            // submit form -> browser akan men-download response file
            document.getElementById('export-form').submit();
            // optional: close modal
            toggleExcelModal();
        }
        // Jam & Tanggal Sekarang
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById("current-date").innerText = now.toLocaleDateString('id-ID', options);
            document.getElementById("current-time").innerText = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Inisialisasi QR Scanner
        document.addEventListener("DOMContentLoaded", () => {
            const qrReader = document.getElementById("qr-reader");
            if (!qrReader) return;

            new Html5QrcodeScanner("qr-reader", {
                fps: 10,
                qrbox: 250
            }).render((decodedText) => {
                // decodedText = hashId dari QR
                fetch(`/presences/verify/${decodedText}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = "";

                        if (data.worker) {
                            html = `
                            <div class="text-left mt-2">
                                <p><b>Nama:</b> ${data.worker.name}</p>
                                <p><b>Kode:</b> ${data.worker.code}</p>
                                <p><b>Kategori:</b> ${data.worker.category}</p>
                            </div>
                        `;
                        }

                        // Timer hitung mundur 5 detik
                        let timerInterval;
                        Swal.fire({
                            icon: data.status === 'success' ? 'success' : data.status ===
                                'error' ? 'error' : 'info',
                            title: data.message,
                            html: `
        ${data.worker ? `
                                                                                            <table class="swal2-table" style="width:100%;text-align:left;border-collapse:collapse;margin-top:10px">
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
                                                                                        ` : ''}
        <br>
        <b>Menutup dalam <span id="swal-timer">5</span> detik...</b>
    `,
                            timer: 5000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            didOpen: () => {
                                const timerSpan = Swal.getHtmlContainer().querySelector(
                                    '#swal-timer');
                                let timeLeft = 5;
                                timerInterval = setInterval(() => {
                                    timeLeft--;
                                    if (timeLeft >= 0) timerSpan.textContent =
                                        timeLeft;
                                }, 1000);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        });
                        toggleQrModal();
                        // Auto reload setelah 5 detik jika success
                        if (data.status === 'success') {
                            setTimeout(() => location.reload(), 5000);
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memproses presensi'
                        });
                    });
            });
        });
    </script>


</x-app-layout>
