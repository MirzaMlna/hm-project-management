<div class="p-6 text-gray-900">
    <div class="overflow-x-auto rounded">
        <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200">
            <thead class="text-xs text-white uppercase bg-sky-700">
                <tr>
                    <th class="px-4 py-3 text-start w-12">#</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3 text-start">Jumlah Jenis</th>
                    <th class="px-4 py-3 text-start w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-start font-medium text-gray-800">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $category->category }}
                        </td>
                        <td class="px-4 py-3 text-start font-semibold text-gray-800">
                            {{ $category->items_count ?? 0 }}
                        </td>
                        <td class="px-4 py-3 text-start space-x-3">
                            <button
                                onclick="toggleEditModal({{ $category->id }}, '{{ $category->category }}')"
                                class="text-yellow-500 hover:text-yellow-600">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('item-categories.destroy', $category->id) }}"
                                method="POST" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-start py-4 text-gray-500 italic bg-gray-50">
                            Belum ada data kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
