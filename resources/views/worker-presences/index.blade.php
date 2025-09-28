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
                    <h3 class="text-lg font-semibold"><span><i class="bi bi-scan"></i></span>Hasil Scan</h3>
                    <div class="flex justify-end">
                        <x-primary-button onclick="toggleQrModal()">
                            <i class="bi bi-qr-code-scan mr-2"></i> Scan QR Code
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white text-center">
                                <td class="p-2">1</td>
                                <td class="p-2">Jawa</td>
                                <td class="p-2">Budi</td>
                                <td class="p-2">W001</td>
                                <td class="p-2"><span class="font-bold text-lg">07:15</span><br> Datang Lebih Awal
                                </td>
                                <td class="p-2"><span class="font-bold text-lg">12:05</span><br> Tepat Waktu</td>
                                <td class="p-2"><span class="font-bold text-lg">16:20</span><br> Pulang Lebih Lambat
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

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
    <script>
        // QR Modal Toggle
        function toggleQrModal() {
            document.getElementById("qrModal").classList.toggle("hidden");
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (document.getElementById("qr-reader")) {
                new Html5QrcodeScanner("qr-reader", {
                    fps: 10,
                    qrbox: 250
                }).render((decodedText) => {
                    alert("QR Terdeteksi: " + decodedText);
                    toggleQrModal();
                });
            }
        });
        // Jam & Tanggal Sekarang
        function updateDateTime() {
            const now = new Date();

            // Format tanggal (contoh: 27 September 2025)
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById("current-date").innerText = now.toLocaleDateString('id-ID', options);

            // Format waktu (HH:mm:ss)
            document.getElementById("current-time").innerText = now.toLocaleTimeString('id-ID');
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</x-app-layout>
