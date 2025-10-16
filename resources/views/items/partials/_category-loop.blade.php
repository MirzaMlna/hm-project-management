<div class="p-4 sm:p-6 text-gray-900 space-y-8">
    @php
        $loopCategories = $selectedCategory ? $categories->where('id', $selectedCategory) : $categories;
    @endphp

    @forelse ($loopCategories as $category)
        <div class="rounded border border-gray-200 shadow-sm">
            <div class="bg-sky-700 text-white px-4 py-2 rounded-t font-semibold text-sm sm:text-base">
                {{ strtoupper($category->category) }}
            </div>

            <div class="overflow-x-auto mt-2">
                <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200">
                    <thead class="text-xs sm:text-sm text-white uppercase bg-sky-700">
                        <tr>
                            <th class="px-3 sm:px-4 py-3 text-start w-10 sm:w-12">#</th>
                            <th class="px-3 sm:px-4 py-3">Kode</th>
                            <th class="px-3 sm:px-4 py-3">Jenis</th>
                            <th class="px-3 sm:px-4 py-3">Satuan</th>
                            <th class="px-3 sm:px-4 py-3">Keterangan</th>
                            <th class="px-3 sm:px-4 py-3">Foto</th>
                            <th class="px-3 sm:px-4 py-3 text-start w-20 sm:w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $categoryItems = $items->where('item_category_id', $category->id); @endphp

                        @forelse ($categoryItems as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-3 sm:px-4 py-3 text-center font-medium">{{ $loop->iteration }}</td>
                                <td class="px-3 sm:px-4 py-3">{{ $item->code }}</td>
                                <td class="px-3 sm:px-4 py-3 font-semibold text-gray-900">{{ $item->name }}</td>
                                <td class="px-3 sm:px-4 py-3">{{ $item->unit }}</td>
                                <td class="px-3 sm:px-4 py-3">{{ $item->description }}</td>
                                <td class="px-3 sm:px-4 py-3">
                                    @if ($item->photo)
                                        <img src="{{ asset('storage/' . $item->photo) }}"
                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded object-cover border mx-auto">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-4 py-3 text-center space-x-1">
                                    <button type="button" class="btn-edit"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ e($item->name) }}"
                                        data-unit="{{ e($item->unit) }}"
                                        data-category="{{ $item->item_category_id }}"
                                        data-description="{{ e($item->description) }}"
                                        data-photo="{{ $item->photo ? asset('storage/' . $item->photo) : '' }}">
                                        <i class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600"></i>
                                    </button>

                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit">
                                            <i class="bi bi-trash3 text-red-500 hover:text-red-600"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500 italic bg-gray-50">
                                    Tidak ada barang dalam kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="text-center text-gray-500 py-10">
            Belum ada kategori barang yang terdaftar.
        </div>
    @endforelse
</div>
