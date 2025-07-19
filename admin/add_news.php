<?php
session_start();
// Keamanan: Hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Berita Baru - STISConnect</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin_form.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <main>
        <div class="form-container">
            <div class="form-card">
                <h2>Publikasikan Berita Baru</h2>
                <p>Isi detail berita atau pengumuman di bawah ini.</p>
                <form action="../actions/create_news.php" method="POST">
                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" required>
                            <option value="berita_terbaru">Berita Terbaru</option>
                            <option value="pengumuman_kampus">Pengumuman Kampus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="content">Isi Berita</label>
                        <textarea id="content" name="content" rows="10" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image_url">URL Gambar</label>
                        <input type="text" id="image_url" name="image_url" placeholder="https://... (contoh: dari placehold.co)">
                    </div>
                    <div class="form-actions">
                        <a href="manage_news.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Publikasikan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
    <script src="../assets/js/theme-switcher.js"></script>
</body>
</html>