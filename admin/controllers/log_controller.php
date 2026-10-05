<?php
require_once __DIR__ . '/../../functions/core/helper.php';
require_once __DIR__ . '/../../functions/general/log_func.php';

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$logs = ambil_log_aktivitas($bulan, $tahun);

// View: Tampilkan
require_once __DIR__ . '/../views/log_index.php';
?>
