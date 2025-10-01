<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bonus Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form Create / Edit --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Alert sukses/gagal --}}
                @if (session('success'))
                    <div class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                <h3 class="font-semibold mb-4 flex items-center">
                    <i class="bi bi-clock-history mr-2"></i> Pengaturan Bonus Tukang
                </h3>

                <form action="{{ route('worker-bonuses.store') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Bonus Datang Lebih Awal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bonus Datang Lebih Awal
                        </label>
                        <input type="number" name="work_earlier"
                            value="{{ old('work_earlier', $bonus->work_earlier ?? 0) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-sky-200"
                            placeholder="Masukkan nominal bonus">
                    </div>

                    {{-- Bonus Kerja Lebih Lama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bonus Kerja Lebih Lama
                        </label>
                        <input type="number" name="work_longer"
                            value="{{ old('work_longer', $bonus->work_longer ?? 0) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-sky-200"
                            placeholder="Masukkan nominal bonus">
                    </div>

                    {{-- Bonus Lembur Malam --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bonus Lembur Malam
                        </label>
                        <input type="number" name="overtime" value="{{ old('overtime', $bonus->overtime ?? 0) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-sky-200"
                            placeholder="Masukkan nominal bonus">
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit"
                            class="px-4 py-2 bg-sky-700 text-white rounded-lg shadow hover:bg-sky-800">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
