<?php
require_once __DIR__ . '/../../config/database.php';


function ambil_semua_alat($kategori_id = "") {
    global $conn;
    $kategori_id = mysqli_real_escape_string($conn, $kategori_id);
    
    $where = "";
    if (!empty($kategori_id)) {
        $where = "WHERE alat.id_kategori = '$kategori_id'";
    }

    $query = "SELECT alat.*, kategori.nama_kategori 
              FROM alat 
              JOIN kategori ON alat.id_kategori = kategori.id_kategori 
              $where
              ORDER BY alat.nama_alat ASC";
    return mysqli_query($conn, $query);
}


function ambil_alat_pagination($keyword = "", $limit = 8, $offset = 0, $kategori_id = "") {
    global $conn;
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $kategori_id = mysqli_real_escape_string($conn, $kategori_id);
    
    $where_clauses = [];
    if (!empty($keyword)) {
        $where_clauses[] = "(alat.nama_alat LIKE '%$keyword%' OR kategori.nama_kategori LIKE '%$keyword%')";
    }
    if (!empty($kategori_id)) {
        $where_clauses[] = "alat.id_kategori = '$kategori_id'";
    }

    $where_sql = "";
    if (count($where_clauses) > 0) {
        $where_sql = "WHERE " . implode(" AND ", $where_clauses);
    }

    $query = "SELECT alat.*, kategori.nama_kategori 
              FROM alat 
              JOIN kategori ON alat.id_kategori = kategori.id_kategori 
              $where_sql 
              ORDER BY alat.nama_alat ASC 
              LIMIT $limit OFFSET $offset";
              
    return mysqli_query($conn, $query);
}

function hitung_jumlah_alat($keyword = "", $kategori_id = "") {
    global $conn;
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $kategori_id = mysqli_real_escape_string($conn, $kategori_id);
    
    $where_clauses = [];
    if (!empty($keyword)) {
        $where_clauses[] = "(alat.nama_alat LIKE '%$keyword%' OR kategori.nama_kategori LIKE '%$keyword%')";
    }
    if (!empty($kategori_id)) {
        $where_clauses[] = "alat.id_kategori = '$kategori_id'";
    }

    $where_sql = "";
    if (count($where_clauses) > 0) {
        $where_sql = "WHERE " . implode(" AND ", $where_clauses);
    }

    $query = "SELECT COUNT(*) as total 
              FROM alat 
              JOIN kategori ON alat.id_kategori = kategori.id_kategori 
              $where_sql";

              
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}


function ambil_alat_by_id($id) {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM alat WHERE id_alat = '$id'");
    return mysqli_fetch_assoc($result);
}

function upload_gambar($file) {
    $namaFile   = $file['name'];
    $ukuranFile = $file['size'];
    $error      = $file['error'];
    $tmpName    = $file['tmp_name'];

    // Cek apakah ada gambar yang diupload
    if ($error === 4) {
        return ['status' => true, 'nama' => 'default.jpg'];
    }

    // Cek ekstensi
    $ekstensiValid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensi      = explode('.', $namaFile);
    $ekstensi      = strtolower(end($ekstensi));

    if (!in_array($ekstensi, $ekstensiValid)) {
        return ['status' => false, 'pesan' => 'Format file tidak valid! Harap upload gambar (JPG/PNG).'];
    }

    // Cek ukuran (max 2MB)
    if ($ukuranFile > 2000000) {
        return ['status' => false, 'pesan' => 'Ukuran file terlalu besar! Maksimal 2MB.'];
    }

    // Generate nama baru
    $namaBaru = uniqid() . '.' . $ekstensi;
    if (move_uploaded_file($tmpName, '../assets/img/' . $namaBaru)) {
        return ['status' => true, 'nama' => $namaBaru];
    } else {
        return ['status' => false, 'pesan' => 'Gagal mengupload gambar ke server.'];
    }
}

function tambah_alat($data, $file) {
    global $conn;
    $kategori  = $data['kategori'];
    $nama      = htmlspecialchars($data['nama']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $stok      = $data['stok'];

    // Upload gambar
    $upload = upload_gambar($file);
    if (!$upload['status']) {
        return ['status' => false, 'pesan' => $upload['pesan']];
    }
    $gambar = $upload['nama'];

    $query = "INSERT INTO alat (id_kategori, nama_alat, deskripsi, stok, gambar) 
              VALUES ('$kategori', '$nama', '$deskripsi', '$stok', '$gambar')";
    
    if (mysqli_query($conn, $query)) {
        return ['status' => true, 'pesan' => 'Alat berhasil ditambahkan!'];
    } else {
        return ['status' => false, 'pesan' => 'Gagal insert database: ' . mysqli_error($conn)];
    }
}

function edit_alat($data, $file) {
    global $conn;
    $id        = $data['id_alat'];
    $kategori  = $data['kategori'];
    $nama      = htmlspecialchars($data['nama']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $stok      = $data['stok'];
    $gambarLama= $data['gambarLama'];

    // Cek apakah user pilih gambar baru or not
    if ($file['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $upload = upload_gambar($file);
        if (!$upload['status']) {
            return ['status' => false, 'pesan' => $upload['pesan']];
        }
        $gambar = $upload['nama'];
    }

    $query = "UPDATE alat SET id_kategori='$kategori', nama_alat='$nama', deskripsi='$deskripsi', stok='$stok', gambar='$gambar' 
              WHERE id_alat='$id'";
    
    if (mysqli_query($conn, $query)) {
        return ['status' => true, 'pesan' => 'Data berhasil diupdate!'];
    } else {
        return ['status' => false, 'pesan' => 'Gagal update database: ' . mysqli_error($conn)];
    }
}

function hapus_alat($id) {
    global $conn;
    
    // Ambil info gambar dulu
    $alat = ambil_alat_by_id($id);
    
    try {
        if (mysqli_query($conn, "DELETE FROM alat WHERE id_alat = '$id'")) {
            // Hapus gambar fisik jika bukan default dan delete DB sukses
            if ($alat && $alat['gambar'] != 'default.jpg') {
                $path = __DIR__ . '/../../assets/img/' . $alat['gambar'];
                if (file_exists($path)) unlink($path);
            }
            return true;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            return 'CONSTRAINT';
        }
        return false;
    }
    return false;
}
?>
