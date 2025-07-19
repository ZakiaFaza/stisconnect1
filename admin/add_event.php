<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Event Baru - STISConnect</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.1">
    <link rel="stylesheet" href="../assets/css/admin_form.css?v=1.1">
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <main>
        <div class="form-container">
            <div class="form-card">
                <h2>Tambah Event Baru</h2>
                <p>Event yang dibuat akan otomatis membuka rekrutmen panitia.</p>
                <form action="../actions/create_event.php" method="POST">
                    <div class="form-group">
                        <label for="name">Nama Event</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="event_date">Tanggal Pelaksanaan</label>
                        <input type="date" id="event_date" name="event_date" required>
                    </div>
                    <div class="form-group">
                        <label>Status Rekrutmen</label>
                        <div class="radio-group">
                            <label><input type="radio" name="recruitment_open" value="1" checked> Buka</label>
                            <label><input type="radio" name="recruitment_open" value="0"> Tutup</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Posisi/Divisi yang Dibutuhkan</label>
                        <div id="positions-container">
                            <div class="position-input-group">
                                <input type="text" name="positions[]" placeholder="Contoh: Divisi Acara" required>
                                <button type="button" class="remove-position-btn">&times;</button>
                            </div>
                        </div>
                        <button type="button" id="add-position-btn" class="btn btn-secondary">Tambah Posisi</button>
                    </div>

                    <div class="form-actions">
                        <a href="../dashboard.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Event</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
    <script src="../assets/js/theme-switcher.js"></script>
    <script>
        // JavaScript untuk menambah dan menghapus input posisi
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('positions-container');
            const addBtn = document.getElementById('add-position-btn');

            addBtn.addEventListener('click', function() {
                const positionInput = document.createElement('div');
                positionInput.classList.add('position-input-group');
                positionInput.innerHTML = `
                    <input type="text" name="positions[]" placeholder="Contoh: Divisi Acara" required>
                    <button type="button" class="remove-position-btn">&times;</button>
                `;
                container.appendChild(positionInput);
            });

            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-position-btn')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
</body>
</html>