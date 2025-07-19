<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - STISConnect</title>
    <!-- Memuat file CSS khusus untuk halaman ini -->
    <link rel="stylesheet" href="assets/css/auth.css?v=1.5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="auth-page">
    <a href="index.php" class="back-to-home">← Kembali ke Beranda</a>
    <div class="auth-card">
        <div class="auth-info-panel">
            <div class="info-content">
                <h2 class="logo">STIS<b>Connect</b></h2>
                <p>Selamat Datang Kembali! Masuk untuk melanjutkan perjalanan Anda.</p>
            </div>
        </div>
        <div class="auth-form-panel">
            <h3>Sign In</h3>
            
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert success">Registrasi berhasil! Silakan login.</div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert error">NIM atau password salah.</div>
            <?php endif; ?>

            <form action="auth/login.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password">
                            <i data-feather="eye"></i>
                        </span>
                    </div>
                </div>
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember_me">
                        <span class="custom-checkbox"></span>
                        Remember me
                    </label>
                    <a href="forgot-password.php">Forgot password?</a>
                </div>
                <!-- Tombol Sign In -->
                <button type="submit" class="btn-auth">Sign In</button>
            </form>
            <p class="auth-switch">Belum punya akun? <a href="register.php">Sign Up</a></p>
        </div>
    </div>

    <script>
        feather.replace();
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', () => {
                const passwordInput = item.previousElementSibling;
                const icon = item.querySelector('i');
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                icon.setAttribute('data-feather', isPassword ? 'eye-off' : 'eye');
                feather.replace();
            });
        });
    </script>
</body>
</html>