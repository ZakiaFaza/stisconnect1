<?php
session_start();
require_once '../config/database.php';

// Pastikan user sudah login dan merupakan mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    // Jika tidak, redirect ke halaman login
    header("Location: ../login.php");
    exit();
}

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];

    // Validasi sederhana
    if (empty($event_id)) {
        header("Location: ../index.php");
        exit();
    }

    // Cek apakah rekrutmen masih dibuka
    $event_stmt = $conn->prepare("SELECT recruitment_open FROM events WHERE id = ?");
    $event_stmt->bind_param("i", $event_id);
    $event_stmt->execute();
    $event_result = $event_stmt->get_result()->fetch_assoc();
    $event_stmt->close();

    if (!$event_result || !$event_result['recruitment_open']) {
        // Jika rekrutmen ditutup atau event tidak ada
        header("Location: ../event_detail.php?id=" . $event_id . "&status=closed");
        exit();
    }

    // Cek pendaftaran ganda (sebagai pengaman tambahan)
    $check_stmt = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Jika sudah terdaftar
        header("Location: ../event_detail.php?id=" . $event_id . "&status=exists");
        exit();
    }
    $check_stmt->close();

    // Masukkan data pendaftaran baru ke database
    $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute()) {
        // Pendaftaran berhasil
        header("Location: ../event_detail.php?id=" . $event_id . "&status=success");
        exit();
    } else {
        // Pendaftaran gagal
        header("Location: ../event_detail.php?id=" . $event_id . "&status=error");
        exit();
    }

    $stmt->close();
} else {
    // Jika bukan POST, redirect
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>