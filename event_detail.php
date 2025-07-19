<?php
session_start();
require_once 'config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) { header("Location: index.php"); exit(); }
$event_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) { header("Location: index.php"); exit(); }

$positions_json = $event['required_positions'] ?? '[]'; 
$required_positions = json_decode($positions_json, true);
if (!is_array($required_positions)) {
    $required_positions = [];
}

$has_registered = false;
if (isset($_SESSION['user_id'])) {
    $check_stmt = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->bind_param("ii", $_SESSION['user_id'], $event_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) { $has_registered = true; }
    $check_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['name']); ?> - STISConnect</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/detail.css">
    <link rel="stylesheet" href="assets/css/modal.css">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main>
        <div class="container detail-container">
            <div class="event-header">
                <h1><?php echo htmlspecialchars($event['name']); ?></h1>
                <p class="event-date">Tanggal Pelaksanaan: <?php echo date('d F Y', strtotime($event['event_date'])); ?></p>
            </div>
            <div class="event-content">
                <h2>Deskripsi Event</h2>
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>

            <div class="event-action">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'mahasiswa'): ?>
                    <?php if ($event['recruitment_open']): ?>
                        <?php if ($has_registered): ?>
                            <button class="btn btn-disabled" disabled>Anda Sudah Terdaftar</button>
                        <?php else: ?>
                            <button id="open-modal-btn" class="btn btn-primary">Daftar Jadi Panitia</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-disabled" disabled>Rekrutmen Ditutup</button>
                    <?php endif; ?>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <p>Silakan <a href="login.php">login</a> untuk mendaftar.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <div id="registration-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Form Pendaftaran Panitia</h2>
            <p>Event: <strong><?php echo htmlspecialchars($event['name']); ?></strong></p>
            <form action="actions/register_event.php" method="POST" enctype="multipart/form-data" class="modal-form">
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                
                <div class="form-group">
                    <label for="desired_position">Pilih Posisi yang Diinginkan</label>
                    <select id="desired_position" name="desired_position" required>
                        <option value="" disabled selected>-- Pilih Posisi --</option>
                        <?php if (!empty($required_positions)): ?>
                            <?php foreach ($required_positions as $position): ?>
                                <option value="<?php echo htmlspecialchars($position); ?>"><?php echo htmlspecialchars($position); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="General Volunteer">General Volunteer</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="motivation_letter">Surat Motivasi Singkat</label>
                    <textarea id="motivation_letter" name="motivation_letter" rows="4" placeholder="Jelaskan mengapa Anda tertarik dan cocok untuk posisi ini..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="cv_file">Unggah CV (PDF, maks 2MB)</label>
                    <label for="cv_file" class="file-upload-wrapper">
                        <input type="file" id="cv_file" name="cv_file" accept=".pdf" required>
                        <span class="file-upload-label">
                            Klik untuk memilih file atau <strong>seret file ke sini</strong>.
                        </span>
                        <div id="file-name-display"></div>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-full">Kirim Pendaftaran</button>
            </form>
        </div>
    </div>

    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/notification.js"></script>
</body>
</html>