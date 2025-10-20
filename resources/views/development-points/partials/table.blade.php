<div class="overflow-x-auto">
    <table class="min-w-full text-sm border border-gray-200 rounded text-gray-700">
        <thead class="bg-sky-700 text-white uppercase text-xs tracking-wider">
            <tr>
                <th class="px-3 py-2 text-start w-10">#</th>
                <th class="px-3 py-2 text-start">Titik</th>
                <th class="px-3 py-2 text-start">Foto</th>
                <th class="px-3 py-2 text-start w-20">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($points as $point)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2 text-start">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2 font-semibold">{{ $point->development_point }}</td>
                    <td class="px-3 py-2 text-start">
                        @if ($point->photo)
                            <button type="button"
                                onclick="showPhoto('{{ asset('storage/' . $point->photo) }}')"
                                class="text-sky-600 hover:text-sky-800 underline">
                                Lihat
                            </button>
                        @else
                            <span class="text-gray-500 italic">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-start space-x-2">
                        <button
                            onclick="toggleEditModal({{ $point->id }}, '{{ $point->development_point }}', '{{ $point->photo }}')"
                            class="text-yellow-600 hover:text-yellow-800">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <form action="{{ route('development-points.destroy', $point->id) }}" method="POST"
                            class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-4 text-start text-gray-500 italic">
                        Belum ada titik pembangunan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $points->links() }}
</div>
