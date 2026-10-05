<?php
// Ambil parameter 'page' dari URL, default ke 'dashboard' jika tidak ada
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {
    case 'dashboard':
        include 'dashboard.php';
        break;
        
    // Pengguna
    case 'pengguna':
        include 'controllers/pengguna_controller.php';
        break;
    case 'pengguna_tambah':
    case 'pengguna_edit':
        include 'controllers/pengguna_form_controller.php';
        break;
    case 'pengguna_aksi':
        include 'controllers/pengguna_aksi_controller.php';
        break;
    case 'pengguna_hapus':
        include 'controllers/pengguna_delete_controller.php';
        break;
        
    // Kategori
    case 'kategori':
        include 'controllers/kategori_controller.php';
        break;
    case 'kategori_tambah':
    case 'kategori_edit':
        include 'controllers/kategori_form_controller.php';
        break;
    case 'kategori_hapus':
        include 'controllers/kategori_delete_controller.php';
        break;
        
    // Alat
    case 'alat':
        include 'controllers/alat_controller.php';
        break;
    case 'alat_tambah':
    case 'alat_edit':
        include 'controllers/alat_form_controller.php';
        break;
    case 'alat_hapus':
        include 'controllers/alat_delete_controller.php';
        break;
        
    // Peminjaman
    case 'peminjaman':
        include 'controllers/peminjaman_controller.php';
        break;
    case 'peminjaman_detail':
        include 'controllers/peminjaman_detail_controller.php';
        break;
    // Log Aktivitas
    case 'log_aktivitas':
        include 'controllers/log_controller.php';
        break;

    // Profil Saya [NEW]
    case 'profil':
        include __DIR__ . '/../views/profil.php';
        break;

        
    default:
        include __DIR__ . '/../views/404.php';
        break;
}
?>
