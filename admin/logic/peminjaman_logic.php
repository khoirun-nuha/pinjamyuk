<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Ambil semua data peminjaman dengan filter bulan & tahun
 */
function ambil_semua_peminjaman($bulan = '', $tahun = '') {
    global $conn;

    $where = [];
    if (!empty($bulan)) {
        $where[] = "MONTH(p.tgl_pinjam) = '$bulan'";
    }
    if (!empty($tahun)) {
        $where[] = "YEAR(p.tgl_pinjam) = '$tahun'";
    }

    $where_sql = "";
    if (count($where) > 0) {
        $where_sql = "WHERE " . implode(" AND ", $where);
    }

    $query = "SELECT p.*, 
                     u_peminjam.nama_lengkap as nama_peminjam,
                     u_petugas.nama_lengkap as nama_petugas,
                     u_kembali.nama_lengkap as nama_petugas_kembali
              FROM peminjaman p
              JOIN pengguna u_peminjam ON p.id_pengguna = u_peminjam.id_pengguna
              LEFT JOIN pengguna u_petugas ON p.id_petugas = u_petugas.id_pengguna
              LEFT JOIN pengguna u_kembali ON p.id_petugas_kembali = u_kembali.id_pengguna
              $where_sql
              ORDER BY p.tgl_pinjam DESC";

    return mysqli_query($conn, $query);
}

/**
 * Ambil detail peminjaman berdasarkan ID
 */
function ambil_detail_peminjaman($id_peminjaman) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id_peminjaman);

    // Ambil Info Peminjaman
    $queryInfo = "SELECT p.*, 
                         u_peminjam.nama_lengkap as nama_peminjam,
                         u_peminjam.alamat as alamat_peminjam,
                         u_petugas.nama_lengkap as nama_petugas,
                         u_kembali.nama_lengkap as nama_petugas_kembali
                  FROM peminjaman p
                  JOIN pengguna u_peminjam ON p.id_pengguna = u_peminjam.id_pengguna
                  LEFT JOIN pengguna u_petugas ON p.id_petugas = u_petugas.id_pengguna
                  LEFT JOIN pengguna u_kembali ON p.id_petugas_kembali = u_kembali.id_pengguna
                  WHERE p.id_peminjaman = '$id'";
    
    $infoResult = mysqli_query($conn, $queryInfo);
    $info = mysqli_fetch_assoc($infoResult);

    // Ambil Barang yang dipinjam
    $queryItems = "SELECT dp.*, a.nama_alat, a.gambar, k.nama_kategori
                   FROM detail_peminjaman dp
                   JOIN alat a ON dp.id_alat = a.id_alat
                   JOIN kategori k ON a.id_kategori = k.id_kategori
                   WHERE dp.id_peminjaman = '$id'";
    
    $itemsResult = mysqli_query($conn, $queryItems);
    $items = [];
    while ($row = mysqli_fetch_assoc($itemsResult)) {
        $items[] = $row;
    }

    return [
        'info' => $info,
        'items' => $items
    ];
}
?>
