<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';

switch ($page) {
    case 'beranda':
        include 'beranda.php';
        break;
        
    case 'alat':
        include 'alat/index.php';
        break;
        
    case 'riwayat':
        include 'riwayat/index.php';
        break;
        
    // Profil Saya [NEW]
    case 'profil':
        include __DIR__ . '/../views/profil.php';
        break;

    case 'keranjang':
        include 'keranjang/index.php';
        break;
        
    default:
        include __DIR__ . '/../views/404.php';
        break;
}
?>
