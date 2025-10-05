<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategori Tukang
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert sukses/gagal --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="alert-error"
                    class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm transition-opacity duration-500">
                    ❌ {{ session('error') }}
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Tombol Tambah --}}
                <div class="flex justify-end items-end">
                    <x-primary-button class="mt-6 mr-6" onclick="toggleCreateModal()">
                        <i class="bi bi-plus-circle mr-2"></i>Tambah Kategori Tukang
                    </x-primary-button>
                </div>

                {{-- Tabel --}}
                <div class="p-6 text-gray-900">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-sky-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">Kategori</th>
                                    <th scope="col" class="px-6 py-3">Jumlah Tukang</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-900">
                                        <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                            {{ $index + 1 }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $category->category }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $category->workers_count }}
                                        </td>
                                        <td class="px-6 py-4 space-x-3">
                                            <button
                                                onclick="toggleEditModal({{ $category->id }}, '{{ $category->category }}')">
                                                <i class="bi bi-pencil-square text-yellow-500"></i>
                                            </button>
                                            <form action="{{ route('worker-categories.destroy', $category->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                                    <i class="bi bi-trash3 text-red-500"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Belum ada data kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('worker-categories.create-modal')
    @include('worker-categories.edit-modal')



    {{-- Script Menampilkan Modal --}}
    <script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        function toggleEditModal(id = null, category = null) {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');

            if (id && category) {
                document.getElementById('edit_category').value = category;
                document.getElementById('editForm').action = `/worker-categories/${id}`;
            }
        }
        // Hilangkan alert setelah 5 detik (5000 ms)
        setTimeout(() => {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');

            [successAlert, errorAlert].forEach(alert => {
                if (alert) {
                    alert.classList.add('opacity-0'); // efek fade
                    setTimeout(() => alert.remove(), 500); // hapus setelah animasi
                }
            });
        }, 5000);
    </script>
</x-app-layout>
