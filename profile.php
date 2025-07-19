<?php
session_start();
require_once 'config/database.php';

// Jika pengguna belum login, redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$stmt = $conn->prepare("SELECT nim, full_name, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - STISConnect</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css"> <!-- CSS Khusus Halaman Profil -->
</head>
<body>
    <?php include 'templates/header.php'; // Memuat header yang sudah diperbarui ?>

    <main>
        <div class="profile-container container">
            <h1>Profil Saya</h1>
            
            <div class="profile-card">
                <h2>Informasi Akun</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIM</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['nim']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Peran</span>
                        <span class="info-value"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></span>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <h2>Ubah Password</h2>
                 <?php
                    // Menampilkan pesan sukses atau error
                    if (isset($_GET['status'])) {
                        if ($_GET['status'] == 'success') {
                            echo '<div class="alert success">Password berhasil diperbarui!</div>';
                        } elseif ($_GET['status'] == 'error') {
                             echo '<div class="alert error">Terjadi kesalahan. Silakan coba lagi.</div>';
                        } elseif ($_GET['status'] == 'pwd_mismatch') {
                             echo '<div class="alert error">Konfirmasi password baru tidak cocok.</div>';
                        } elseif ($_GET['status'] == 'wrong_pwd') {
                             echo '<div class="alert error">Password lama yang Anda masukkan salah.</div>';
                        }
                    }
                ?>
                <form action="actions/update_profile.php" method="POST">
                    <div class="form-group">
                        <label for="old_password">Password Lama</label>
                        <input type="password" name="old_password" id="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/notification.js"></script>
</body>
</html>