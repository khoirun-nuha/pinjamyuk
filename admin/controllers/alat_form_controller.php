<?php
require_once __DIR__ . '/../logic/alat_logic.php';
require_once __DIR__ . '/../logic/kategori_logic.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$alat = null;

if ($id) {
    $alat = ambil_alat_by_id($id);
}

// Ambil data kategori untuk dropdown
$kategoris = ambil_semua_kategori();

if (isset($_POST['simpan'])) {
    if ($id) {
        $result = edit_alat($_POST, $_FILES['gambar']);
        if ($result['status']) {
            alert($result['pesan']);
            redirect('admin/index.php?page=alat');
        } else {
            alert($result['pesan'], 'error');
        }
    } else {
        $result = tambah_alat($_POST, $_FILES['gambar']);
        if ($result['status']) {
            alert($result['pesan']);
            redirect('admin/index.php?page=alat');
        } else {
            alert($result['pesan'], 'error');
        }
    }
}

// View: Tampilkan Form
require_once __DIR__ . '/../views/alat_form.php';
?>
