<?php
require_once __DIR__ . '/../config/database.php';

// Konfigurasi
$tarif_denda_per_item = 2000;
$jumlah_dummy = 5; // Berapa banyak data yang mau dibuat

echo "--- MULAI SEEDING DATA DENDA ---\n";

// 1. Ambil User Peminjam (Random)
$q_user = mysqli_query($conn, "SELECT id_pengguna FROM pengguna WHERE level = 'peminjam' ORDER BY RAND() LIMIT 1");
if (mysqli_num_rows($q_user) == 0) die("Error: Tidak ada user peminjam.\n");
$id_peminjam = mysqli_fetch_assoc($q_user)['id_pengguna'];

// 2. Ambil Petugas (Random)
$q_petugas = mysqli_query($conn, "SELECT id_pengguna FROM pengguna WHERE level = 'petugas' OR level = 'admin' ORDER BY RAND() LIMIT 1");
if (mysqli_num_rows($q_petugas) == 0) die("Error: Tidak ada user petugas/admin.\n");
$id_petugas = mysqli_fetch_assoc($q_petugas)['id_pengguna'];

// 3. Ambil Alat (Random)
$q_alat = mysqli_query($conn, "SELECT id_alat, stok FROM alat WHERE stok > 5 LIMIT 1");
if (mysqli_num_rows($q_alat) == 0) die("Error: Tidak ada alat dengan stok cukup.\n");
$alat = mysqli_fetch_assoc($q_alat);
$id_alat = $alat['id_alat'];

echo "User ID: $id_peminjam | Petugas ID: $id_petugas | Alat ID: $id_alat\n";

// Skenario 1: Telat 1 Hari, 2 Item
// Denda: 2 item * 2000 * 1 hari = 4000
create_transaction($id_peminjam, $id_petugas, $id_alat, 2, 1);

// Skenario 2: Telat 2 Hari, 2 Item
// Denda: 2 item * 2000 * 2 hari = 8000
create_transaction($id_peminjam, $id_petugas, $id_alat, 2, 2);

// Skenario 3: Telat 5 Hari, 1 Item
create_transaction($id_peminjam, $id_petugas, $id_alat, 1, 5);


function create_transaction($id_peminjam, $id_petugas, $id_alat, $qty_item, $days_late) {
    global $conn, $tarif_denda_per_item;

    // Hitung Tanggal
    $tgl_pinjam = date('Y-m-d H:i:s', strtotime("-".($days_late + 5)." days")); // Pinjam 5 hari + telat lalu
    $tgl_rencana = date('Y-m-d', strtotime("-".$days_late." days")); // Rencana kembali seharusnya X hari lalu
    $tgl_kembali_real = date('Y-m-d H:i:s'); // Kembali SEKARANG

    // Hitung Denda
    $denda = $qty_item * $tarif_denda_per_item * $days_late;

    echo "\n[CREATE] Telat $days_late hari, $qty_item item. Ekspektasi Denda: $denda\n";

    // 1. Insert Peminjaman
    $status = 'dikembalikan';
    $q_ins = "INSERT INTO peminjaman (id_pengguna, tgl_pinjam, tgl_kembali_rencana, tgl_kembali_real, status, denda, id_petugas, id_petugas_kembali) 
              VALUES ('$id_peminjam', '$tgl_pinjam', '$tgl_rencana', '$tgl_kembali_real', '$status', '$denda', '$id_petugas', '$id_petugas')";
    
    if (mysqli_query($conn, $q_ins)) {
        $id_trans = mysqli_insert_id($conn);
        echo " -> Header Peminjaman OK (ID: $id_trans)\n";

        // 2. Insert Detail
        $q_det = "INSERT INTO detail_peminjaman (id_peminjaman, id_alat, jumlah) VALUES ('$id_trans', '$id_alat', '$qty_item')";
        if (mysqli_query($conn, $q_det)) {
            echo " -> Detail Peminjaman OK\n";
        } else {
            echo " -> GAGAL Detail: " . mysqli_error($conn) . "\n";
        }
    } else {
        echo " -> GAGAL Header: " . mysqli_error($conn) . "\n";
    }
}

echo "\n--- SELESAI ---\n";
?>
