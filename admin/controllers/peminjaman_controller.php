<?php
require_once __DIR__ . '/../logic/peminjaman_logic.php';

// Filter
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Ambil Data
$peminjaman = ambil_semua_peminjaman($bulan, $tahun);

// Load View
require_once __DIR__ . '/../views/peminjaman_index.php';
?>
