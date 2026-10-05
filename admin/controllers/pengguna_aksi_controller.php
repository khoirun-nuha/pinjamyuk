<?php
require_once __DIR__ . '/../logic/pengguna_logic.php';

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi == 'reset') {
    if (reset_password_pengguna($id)) {
        alert('Password berhasil direset menjadi 12345678!');
    } else {
        alert('Gagal reset password!', 'error');
    }
} elseif ($aksi == 'blokir') {
    if (blokir_pengguna($id)) {
        alert('Pengguna berhasil diblokir!');
    } else {
        alert('Gagal memblokir pengguna!', 'error');
    }
} elseif ($aksi == 'buka_blokir') {
    if (buka_blokir_pengguna($id)) {
        alert('Blokir pengguna berhasil dibuka!');
    } else {
        alert('Gagal membuka blokir!', 'error');
    }
} elseif ($aksi == 'hapus') {
    // Handling delete here as well if merged, or can be separate.
    // Assuming delete has its own controller or handled here?
    // Let's create a separate delete controller to be consistent, but logic can be here too.
}

redirect('admin/index.php?page=pengguna');
?>
