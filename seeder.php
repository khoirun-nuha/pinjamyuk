<?php
// Include koneksi database
require_once 'config/database.php';

// Include files
require_once 'seeders/PenggunaSeeder.php';
require_once 'seeders/KategoriSeeder.php';
require_once 'seeders/AlatSeeder.php';
require_once 'triggers/StockTrigger.php';

echo "<h3>Mulai Proses Setup Database...</h3><hr>";

// Nonaktifkan foreign key checks untuk truncate
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// 1. Truncate Tables (Kosongkan tabel)
$tables = ['detail_peminjaman', 'peminjaman', 'alat', 'kategori', 'pengguna', 'log_aktivitas'];
foreach ($tables as $table) {
    if (mysqli_query($conn, "TRUNCATE TABLE $table")) {
        echo "[RESET] Tabel $table berhasil dikosongkan.<br>";
    }
}

// Aktifkan kembali foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

echo "<hr>";

// 2. Jalankan Seeders
seedPengguna($conn);
echo "<br>";

seedKategori($conn);
echo "<br>";

seedAlat($conn);
echo "<br>";

echo "<hr>";

// 3. Jalankan Triggers
createStockTriggers($conn);

echo "<hr><h3>Proses Selesai!</h3>";
echo "<p>Silakan login dengan:</p>";
echo "<ul>
        <li><b>Admin</b>: admin / 12345678</li>
        <li><b>Petugas</b>: petugas / 12345678</li>
        <li><b>Peminjam</b>: siswa / 12345678</li>
      </ul>";
echo "<a href='index.php'>Kembali ke Halaman Utama</a>";
?>
