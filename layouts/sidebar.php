<?php
$level = $_SESSION['level'];
$page = $_GET['page'] ?? '';
?>
<link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css') ?>">

<nav id="sidebarMenu" class="sidebar-custom collapse d-md-block">
    <div class="sidebar-header text-center pt-4 position-relative">
        <!-- Close Button (Mobile Only) -->
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-md-none" aria-label="Close" onclick="document.body.classList.remove('sidebar-mobile-open')"></button>

        <h3 class="fw-bold mb-0 text-white" style="letter-spacing: 2px;">
            <i class="fas fa-layer-group me-2"></i>PinjamYuk
        </h3>
        <small class="text-white-50 d-block mt-2">Aplikasi Peminjaman Sekolah</small>
    </div>
    <hr class="mx-4 border-light opacity-25">
    
    <ul class="nav flex-column">
        <!-- ADMIN MENU -->
        <?php if ($level == 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'dashboard' || $page == '') ? 'active' : '' ?>" href="<?= base_url('admin/index.php') ?>">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'pengguna' || strpos($page, 'pengguna_') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/index.php?page=pengguna') ?>">
                <i class="fas fa-users-cog"></i> Kelola User
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'kategori' || strpos($page, 'kategori_') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/index.php?page=kategori') ?>">
                <i class="fas fa-shapes"></i> Kategori
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'alat' || strpos($page, 'alat_') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/index.php?page=alat') ?>">
                <i class="fas fa-laptop-code"></i> Data Alat
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'log_aktivitas') ? 'active' : '' ?>" href="<?= base_url('admin/index.php?page=log_aktivitas') ?>">
                <i class="fas fa-clock-rotate-left"></i> Log Aktivitas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'peminjaman' || strpos($page, 'peminjaman_') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/index.php?page=peminjaman') ?>">
                <i class="fas fa-file-invoice-dollar"></i> Data Peminjaman
            </a>
        </li>
        
        <!-- PETUGAS MENU -->
        <?php elseif ($level == 'petugas'): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'dashboard' || $page == '') ? 'active' : '' ?>" href="<?= base_url('petugas/index.php') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'verifikasi' || strpos($page, 'verifikasi_') === 0) ? 'active' : '' ?>" href="<?= base_url('petugas/index.php?page=verifikasi') ?>">
                <i class="fas fa-handshake"></i> Peminjaman
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($page == 'laporan') ? 'active' : '' ?>" href="<?= base_url('petugas/index.php?page=laporan') ?>">
                <i class="fas fa-file-contract"></i> Laporan
            </a>
        </li>

        <!-- PEMINJAM MENU -->
        <?php else: ?>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'beranda' || $page == '') ? 'active' : '' ?>" href="<?= base_url('peminjam/index.php') ?>">
                <i class="fas fa-house-chimney"></i> Beranda
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'alat' || strpos($page, 'alat_') === 0) ? 'active' : '' ?>" href="<?= base_url('peminjam/index.php?page=alat') ?>">
                <i class="fas fa-magnifying-glass"></i> Daftar Alat
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'riwayat' || strpos($page, 'riwayat_') === 0) ? 'active' : '' ?>" href="<?= base_url('peminjam/index.php?page=riwayat') ?>">
                <i class="fas fa-timeline"></i> Riwayat
            </a>
        </li>
        <li class="nav-item">
                <a class="nav-link <?= ($page == 'keranjang') ? 'active' : '' ?>" href="<?= base_url('peminjam/index.php?page=keranjang') ?>">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                </a>
        </li>
        <?php endif; ?>

        <!-- Shared Menus -->
        <li class="nav-item mt-3">
             <div class="text-white-50 small px-4 mb-2">PENGATURAN</div>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($page == 'profil') ? 'active' : '' ?>" href="<?= base_url($_SESSION['level'] . '/index.php?page=profil') ?>">
                <i class="fas fa-user-circle"></i> Profil Saya
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white-50 confirm-action" href="<?= base_url('logout.php') ?>" data-message="Yakin ingin keluar?">
                <i class="fas fa-arrow-right-from-bracket"></i> Logout
            </a>
        </li>
    </ul>
</nav>
