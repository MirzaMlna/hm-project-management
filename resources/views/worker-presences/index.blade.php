<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                Presensi Tukang
            </h2>
            <div class="text-end">
                <p class="text-sm font-semibold text-gray-700">
                    <span id="current-time"></span> <br>
                    <span id="current-date"></span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 🟦 Jadwal Presensi --}}
            @include('worker-presences._schedule-cards', [
                'worker_presence_schedules' => $worker_presence_schedules,
            ])

            {{-- 🟨 Layout Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Scanner --}}
                @include('worker-presences._scanner', ['notPresentCount' => $notPresentCount])

                {{-- Tabel --}}
                @include('worker-presences._table', ['presences' => $presences])
            </div>
        </div>
    </div>

    {{-- 🟩 Modal Export Excel --}}
    @include('worker-presences._excel-modal')

    {{-- 📜 SCRIPT --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
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

        // 🕒 Update waktu realtime
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

        // 🔍 QR Scanner
        document.addEventListener("DOMContentLoaded", () => {
            const qrReader = document.getElementById("qr-reader");
            if (!qrReader) return;

            let isCooldown = false;

            new Html5QrcodeScanner("qr-reader", {
                fps: 10,
                qrbox: 250,
                rememberLastUsedCamera: true,
                aspectRatio: 1.0
            }, false).render((decodedText) => {
                if (isCooldown) return;
                isCooldown = true;

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

                        // 🧱 Modal Konfirmasi Presensi
                        Swal.fire({
                                html: `
        <div class="flex flex-wrap justify-center items-center gap-5 mt-3 animate-[fadeIn_0.3s_ease-in-out]">
            <div class="flex-shrink-0">
                ${data.worker.photo
                    ? `<img src="${data.worker.photo}" alt="${data.worker.name}"
                                            class="w-48 h-48 object-cover rounded-xl border-4 border-gray-100 shadow-lg transition-transform duration-300 hover:scale-[1.02]" />`
                    : `<div class="w-48 h-48 flex items-center justify-center bg-gray-100 text-gray-400 rounded-xl">Tidak ada foto</div>`}
            </div>

            <div class="flex-1 min-w-[220px] bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 shadow-inner text-sm leading-relaxed text-gray-700">
                <p><span class="font-semibold text-gray-900">Nama:</span> ${data.worker.name}</p>
                <p><span class="font-semibold text-gray-900">Kode:</span> ${data.worker.code}</p>
                <p><span class="font-semibold text-gray-900">Kategori:</span> ${data.worker.category}</p>
            </div>
        </div>

        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }
        </style>
    `,

                                showCancelButton: true,
                                confirmButtonText: '<i class="bi bi-check2-circle"></i> Konfirmasi',
                                cancelButtonText: '<i class="bi bi-x-circle"></i> Batal',
                                reverseButtons: true,
                                buttonsStyling: false,
                                didOpen: () => {
                                    const confirmBtn = Swal.getConfirmButton();
                                    const cancelBtn = Swal.getCancelButton();

                                    confirmBtn.classList.add(
                                        'bg-sky-700', 'hover:bg-sky-800', 'text-white',
                                        'px-4', 'py-2.5', 'rounded-lg', 'font-semibold',
                                        'transition'
                                    );
                                    cancelBtn.classList.add(
                                        'bg-gray-200', 'hover:bg-gray-300', 'text-gray-700',
                                        'px-4', 'py-2.5', 'rounded-lg', 'font-medium',
                                        'mr-2', 'transition'
                                    );
                                }
                            })
                            .then((result) => {
                                if (result.isConfirmed) {
                                    fetch(`/presences/verify/${decodedText}`)
                                        .then(res => res.json())
                                        .then(resp => {
                                            Swal.fire({
                                                icon: resp.status === 'success' ?
                                                    'success' : 'error',
                                                title: resp.message,
                                                timer: 2500,
                                                showConfirmButton: false
                                            }).then(() => {
                                                if (resp.status === 'success') location
                                                    .reload();
                                            });
                                        })
                                        .finally(() => setTimeout(() => isCooldown = false, 2000));
                                } else {
                                    isCooldown = false;
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
