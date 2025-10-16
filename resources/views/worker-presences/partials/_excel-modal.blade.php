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
                    class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-500 transition">
                    Generate Excel
                </button>
            </div>
        </form>
    </div>
</div>
