<?php 
session_start();
require_once '../config/database.php';

// Keamanan: Hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Cek ID event dari URL
if (!isset($_GET['id'])) {
    header("Location: ../dashboard.php");
    exit();
}

$event_id = intval($_GET['id']);

// Ambil data event yang akan diedit
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) {
    // Jika event tidak ditemukan
    header("Location: ../dashboard.php?error=notfound");
    exit();
}

// Decode posisi yang sudah ada dari JSON
$existing_positions = json_decode($event['required_positions'], true);
if (!is_array($existing_positions)) {
    $existing_positions = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Event - STISConnect</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin_form.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <main>
        <div class="form-container">
            <div class="form-card">
                <h2>Edit Event: <?php echo htmlspecialchars($event['name']); ?></h2>
                <form action="../actions/update_event.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Nama Event</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="event_date">Tanggal Pelaksanaan</label>
                        <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Status Rekrutmen</label>
                        <div class="radio-group">
                            <label><input type="radio" name="recruitment_open" value="1" <?php if($event['recruitment_open']) echo 'checked'; ?>> Buka</label>
                            <label><input type="radio" name="recruitment_open" value="0" <?php if(!$event['recruitment_open']) echo 'checked'; ?>> Tutup</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Posisi/Divisi yang Dibutuhkan</label>
                        <div id="positions-container">
                            <?php foreach ($existing_positions as $position): ?>
                                <div class="position-input-group">
                                    <input type="text" name="positions[]" placeholder="Contoh: Divisi Acara" value="<?php echo htmlspecialchars($position); ?>" required>
                                    <button type="button" class="remove-position-btn">&times;</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-position-btn" class="btn btn-secondary">Tambah Posisi</button>
                    </div>

                    <div class="form-actions">
                        <a href="../dashboard.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
    <script src="../assets/js/theme-switcher.js"></script>
    <script>
        // JavaScript untuk menambah dan menghapus input posisi
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('positions-container');
            const addBtn = document.getElementById('add-position-btn');

            addBtn.addEventListener('click', function() {
                const positionInput = document.createElement('div');
                positionInput.classList.add('position-input-group');
                positionInput.innerHTML = `
                    <input type="text" name="positions[]" placeholder="Contoh: Divisi Acara" required>
                    <button type="button" class="remove-position-btn">&times;</button>
                `;
                container.appendChild(positionInput);
            });

            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-position-btn')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
</body>
</html>