<?php
session_start();
require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

// Ambil konten saat ini dari DB
$contents_res = $conn->query("SELECT content_key, content_value FROM site_content");
$contents = [];
while($row = $contents_res->fetch_assoc()) {
    $contents[$row['content_key']] = $row['content_value'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Halaman About - STISConnect</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.4">
    <link rel="stylesheet" href="../assets/css/admin_form.css?v=1.4">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <main>
        <div class="form-container">
            <div class="form-card">
                <h2>Kelola Halaman "About"</h2>
                <form action="../actions/update_about.php" method="POST">
                    <div class="form-group">
                        <label for="about_mission">Misi Kami</label>
                        <textarea id="about_mission" name="about_mission" rows="5" required><?php echo htmlspecialchars($contents['about_mission'] ?? ''); ?></textarea>
                    </div>
                    <hr>
                    <h4>Narahubung</h4>
                    <div id="contacts-container">
                        <!-- Kontak dinamis akan ditambahkan di sini oleh JavaScript -->
                    </div>
                    <button type="button" id="add-contact-btn" class="btn btn-secondary">Tambah Narahubung</button>
                    
                    <div class="form-actions">
                        <a href="../dashboard.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>

    <!-- SCRIPT YANG HILANG DITAMBAHKAN DI SINI -->
    <script src="../assets/js/theme-switcher.js"></script>
    <script src="../assets/js/notification.js"></script>
    <script>
        // JavaScript untuk manajemen kontak dinamis
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('contacts-container');
            const addBtn = document.getElementById('add-contact-btn');
            let contactIndex = 0;
            
            const addContactForm = (name = '', role = '', phone = '') => {
                const contactGroup = document.createElement('div');
                contactGroup.className = 'contact-input-group';
                contactGroup.innerHTML = `
                    <input type="text" name="contacts[${contactIndex}][name]" placeholder="Nama" value="${name}" required>
                    <input type="text" name="contacts[${contactIndex}][role]" placeholder="Jabatan" value="${role}" required>
                    <input type="text" name="contacts[${contactIndex}][phone]" placeholder="No. WA (628...)" value="${phone}" required>
                    <button type="button" class="remove-contact-btn">&times;</button>
                `;
                container.appendChild(contactGroup);
                contactIndex++;
            };

            <?php
            $contacts = [];
            foreach ($contents as $key => $value) {
                if (strpos($key, 'contact_person_') === 0) {
                    $parts = explode('_', $key);
                    $index = $parts[2];
                    $field = $parts[3];
                    $contacts[$index][$field] = $value;
                }
            }
            ksort($contacts);
            if (!empty($contacts)) {
                foreach ($contacts as $contact) {
                    echo "addContactForm('".addslashes($contact['name'])."', '".addslashes($contact['role'])."', '".addslashes($contact['phone'])."');\n";
                }
            } else {
                echo "addContactForm();\n"; // Tambah satu form kosong jika tidak ada data
            }
            ?>

            addBtn.addEventListener('click', () => addContactForm());

            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-contact-btn')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
</body>
</html>