<script>
    // Hilangkan alert setelah 5 detik (5000 ms)
    setTimeout(() => {
        const successAlert = document.getElementById('alert-success');
        const errorAlert = document.getElementById('alert-error');

        [successAlert, errorAlert].forEach(alert => {
            if (alert) {
                alert.classList.add('opacity-0'); // efek fade
                setTimeout(() => alert.remove(), 500); // hapus setelah animasi
            }
        });
    }, 5000);
</script>
