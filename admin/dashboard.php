<?php
// 1. Data Kartu Statistik
$user_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengguna"));
$alat_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM alat")); // Total item types, not quantity
$active_loans = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE status = 'dipinjam'"));
$new_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE status = 'diajukan'"));

// 2. Data Chart: Statistik Peminjaman per Bulan (6 bulan terakhir)
$months = [];
$totals = [];
$query_chart = "SELECT DATE_FORMAT(tgl_pinjam, '%M') as bulan, COUNT(*) as total 
                FROM peminjaman 
                GROUP BY MONTH(tgl_pinjam), YEAR(tgl_pinjam) 
                ORDER BY tgl_pinjam DESC LIMIT 6";
$res_chart = mysqli_query($conn, $query_chart);
while ($row_c = mysqli_fetch_assoc($res_chart)) {
    $months[] = $row_c['bulan']; // E.g., 'January'
    $totals[] = $row_c['total'];
}
// Balik urutan agar kronologis (Jan, Feb, Mar...)
$months = array_reverse($months);
$totals = array_reverse($totals);

// 3. Data Chart: Ketersediaan Alat
// Stok Tersedia (Sum of stok in alat table)
$query_stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total_ready FROM alat"));
$stok_ready = $query_stok['total_ready'] ?? 0;

// Stok Dipinjam (Sum of jumlah in detail_peminjaman for active loans)
// Status yg mengurangi stok: 'diajukan', 'dipinjam', 'diajukan_kembali'
$query_borrowed = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(d.jumlah) as total_borrowed 
    FROM detail_peminjaman d 
    JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman 
    WHERE p.status IN ('diajukan', 'dipinjam', 'diajukan_kembali')
"));
$stok_borrowed = $query_borrowed['total_borrowed'] ?? 0;


// 4. Recent Activity
$query_log = mysqli_query($conn, "SELECT l.*, p.nama_lengkap FROM log_aktivitas l JOIN pengguna p ON l.id_pengguna = p.id_pengguna ORDER BY l.waktu DESC LIMIT 5");
?>

<!-- Custom Dashboard CSS -->
<!-- Custom Dashboard CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">

<!-- Hero Section -->
<div class="row mb-5 animate__animated animate__fadeInDown">
    <div class="col-12">
        <div class="dashboard-hero d-flex align-items-center justify-content-between">
            <div style="z-index: 1;">
                <h2 class="fw-bold mb-1">Selamat Datang, <?= $_SESSION['nama'] ?? 'Admin' ?>! 👋</h2>
                <p class="mb-0 opacity-75">Berikut adalah ringkasan aktivitas peminjaman hari ini.</p>
            </div>
            <div class="d-none d-md-block text-end" style="z-index: 1;">
                <h5 class="fw-bold mb-0"><?= date('l, d F Y') ?></h5>
                <small class="opacity-75">Update Terakhir: Sekarang</small>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-5 animate__animated animate__fadeInUp">
    <!-- Card User -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card stat-card-gradient-1 text-white h-100 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold ls-1 mb-2" style="letter-spacing: 1px;">Total Pengguna</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $user_count ?></h2>
                </div>
            </div>
            <i class="fas fa-users stat-icon-bg text-white"></i>
        </div>
    </div>

    <!-- Card Alat -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card stat-card-gradient-2 text-white h-100 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold ls-1 mb-2" style="letter-spacing: 1px;">Jenis Alat</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $alat_count ?></h2>
                </div>
            </div>
            <i class="fas fa-tools stat-icon-bg text-white"></i>
        </div>
    </div>

    <!-- Card Dipinjam -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card stat-card-gradient-3 text-white h-100 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold ls-1 mb-2" style="letter-spacing: 1px;">Sedang Dipinjam</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $active_loans ?></h2>
                </div>
            </div>
            <i class="fas fa-box-open stat-icon-bg text-white"></i>
        </div>
    </div>

    <!-- Card Permintaan -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card stat-card-gradient-4 text-white h-100 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold ls-1 mb-2" style="letter-spacing: 1px;">Permintaan Baru</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $new_requests ?></h2>
                </div>
            </div>
            <i class="fas fa-bell stat-icon-bg text-white"></i>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4 animate__animated animate__fadeInUp animate__delay-1s">
    <!-- Bar Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar me-2"></i>Statistik Peminjaman</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie me-2"></i>Status Aset</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small text-muted">
                    <i class="fas fa-circle text-success me-1"></i> Tersedia
                    <span class="mx-2">|</span>
                    <i class="fas fa-circle text-warning me-1"></i> Dipinjam
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card shadow-sm border-0 mb-4 animate__animated animate__fadeInUp animate__delay-1s" style="border-radius: 15px;">
    <div class="card-header bg-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-borderless table-striped">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($log = mysqli_fetch_assoc($query_log)): 
                        $badge_cls = 'secondary';
                        if($log['aktivitas'] == 'login') $badge_cls = 'success';
                        elseif($log['aktivitas'] == 'peminjaman') $badge_cls = 'primary';
                        elseif($log['aktivitas'] == 'pengembalian') $badge_cls = 'info';
                    ?>
                    <tr>
                        <td class="ps-4 text-muted small"><i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($log['waktu'])) ?></td>
                        <td class="fw-bold"><?= $log['nama_lengkap'] ?></td>
                        <td><span class="badge bg-<?= $badge_cls ?> bg-opacity-75"><?= ucfirst($log['aktivitas']) ?></span></td>
                        <td class="text-muted small"><?= $log['keterangan'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Data passed from PHP to JS
    const monthLabels = <?= json_encode($months) ?>;
    const loanData = <?= json_encode($totals) ?>;
    const stockReady = <?= $stok_ready ?>;
    const stockBorrowed = <?= $stok_borrowed ?>;
</script>
<script src="<?= base_url('assets/js/admin_dashboard.js') ?>"></script>
