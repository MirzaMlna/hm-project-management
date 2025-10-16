<div class="p-6 text-gray-900 space-y-8">
    @forelse ($categories as $category)
        @php
            $categoryStocks = $stocks->filter(fn($s) => $s->item && $s->item->item_category_id == $category->id);
        @endphp

        <div class="rounded border border-gray-200 shadow-sm">
            {{-- Header kategori --}}
            <div class="bg-sky-700 text-white px-4 py-2 rounded-t font-semibold">
                {{ strtoupper($category->category) }}
            </div>

            {{-- Tabel stok --}}
            <div class="overflow-x-auto">
                <table
                    class="min-w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">#</th>
                            <th class="px-4 py-3">Barang</th>
                            <th class="px-4 py-3">Satuan</th>
                            <th class="px-4 py-3 text-center">Stok Saat Ini</th>
                            <th class="px-4 py-3 text-center">Minimal</th>
                            <th class="px-4 py-3 text-center">Terakhir Diperbarui</th>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categoryStocks as $stock)
                            <tr class="bg-white border-b border-gray-100 hover:bg-sky-50 transition">
                                <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>

                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ $stock->item->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $stock->item->unit ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center font-medium">
                                    {{ $stock->current_stock }}
                                </td>

                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ $stock->minimum_stock ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center text-gray-600 whitespace-nowrap">
                                    {{ $stock->last_updated ? \Carbon\Carbon::parse($stock->last_updated)->translatedFormat('d M Y H:i') : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center items-center gap-3">
                                        <button type="button" class="btn-edit" data-id="{{ $stock->id }}"
                                            data-current="{{ $stock->current_stock }}"
                                            data-minimum="{{ $stock->minimum_stock }}">
                                            <i
                                                class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600 text-base"></i>
                                        </button>

                                        <form action="{{ route('item-stocks.destroy', $stock->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus stok ini?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                <i
                                                    class="bi bi-trash3 text-red-500 hover:text-red-600 text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500 italic bg-gray-50">
                                    Tidak ada stok dalam kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="text-start text-gray-500 py-10">
            Belum ada kategori barang yang terdaftar.
        </div>
    @endforelse
</div>
