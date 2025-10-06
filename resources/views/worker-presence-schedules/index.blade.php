<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rentang Waktu Presensi
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
                <h3 class="font-semibold mb-2 flex items-center text-lg text-gray-800">
                    <i class="bi bi-clock-history mr-2 text-sky-600"></i> Pengaturan Rentang Waktu Presensi
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>

                {{-- Form --}}
                <form id="timeSettingsForm" action="{{ route('worker-presence-schedules.save') }}" method="POST"
                    class="space-y-6">
                    @csrf

                    {{-- Check In 1 --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="font-medium">Check-In 1</label>
                        <input type="time" name="first_check_in_start"
                            value="{{ old('first_check_in_start', $schedule->first_check_in_start ?? '') }}"
                            class="border rounded p-2 w-full" required>
                        <input type="time" name="first_check_in_end"
                            value="{{ old('first_check_in_end', $schedule->first_check_in_end ?? '') }}"
                            class="border rounded p-2 w-full" required>
                    </div>

                    {{-- Check In 2 --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="font-medium">Check-In 2</label>
                        <input type="time" name="second_check_in_start"
                            value="{{ old('second_check_in_start', $schedule->second_check_in_start ?? '') }}"
                            class="border rounded p-2 w-full" required>
                        <input type="time" name="second_check_in_end"
                            value="{{ old('second_check_in_end', $schedule->second_check_in_end ?? '') }}"
                            class="border rounded p-2 w-full" required>
                    </div>

                    {{-- Check Out --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="font-medium">Check-Out</label>
                        <input type="time" name="check_out_start"
                            value="{{ old('check_out_start', $schedule->check_out_start ?? '') }}"
                            class="border rounded p-2 w-full" required>
                        <input type="time" name="check_out_end"
                            value="{{ old('check_out_end', $schedule->check_out_end ?? '') }}"
                            class="border rounded p-2 w-full" required>
                    </div>

                    <hr class="my-6">

                    {{-- Konfirmasi teks --}}
                    <div>
                        <label class="font-medium block mb-2">Konfirmasi</label>
                        <input type="text" id="confirmationText" placeholder='Ketik "HM BUILDERS" untuk menyimpan'
                            class="border rounded p-2 w-full focus:ring focus:ring-sky-200">
                        <p class="text-xs text-gray-500 mt-1">
                            * Wajib ketik persis "HM BUILDERS" (huruf besar semua)
                        </p>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex flex-col sm:flex-row justify-end">
                        <button type="submit" id="saveBtn"
                            class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-2 rounded w-full sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            {{ $schedule ? 'Update Pengaturan' : 'Simpan Pengaturan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        const confirmationText = document.getElementById('confirmationText');
        const saveBtn = document.getElementById('saveBtn');

        confirmationText.addEventListener('input', () => {
            saveBtn.disabled = confirmationText.value.trim() !== "HM BUILDERS";
        });

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
