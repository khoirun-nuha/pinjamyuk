<?php
require_once __DIR__ . '/../../config/database.php';

function ambil_semua_pengguna() {
    global $conn;
    return mysqli_query($conn, "SELECT * FROM pengguna ORDER BY level ASC");
}

function ambil_pengguna_by_id($id) {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$id'");
    return mysqli_fetch_assoc($result);
}

function tambah_pengguna($data) {
    global $conn;
    $username = htmlspecialchars($data['username']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Secure Hash
    $nama     = htmlspecialchars($data['nama']);
    $alamat   = htmlspecialchars($data['alamat']);
    $level    = $data['level'];

    $query = "INSERT INTO pengguna (username, password, nama_lengkap, alamat, level) 
              VALUES ('$username', '$password', '$nama', '$alamat', '$level')";
    
    try {
        if (mysqli_query($conn, $query)) {
            return true;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Duplicate entry
            return 'DUPLICATE';
        }
        throw $e; // Throw other errors
    }
    return false;
}

function edit_pengguna($data) {
    global $conn;
    $id       = $data['id_pengguna'];
    $username = htmlspecialchars($data['username']);
    $nama     = htmlspecialchars($data['nama']);
    $alamat   = htmlspecialchars($data['alamat']);
    $level    = $data['level'];

    // Cek apakah password diubah
    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $query = "UPDATE pengguna SET username='$username', password='$password', nama_lengkap='$nama', alamat='$alamat', level='$level' WHERE id_pengguna='$id'";
    } else {
        $query = "UPDATE pengguna SET username='$username', nama_lengkap='$nama', alamat='$alamat', level='$level' WHERE id_pengguna='$id'";
    }
    
    try {
        if (mysqli_query($conn, $query)) {
            return true;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            return 'DUPLICATE';
        }
        throw $e;
    }
    return false;
}

function hapus_pengguna($id) {
    global $conn;
    try {
        if (mysqli_query($conn, "DELETE FROM pengguna WHERE id_pengguna = '$id'")) {
            return true;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            return 'CONSTRAINT'; // Biasanya karena ada log aktivitas atau transaksi
        }
        return false;
    }
    return false;
}

function blokir_pengguna($id) {
    global $conn;
    return mysqli_query($conn, "UPDATE pengguna SET status = 'blokir' WHERE id_pengguna = '$id'");
}

function buka_blokir_pengguna($id) {
    global $conn;
    return mysqli_query($conn, "UPDATE pengguna SET status = 'aktif' WHERE id_pengguna = '$id'");
}

function reset_password_pengguna($id) {
    global $conn;
    $default_password = password_hash('12345678', PASSWORD_DEFAULT); // Default: 12345678
    return mysqli_query($conn, "UPDATE pengguna SET password = '$default_password' WHERE id_pengguna = '$id'");
}
?>
