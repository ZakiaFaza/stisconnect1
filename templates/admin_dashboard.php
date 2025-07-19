<?php 
if (!isset($conn)) { exit('Akses langsung tidak diizinkan.'); }

$events_result_for_table = $conn->query("SELECT id, name, event_date, recruitment_open FROM events ORDER BY event_date DESC");
$events_result_for_accordion = $conn->query("SELECT id, name FROM events ORDER BY event_date DESC");
?>

<div class="dashboard-card">
    <div class="card-header">
        <h2>Manajemen Event & Konten</h2>
        <div class="search-wrapper">
            <input type="text" id="event-search-input" placeholder="Cari event di tabel...">
        </div>
    </div>
    <hr>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Event</th>
                <th>Tanggal</th>
                <th>Status Rekrutmen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="event-table-body">
            <?php if ($events_result_for_table->num_rows > 0): ?>
                <?php while($event = $events_result_for_table->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                    <td><?php echo date('d M Y', strtotime($event['event_date'])); ?></td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="recruitment-toggle" data-id="<?php echo $event['id']; ?>" <?php if($event['recruitment_open']) echo 'checked'; ?>>
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td class="action-links">
                        <a href="admin/edit_event.php?id=<?php echo $event['id']; ?>">Edit</a>
                        <a href="actions/delete_event.php?id=<?php echo $event['id']; ?>" class="reject" onclick="return confirm('Yakin?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="management-links" style="margin-top: 20px;">
        <a href="admin/add_event.php" class="btn btn-primary">Tambah Event</a>
        <a href="admin/manage_news.php" class="btn btn-secondary">Kelola Berita</a>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1): ?>
            <a href="admin/manage_users.php" class="btn btn-secondary">Kelola Pengguna</a>
        <?php endif; ?>
        <a href="admin/manage_about.php" class="btn btn-secondary">Kelola Halaman About</a>
    </div>
</div>

<!-- Kartu untuk Manajemen Pendaftar -->
<div class="dashboard-card">
    <h2>Manajemen Pendaftar</h2>
    <p>Klik pada nama event untuk melihat dan mengelola pendaftar.</p>
    <div class="accordion">
    <?php 
    if ($events_result_for_accordion->num_rows > 0):
        while($event = $events_result_for_accordion->fetch_assoc()):
            $event_id_for_page = $event['id'];
    ?>
        <details class="accordion-item">
            <summary class="accordion-header">
                <span><?php echo htmlspecialchars($event['name']); ?></span>
                <span class="accordion-icon">+</span>
            </summary>
            <div class="accordion-content">
                <div class="applicants-header">
                    <h4>Daftar Pendaftar</h4>
                    <a href="actions/export_csv.php?event_id=<?php echo $event_id_for_page; ?>" class="btn btn-export">Export ke CSV</a>
                </div>
                <?php
                // --- Logika Lengkap untuk Pendaftar ---
                $limit = 5;
                $page_param = "page_event_" . $event_id_for_page;
                $page = isset($_GET[$page_param]) ? (int)$_GET[$page_param] : 1;
                $offset = ($page - 1) * $limit;

                $count_stmt = $conn->prepare("SELECT COUNT(id) AS total FROM registrations WHERE event_id = ?");
                $count_stmt->bind_param("i", $event_id_for_page);
                $count_stmt->execute();
                $total_applicants = $count_stmt->get_result()->fetch_assoc()['total'];
                $total_pages = ceil($total_applicants / $limit);
                $count_stmt->close();
                
                $reg_stmt = $conn->prepare("SELECT r.id, u.full_name, u.nim, r.status FROM registrations r JOIN users u ON r.user_id = u.id WHERE r.event_id = ? ORDER BY r.registration_date DESC LIMIT ? OFFSET ?");
                $reg_stmt->bind_param("iii", $event_id_for_page, $limit, $offset);
                $reg_stmt->execute();
                $applicants = $reg_stmt->get_result();
                $reg_stmt->close();
                
                if ($applicants->num_rows > 0):
                ?>
                    <table class="data-table applicants-table">
                        <thead>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($applicant = $applicants->fetch_assoc()): ?>
                            <tr id="registration-row-<?php echo $applicant['id']; ?>">
                                <td><?php echo htmlspecialchars($applicant['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($applicant['nim']); ?></td>
                                <td class="status-cell">
                                    <span class="status-badge status-<?php echo htmlspecialchars($applicant['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($applicant['status'])); ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <?php if($applicant['status'] == 'pending'): ?>
                                        <button class="btn-action approve" data-id="<?php echo $applicant['id']; ?>">Approve</button>
                                        <button class="btn-action reject" data-id="<?php echo $applicant['id']; ?>">Reject</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php if ($total_pages > 1): ?>
                    <nav class="pagination">
                        <?php
                            $query_params = $_GET;
                            $query_params[$page_param] = $page > 1 ? $page - 1 : 1;
                            $prev_link = 'dashboard.php?' . http_build_query($query_params);
                        ?>
                        <a href="<?php echo $prev_link; ?>" class="<?php if($page <= 1){ echo 'disabled'; } ?>">«</a>
                        <?php for ($i = 1; $i <= $total_pages; $i++): 
                            $query_params[$page_param] = $i;
                            $page_link = 'dashboard.php?' . http_build_query($query_params);
                        ?>
                            <a href="<?php echo $page_link; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        <?php
                            $query_params[$page_param] = $page < $total_pages ? $page + 1 : $total_pages;
                            $next_link = 'dashboard.php?' . http_build_query($query_params);
                        ?>
                        <a href="<?php echo $next_link; ?>" class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">»</a>
                    </nav>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="no-data">Belum ada pendaftar untuk event ini.</p>
                <?php endif; ?>
            </div>
        </details>
    <?php 
        endwhile;
    else: ?>
        <p class="no-data">Belum ada event yang dibuat.</p>
    <?php endif; ?>
    </div>
</div>