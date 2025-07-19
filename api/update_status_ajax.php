<?php
// Tujuan: Menerima permintaan dari JavaScript, update database, dan kirim respon JSON.
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Keamanan: Pastikan yang mengakses adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit();
}

// Ambil data yang dikirim oleh JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$registration_id = $data['id'] ?? null;
$new_status = $data['status'] ?? null;

if (!$registration_id || !$new_status) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit();
}

// Keamanan: Pastikan status yang diinput valid
if ($new_status !== 'approved' && $new_status !== 'rejected') {
    echo json_encode(['success' => false, 'message' => 'Status tidak valid.']);
    exit();
}

// Update status di database
$stmt = $conn->prepare("UPDATE registrations SET status = ? WHERE id = ?");
$stmt->bind_param("si", $new_status, $registration_id);

if ($stmt->execute()) {
    // Kirim notifikasi ke mahasiswa (logika yang sudah ada)
    $info_stmt = $conn->prepare("SELECT r.user_id, e.name AS event_name FROM registrations r JOIN events e ON r.event_id = e.id WHERE r.id = ?");
    $info_stmt->bind_param("i", $registration_id);
    $info_stmt->execute();
    $info_result = $info_stmt->get_result()->fetch_assoc();
    if ($info_result) {
        $target_user_id = $info_result['user_id'];
        $event_name = $info_result['event_name'];
        $status_text = ($new_status == 'approved') ? 'disetujui' : 'ditolak';
        $message = "Pendaftaran Anda untuk event '" . $event_name . "' telah " . $status_text . ".";
        $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, 'dashboard.php')");
        $notif_stmt->bind_param("is", $target_user_id, $message);
        $notif_stmt->execute();
        $notif_stmt->close();
    }
    $info_stmt->close();

    echo json_encode(['success' => true, 'new_status' => $new_status]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui database.']);
}

$stmt->close();
$conn->close();
?>