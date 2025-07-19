<?php
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $news_id = $_POST['news_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $image_url = !empty($_POST['image_url']) ? $_POST['image_url'] : null;

    if (empty($news_id) || empty($title) || empty($content) || empty($category)) {
        // Handle error: field kosong
        header("Location: ../admin/edit_news.php?id=$news_id&error=empty");
        exit();
    }

    $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, category = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $content, $category, $image_url, $news_id);

    if ($stmt->execute()) {
        header("Location: ../admin/manage_news.php?status=edit_success");
    } else {
        header("Location: ../admin/edit_news.php?id=$news_id&status=error");
    }
    $stmt->close();
    $conn->close();
}
?>