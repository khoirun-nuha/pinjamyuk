<?php
require_once __DIR__ . '/../config/database.php';

function createStockTriggers($conn) {
    echo "<h3>Membuat Trigger Stok...</h3>";

    // 1. DELETE EXISITNG TRIGGERS
    $drop_triggers = [
        "DROP TRIGGER IF EXISTS kurangi_stok",
        "DROP TRIGGER IF EXISTS kembalikan_stok",
        "DROP TRIGGER IF EXISTS tambah_stok_kembali" // Clean up any potential variations
    ];

    foreach ($drop_triggers as $sql) {
        mysqli_query($conn, $sql);
    }

    // 2. CREATE QUERY FOR 'kurangi_stok'
    // Trigger ini aktif saat data masuk ke tabel detail_peminjaman
    $trigger1 = "CREATE TRIGGER kurangi_stok 
                 AFTER INSERT ON detail_peminjaman
                 FOR EACH ROW
                 BEGIN
                    UPDATE alat SET stok = stok - NEW.jumlah 
                    WHERE id_alat = NEW.id_alat;
                 END";

    if (mysqli_query($conn, $trigger1)) {
        echo "[BERHASIL] Trigger 'kurangi_stok' dibuat.<br>";
    } else {
        echo "[GAGAL] Trigger 'kurangi_stok': " . mysqli_error($conn) . "<br>";
    }

    // 3. CREATE QUERY FOR 'kembalikan_stok'
    // Trigger ini aktif saat status peminjaman berubah menjadi 'kembali'
    // Asumsi: status 'kembali' adalah penanda barang sudah kembali ke inventaris
    $trigger2 = "CREATE TRIGGER kembalikan_stok 
                 AFTER UPDATE ON peminjaman
                 FOR EACH ROW
                 BEGIN
                    IF NEW.status = 'kembali' AND OLD.status != 'kembali' THEN
                        UPDATE alat a
                        JOIN detail_peminjaman dp ON a.id_alat = dp.id_alat
                        SET a.stok = a.stok + dp.jumlah
                        WHERE dp.id_peminjaman = NEW.id_peminjaman;
                    END IF;
                 END";

    if (mysqli_query($conn, $trigger2)) {
        echo "[BERHASIL] Trigger 'kembalikan_stok' dibuat.<br>";
    } else {
        echo "[GAGAL] Trigger 'kembalikan_stok': " . mysqli_error($conn) . "<br>";
    }
}

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    createStockTriggers($conn);
}
?>
