<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('workers.update', $worker->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>Nama</label>
                            <input type="text" name="name" value="{{ old('name', $worker->name) }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $worker->phone) }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $worker->birth_date) }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>Gaji Harian</label>
                            <input type="number" name="daily_salary"
                                value="{{ old('daily_salary', $worker->daily_salary) }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>Foto</label>
                            <input type="file" name="photo" accept="image/*" class="border rounded p-2 w-full">
                        </div>
                        <div class="md:col-span-2">
                            <label>Alamat</label>
                            <textarea name="address" class="w-full border rounded p-2">{{ old('address', $worker->address) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label>Catatan</label>
                            <textarea name="note" class="w-full border rounded p-2">{{ old('note', $worker->note) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('workers.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Perbarui</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
