document.addEventListener('DOMContentLoaded', () => {
    const notificationBell = document.getElementById('notification-bell');
    
    if (!notificationBell) {
        return; // Hentikan jika elemen tidak ditemukan
    }

    const notificationBadge = notificationBell.querySelector('.notification-badge');

    // Event listener untuk menandai notifikasi sebagai sudah dibaca
    notificationBell.addEventListener('click', async () => {
        // Hanya jalankan jika ada badge (artinya ada notifikasi belum dibaca)
        if (notificationBadge) {
            try {
                const response = await fetch('api/mark_all_read.php', {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.success && result.updated) {
                    // Hilangkan badge secara visual setelah berhasil
                    notificationBadge.style.display = 'none';
                }
            } catch (error) {
                console.error('Gagal menandai notifikasi:', error);
            }
        }
    });
});