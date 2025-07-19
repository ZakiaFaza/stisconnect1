<?php 
require_once 'config/database.php';
$gallery_items = $conn->query("SELECT g.*, e.name as event_name FROM gallery g JOIN events e ON g.event_id = e.id ORDER BY g.uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Galeri Kegiatan - STISConnect</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/gallery.css">
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="page-header reveal">
            <h1>Galeri Kegiatan</h1>
            <p>Dokumentasi momen-momen berharga dari berbagai kegiatan di kampus.</p>
        </div>
        <div class="page-content">
            <div class="gallery-grid">
                <?php while($item = $gallery_items->fetch_assoc()): ?>
                <div class="gallery-item reveal">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['caption']); ?>">
                    <div class="gallery-caption">
                        <h3><?php echo htmlspecialchars($item['event_name']); ?></h3>
                        <p><?php echo htmlspecialchars($item['caption']); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>