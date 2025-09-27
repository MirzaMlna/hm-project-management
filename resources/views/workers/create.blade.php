<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('workers.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Nama --}}
                        <div>
                            <label>Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full border rounded p-2 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- No. Telepon --}}
                        <div>
                            <label>No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full border rounded p-2 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full border rounded p-2 @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gaji Harian --}}
                        <div>
                            <label>Gaji Harian</label>
                            <input type="number" name="daily_salary" value="{{ old('daily_salary') }}"
                                class="w-full border rounded p-2 @error('daily_salary') border-red-500 @enderror">
                            @error('daily_salary')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto --}}
                        <div>
                            <label>Foto</label>
                            <input type="file" name="photo" accept="image/*"
                                class="border rounded p-2 w-full @error('photo') border-red-500 @enderror">
                            @error('photo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label>Alamat</label>
                            <textarea name="address" class="w-full border rounded p-2 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="md:col-span-2">
                            <label>Catatan</label>
                            <textarea name="note" class="w-full border rounded p-2 @error('note') border-red-500 @enderror">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="mt-2 flex justify-end">
                        <a href="{{ route('workers.index') }}" class="bg-gray-300 px-4 py-2 me-2 rounded">Batal</a>
                        <button type="submit" class="bg-sky-800 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
