<?php
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Cek ID berita dari URL
if (!isset($_GET['id'])) {
    header("Location: manage_news.php");
    exit();
}

$news_id = intval($_GET['id']);

// Ambil data berita yang akan diedit dari database
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();
$stmt->close();

// Jika berita tidak ditemukan, kembali ke halaman manajemen
if (!$news) {
    header("Location: manage_news.php?error=notfound");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Berita - STISConnect</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin_form.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <main>
        <div class="form-container">
            <div class="form-card">
                <h2>Edit Berita</h2>
                <p>Perbarui detail untuk berita "<?php echo htmlspecialchars($news['title']); ?>"</p>
                <form action="../actions/update_news.php" method="POST">
                    <input type="hidden" name="news_id" value="<?php echo $news['id']; ?>">
                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" required>
                            <option value="berita_terbaru" <?php if($news['category'] == 'berita_terbaru') echo 'selected'; ?>>Berita Terbaru</option>
                            <option value="pengumuman_kampus" <?php if($news['category'] == 'pengumuman_kampus') echo 'selected'; ?>>Pengumuman Kampus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="content">Isi Berita</label>
                        <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image_url">URL Gambar</label>
                        <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($news['image_url']); ?>" placeholder="https://...">
                    </div>
                    <div class="form-actions">
                        <a href="manage_news.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
    <script src="../assets/js/theme-switcher.js"></script>
</body>
</html>