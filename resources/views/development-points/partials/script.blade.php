<script>
    function toggleCreateModal() {
        document.getElementById('createModal').classList.toggle('hidden');
    }

    function toggleEditModal(id = null, point = null, photo = null) {
        const modal = document.getElementById('editModal');
        modal.classList.toggle('hidden');

        if (id && point) {
            document.getElementById('edit_point').value = point;
            document.getElementById('editForm').action = `/development-points/${id}`;

            const previewDiv = document.getElementById('previewPhoto');
            previewDiv.innerHTML = '';
            if (photo) {
                previewDiv.innerHTML =
                    `<img src="/storage/${photo}" alt="Foto lama" class="h-16 w-16 object-cover rounded">`;
            }
        }
    }

    function showPhoto(src) {
        const modal = document.createElement('div');
        modal.className = "fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4";
        modal.innerHTML = `
            <div class='bg-white rounded-lg shadow-lg max-w-lg w-full relative'>
                <button onclick='this.closest(".fixed").remove()' class='absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl'>✕</button>
                <div class='p-4'>
                    <img src='${src}' alt='Foto Titik Pembangunan' class='max-h-[70vh] w-full object-contain rounded'>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // Auto-hide alert
    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.classList.add('opacity-0');
            setTimeout(() => alert.remove(), 500);
        }
    }, 4000);
</script>
