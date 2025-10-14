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

                {{-- Header + tombol tambah --}}
                <div class="flex justify-between items-center px-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class=""></i>
                    </h3>
                    <x-primary-button onclick="toggleCreateModal()" title="Tambah Kategori Tukang">
                        <i class="bi bi-plus-circle"></i>
                    </x-primary-button>
                </div>


                {{-- Tabel --}}
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
                                                <button
                                                    onclick="toggleEditModal({{ $category->id }}, '{{ $category->category }}')">
                                                    <i
                                                        class="bi bi-pencil-square text-yellow-500 hover:text-yellow-600"></i>
                                                </button>
                                                <form action="{{ route('worker-categories.destroy', $category->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
            </div>
        </div>
    </div>

    {{-- Modal Tambah & Edit --}}
    @include('worker-categories.create-modal')
    @include('worker-categories.edit-modal')

    {{-- Script --}}
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

        // Auto-hide alert
        setTimeout(() => {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');
            [successAlert, errorAlert].forEach(alert => {
                if (alert) {
                    alert.classList.add('opacity-0');
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
</x-app-layout>
