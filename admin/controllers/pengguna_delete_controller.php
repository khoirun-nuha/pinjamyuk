<?php
require_once __DIR__ . '/../logic/pengguna_logic.php';

$id = $_GET['id'];

if (hapus_pengguna($id)) {
    alert('Pengguna berhasil dihapus!');
} else {
    alert('Gagal menghapus pengguna!', 'error');
}

redirect('admin/index.php?page=pengguna');
?>
