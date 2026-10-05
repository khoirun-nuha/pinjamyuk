<?php
// Fungsi untuk mencatat log aktivitas
function catat_log($id_pengguna, $aktivitas, $keterangan = '') {
    global $conn;
    
    // Pastikan koneksi database tersedia
    if (!$conn) {
        return false;
    }

    $id_pengguna = mysqli_real_escape_string($conn, $id_pengguna);
    $aktivitas = mysqli_real_escape_string($conn, $aktivitas);
    $keterangan = mysqli_real_escape_string($conn, $keterangan);

    $query = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, keterangan) 
              VALUES ('$id_pengguna', '$aktivitas', '$keterangan')";
    
    return mysqli_query($conn, $query);
}


// Fungsi untuk mengambil data log aktivitas
function ambil_log_aktivitas($bulan = null, $tahun = null) {
    global $conn;
    
    // Pastikan koneksi database tersedia
    if (!$conn) {
        return [];
    }

    $query = "SELECT l.*, p.nama_lengkap, p.username, p.level 
              FROM log_aktivitas l 
              LEFT JOIN pengguna p ON l.id_pengguna = p.id_pengguna 
              WHERE 1=1";
    
    if ($bulan) {
        $bulan = mysqli_real_escape_string($conn, $bulan);
        $query .= " AND MONTH(l.waktu) = '$bulan'";
    }
    
    if ($tahun) {
        $tahun = mysqli_real_escape_string($conn, $tahun);
        $query .= " AND YEAR(l.waktu) = '$tahun'";
    }
    
    $query .= " ORDER BY l.waktu DESC";
    
    $result = mysqli_query($conn, $query);
    $logs = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $logs[] = $row;
        }
    }
    
    return $logs;
}

