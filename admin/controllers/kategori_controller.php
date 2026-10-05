<?php
require_once __DIR__ . '/../logic/kategori_logic.php';

// Logic: Ambil data
$kategoris = ambil_semua_kategori();

// View: Tampilkan
require_once __DIR__ . '/../views/kategori_index.php';
?>
