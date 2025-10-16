<script>
        function toggleCreateModal() {
            document.getElementById('createModal').classList.toggle('hidden');
        }

        function toggleEditModal(id = null, category = null) {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');
            if (id && category) {
                document.getElementById('edit_category').value = category;
                document.getElementById('editForm').action = `/worker-categories/${id}`;
            }
        }

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