<?php
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $recruitment_open = $_POST['recruitment_open'];
    $created_by = $_SESSION['user_id'];
    
    // Ambil data posisi, bersihkan, dan encode ke JSON
    $positions = isset($_POST['positions']) ? array_filter($_POST['positions']) : [];
    $positions_json = json_encode($positions);

    if (empty($name) || empty($description) || empty($event_date)) {
        header("Location: ../admin/add_event.php?error=empty");
        exit();
    }

    $recruitment_open = 1; 

    $stmt = $conn->prepare("INSERT INTO events (name, description, event_date, recruitment_open, required_positions, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisi", $name, $description, $event_date, $recruitment_open, $positions_json, $created_by);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?status=add_success");
    } else {
        header("Location: ../admin/add_event.php?status=error");
    }
    $stmt->close();
    $conn->close();
}
?>