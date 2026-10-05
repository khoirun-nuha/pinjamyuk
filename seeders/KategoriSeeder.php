<?php
require_once __DIR__ . '/../config/database.php';

function seedKategori($conn) {
    echo "<h3>Seeding Kategori...</h3>";

    $categories = [
        "('Elektronik')",       // 1
        "('Perlengkapan Audio')", // 2
        "('Perlengkapan Visual')",// 3
        "('Kabel & Konektor')",   // 4
        "('Furniture')",        // 5
        "('Aksesoris Komputer')" // 6
    ];

    $sql_categories = "INSERT INTO kategori (nama_kategori) VALUES " . implode(", ", $categories);

    if (mysqli_query($conn, $sql_categories)) {
        echo "[BERHASIL] " . count($categories) . " Kategori ditambahkan.<br>";
    } else {
        echo "[GAGAL] Insert Kategori: " . mysqli_error($conn) . "<br>";
    }
}

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    seedKategori($conn);
}
?>
