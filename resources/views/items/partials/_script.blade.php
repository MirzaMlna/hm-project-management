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
