<?php
session_start();

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Arahkan (redirect) pengguna ke halaman utama (index.php) setelah logout
header("Location: ../index.php");
exit(); // Pastikan tidak ada kode lain yang dieksekusi setelah redirect
?>