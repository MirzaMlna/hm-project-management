<div class="p-6 text-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-600 border border-gray-200">
            <thead class="text-xs text-white uppercase bg-sky-700">
                <tr>
                    <th class="px-4 py-3 text-start w-12">#</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama Pemasok</th>
                    <th class="px-4 py-3">Telepon</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Keterangan</th>
                    <th class="px-4 py-3 text-start w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $index => $supplier)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-start">
                            {{ $suppliers->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3">{{ $supplier->code }}</td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $supplier->supplier }}</td>
                        <td class="px-4 py-3">{{ $supplier->phone ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $supplier->address ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $supplier->description ?? '-' }}</td>
                        <td class="px-4 py-3 text-start space-x-2">
                            <button type="button" class="btn-edit" data-id="{{ $supplier->id }}"
                                data-supplier="{{ e($supplier->supplier) }}" data-phone="{{ e($supplier->phone) }}"
                                data-address="{{ e($supplier->address) }}"
                                data-description="{{ e($supplier->description) }}">
                                <i class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600"></i>
                            </button>
                            <form action="{{ route('item-suppliers.destroy', $supplier->id) }}" method="POST"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus pemasok ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    <i class="bi bi-trash3 text-red-500 hover:text-red-600"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-start py-4 text-gray-500 italic bg-gray-50">
                            Belum ada pemasok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginate --}}
    @if ($suppliers->hasPages())
        <div class="mt-4">
            {{ $suppliers->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
