<?php
require_once __DIR__ . '/../logic/pengguna_logic.php';

// Logic: Ambil data
$users = ambil_semua_pengguna();

// View: Tampilkan
require_once __DIR__ . '/../views/pengguna_index.php';
?>
