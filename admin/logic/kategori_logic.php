<?php
require_once __DIR__ . '/../../config/database.php';

function ambil_semua_kategori() {
    global $conn;
    return mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
}

function ambil_kategori_by_id($id) {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori = '$id'");
    return mysqli_fetch_assoc($result);
}

function tambah_kategori($data) {
    global $conn;
    $nama = htmlspecialchars($data['nama']);
    
    return mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
}

function edit_kategori($data) {
    global $conn;
    $id   = $data['id_kategori'];
    $nama = htmlspecialchars($data['nama']);
    
    return mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori='$id'");
}

function hapus_kategori($id) {
    global $conn;
    try {
        if (mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori = '$id'")) {
            return true;
        }
    } catch (mysqli_sql_exception $e) {
        // Cek error code 1451 (Foreign Key Constraint)
        if ($e->getCode() == 1451) {
            return 'CONSTRAINT';
        }
        // Error lain
        return false;
    }
    return false;
}
?>
