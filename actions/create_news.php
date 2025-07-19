<?php

session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $image_url = !empty($_POST['image_url']) ? $_POST['image_url'] : null;
    $author_id = $_SESSION['user_id'];

    if (empty($title) || empty($content) || empty($category)) {
        // Handle error: field kosong
        header("Location: ../admin/add_news.php?error=empty");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO news (title, content, category, image_url, author_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $content, $category, $image_url, $author_id);

    if ($stmt->execute()) {
        header("Location: ../admin/manage_news.php?status=add_success");
    } else {
        header("Location: ../admin/add_news.php?status=error");
    }
    $stmt->close();
    $conn->close();
}
?>