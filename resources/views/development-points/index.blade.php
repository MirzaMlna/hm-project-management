<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Titik Pembangunan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-semibold">Daftar Titik Pembangunan</h3>
                    <a href="{{ route('development-points.create') }}"
                        class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">
                        + Tambah
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="w-full text-sm border">
                    <thead class="bg-sky-600 text-white">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Titik</th>
                            <th class="px-4 py-2">Deskripsi</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($points as $point)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 font-semibold">{{ $point->development_point }}</td>
                                <td class="px-4 py-2">{{ $point->description }}</td>
                                <td class="px-4 py-2 text-center space-x-2">
                                    <a href="{{ route('development-points.edit', $point) }}"
                                        class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <form action="{{ route('development-points.destroy', $point) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Belum ada titik pembangunan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $points->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
