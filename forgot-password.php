<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - STISConnect</title>
    <link rel="stylesheet" href="assets/css/auth.css?v=1.5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">
    <a href="login.php" class="back-to-home">← Kembali ke Login</a>
    <div class="auth-card">
        <div class="auth-info-panel">
            <div class="info-content">
                <h2>Lupa Password?</h2>
                <p>Jangan khawatir. Masukkan NIM Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.</p>
            </div>
        </div>
        <div class="auth-form-panel">
            <h3>Reset Password</h3>
            <?php 
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert success">' . $_SESSION['message'] . '</div>';
                    unset($_SESSION['message']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert error">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
            ?>
            <form action="actions/send-reset-link.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" required>
                </div>
                <button type="submit" class="btn-auth">Kirim Tautan Reset</button>
            </form>
        </div>
    </div>
</body>
</html>