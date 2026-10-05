<?php
require_once __DIR__ . '/../logic/alat_logic.php';
require_once __DIR__ . '/../logic/kategori_logic.php';

// Logic: Ambil data dan filter
$kategori_id = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$kategories = ambil_semua_kategori();

$alats = ambil_semua_alat($kategori_id);

// View: Tampilkan
require_once __DIR__ . '/../views/alat_index.php';
?>
