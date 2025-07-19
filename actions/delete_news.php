<?php
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $news_id);
    
    if ($stmt->execute()) {
        header("Location: ../admin/manage_news.php?status=delete_success");
    } else {
        header("Location: ../admin/manage_news.php?status=delete_error");
    }
    $stmt->close();
} else {
    header("Location: ../admin/manage_news.php");
}
$conn->close();
exit();
?>