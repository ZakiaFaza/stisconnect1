<?php
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $recruitment_open = $_POST['recruitment_open'];

    // Ambil data posisi, bersihkan, dan encode ke JSON
    $positions = isset($_POST['positions']) ? array_filter($_POST['positions']) : [];
    $positions_json = json_encode($positions);

    if (empty($event_id) || empty($name) || empty($description) || empty($event_date)) {
        header("Location: ../admin/edit_event.php?id=$event_id&error=empty");
        exit();
    }

    $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, event_date = ?, recruitment_open = ?, required_positions = ? WHERE id = ?");
    $stmt->bind_param("sssisi", $name, $description, $event_date, $recruitment_open, $positions_json, $event_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?status=edit_success");
    } else {
        header("Location: ../admin/edit_event.php?id=$event_id&status=error");
    }
    $stmt->close();
    $conn->close();
}
?>