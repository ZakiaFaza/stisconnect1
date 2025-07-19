<?php
require_once 'config/database.php';
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
$sql = "SELECT * FROM news";
if ($category_filter != 'all') {
    $sql .= " WHERE category = ?";
}
$sql .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
if ($category_filter != 'all') {
    $stmt->bind_param("s", $category_filter);
}
$stmt->execute();
$news_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Berita & Pengumuman - STISConnect</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/news.css">
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="page-header reveal">
            <h1>Berita & Pengumuman</h1>
            <p>Ikuti informasi terbaru dari lingkungan kampus.</p>
        </div>
        <div class="page-content">
            <div class="category-filter reveal">
                <a href="news.php" class="<?php if($category_filter == 'all') echo 'active';?>">Semua</a>
                <a href="news.php?category=berita_terbaru" class="<?php if($category_filter == 'berita_terbaru') echo 'active';?>">Berita Terbaru</a>
                <a href="news.php?category=pengumuman_kampus" class="<?php if($category_filter == 'pengumuman_kampus') echo 'active';?>">Pengumuman Kampus</a>
            </div>
            <div class="news-grid">
                <?php while($news = $news_result->fetch_assoc()): ?>
                <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="news-card-full reveal">
                    <div class="news-card-image">
                        <img src="<?php echo htmlspecialchars($news['image_url']); ?>" alt="Gambar Berita">
                    </div>
                    <div class="news-content-full">
                        <span class="category"><?php echo ucwords(str_replace('_', ' ', $news['category'])); ?></span>
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p><?php echo substr(strip_tags($news['content']), 0, 120) . '...'; ?></p>
                        <span class="date"><?php echo date('d M Y', strtotime($news['created_at'])); ?></span>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>