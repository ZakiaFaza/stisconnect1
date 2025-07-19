<?php
// Berfungsi untuk membuat koneksi ke database.
define('DB_HOST', 'localhost');
define('DB_USER', 'projec15_root'); 
define('DB_PASS', '@kaesquare123'); 
define('DB_NAME', 'projec15_stisconnect_db');

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}