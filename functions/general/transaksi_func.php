<?php
require_once __DIR__ . '/../../config/database.php';

require_once __DIR__ . '/log_func.php';
require_once __DIR__ . '/../../functions/core/helper.php';
require_once __DIR__ . '/../../functions/core/debug.php'; // Debug Logger

// PEMINJAM: Ambil history sendiri
function ambil_peminjaman_user($id_pengguna, $status = '') {
    global $conn;
    $id_pengguna = mysqli_real_escape_string($conn, $id_pengguna);
    $status = mysqli_real_escape_string($conn, $status);
    
    $where_status = "";
    if (!empty($status)) {
        $where_status = "AND status = '$status'";
    }

    $query = "SELECT * FROM peminjaman WHERE id_pengguna = '$id_pengguna' $where_status ORDER BY tgl_pinjam DESC";
    return mysqli_query($conn, $query);
}

// PETUGAS/ADMIN: Ambil semua history
function ambil_semua_peminjaman($bulan = null, $tahun = null, $id_petugas = null) {
    global $conn;
    
    $where_parts = [];

    if (!empty($bulan)) {
        $where_parts[] = "MONTH(p.tgl_pinjam) = '$bulan'";
    }
    if (!empty($tahun)) {
        $where_parts[] = "YEAR(p.tgl_pinjam) = '$tahun'";
    }

    if ($id_petugas) {
        // Jika filter per petugas, cek apakah dia petugas pinjam ATAU petugas kembali
        $where_parts[] = "(p.id_petugas = '$id_petugas' OR p.id_petugas_kembali = '$id_petugas')";
    }

    $where_clause = "";
    if (!empty($where_parts)) {
        $where_clause = "WHERE " . implode(" AND ", $where_parts);
    }

    $query = "SELECT p.*, u.nama_lengkap as nama_peminjam, 
                     pt.nama_lengkap as nama_petugas,
                     pt_k.nama_lengkap as nama_petugas_kembali
              FROM peminjaman p 
              JOIN pengguna u ON p.id_pengguna = u.id_pengguna 
              LEFT JOIN pengguna pt ON p.id_petugas = pt.id_pengguna
              LEFT JOIN pengguna pt_k ON p.id_petugas_kembali = pt_k.id_pengguna
              $where_clause
              ORDER BY p.tgl_pinjam DESC";
    return mysqli_query($conn, $query);
}

// AMBIL DETAIL
function ambil_detail_peminjaman($id_pinjam) {
    global $conn;
    $query = "SELECT dp.*, a.nama_alat, a.gambar 
              FROM detail_peminjaman dp 
              JOIN alat a ON dp.id_alat = a.id_alat 
              WHERE dp.id_peminjaman = '$id_pinjam'";
    return mysqli_query($conn, $query);
}

// PEMINJAM: Ajukan Pinjam (Dari Keranjang)
function ajukan_pinjam($id_pengguna, $keranjang) {
    global $conn;
    
    // Mulai Transaksi
    mysqli_begin_transaction($conn);
    
    try {
        // 1. Insert Header Peminjaman: Set tgl_pengajuan_pinjam = NOW(), tgl_pinjam = NULL, tgl_kembali_rencana = NULL
        // NOTE: tgl_kembali_rencana nanti diisi otomatis saat verifikasi (+7 hari)
        $query_header = "INSERT INTO peminjaman (id_pengguna, tgl_pengajuan_pinjam, tgl_pinjam, tgl_kembali_rencana, status) 
                         VALUES ('$id_pengguna', NOW(), NULL, NULL, 'diajukan')";
        if (!mysqli_query($conn, $query_header)) {
            throw new Exception("Gagal insert header");
        }
        $id_peminjaman = mysqli_insert_id($conn);

        // 2. Insert Detail
        foreach ($keranjang as $item) {
            $id_alat = $item['id'];
            $jumlah  = $item['qty'];

            // Cek Stok (Validasi Tetap Harus Ada di PHP untuk UX)
            $cek_stok = mysqli_query($conn, "SELECT stok, nama_alat FROM alat WHERE id_alat = '$id_alat' FOR UPDATE");
            $data_alat = mysqli_fetch_assoc($cek_stok);

            if (!$data_alat) {
                throw new Exception("Barang tidak ditemukan!");
            }

            if ($data_alat['stok'] < $jumlah) {
                throw new Exception("Stok barang " . $data_alat['nama_alat'] . " tidak mencukupi! Sisa: " . $data_alat['stok']);
            }
            
            // Insert Detail -> TRIGGER tr_kurang_stok_insert AKAN JALAN OTOMATIS
            $query_detail = "INSERT INTO detail_peminjaman (id_peminjaman, id_alat, jumlah) 
                             VALUES ('$id_peminjaman', '$id_alat', '$jumlah')";
            if (!mysqli_query($conn, $query_detail)) {
                throw new Exception("Gagal insert detail: " . mysqli_error($conn));
            }

            // NOTE: Tidak ada UPDATE stok manual di sini. Trigger yang handle.
        }

        // Catat Log
        catat_log($id_pengguna, 'peminjaman', 'Mengajukan peminjaman baru (ID: ' . $id_peminjaman . ')');

        // Commit jika sukses
        mysqli_commit($conn);
        return true;

    } catch (Exception $e) {
        // Rollback jika gagal
        mysqli_rollback($conn);
        $_SESSION['error_msg'] = $e->getMessage(); 
        return false;
    }
}

// PETUGAS: Verifikasi (Setuju/Tolak)
function verifikasi_pinjam($id_pinjam, $status, $id_petugas) {
    global $conn;
    
    // Status: 'dipinjam' (Setuju) atau 'ditolak'
    
    // Jika DITOLAK -> TRIGGER tr_restock_update akan JALAN OTOMATIS (karena status berubah jadi ditolak)
    // Jika DIPINJAM -> Set tgl_pinjam = NOW(), tgl_kembali_rencana = +7 hari
    
    $extra_update = "";
    if ($status == 'dipinjam') {
        $extra_update = ", tgl_pinjam = NOW(), tgl_kembali_rencana = DATE_ADD(NOW(), INTERVAL 7 DAY)";
    }
    
    $query = "UPDATE peminjaman SET status = '$status', id_petugas = '$id_petugas' $extra_update
              WHERE id_peminjaman = '$id_pinjam'";
    
    if (mysqli_query($conn, $query)) {
        // Log Aktivitas
        $ket_status = ($status == 'dipinjam') ? 'Disetujui' : 'Ditolak';
        catat_log($id_petugas, 'peminjaman', "Memverifikasi peminjaman ID: $id_pinjam (Status: $ket_status)");
        
        return true;
    } 
    return false;
}

// PETUGAS: Proses Kembali
function proses_kembali($id_pinjam, $tgl_kembali_real, $denda, $id_petugas) {
    global $conn;
    
    debug_log("START proses_kembali (Trigger Mode) ID: $id_pinjam");
    
    // Validasi Logic Stok sudah dihandle TRIGGER tr_restock_update
    // Saat status update jadi 'dikembalikan', trigger otomatis restore stok.
    
    // FIX: Simpan ID Petugas ke kolom 'id_petugas_kembali' agar tidak menimpa petugas pinjam
    $query = "UPDATE peminjaman SET 
                status = 'dikembalikan', 
                tgl_kembali_real = '$tgl_kembali_real', 
                denda = '$denda',
                id_petugas_kembali = '$id_petugas' 
              WHERE id_peminjaman = '$id_pinjam'";
              
    if (mysqli_query($conn, $query)) {
        debug_log("Update status SUCCESS. Trigger should have fired.");
        
        // Opsional: Log visual saja (baca detail tapi jangan update stok manual)
        $q_detail = mysqli_query($conn, "SELECT a.nama_alat, dp.jumlah FROM detail_peminjaman dp JOIN alat a ON dp.id_alat=a.id_alat WHERE dp.id_peminjaman='$id_pinjam'");
        $items = [];
        while($r = mysqli_fetch_assoc($q_detail)) $items[] = $r['nama_alat'];
        
        catat_log($id_petugas, 'pengembalian', "Memproses pengembalian ID: $id_pinjam. Items: " . implode(", ", $items));
        return true;
    }
    
    debug_log("FAIL Update Header: " . mysqli_error($conn));
    return false;
}

// PEMINJAM: Ajukan Pengembalian
function ajukan_kembali($id_pinjam) {
    global $conn;
    $query = "UPDATE peminjaman SET status = 'diajukan_kembali', tgl_pengajuan_kembali = NOW() WHERE id_peminjaman = '$id_pinjam'";
    return mysqli_query($conn, $query);
}
// PEMINJAM: Batalkan Pengajuan
function batalkan_peminjaman($id_pinjam, $id_pengguna) {
    global $conn;

    // Pastikan status masih 'diajukan' dan milik user yang benar
    $cek = mysqli_query($conn, "SELECT status FROM peminjaman WHERE id_peminjaman = '$id_pinjam' AND id_pengguna = '$id_pengguna'");
    if (mysqli_num_rows($cek) == 0) return false;
    
    $data = mysqli_fetch_assoc($cek);
    if ($data['status'] != 'diajukan') return false; // Hanya bisa batal jika masih diajukan

    // Mulai Transaksi
    mysqli_begin_transaction($conn);

    try {
        // 1. Ambil detail barang untuk kembalikan stok
        $details = ambil_detail_peminjaman($id_pinjam);
        while ($item = mysqli_fetch_assoc($details)) {
            $id_alat = $item['id_alat'];
            $qty = $item['jumlah'];

            // Kembalikan Stok Manual (Karena trigger biasanya hanya handle insert/delete atau status tertentu)
            // Kita asumsikan saat insert stok sudah berkurang via trigger/logic insert
            $update_stok = "UPDATE alat SET stok = stok + $qty WHERE id_alat = '$id_alat'";
            if (!mysqli_query($conn, $update_stok)) {
                throw new Exception("Gagal mengembalikan stok alat ID: $id_alat");
            }
        }

        // 2. Update Status Peminjaman jadi 'dibatalkan'
        $update_status = "UPDATE peminjaman SET status = 'dibatalkan' WHERE id_peminjaman = '$id_pinjam'";
        if (!mysqli_query($conn, $update_status)) {
            throw new Exception("Gagal update status peminjaman");
        }

        // 3. Catat Log
        catat_log($id_pengguna, 'peminjaman', "Membatalkan pengajuan peminjaman ID: $id_pinjam");

        mysqli_commit($conn);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        return false;
    }
}
?>
