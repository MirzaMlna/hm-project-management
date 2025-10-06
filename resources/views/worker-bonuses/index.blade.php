<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bonus Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

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

                {{-- Judul --}}
                <h3 class="font-semibold mb-6 flex items-center text-lg text-gray-800">
                    <i class="bi bi-clock-history mr-2 text-sky-600"></i> Pengaturan Bonus Tukang
                </h3>

                {{-- Form --}}
                <form action="{{ route('worker-bonuses.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Bonus Datang Lebih Awal --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Bonus Datang Lebih Awal
                            </label>
                            <input type="number" name="work_earlier"
                                value="{{ old('work_earlier', $workerBonus->work_earlier ?? 0) }}"
                                class="w-full border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-sky-200"
                                placeholder="Masukkan nominal bonus">
                        </div>

                        {{-- Bonus Kerja Lebih Lama --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Bonus Kerja Lebih Lama
                            </label>
                            <input type="number" name="work_longer"
                                value="{{ old('work_longer', $workerBonus->work_longer ?? 0) }}"
                                class="w-full border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-sky-200"
                                placeholder="Masukkan nominal bonus">
                        </div>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-sky-700 text-white rounded-lg shadow hover:bg-sky-800 w-full sm:w-auto">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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
