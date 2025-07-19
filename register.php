<?php 
session_start();
if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - STISConnect</title>
    <link rel="stylesheet" href="assets/css/auth.css"> <!-- File CSS Khusus -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">
    <a href="index.php" class="back-to-home">← Kembali ke Beranda</a>
    <div class="auth-card">
        <!-- Panel Kiri dengan Gambar dan Teks Sambutan -->
        <div class="auth-info-panel">
            <div class="info-content">
                <h2>Bergabunglah dengan Kami!</h2>
                <p>Buat akun baru untuk mulai menjelajahi semua fitur di STISConnect.</p>
            </div>
        </div>
        <!-- Panel Kanan dengan Form Registrasi -->
        <div class="auth-form-panel">
            <h3>Sign Up</h3>
            <?php if (isset($_GET['error'])) { /* ... kode pesan error ... */ } ?>

            <form action="auth/register.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" required pattern="[a-zA-Z\s]+">
                </div>
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" required pattern="[0-9]{9,}" title="NIM harus berupa angka minimal 9 digit.">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <button type="submit" class="btn-auth">Sign Up</button>
            </form>
            <p class="auth-switch">Sudah punya akun? <a href="login.php">Sign In</a></p>
        </div>
    </div>
</body>
</html>