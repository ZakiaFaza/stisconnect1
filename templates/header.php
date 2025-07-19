<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    $base_url = 'http://localhost/STISConnectv1';
} else {
    $base_url = 'http://stisconnect.project2ks2.my.id';
}

// Pastikan koneksi database ada sebelum menjalankan query notifikasi
$unread_notifications = 0;
$notifications_list = [];
if (isset($_SESSION['user_id']) && isset($conn)) {
    $current_user_id = $_SESSION['user_id'];
    
    // Ambil data notifikasi
    $count_stmt = $conn->prepare("SELECT COUNT(id) AS unread_count FROM notifications WHERE user_id = ? AND is_read = FALSE");
    $count_stmt->bind_param("i", $current_user_id);
    $count_stmt->execute();
    $unread_notifications = $count_stmt->get_result()->fetch_assoc()['unread_count'];
    $count_stmt->close();
    
    $list_stmt = $conn->prepare("SELECT id, message, is_read, link FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $list_stmt->bind_param("i", $current_user_id);
    $list_stmt->execute();
    $notifications_list = $list_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $list_stmt->close();
}
?>
<header class="main-header">
    <div class="container">
        <a href="<?php echo $base_url; ?>/index.php" class="logo">STIS<b>Connect</b></a>
        <nav class="main-nav" id="main-nav">
            <ul>
                <li><a href="<?php echo $base_url; ?>/index.php">Home</a></li>
                <li><a href="<?php echo $base_url; ?>/events.php">Events</a></li>
                <li><a href="<?php echo $base_url; ?>/news.php">News</a></li>
                <li><a href="<?php echo $base_url; ?>/gallery.php">Gallery</a></li>
                <li><a href="<?php echo $base_url; ?>/about.php">About</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base_url; ?>/dashboard.php">Dashboard</a></li>
                    <li><a href="<?php echo $base_url; ?>/profile.php">Profil</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="header-actions">
            <!-- ... (Tombol Tema & Notifikasi) ... -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $base_url; ?>/auth/logout.php" class="btn btn-logout">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>/login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </div>
        <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>