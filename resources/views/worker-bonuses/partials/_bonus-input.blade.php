<form action="{{ route('worker-bonuses.store') }}" method="POST" class="space-y-6">
    @csrf

    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">
            Bonus Kerja Lebih Lama
        </label>
        <input type="number" name="work_longer" value="{{ old('work_longer', $workerBonus->work_longer ?? 0) }}"
            class="w-full border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-sky-200"
            placeholder="Masukkan nominal bonus">
    </div>

    {{-- Tombol Simpan --}}
    <div class="flex flex-col sm:flex-row justify-end gap-2">
        <button type="submit"
            class="px-4 py-2 bg-sky-700 text-white rounded-lg shadow hover:bg-sky-800 w-full sm:w-auto">
            Simpan
        </button>
    </div>
</form>
