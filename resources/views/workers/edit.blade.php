<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="bi bi-pencil-square text-yellow-600"></i> Edit Tukang
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-xl p-8 border border-gray-100">
                {{-- Judul dan Deskripsi --}}
                <div class="mb-6 text-center md:text-left">
                    <h3 class="text-lg font-semibold text-gray-800">Form Edit Data Tukang</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Perbarui data tukang sesuai kebutuhan. Pastikan informasi di bawah ini sudah benar sebelum
                        disimpan.
                    </p>
                </div>

                <form method="POST" action="{{ route('workers.update', $worker->id) }}" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $worker->name) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm p-2.5 @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama tukang">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori Tukang --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Tukang</label>
                            <select name="worker_category_id"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm p-2.5 @error('worker_category_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('worker_category_id', $worker->worker_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('worker_category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- No. Telepon --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $worker->phone) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm p-2.5 @error('phone') border-red-500 @enderror"
                                placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $worker->birth_date) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm p-2.5 @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gaji Harian --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Harian (Rp)</label>
                            <input type="number" name="daily_salary"
                                value="{{ old('daily_salary', $worker->daily_salary) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm p-2.5 @error('daily_salary') border-red-500 @enderror"
                                placeholder="Contoh: 150000">
                            @error('daily_salary')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto (JPG/PNG, max 5MB)</label>

                            {{-- Preview foto lama --}}
                            @if ($worker->photo)
                                <div class="mb-2">
                                    <img id="photo-preview-old" src="{{ asset('storage/' . $worker->photo) }}"
                                        alt="Foto {{ $worker->name }}"
                                        class="h-24 w-24 object-cover rounded-lg border border-gray-300">
                                </div>
                            @endif

                            {{-- Preview foto baru --}}
                            <img id="photo-preview-new"
                                class="h-24 w-24 object-cover rounded-lg border border-gray-300 hidden mb-2" />

                            <input id="photo" type="file" name="photo" accept="image/*"
                                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-yellow-500 focus:ring-yellow-500 @error('photo') border-red-500 @enderror"
                                onchange="validateAndPreviewPhoto(event)">
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="photo-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti foto.</p>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm p-2.5 @error('address') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('address', $worker->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="note" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm p-2.5 @error('note') border-red-500 @enderror"
                            placeholder="Tambahkan catatan (opsional)">{{ old('note', $worker->note) }}</textarea>
                        @error('note')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('workers.index') }}"
                            class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-400 transition">
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Preview + Validasi Foto Otomatis --}}
    <script>
        function validateAndPreviewPhoto(event) {
            const file = event.target.files[0];
            const previewNew = document.getElementById('photo-preview-new');
            const previewOld = document.getElementById('photo-preview-old');
            const errorMsg = document.getElementById('photo-error');

            errorMsg.classList.add('hidden');
            previewNew.classList.add('hidden');

            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!allowedTypes.includes(file.type)) {
                    errorMsg.textContent = 'Format tidak didukung. Gunakan hanya JPG, JPEG, atau PNG.';
                    errorMsg.classList.remove('hidden');
                    event.target.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    errorMsg.textContent = 'Ukuran file terlalu besar. Maksimal 5MB.';
                    errorMsg.classList.remove('hidden');
                    event.target.value = '';
                    return;
                }

                // Tampilkan preview foto baru
                const reader = new FileReader();
                reader.onload = e => {
                    previewNew.src = e.target.result;
                    previewNew.classList.remove('hidden');
                    if (previewOld) previewOld.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
