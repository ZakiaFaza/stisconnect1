<?php
session_start();
require_once '../config/database.php';

// Pastikan user sudah login dan merupakan mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $desired_position = trim($_POST['desired_position']);
    $motivation_letter = trim($_POST['motivation_letter']);
    $cv_file = $_FILES['cv_file'];

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

    // Cek pendaftaran ganda 
    $check_stmt = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    $upload_dir = '../uploads/cv/';
    $file_path_for_db = null;

    // Pastikan ada file yang diunggah dan tidak ada error
    if (isset($cv_file) && $cv_file['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $cv_file['tmp_name'];
        $file_name = $cv_file['name'];
        $file_size = $cv_file['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validasi ekstensi file (hanya PDF)
        if ($file_ext !== 'pdf') {
            header("Location: ../event_detail.php?id=$event_id&error=file_type");
            exit();
        }

        // Validasi ukuran file (maks 2MB)
        if ($file_size > 2097152) {
            header("Location: ../event_detail.php?id=$event_id&error=file_size");
            exit();
        }

        // Buat nama file unik untuk mencegah penimpaan file
        $new_file_name = uniqid('', true) . '-' . $user_id . '.' . $file_ext;
        $dest_path = $upload_dir . $new_file_name;

        // Pindahkan file dari temporary ke folder permanen
        if (move_uploaded_file($file_tmp_path, $dest_path)) {
            $file_path_for_db = 'uploads/cv/' . $new_file_name;
        } else {
            header("Location: ../event_detail.php?id=$event_id&error=upload_failed");
            exit();
        }
    } else {
        // Jika tidak ada file atau ada error upload
        header("Location: ../event_detail.php?id=$event_id&error=no_file");
        exit();
    }

    if ($check_stmt->num_rows > 0) {
        // Jika sudah terdaftar
        header("Location: ../event_detail.php?id=" . $event_id . "&status=exists");
        exit();
    }
    $check_stmt->close();

    $stmt = $conn->prepare(
        "INSERT INTO registrations (user_id, event_id, desired_position, motivation_letter, cv_file_path, status) 
         VALUES (?, ?, ?, ?, ?, 'pending')"
    );
    $stmt->bind_param("iisss", $user_id, $event_id, $desired_position, $motivation_letter, $file_path_for_db);

    if ($stmt->execute()) {
        header("Location: ../event_detail.php?id=$event_id&status=success");
    } else {
        header("Location: ../event_detail.php?id=$event_id&status=error");
    }
    $stmt->close();
}

$conn->close();
exit();
?>