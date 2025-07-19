<?php 
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

// Hanya Admin Utama (ID=1) yang bisa menjalankan aksi ini.
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Hanya Admin Utama yang dapat mengubah peran.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id_to_update = $data['id'] ?? null;
$is_admin = $data['is_admin'] ?? false;
$new_role = $is_admin ? 'admin' : 'mahasiswa';

if (!$user_id_to_update) {
    echo json_encode(['success' => false, 'message' => 'ID Pengguna tidak valid.']);
    exit();
}

// Mencegah admin menghapus peran adminnya sendiri (double check)
if ($user_id_to_update == $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Anda tidak dapat mengubah peran Anda sendiri.']);
    exit();
}

$stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $new_role, $user_id_to_update);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui database.']);
}
$stmt->close();
$conn->close();
?>