document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.role-toggle').forEach(toggle => {
        toggle.addEventListener('change', async (e) => {
            const userId = e.target.dataset.id;
            const isAdmin = e.target.checked;
            
            try {
                // Pastikan path ke API sudah benar (../ karena JS dipanggil dari admin/manage_users.php)
                const response = await fetch('../api/update_user_role.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: userId, is_admin: isAdmin })
                });
                const result = await response.json();
                
                if (!result.success) {
                    alert('Gagal mengubah peran: ' + result.message);
                    e.target.checked = !isAdmin; // Kembalikan tombol ke state semula jika gagal
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan.');
                e.target.checked = !isAdmin; // Kembalikan tombol ke state semula jika gagal
            }
        });
    });
});