<?php
// Dashboard Siswa / Peminjam
$id_user = $_SESSION['id_pengguna'];
// Cek pinjaman aktif
$active_loans_query = mysqli_query($conn, "
    SELECT p.*, COUNT(d.id_alat) as total_items 
    FROM peminjaman p 
    JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman 
    WHERE p.id_pengguna = '$id_user' AND p.status IN ('dipinjam', 'diajukan') 
    GROUP BY p.id_peminjaman 
    ORDER BY p.tgl_pinjam DESC 
    LIMIT 3
");
// Hitung total peminjaman sepanjang masa
$total_history = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_pengguna = '$id_user' AND status = 'dikembalikan'"));
?>

<!-- Custom Styles for Dashboard -->
<!-- Custom Styles for Dashboard -->
<link rel="stylesheet" href="<?= base_url('assets/css/beranda.css') ?>">

<?php
// Function to get dynamic greeting
function getGreeting() {
    $hour = date('H');
    if ($hour >= 4 && $hour < 11) return "Selamat Pagi";
    if ($hour >= 11 && $hour < 15) return "Selamat Siang";
    if ($hour >= 15 && $hour < 18) return "Selamat Sore";
    return "Selamat Malam";
}

// Fetch User Data specifically for the profile picture
$user_query = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$id_user'");
$user_data = mysqli_fetch_assoc($user_query);
$foto_profil = !empty($user_data['gambar']) ? 'assets/img/' . $user_data['gambar'] : null;
?>

<!-- Hero Section -->
<div class="row mb-5 fade-up">
    <div class="col-12">
        <div class="hero-section p-4 p-lg-5 text-white" style="background: linear-gradient(-45deg, #1a2980, #26d0ce, #4e54c8, #23d5ab); background-size: 400% 400%; animation: gradientSidebar 15s ease infinite;">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">

                    <h1 class="display-4 fw-bold mb-3 welcome-text">
                        <?= getGreeting() ?>, <span id="typing-text"></span><span class="cursor-blink">|</span>
                    </h1>
                    <p class="lead mb-4 opacity-85" style="max-width: 600px;">
                        Selamat datang kembali di <strong>PinjamYuk</strong>. Akses semua kebutuhan peralatan sekolahmu dengan mudah dan cepat.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="index.php?page=alat" class="btn btn-light text-primary fw-bold btn-action shadow-lg">
                            <i class="fas fa-search me-2"></i>Cari Alat
                        </a>
                        <a href="index.php?page=riwayat" class="btn btn-outline-light fw-bold btn-action backdrop-blur">
                            <i class="fas fa-history me-2"></i>Riwayat
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center position-relative">
                   <?php if($foto_profil && file_exists(__DIR__ . '/../../' . $foto_profil)): ?>
                        <div class="profile-glow d-inline-block position-relative">
                            <img src="<?= base_url($foto_profil) ?>" 
                                 alt="Profile" 
                                 class="rounded-circle shadow-lg animate-float" 
                                 style="width: 220px; height: 220px; object-fit: cover; border: 5px solid rgba(255,255,255,0.3);">
                                 <div class="position-absolute bottom-0 end-0 bg-success border border-4 border-white rounded-circle p-3" style="width: 30px; height: 30px;"></div>
                        </div>
                   <?php else: ?>
                        <div class="avatar-placeholder animate-float d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-10 backdrop-blur shadow-lg" 
                             style="width: 220px; height: 220px; border: 5px solid rgba(255,255,255,0.2);">
                             <i class="fas fa-user fa-5x text-white opacity-75"></i>
                        </div>
                   <?php endif; ?>
                </div>
            </div>
            
            <!-- Decorations -->
            <div class="circle-deco" style="width: 300px; height: 300px; top: -100px; right: -50px; opacity: 0.1;"></div>
            <div class="circle-deco" style="width: 150px; height: 150px; bottom: -50px; left: 10%; animation: pulse 3s infinite; opacity: 0.1;"></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Active Loans & Stats -->
    <div class="col-lg-8">
        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-6 fade-up" style="transition-delay: 100ms;">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 fw-bold text-uppercase small">Total Selesai</p>
                                <h2 class="display-5 fw-bold text-success mb-0 count-up" data-target="<?= $total_history ?>">0</h2>
                            </div>
                            <div class="icon-box bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px; border-radius: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                            </div>
                            <small class="text-muted mt-2 d-block">Terus tingkatkan reputasi peminjamanmu!</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 fade-up" style="transition-delay: 200ms;">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 fw-bold text-uppercase small">Pinjaman Aktif</p>
                                <h2 class="display-5 fw-bold text-warning mb-0 count-up" data-target="<?= mysqli_num_rows($active_loans_query) ?>">0</h2>
                            </div>
                            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                <i class="fas fa-exclamation-circle me-1"></i> Jangan lupa kembalikan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Loans List -->
        <div class="fade-up" style="transition-delay: 300ms;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fas fa-list-alt text-primary me-2"></i>Status Pinjaman Terbaru</h5>
                <a href="index.php?page=riwayat" class="btn btn-sm btn-light text-primary fw-bold px-3 rounded-pill hover-scale">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <?php if(mysqli_num_rows($active_loans_query) > 0): ?>
                <?php 
                $active_loans_query->data_seek(0); // Reset pointer
                $delay = 400;
                while($loan = mysqli_fetch_assoc($active_loans_query)): 
                    $status_cls = ($loan['status'] == 'dipinjam') ? 'warning' : 'primary';
                    $delay += 100;
                ?>
                <div class="card glass-card mb-3 fade-up border-start border-4 border-<?= $status_cls ?>" style="transition-delay: <?= $delay ?>ms;">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-<?= $status_cls ?> bg-opacity-10 text-<?= $status_cls ?> mb-0 me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Kode Pinjam #<?= $loan['id_peminjaman'] ?></h6>
                                        <div class="d-flex flex-column small text-muted">
                                            <span><i class="far fa-calendar-alt me-2 width-20"></i><?= date('d M Y', strtotime($loan['tgl_pinjam'])) ?></span>
                                            <span><i class="far fa-clock me-2 width-20"></i><?= date('H:i', strtotime($loan['tgl_pinjam'])) ?> WIB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0 text-center">
                                <span class="d-block text-muted small mb-1">Status</span>
                                <span class="status-badge bg-<?= $status_cls ?> bg-opacity-10 text-<?= $status_cls ?>">
                                    <?= ucfirst($loan['status']) ?>
                                </span>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="index.php?page=riwayat" class="btn btn-light text-<?= $status_cls ?> rounded-pill btn-sm fw-bold w-100 hover-scale">
                                    Detail <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card glass-card text-center py-5 fade-up" style="transition-delay: 400ms;">
                    <div class="card-body">
                        <div class="mb-4 text-muted opacity-50">
                            <i class="fas fa-shopping-basket fa-4x mb-3"></i>
                            <h5>Belum ada pinjaman aktif</h5>
                        </div>
                        <a href="index.php?page=alat" class="btn btn-primary rounded-pill px-4 shadow-sm hover-scale">
                            Mulai Meminjam
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Info & Calendar -->
    <div class="col-lg-4">
        <!-- Info Card -->
        <div class="card glass-card mb-4 fade-up" style="transition-delay: 300ms; background: linear-gradient(145deg, #ffffff, #f0f2f5);">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-info me-2"></i>Informasi Penting</h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                        <span class="text-muted small">Barang wajib dikembalikan tepat waktu sesuai tanggal rencana.</span>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-exclamation-triangle text-warning mt-1 me-3"></i>
                        <span class="text-muted small">Keterlambatan akan dikenakan denda sebesar <strong>Rp 1.000/hari</strong>.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="fas fa-heart text-danger mt-1 me-3"></i>
                        <span class="text-muted small">Jaga kondisi barang agar teman lain bisa menggunakannya juga.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Clock/Date Widget -->
        <div class="card bg-dark text-white border-0 shadow-lg fade-up" style="border-radius: 20px; transition-delay: 400ms; overflow: hidden;">
            <div class="card-body p-4 text-center position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=500&q=60') center/cover; opacity: 0.3;">
                </div>
                <div class="position-relative z-1">
                    <h3 class="fw-bold mb-0" id="clock-time">00:00:00</h3>
                    <p class="mb-3 text-white-50" id="clock-date">Loading...</p>
                    <hr class="border-white opacity-25">
                    <small class="text-white-50 fst-italic">"Waktu adalah hal yang paling berharga."</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Define user name for the typing effect in external JS
    const userName = "<?= $_SESSION['nama'] ?>";
</script>
<script src="<?= base_url('assets/js/beranda.js') ?>"></script>
