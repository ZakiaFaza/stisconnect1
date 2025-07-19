document.addEventListener('DOMContentLoaded', () => {
    const actionButtons = document.querySelectorAll('.btn-action');

    actionButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const registrationId = button.dataset.id;
            const newStatus = button.classList.contains('approve') ? 'approved' : 'rejected';
            
            // Nonaktifkan kedua tombol untuk mencegah klik ganda
            const parent = button.parentElement;
            parent.querySelectorAll('.btn-action').forEach(btn => btn.disabled = true);
            button.textContent = 'Memproses...';

            try {
                const response = await fetch('api/update_status_ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: registrationId,
                        status: newStatus
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    // Update UI jika berhasil
                    const row = document.getElementById(`registration-row-${registrationId}`);
                    const statusCell = row.querySelector('.status-cell');
                    const actionCell = row.querySelector('.action-buttons');

                    // Hapus tombol-tombol
                    actionCell.innerHTML = ''; 

                    // Update badge status
                    const statusText = result.new_status.charAt(0).toUpperCase() + result.new_status.slice(1);
                    statusCell.innerHTML = `<span class="status-badge status-${result.new_status}">${statusText}</span>`;
                } else {
                    // Jika gagal, aktifkan kembali tombol dan tampilkan error
                    alert('Gagal memperbarui status: ' + result.message);
                    parent.querySelectorAll('.btn-action').forEach(btn => btn.disabled = false);
                    button.textContent = button.classList.contains('approve') ? 'Approve' : 'Reject';
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan.');
                parent.querySelectorAll('.btn-action').forEach(btn => btn.disabled = false);
                button.textContent = button.classList.contains('approve') ? 'Approve' : 'Reject';
            }
        });
    });

    const searchInput = document.getElementById('event-search-input');
    const eventTableBody = document.getElementById('event-table-body');
    const tableRows = eventTableBody.getElementsByTagName('tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const nameCell = row.cells[0];
                if (nameCell) {
                    const nameText = nameCell.textContent.toLowerCase();
                    if (nameText.includes(searchTerm)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            }
        });
    }
});