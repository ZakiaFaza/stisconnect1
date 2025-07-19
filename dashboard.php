<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';

// Mengambil data pengguna yang sedang login
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];
$user_full_name = $_SESSION['full_name'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - STISConnect</title>
    
    <!-- Memuat semua file CSS yang dibutuhkan -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    
    <?php include 'templates/header.php'; // Header dimuat dari file terpisah ?>

    <main style="padding-top: 40px; padding-bottom: 40px;">
        <div class="container">
            <div class="dashboard-header reveal">
                <h1>Selamat Datang, <?php echo htmlspecialchars($user_full_name); ?>!</h1>
                <p>Ini adalah halaman dashboard Anda. Peran Anda saat ini adalah <strong><?php echo ucfirst($user_role); ?></strong>.</p>
            </div>

            <div class="dashboard-content reveal" data-delay="100">
                <?php
                // Memuat tampilan berdasarkan peran pengguna
                if ($user_role == 'admin') {
                    include 'templates/admin_dashboard.php';
                } else {
                    include 'templates/mahasiswa_dashboard.php';
                }
                ?>
            </div>
        </div>
    </main>

    <?php include 'templates/footer.php'; // Footer dimuat dari file terpisah ?>
    
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/notification.js"></script>
    <script src="assets/js/admin_dashboard.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>