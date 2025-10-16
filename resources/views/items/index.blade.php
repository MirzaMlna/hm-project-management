<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jenis Barang
        </h2>

    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert sukses --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @include('items.partials._filter-actions')
                @include('items.partials._category-loop')
            </div>
        </div>
    </div>

    {{-- Semua modal --}}
    @include('items.partials._create-modal')
    @include('items.partials._edit-modal')
    @include('items.partials._import-modal')
</x-app-layout>

{{-- Script --}}
<script>
    function toggleCreateModal() {
        document.getElementById('createModal').classList.toggle('hidden');
    }

    function toggleImportModal() {
        document.getElementById('importModal').classList.toggle('hidden');
    }

    // Tombol Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name || '';
            const unit = btn.dataset.unit || '';
            const catId = btn.dataset.category || '';
            const desc = btn.dataset.description || '';
            const photo = btn.dataset.photo || '';

            document.getElementById('editForm').action = "{{ url('items') }}/" + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_unit').value = unit;
            document.getElementById('edit_category').value = catId;
            document.getElementById('edit_description').value = desc;

            const img = document.getElementById('edit_photo_preview');
            const noPhoto = document.getElementById('edit_no_photo');
            if (photo) {
                img.src = photo;
                img.classList.remove('hidden');
                noPhoto.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                noPhoto.classList.remove('hidden');
            }

            document.getElementById('editModal').classList.remove('hidden');
        });
    });

    // Auto-hide alert
    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.classList.add('opacity-0');
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
</script>
