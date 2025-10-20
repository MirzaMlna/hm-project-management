<script>
    function toggleCreateModal() {
        document.getElementById('createModal').classList.toggle('hidden');
    }

    function updateTotalPrice() {
        const qty = document.querySelector('input[name="quantity"]').value || 0;
        const price = document.querySelector('input[name="unit_price"]').value || 0;
        const total = qty * price;
        document.getElementById('total_price').value = new Intl.NumberFormat('id-ID').format(total);
    }

    // Auto hide alert
    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.classList.add('opacity-0');
            setTimeout(() => alert.remove(), 500);
        }
    }, 4000);

    // Dropdown dinamis: Kategori → Barang
    document.getElementById('categorySelect').addEventListener('change', function() {
        const categoryId = this.value;
        const itemSelect = document.getElementById('itemSelect');
        itemSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
        itemSelect.disabled = true;

        if (categoryId) {
            fetch(`/get-items-by-category/${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            itemSelect.appendChild(option);
                        });
                        itemSelect.disabled = false;
                    } else {
                        const opt = document.createElement('option');
                        opt.textContent = 'Tidak ada barang di kategori ini';
                        itemSelect.appendChild(opt);
                    }
                })
                .catch(() => {
                    const opt = document.createElement('option');
                    opt.textContent = 'Gagal memuat data';
                    itemSelect.appendChild(opt);
                });
        }
    });
</script>
