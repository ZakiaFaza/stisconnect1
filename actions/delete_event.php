<?php 
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
    
    // Hapus pendaftaran terkait terlebih dahulu
    $stmt_reg = $conn->prepare("DELETE FROM registrations WHERE event_id = ?");
    $stmt_reg->bind_param("i", $event_id);
    $stmt_reg->execute();
    $stmt_reg->close();

    // Hapus event
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    
    if ($stmt->execute()) {
        header("Location: ../dashboard.php?status=delete_success");
    } else {
        header("Location: ../dashboard.php?status=delete_error");
    }
    $stmt->close();
} else {
    header("Location: ../dashboard.php");
}
$conn->close();
exit();
?>