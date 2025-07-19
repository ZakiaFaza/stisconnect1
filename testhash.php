<?php
// File: test_hash.php
// Tujuan: Membuat hash yang 100% kompatibel dengan sistem Anda.

$passwordToHash = 'password123';

$newHash = password_hash($passwordToHash, PASSWORD_DEFAULT);

echo "<h1>Hash Baru Anda</h1>";
echo "<p>Silakan salin seluruh teks di bawah ini dan tempel ke kolom 'password' untuk user admin di phpMyAdmin.</p>";
echo "<hr>";
echo "<pre>" . htmlspecialchars($newHash) . "</pre>";

?>