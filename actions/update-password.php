<?php 
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm || strlen($password) < 8) {
        // Handle error
        header("Location: ../reset-password.php?token=$token&error=invalid_password");
        exit();
    }

    $stmt = $conn->prepare("SELECT nim FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($reset_request = $result->fetch_assoc()) {
        $nim = $reset_request['nim'];
        $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update password di tabel users
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE nim = ?");
        $update_stmt->bind_param("ss", $new_hashed_password, $nim);
        $update_stmt->execute();

        // Hapus token agar tidak bisa digunakan lagi
        $delete_stmt = $conn->prepare("DELETE FROM password_resets WHERE nim = ?");
        $delete_stmt->bind_param("s", $nim);
        $delete_stmt->execute();

        $_SESSION['message'] = "Password Anda berhasil diatur ulang. Silakan login.";
        header("Location: ../login.php");
        exit();
    } else {
        // Handle error token tidak valid
        header("Location: ../forgot-password.php?error=invalid_token");
        exit();
    }
}
?>