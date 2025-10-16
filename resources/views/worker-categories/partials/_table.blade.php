<div class="p-6 text-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200">
            <thead class="text-xs text-white uppercase bg-sky-700">
                <tr>
                    <th class="px-4 py-3 text-start w-12">#</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3 text-start">Jumlah Tukang</th>
                    <th class="px-4 py-3 text-start w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-start font-medium text-gray-800">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3">{{ $category->category }}</td>
                        <td class="px-4 py-3 text-start">{{ $category->workers_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-3 justify-center">
                                <button onclick="toggleEditModal({{ $category->id }}, '{{ $category->category }}')">
                                    <i class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600"></i>
                                </button>
                                <form action="{{ route('worker-categories.destroy', $category->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        <i class="bi bi-trash3 text-red-500 hover:text-red-600"></i>
                                    </button>
                                </form>
                            </div>
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
