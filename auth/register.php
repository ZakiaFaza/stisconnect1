<?php 
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $full_name = $_POST['full_name'];
    $password = $_POST['password'];
    $errors = [];

    // 1. Validasi NIM
    if (!preg_match('/^[0-9]{9,}$/', $nim)) {
        $errors[] = "NIM harus berupa angka dan minimal 9 digit.";
    }
    
    // 2. Validasi Nama
    if (!preg_match("/^[a-zA-Z-' ]*$/", $full_name)) {
        $errors[] = "Nama lengkap hanya boleh mengandung huruf dan spasi.";
    }

    // 3. Validasi Password
    if (strlen($password) < 8) {
        $errors[] = "Password minimal harus 8 karakter.";
    }

    // Jika ada error, kembali ke form dengan pesan
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../register.php");
        exit();
    }
    // Validasi sederhana agar tidak ada field yang kosong
    if (empty($nim) || empty($full_name) || empty($password)) {
        header("Location: ../register.php?error=empty");
        exit();
    }

    // Cek apakah NIM sudah terdaftar untuk mencegah duplikat
    $stmt = $conn->prepare("SELECT id FROM users WHERE nim = ?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Jika NIM sudah ada
        header("Location: ../register.php?error=nim_exists");
        exit();
    }
    $stmt->close();

    // Hash password sebelum disimpan ke database untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data pengguna baru ke database
    $stmt = $conn->prepare("INSERT INTO users (nim, full_name, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nim, $full_name, $hashed_password);

    if ($stmt->execute()) {
        // Jika registrasi berhasil, arahkan ke halaman login dengan pesan sukses
        header("Location: ../login.php?status=success");
        exit();
    } else {
        // Jika terjadi error saat menyimpan
        die("Registrasi gagal: " . $stmt->error);
    }
    $stmt->close();
}
$conn->close();
?>