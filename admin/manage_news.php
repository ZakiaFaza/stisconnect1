<?php
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil semua berita dari database
$news_result = $conn->query("SELECT id, title, category, created_at FROM news ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita - STISConnect</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <main style="padding-top: 40px; padding-bottom: 40px;">
        <div class="container">
            <div class="dashboard-header">
                <h1>Manajemen Berita</h1>
                <p>Tambah, edit, atau hapus berita dan pengumuman kampus.</p>
            </div>
            <div class="dashboard-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Daftar Berita</h2>
                        <a href="add_news.php" class="btn btn-primary">Tambah Berita Baru</a>
                    </div>
                    <hr>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Judul Berita</th>
                                <th>Kategori</th>
                                <th>Tanggal Publikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($news_result->num_rows > 0): ?>
                                <?php while($news = $news_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($news['title']); ?></td>
                                    <td><?php echo ucwords(str_replace('_', ' ', $news['category'])); ?></td>
                                    <td><?php echo date('d M Y', strtotime($news['created_at'])); ?></td>
                                    <td class="action-links">
                                        <a href="edit_news.php?id=<?php echo $news['id']; ?>">Edit</a>
                                        <a href="../actions/delete_news.php?id=<?php echo $news['id']; ?>" class="reject" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">Hapus</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="no-data">Belum ada berita yang dipublikasikan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
    <script src="../assets/js/theme-switcher.js"></script>
    <script src="../assets/js/notification.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>