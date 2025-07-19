<?php 
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    
    // Cek apakah user ada dan bukan admin
    $stmt = $conn->prepare("SELECT role FROM users WHERE nim = ?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && $user['role'] !== 'admin') {
        $token = bin2hex(random_bytes(50));
        $expires = new DateTime('NOW');
        $expires->add(new DateInterval('PT1H')); // Token berlaku 1 jam
        $expires_at = $expires->format('Y-m-d H:i:s');
        
        // Simpan token ke database
        $stmt = $conn->prepare("INSERT INTO password_resets (nim, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nim, $token, $expires_at);
        $stmt->execute();

        $reset_link = "http://localhost/STISConnectv1/reset-password.php?token=" . $token;
        $email_to = $nim . "@stis.ac.id";
        
        // Untuk tujuan demo, kita tampilkan link di pesan sukses
        $_SESSION['message'] = "Tautan reset telah dikirim ke " . $email_to . ". <br><small>Demo Link: <a href='$reset_link' style='color:white;'>Klik di sini</a></small>";
    } else {
        // Tampilkan pesan yang sama meskipun user tidak ada untuk alasan keamanan
        $_SESSION['message'] = "Jika NIM Anda terdaftar, tautan reset akan dikirim ke email Anda.";
    }
    header("Location: ../forgot-password.php");
    exit();
}
?>