<?php
require_once __DIR__ . '/../logic/peminjaman_logic.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    echo "ID Peminjaman tidak ditemukan!";
    exit;
}

// Ambil Data Detail
$data = ambil_detail_peminjaman($id);
$info = $data['info'];
$items = $data['items'];

if (!$info) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

// Load View
require_once __DIR__ . '/../views/peminjaman_detail.php';
?>
