<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {
    case 'dashboard':
        include 'dashboard.php';
        break;
        
    case 'verifikasi':
        include 'verifikasi/index.php';
        break;
        
    case 'pengembalian':
        include 'pengembalian/index.php';
        // Laporan
    // Laporan
    case 'laporan':
        include 'laporan/index.php';
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
