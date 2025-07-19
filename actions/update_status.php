<?php
session_start();
require_once '../config/database.php';

// Pastikan yang mengakses adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Validasi input dari URL
if (isset($_GET['id']) && isset($_GET['status'])) {
    $registration_id = intval($_GET['id']);
    $new_status = $_GET['status'];

    // Pastikan status yang diinput valid
    if ($new_status == 'approved' || $new_status == 'rejected') {
        
        // --- AWAL LOGIKA BARU ---
        // Ambil user_id dan event_name sebelum update
        $info_stmt = $conn->prepare(
            "SELECT r.user_id, e.name AS event_name 
             FROM registrations r
             JOIN events e ON r.event_id = e.id
             WHERE r.id = ?"
        );
        $info_stmt->bind_param("i", $registration_id);
        $info_stmt->execute();
        $info_result = $info_stmt->get_result()->fetch_assoc();
        $target_user_id = $info_result['user_id'];
        $event_name = $info_result['event_name'];
        $info_stmt->close();
        // --- AKHIR LOGIKA BARU ---

        // Update status di database
        $stmt = $conn->prepare("UPDATE registrations SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $registration_id);

        if ($stmt->execute()) {
            // --- AWAL LOGIKA BARU ---
            // Buat pesan notifikasi
            $status_text = ($new_status == 'approved') ? 'disetujui' : 'ditolak';
            $message = "Selamat! Pendaftaran Anda untuk event '" . $event_name . "' telah " . $status_text . ".";
            
            // Simpan notifikasi ke database
            $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
            $link_to_dashboard = 'dashboard.php';
            $notif_stmt->bind_param("iss", $target_user_id, $message, $link_to_dashboard);
            $notif_stmt->execute();
            $notif_stmt->close();
            // --- AKHIR LOGIKA BARU ---

            // Berhasil, kembali ke dashboard
            header("Location: ../dashboard.php?update=success");
        } else {
            // Gagal
            header("Location: ../dashboard.php?update=error");
        }
        $stmt->close();

    } else {
        // Status tidak valid
        header("Location: ../dashboard.php?update=invalid");
    }

} else {
    // Parameter tidak lengkap
    header("Location: ../dashboard.php");
}

$conn->close();
exit();
?>