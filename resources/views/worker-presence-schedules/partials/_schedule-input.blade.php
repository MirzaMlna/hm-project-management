<form id="timeSettingsForm" action="{{ route('worker-presence-schedules.save') }}" method="POST" class="space-y-6">
    @csrf

    {{-- Presensi 1 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <label class="font-bold text-gray-700">Presensi 1</label>
        <input type="time" name="first_check_in_start"
            value="{{ old('first_check_in_start', $schedule->first_check_in_start ?? '') }}"
            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
        <input type="time" name="first_check_in_end"
            value="{{ old('first_check_in_end', $schedule->first_check_in_end ?? '') }}"
            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
    </div>

    {{-- Presensi 2 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <label class="font-bold text-gray-700">Presensi 2</label>
        <input type="time" name="second_check_in_start"
            value="{{ old('second_check_in_start', $schedule->second_check_in_start ?? '') }}"
            class="border rounded p-2 w-full focus:ring focus:ring-sky-200" required>
        <input type="time" name="second_check_in_end"
            value="{{ old('second_check_in_end', $schedule->second_check_in_end ?? '') }}"
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
