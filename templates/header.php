<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tentukan base path untuk proyek Anda.
// Ubah '/STISConnectv1' jika nama folder proyek Anda berbeda.
$base_path = '/STISConnectv1'; 

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
        <a href="<?php echo $base_path; ?>/index.php" class="logo">STIS<b>Connect</b></a>
        <nav class="main-nav">
            <ul>
                <li><a href="<?php echo $base_path; ?>/index.php">Home</a></li>
                <li><a href="<?php echo $base_path; ?>/events.php">Events</a></li>
                <li><a href="<?php echo $base_path; ?>/news.php">News</a></li>
                <li><a href="<?php echo $base_path; ?>/gallery.php">Gallery</a></li>
                <li><a href="<?php echo $base_path; ?>/about.php">About</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base_path; ?>/dashboard.php">Dashboard</a></li>
                    <li><a href="<?php echo $base_path; ?>/profile.php">Profil</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="header-actions">
            <!-- Tombol Mode Gelap -->
            <button id="theme-switcher" class="theme-button" title="Ganti Tema">
                <svg id="theme-icon-sun" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m4.93 19.07 1.41-1.41"></path><path d="m17.66 6.34 1.41-1.41"></path></svg>
                <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg>
            </button>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Notifikasi Dropdown -->
                <div class="notification-dropdown" id="notification-bell">
                    <a href="javascript:void(0);" title="Notifikasi" class="notification-trigger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                        <?php if ($unread_notifications > 0): ?>
                            <span class="notification-badge"><?php echo $unread_notifications; ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-content">
                        <div class="dropdown-header">Notifikasi</div>
                        <?php if (!empty($notifications_list)): ?>
                            <?php foreach ($notifications_list as $notif): ?>
                                <a href="<?php echo $base_path . '/' . htmlspecialchars($notif['link']); ?>" class="dropdown-item <?php echo $notif['is_read'] ? '' : 'unread'; ?>">
                                    <p><?php echo htmlspecialchars($notif['message']); ?></p>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="dropdown-item"><p>Tidak ada notifikasi.</p></div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Tombol Logout -->
                <a href="<?php echo $base_path; ?>/auth/logout.php" class="btn btn-logout">Logout</a>
            <?php else: ?>
                <!-- Tombol Login -->
                <a href="<?php echo $base_path; ?>/login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>