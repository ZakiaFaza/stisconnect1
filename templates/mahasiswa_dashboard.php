<?php
if (!isset($conn)) {
    exit('Akses langsung tidak diizinkan.');
}

// Ambil riwayat pendaftaran mahasiswa
$stmt = $conn->prepare(
    "SELECT e.name AS event_name, e.event_date, r.registration_date, r.status 
     FROM registrations r 
     JOIN events e ON r.event_id = e.id 
     WHERE r.user_id = ? 
     ORDER BY r.registration_date DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$registrations = $stmt->get_result();
$stmt->close();
?>

<div class="dashboard-card">
    <h2>Riwayat Pendaftaran Saya</h2>
    <hr>
    <?php if ($registrations->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Event</th>
                    <th>Tanggal Event</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reg = $registrations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reg['event_name']); ?></td>
                        <td><?php echo date('d M Y', strtotime($reg['event_date'])); ?></td>
                        <td><?php echo date('d M Y, H:i', strtotime($reg['registration_date'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo htmlspecialchars($reg['status']); ?>">
                                <?php echo ucfirst(htmlspecialchars($reg['status'])); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">Anda belum pernah mendaftar di event manapun. <a href="index.php#events">Cari event sekarang!</a></p>
    <?php endif; ?>
</div>