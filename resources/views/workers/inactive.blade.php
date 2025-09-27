<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tukang
            </h2>
            <a href="{{ route('workers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                <span><i class="bi bi-person-check me-2"></i></span>Tukang Aktif
            </a>
        </div>

    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold"><span><i class="bi bi-person-slash me-2"></i></span>Daftar Tukang
                        Nonaktif</h3>

                </div>

                <div class="overflow-x-auto rounded-lg overflow-hidden border border-gray-200">
                    <table class="min-w-full text-sm text-center">
                        <thead class="bg-sky-800 text-white">
                            <tr>
                                <th class="px-4 py-2">NO</th>
                                <th class="px-4 py-2">KATEGORI</th>
                                <th class="px-4 py-2">NAMA</th>
                                <th class="px-4 py-2">KODE</th>
                                <th class="px-4 py-2">CATATAN</th>
                                <th class="px-4 py-2">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($workers as $index => $worker)
                                <tr>
                                    <td class="px-4 py-2">{{ $workers->firstItem() + $index }}</td>
                                    <td class="px-4 py-2">{{ $worker->category ? $worker->category->category : '-' }}
                                    </td>
                                    <td class="px-4 py-2">{{ $worker->name }}</td>
                                    <td class="px-4 py-2">{{ $worker->code }}</td>
                                    <td class="px-4 py-2 whitespace-pre-line">{{ $worker->note ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex justify-center items-center">
                                            <form action="{{ route('workers.activate', $worker->id) }}" method="POST"
                                                onsubmit="return confirm('Aktifkan kembali tukang ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="text-green-600 hover:text-green-700 p-2 rounded text-sm"
                                                    title="Aktifkan Tukang">
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('workers.destroy', $worker->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Tukang"
                                                    class="text-red-600 hover:text-red-900 p-2 rounded text-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada tukang nonaktif.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                <div class="mt-4">
                    {{ $workers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
