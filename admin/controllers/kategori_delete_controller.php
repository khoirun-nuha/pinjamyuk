<?php
require_once __DIR__ . '/../logic/kategori_logic.php';

$id = $_GET['id'];

// Logic: Hapus
$result = hapus_kategori($id);

if ($result === true) {
    alert('Kategori berhasil dihapus!');
} elseif ($result === 'CONSTRAINT') {
    alert('Gagal menghapus! Kategori ini sedang digunakan oleh data Alat.', 'error');
} else {
    alert('Gagal menghapus kategori! Terjadi kesalahan sistem.', 'error');
}

redirect('admin/index.php?page=kategori');
?>
