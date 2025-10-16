<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="bi bi-clock-history text-sky-700"></i> Rentang Waktu Presensi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-xl p-8 border border-gray-100">
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
                <div class="mb-6 text-center md:text-left">
                    <h3 class="text-lg font-semibold text-gray-800">Pengaturan Rentang Waktu Presensi</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Atur waktu mulai dan berakhir untuk setiap sesi presensi harian.
                    </p>
                </div>

                {{-- Form --}}
                <form id="timeSettingsForm" action="{{ route('worker-presence-schedules.save') }}" method="POST"
                    class="space-y-6">
                    @csrf

                    {{-- Presensi 1 --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="font-medium text-gray-700">Presensi 1</label>
                        <input type="time" name="first_check_in_start"
                            value="{{ old('first_check_in_start', $schedule->first_check_in_start ?? '') }}"
                            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
                        <input type="time" name="first_check_in_end"
                            value="{{ old('first_check_in_end', $schedule->first_check_in_end ?? '') }}"
                            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
                    </div>

                    {{-- Presensi 2 --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="font-medium text-gray-700">Presensi 2</label>
                        <input type="time" name="second_check_in_start"
                            value="{{ old('second_check_in_start', $schedule->second_check_in_start ?? '') }}"
                            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
                        <input type="time" name="second_check_in_end"
                            value="{{ old('second_check_in_end', $schedule->second_check_in_end ?? '') }}"
                            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
                    </div>

                    {{-- Presensi Pulang --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="font-medium text-gray-700">Presensi Pulang</label>
                        <input type="time" name="check_out_start"
                            value="{{ old('check_out_start', $schedule->check_out_start ?? '') }}"
                            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
                        <input type="time" name="check_out_end"
                            value="{{ old('check_out_end', $schedule->check_out_end ?? '') }}"
                            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
                    </div>

                    <hr class="my-6">

                    {{-- Tombol Submit --}}
                    <div class="flex flex-col sm:flex-row justify-end">
                        <button type="submit"
                            class="bg-sky-700 hover:bg-sky-800 text-white px-6 py-2 rounded w-full sm:w-auto transition">
                            {{ $schedule ? 'Update Pengaturan' : 'Simpan Pengaturan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Auto-hide alert --}}
    <script>
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
