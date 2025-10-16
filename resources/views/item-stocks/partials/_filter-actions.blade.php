<div class="flex items-center justify-between px-6 mt-6 gap-2 flex-wrap">
    {{-- 🔹 Dropdown Filter --}}
    <form method="GET" action="{{ route('item-stocks.index') }}">
        <select name="category" onchange="this.form.submit()"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-700 bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition w-48">
            <option value="">Semua Kategori</option>
            @foreach ($allCategories as $cat)
                <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                    {{ $cat->category }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- 🔹 Tombol Export & Tambah --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('item-stocks.export') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-md
            text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400
            transition duration-150 ease-in-out shadow-sm">
            <i class="bi bi-file-earmark-spreadsheet text-base"></i>
        </a>

        <button type="button" onclick="toggleCreateModal()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-md
            text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-400
            transition duration-150 ease-in-out shadow-sm">
            <i class="bi bi-plus-circle text-base"></i>
        </button>
    </div>
</div>
