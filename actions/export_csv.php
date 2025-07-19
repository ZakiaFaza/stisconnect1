<?php
session_start();
require_once '../config/database.php';

// Pastikan yang mengakses adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Akses ditolak.");
}

// Pastikan ID event ada di URL
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit("ID Event tidak ditemukan.");
}

$event_id = intval($_GET['event_id']);

// Ambil nama event untuk nama file
$event_stmt = $conn->prepare("SELECT name FROM events WHERE id = ?");
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_name = $event_stmt->get_result()->fetch_assoc()['name'];
$event_stmt->close();

if (!$event_name) {
    exit("Event tidak valid.");
}

// Ambil semua data pendaftar untuk event (tanpa pagination)
$stmt = $conn->prepare(
    "SELECT u.full_name, u.nim, r.status, r.registration_date
     FROM registrations r
     JOIN users u ON r.user_id = u.id
     WHERE r.event_id = ?
     ORDER BY r.registration_date ASC"
);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

// Siapkan nama file CSV
$filename = "Pendaftar_" . preg_replace('/[^a-zA-Z0-9]+/', '_', $event_name) . ".csv";

// Atur header HTTP untuk memaksa download file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Buka output stream PHP
$output = fopen('php://output', 'w');

// Tulis baris header ke file CSV
fputcsv($output, ['No', 'Nama Lengkap', 'NIM', 'Status Pendaftaran', 'Tanggal Daftar']);

// Tulis data pendaftar baris per baris
$row_number = 1;
while ($row = $result->fetch_assoc()) {
    $csv_row = [
        $row_number++,
        $row['full_name'],
        $row['nim'],
        ucfirst($row['status']),
        date('d-m-Y H:i:s', strtotime($row['registration_date']))
    ];
    fputcsv($output, $csv_row);
}

// Tutup output stream
fclose($output);
$stmt->close();
$conn->close();
exit();
?>