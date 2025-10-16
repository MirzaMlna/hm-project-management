<div class="flex flex-wrap justify-between items-center px-6 mt-6 gap-3">
    {{-- 🔹 Dropdown Filter --}}
    <form method="GET" action="{{ route('items.index') }}">
        <select name="category" onchange="this.form.submit()"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-700 bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition w-48">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                    {{ $cat->category }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- 🔹 Tombol Import & Tambah --}}
    <div class="flex flex-wrap items-center gap-2 justify-end">
        <x-primary-button class="!bg-amber-600 hover:!bg-amber-700 !text-white"
            onclick="toggleImportModal()">
            <i class="bi bi-upload"></i>
        </x-primary-button>

        <x-primary-button class="!bg-sky-700 hover:!bg-sky-800 !text-white"
            onclick="toggleCreateModal()">
            <i class="bi bi-plus-circle"></i>
        </x-primary-button>
    </div>
</div>
