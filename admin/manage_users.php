<?php
session_start();
require_once '../config/database.php';

// Keamanan: Hanya Admin Utama (ID=1) yang bisa mengakses halaman ini.
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: ../dashboard.php");
    exit();
}

// Ambil semua pengguna kecuali admin yang sedang login
$current_admin_id = $_SESSION['user_id'];
$users_result = $conn->prepare("SELECT id, full_name, nim, role FROM users WHERE id != ? ORDER BY full_name ASC");
$users_result->bind_param("i", $current_admin_id);
$users_result->execute();
$users = $users_result->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Pengguna - STISConnect</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.5">
    <link rel="stylesheet" href="../assets/css/dashboard.css?v=1.5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <main style="padding-top: 40px; padding-bottom: 40px;">
        <div class="container">
            <div class="dashboard-header">
                <h1>Manajemen Pengguna</h1>
                <p>Berikan atau cabut hak akses admin untuk pengguna lain.</p>
            </div>
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Daftar Pengguna</h2>
                    <!-- Input Pencarian Baru -->
                    <div class="search-wrapper">
                        <input type="text" id="user-search-input" placeholder="Cari nama atau NIM...">
                    </div>
                </div>
                <hr>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>NIM</th>
                            <th>Status Admin</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        <?php if ($users->num_rows > 0): ?>
                            <?php while($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['nim']); ?></td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" class="role-toggle" data-id="<?php echo $user['id']; ?>" <?php if($user['role'] == 'admin') echo 'checked'; ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="no-data">Tidak ada pengguna lain yang ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
    
    <!-- Memuat semua script yang dibutuhkan -->
    <script src="../assets/js/theme-switcher.js"></script>
    <script src="../assets/js/notification.js"></script>
    <script src="../assets/js/admin_user_management.js"></script>
    
    <!-- Script untuk Live Search -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('user-search-input');
            const userTableBody = document.getElementById('user-table-body');
            const tableRows = userTableBody.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();

                for (let i = 0; i < tableRows.length; i++) {
                    const row = tableRows[i];
                    const nameCell = row.cells[0];
                    const nimCell = row.cells[1];
                    if (nameCell && nimCell) {
                        const nameText = nameCell.textContent.toLowerCase();
                        const nimText = nimCell.textContent.toLowerCase();
                        if (nameText.includes(searchTerm) || nimText.includes(searchTerm)) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>