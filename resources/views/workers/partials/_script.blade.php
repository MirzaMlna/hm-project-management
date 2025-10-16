<script>
    function openDeactivateModal(workerId, workerName) {
        document.getElementById('deactivateModal').classList.remove('hidden');
        document.getElementById('workerNameModal').textContent = workerName;
        const form = document.getElementById('deactivateForm');
        form.action = '/workers/' + workerId + '/deactivate';
        form.note.value = '';
    }

    function closeDeactivateModal() {
        document.getElementById('deactivateModal').classList.add('hidden');
    }

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
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
