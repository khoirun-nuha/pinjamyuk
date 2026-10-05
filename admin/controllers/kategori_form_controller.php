<?php
require_once __DIR__ . '/../logic/kategori_logic.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$kategori = null;

// Logic: Ambil data edit
if ($id) {
    $kategori = ambil_kategori_by_id($id);
}

// Logic: Handle POST Simpan
if (isset($_POST['simpan'])) {
    if ($id) {
        if (edit_kategori($_POST)) {
            alert('Data berhasil diupdate!');
            redirect('admin/index.php?page=kategori');
        } else {
            alert('Gagal update data!');
        }
    } else {
        if (tambah_kategori($_POST)) {
            alert('Kategori berhasil ditambahkan!');
            redirect('admin/index.php?page=kategori');
        } else {
            alert('Gagal tambah kategori!');
        }
    }
}

// View: Tampilkan Form
require_once __DIR__ . '/../views/kategori_form.php';
?>
