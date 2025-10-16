<div class="bg-white p-5 rounded-xl shadow-md border border-gray-100 flex flex-col">
    <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
        <i class="bi bi-qr-code-scan text-sky-700"></i> Scan QR Code
    </h3>
    <div id="qr-reader" class="w-full rounded-lg overflow-hidden border border-gray-300 shadow-inner"></div>

    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-start">
        <p class="text-sm text-red-700 font-semibold">
            Belum Absen: <span class="text-xl font-bold">{{ $notPresentCount }}</span> Tukang
        </p>
    </div>
</div>
