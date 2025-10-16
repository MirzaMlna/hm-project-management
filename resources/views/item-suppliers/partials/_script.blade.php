<script>
    function toggleCreateModal() {
        document.getElementById('createModal').classList.toggle('hidden');
    }

    function toggleEditModal() {
        document.getElementById('editModal').classList.toggle('hidden');
    }

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            document.getElementById('editForm').action = `/item-suppliers/${id}`;
            document.getElementById('edit_supplier').value = btn.dataset.supplier || '';
            document.getElementById('edit_phone').value = btn.dataset.phone || '';
            document.getElementById('edit_address').value = btn.dataset.address || '';
            document.getElementById('edit_description').value = btn.dataset.description || '';
            toggleEditModal();
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
