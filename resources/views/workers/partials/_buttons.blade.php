<div class="flex justify-between items-center mb-4 px-1 gap-3">
    <h3 class="text-lg font-semibold flex items-center gap-2">
        <i class="bi bi-person-check"></i> Daftar Tukang Aktif
    </h3>
    <div class="flex items-center gap-2">
        <button onclick="openImportModal()"
            class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded flex items-center justify-center">
            <i class="bi bi-upload"></i>
        </button>
        <a href="{{ route('workers.printAll') }}"
            class="px-3 py-2 bg-green-700 hover:bg-green-800 text-white rounded flex items-center justify-center">
            <i class="bi bi-person-vcard"></i>
        </a>
        <a href="{{ route('workers.create') }}"
            class="px-3 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded flex items-center justify-center">
            <i class="bi bi-person-plus"></i>
        </a>
    </div>
</div>
