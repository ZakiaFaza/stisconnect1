<?php 
require_once 'config/database.php';
// Ambil semua konten dari database
$contents_res = $conn->query("SELECT content_key, content_value FROM site_content");
$contents = [];
while($row = $contents_res->fetch_assoc()) {
    $contents[$row['content_key']] = $row['content_value'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tentang STISConnect</title>
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
    <link rel="stylesheet" href="assets/css/about.css?v=1.3">
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <main>
        <div class="page-header reveal">
            <h1>Tentang Kami</h1>
            <p>Menghubungkan Mahasiswa dengan Peluang.</p>
        </div>
        <div class="page-content about-container">
            <div class="about-section reveal">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2084&auto=format&fit=crop" alt="Tim STISConnect">
                </div>
                <div class="about-text">
                    <h2>Misi Kami</h2>
                    <p><?php echo htmlspecialchars($contents['about_mission'] ?? 'Konten misi belum diatur.'); ?></p>
                </div>
            </div>
            <div class="contact-section reveal">
                <h2>Narahubung</h2>
                <div class="contact-cards">
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
                    foreach ($contacts as $contact):
                    ?>
                    <div class="contact-card">
                        <h3><?php echo htmlspecialchars($contact['name'] ?? 'N/A'); ?></h3>
                        <p><?php echo htmlspecialchars($contact['role'] ?? 'N/A'); ?></p>
                        <a href="https://wa.me/<?php echo htmlspecialchars($contact['phone'] ?? ''); ?>" target="_blank" class="btn-contact">Hubungi via WhatsApp</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/theme-switcher.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>