<?php
if (!isset($base_path)) {
    // Ubah '/STISConnectv1' jika nama folder proyek Anda berbeda.
    $base_path = '/STISConnectv1'; 
}
?>
<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section about">
                <h3 class="logo">STIS<b>Connect</b></h3>
                <p>Menjadi jembatan bagi mahasiswa untuk terlibat langsung dalam berbagai kegiatan kampus dan mengembangkan potensi diri.</p>
            </div>
            <div class="footer-section links">
                <h3>Halaman</h3>
                <ul>
                    <li><a href="<?php echo $base_path; ?>/index.php">Home</a></li>
                    <li><a href="<?php echo $base_path; ?>/news.php">News</a></li>
                    <li><a href="<?php echo $base_path; ?>/gallery.php">Gallery</a></li>
                    <li><a href="<?php echo $base_path; ?>/about.php">About</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Hubungi Kami</h3>
                <p>
                    Jl. Otto Iskandardinata No.64C, RT.1/RW.4<br>
                    Bidara Cina, Kecamatan Jatinegara<br>
                    Kota Jakarta Timur, DKI Jakarta 13330
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> STISConnect. All rights reserved.</p>
        </div>
    </div>
</footer>