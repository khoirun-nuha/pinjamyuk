<?php
require_once __DIR__ . '/../logic/alat_logic.php';

$id = $_GET['id'];

// Logic: Hapus
$result = hapus_alat($id);

if ($result === true) {
    alert('Alat berhasil dihapus!');
} elseif ($result === 'CONSTRAINT') {
    alert('Gagal menghapus! Alat ini memiliki riwayat peminjaman.', 'error');
} else {
    alert('Gagal menghapus alat! Terjadi kesalahan sistem.', 'error');
}

redirect('admin/index.php?page=alat');
?>
