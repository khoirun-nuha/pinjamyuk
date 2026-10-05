<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';

switch ($page) {
    case 'beranda':
        include 'beranda.php';
        break;
        
    case 'alat':
        include 'alat.php';
        break;
        
    case 'riwayat':
        include 'riwayat.php';
        break;
        
    case 'keranjang':
        include 'keranjang.php';
        break;
        
    default:
        echo "<div class='alert alert-danger'>Halaman tidak ditemukan!</div>";
        break;
}
?>
