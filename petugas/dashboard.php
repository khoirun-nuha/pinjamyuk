<?php
// Hitung data untuk dashboard Petugas
$count_req = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE status='diajukan'"));
$count_active = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE status='dipinjam'"));
$count_return = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE status='diajukan_kembali'"));

// Ambil list tugas prioritas (Verifikasi Peminjaman & Pengembalian)
$query_tasks = mysqli_query($conn, "
    SELECT p.*, u.nama_lengkap 
    FROM peminjaman p 
    JOIN pengguna u ON p.id_pengguna = u.id_pengguna 
    WHERE p.status IN ('diajukan', 'diajukan_kembali') 
    ORDER BY p.tgl_pinjam ASC LIMIT 5
");
?>

<!-- Custom Dashboard CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">

<!-- Hero Section -->
<div class="row mb-5 animate__animated animate__fadeInDown">
    <div class="col-12">
        <div class="dashboard-hero d-flex align-items-center justify-content-between">
            <div style="z-index: 1;">
                <h2 class="fw-bold mb-1">Halo, <?= $_SESSION['nama'] ?? 'Petugas' ?>! 👮‍♂️</h2>
                <p class="mb-0 opacity-75">Ada beberapa tugas menanti verifikasi Anda hari ini.</p>
            </div>
            <div class="d-none d-md-block text-end" style="z-index: 1;">
                <h5 class="fw-bold mb-0"><?= date('l, d F Y') ?></h5>
                <small class="opacity-75">Semangat bertugas!</small>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-5 animate__animated animate__fadeInUp">
    
    <!-- Card Verifikasi Peminjaman -->
    <div class="col-md-4">
        <div class="glass-card stat-card-gradient-1 text-white h-100 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold ls-1 mb-2" style="letter-spacing: 1px;">Verifikasi Pinjam</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $count_req ?></h2>
                    <small class="text-white-50 mt-2 d-block">Permintaan baru</small>
                </div>
            </div>
            <i class="fas fa-clipboard-list stat-icon-bg text-white"></i>
            <a href="index.php?page=verifikasi" class="stretched-link"></a>
        </div>
    </div>

    <!-- Card Verifikasi Pengembalian -->
    <div class="col-md-4">
        <div class="glass-card stat-card-gradient-4 text-white h-100 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold ls-1 mb-2" style="letter-spacing: 1px;">Verifikasi Kembali</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $count_return ?></h2>
                    <small class="text-white-50 mt-2 d-block">Menunggu cek fisik</small>
                </div>
            </div>
            <i class="fas fa-check-double stat-icon-bg text-white"></i>
            <a href="index.php?page=verifikasi" class="stretched-link"></a>
        </div>
    </div>

    <!-- Card Sedang Dipinjam -->
    <div class="col-md-4">
        <div class="glass-card stat-card-gradient-3 text-white h-100 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold ls-1 mb-2" style="letter-spacing: 1px;">Sedang Dipinjam</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $count_active ?></h2>
                    <small class="text-white-50 mt-2 d-block">Barang di luar</small>
                </div>
            </div>
            <i class="fas fa-box-open stat-icon-bg text-white"></i>
        </div>
    </div>

</div>

<!-- Task List Section -->
<div class="row animate__animated animate__fadeInUp animate__delay-1s">
    <div class="col-lg-12 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tasks me-2"></i>Daftar Tugas Prioritas</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-borderless table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center ps-4">No</th>
                                <th>Jenis Tugas</th>
                                <th>Nama Peminjam</th>
                                <th>Waktu Pengajuan</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query_tasks) > 0):
                                while($task = mysqli_fetch_assoc($query_tasks)): 
                                    $jenis_badge = ($task['status'] == 'diajukan') 
                                        ? '<span class="badge bg-primary bg-opacity-75 ms-2">Pinjam Baru</span>' 
                                        : '<span class="badge bg-info text-dark bg-opacity-50 ms-2">Pengembalian</span>';
                            ?>
                            <tr>
                                <td class="text-center ps-4"><?= $no++ ?></td>
                                <td><?= $jenis_badge ?></td>
                                <td class="fw-bold"><?= $task['nama_lengkap'] ?></td>
                                <td class="text-muted small"><i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($task['tgl_pinjam'])) ?></td>
                                <td class="text-center pe-4">
                                    <a href="index.php?page=verifikasi" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                        Proses <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; 
                            else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="fas fa-clipboard-check fa-3x text-success opacity-50"></i></div>
                                    <h6 class="fw-bold">Tidak ada tugas tertunda!</h6>
                                    <small>Kerja bagus, semua permintaan sudah diproses.</small>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
