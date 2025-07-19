<?php 
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STISConnect - Koneksi Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
    <link rel="stylesheet" href="assets/css/news.css?v=1.3">
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="reveal">Temukan Peluang,<br>Bangun Pengalaman.</h1>
                <p class="reveal" data-delay="100">STISConnect adalah jembatan bagi mahasiswa untuk terlibat dalam kegiatan kampus dan mengembangkan potensi diri.</p>
            </div>
        </section>

        <section id="events" class="content-section reveal">
            <div class="container">
                <div class="section-header">
                    <h2>Event Terbaru</h2>
                    <div class="search-wrapper">
                        <input type="text" id="search-input" placeholder="Cari event..." autocomplete="off">
                        <div id="search-suggestions" class="suggestions-container"></div>
                    </div>
                </div>
                <div class="swiper event-slider">
                    <div class="swiper-wrapper">
                        <?php 
                        $events = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 6");
                        while($event = $events->fetch_assoc()): ?>
                        <div class="swiper-slide">
                            <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="event-card">
                                <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                                <div class="date"><?php echo date('d M Y', strtotime($event['event_date'])); ?></div>
                                <p><?php echo substr(htmlspecialchars(strip_tags($event['description'])), 0, 80) . '...'; ?></p>
                                <span class="status <?php echo $event['recruitment_open'] ? 'open' : 'closed'; ?>">
                                    <?php echo $event['recruitment_open'] ? 'Rekrutmen Dibuka' : 'Rekrutmen Ditutup'; ?>
                                </span>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>

        <section id="news" class="content-section reveal">
            <div class="container">
                <div class="section-header">
                    <h2>Berita & Pengumuman</h2>
                    <a href="news.php" class="btn-see-more">Lihat Semua</a>
                </div>
                 <div class="swiper news-slider">
                    <div class="swiper-wrapper">
                        <?php 
                        $news_items = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 6");
                        while($news = $news_items->fetch_assoc()): ?>
                        <div class="swiper-slide">
                            <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="news-card">
                                <img src="<?php echo htmlspecialchars($news['image_url']); ?>" alt="Gambar Berita" onerror="this.onerror=null;this.src='https://placehold.co/600x400/CCCCCC/FFFFFF?text=Image';">
                                <div class="news-content">
                                    <span class="category"><?php echo ucwords(str_replace('_', ' ', $news['category'])); ?></span>
                                    <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                                </div>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>

        <section id="gallery" class="content-section reveal">
            <div class="container">
                <div class="section-header">
                    <h2>Galeri Kegiatan</h2>
                    <a href="gallery.php" class="btn-see-more">Lihat Semua</a>
                </div>
                <div class="swiper gallery-slider">
                    <div class="swiper-wrapper">
                        <?php 
                        $gallery_items = $conn->query("SELECT g.*, e.name as event_name FROM gallery g JOIN events e ON g.event_id = e.id ORDER BY g.uploaded_at DESC LIMIT 6");
                        while($item = $gallery_items->fetch_assoc()): ?>
                        <div class="swiper-slide">
                            <a href="gallery.php" class="news-card"> <!-- Menggunakan style yang sama dengan news-card -->
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['caption']); ?>">
                                <div class="news-content">
                                    <h3><?php echo htmlspecialchars($item['event_name']); ?></h3>
                                </div>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>

        <section class="cta-section reveal">
            <div class="container">
                <h2>Let's Make It Happen.</h2>
                <p>Punya ide untuk kegiatan atau kolaborasi baru di kampus? Jangan ragu untuk menghubungi kami.</p>
                <a href="about.php" class="btn btn-cta">Contact Us</a>
            </div>
        </section>

    </main>
    <?php include 'templates/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/notification.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>