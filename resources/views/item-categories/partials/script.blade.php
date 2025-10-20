<script>
    function toggleCreateModal() {
        document.getElementById('createModal').classList.toggle('hidden');
    }

    function toggleEditModal(id = null, category = null) {
        const modal = document.getElementById('editModal');
        modal.classList.toggle('hidden');

        if (id && category) {
            document.getElementById('edit_category').value = category;
            document.getElementById('editForm').action = `/item-categories/${id}`;
        }
    }
</script>
