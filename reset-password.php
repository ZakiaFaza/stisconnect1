<?php 
session_start();
require_once 'config/database.php';

if (!isset($_GET['token'])) {
    die("Token tidak valid.");
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Token tidak valid atau sudah kedaluwarsa.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Atur Ulang Password - STISConnect</title>
    <link rel="stylesheet" href="assets/css/auth.css?v=1.5">
</head>
<body class="auth-page">
    <div class="auth-card" style="max-width: 500px;">
        <div class="auth-form-panel">
            <h3>Atur Ulang Password</h3>
            <form action="actions/update-password.php" method="POST" class="auth-form">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="password_confirm">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>
                <button type="submit" class="btn-auth">Simpan Password Baru</button>
            </form>
        </div>
    </div>
</body>
</html>