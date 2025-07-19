<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    // Ambil data user berdasarkan NIM
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE nim = ?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verifikasi password yang di-hash
        if (password_verify($password, $user['password'])) {
            // Login berhasil, simpan data penting ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            // Arahkan pengguna ke dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Jika password salah
            header("Location: ../login.php?error=invalid");
            exit();
        }
    } else {
        // Jika user (NIM) tidak ditemukan
        header("Location: ../login.php?error=notfound");
        exit();
    }
    $stmt->close();
}
$conn->close();
?>