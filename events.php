<?php 
require_once 'config/database.php';

// Ambil event yang akan datang (tanggalnya hari ini atau di masa depan)
$upcoming_events_result = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");

// Ambil event yang sudah lewat (tanggalnya sebelum hari ini)
$past_events_result = $conn->query("SELECT * FROM events WHERE event_date < CURDATE() ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Semua Event - STISConnect</title>
    <link rel="stylesheet" href="assets/css/style.css?v=1.5">
    <link rel="stylesheet" href="assets/css/events.css?v=1.5"> <!-- CSS Khusus -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="page-header reveal">
            <h1>Temukan Peluangmu</h1>
            <p>Jadilah bagian dari kegiatan-kegiatan terbaik di kampus dan bangun pengalaman tak terlupakan.</p>
        </div>
        
        <div class="page-content">
            <div class="container">
                <section class="event-category reveal">
                    <h2>Akan Datang</h2>
                    <div class="event-grid">
                        <?php if ($upcoming_events_result->num_rows > 0): ?>
                            <?php while($event = $upcoming_events_result->fetch_assoc()): ?>
                            <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="event-card-large">
                                <div class="event-card-image" style="background-image: url('https://placehold.co/600x400/<?php echo substr(md5($event['name']), 0, 6); ?>/FFFFFF?text=<?php echo urlencode($event['name']); ?>')"></div>
                                <div class="event-card-content">
                                    <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                                    <span class="date"><?php echo date('d F Y', strtotime($event['event_date'])); ?></span>
                                    <p><?php echo substr(strip_tags($event['description']), 0, 100) . '...'; ?></p>
                                    <div class="event-card-footer">
                                        <span class="status <?php echo $event['recruitment_open'] ? 'open' : 'closed'; ?>">
                                            <?php echo $event['recruitment_open'] ? 'Rekrutmen Dibuka' : 'Rekrutmen Ditutup'; ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="no-data">Tidak ada event yang akan datang saat ini. Cek kembali nanti!</p>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="event-category reveal">
                    <h2>Arsip Event</h2>
                    <div class="event-grid">
                        <?php if ($past_events_result->num_rows > 0): ?>
                            <?php while($event = $past_events_result->fetch_assoc()): ?>
                            <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="event-card-large past">
                                <div class="event-card-image" style="background-image: url('https://placehold.co/600x400/<?php echo substr(md5($event['name']), 0, 6); ?>/FFFFFF?text=<?php echo urlencode($event['name']); ?>')"></div>
                                <div class="event-card-content">
                                    <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                                    <span class="date"><?php echo date('d F Y', strtotime($event['event_date'])); ?></span>
                                    <p><?php echo substr(strip_tags($event['description']), 0, 100) . '...'; ?></p>
                                    <div class="event-card-footer">
                                        <span class="status closed">
                                            Telah Selesai
                                        </span>
                                    </div>
                                </div>
                            </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="no-data">Belum ada arsip event.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/notification.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>