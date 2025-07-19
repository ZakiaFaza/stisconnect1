<?php
session_start();
require_once '../config/database.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Hanya proses jika request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validasi konfirmasi password baru
    if ($new_password !== $confirm_password) {
        header("Location: ../profile.php?status=pwd_mismatch");
        exit();
    }

    // 2. Ambil hash password saat ini dari database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $current_hashed_password = $result['password'];
    $stmt->close();

    // 3. Verifikasi password lama
    if (password_verify($old_password, $current_hashed_password)) {
        // Jika password lama benar, hash password baru
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 4. Update password baru ke database
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_hashed_password, $user_id);
        
        if ($update_stmt->execute()) {
            // Berhasil
            header("Location: ../profile.php?status=success");
        } else {
            // Gagal update
            header("Location: ../profile.php?status=error");
        }
        $update_stmt->close();

    } else {
        // Jika password lama salah
        header("Location: ../profile.php?status=wrong_pwd");
        exit();
    }

} else {
    // Jika bukan POST, redirect
    header("Location: ../profile.php");
}

$conn->close();
exit();
?>