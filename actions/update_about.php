<?php 
session_start();
require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contents_to_update = $_POST;
    $stmt = $conn->prepare("UPDATE site_content SET content_value = ? WHERE content_key = ?");
    
    foreach ($contents_to_update as $key => $value) {
        $stmt->bind_param("ss", $value, $key);
        $stmt->execute();
    }
    
    $stmt->close();
    $conn->close();
    header("Location: ../admin/manage_about.php?status=success");
    exit();
}
?>