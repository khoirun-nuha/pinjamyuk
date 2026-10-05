<?php
require_once __DIR__ . '/../logic/pengguna_logic.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$user = null;

// Logic: Ambil data edit
if ($id) {
    $user = ambil_pengguna_by_id($id);
}

// Logic: Handle POST Simpan
if (isset($_POST['simpan'])) {
    if ($id) {
        // Edit mode
        $result = edit_pengguna($_POST);
        if ($result === true) {
            alert('Data berhasil diupdate!');
            redirect('admin/index.php?page=pengguna');
        } elseif ($result === 'DUPLICATE') {
            alert('Username sudah digunakan! Silakan pilih yang lain.', 'error');
        } else {
            alert('Gagal update data!', 'error');
        }
    } else {
        // Add mode
        $result = tambah_pengguna($_POST);
        if ($result === true) {
            alert('Pengguna berhasil ditambahkan!');
            redirect('admin/index.php?page=pengguna');
        } elseif ($result === 'DUPLICATE') {
            alert('Username sudah digunakan! Silakan pilih yang lain.', 'error');
        } else {
            alert('Gagal tambah pengguna!', 'error');
        }
    }
}

// View: Tampilkan Form
require_once __DIR__ . '/../views/pengguna_form.php';
?>
