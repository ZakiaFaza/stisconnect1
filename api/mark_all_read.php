<?php
session_start();
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

require_once '../config/database.php';

$user_id = $_SESSION['user_id'];

// Update semua notifikasi yang belum dibaca milik user ini
$stmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Cek apakah ada baris yang terpengaruh (berarti ada notif yang diupdate)
    $was_updated = $stmt->affected_rows > 0;
    echo json_encode(['success' => true, 'updated' => $was_updated]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database update failed']);
}

$stmt->close();
$conn->close();
?>