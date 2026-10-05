<?php
require_once __DIR__ . '/../config/database.php';

function seedPengguna($conn) {
    echo "<h3>Seeding Pengguna...</h3>";
    
    // Default Password
    $password = password_hash('12345678', PASSWORD_DEFAULT); 
    
    $users = [
        "('admin', '$password', 'Administrator Utama', 'Kantor Pusat', 'admin', 'default_profil.png')",
        "('petugas', '$password', 'Budi Santoso', 'Ruang Inventaris', 'petugas', 'default_profil.png')",
        "('siswa', '$password', 'Andi Pratama', 'Kelas XII RPL 1', 'peminjam', 'default_profil.png')",
        "('guru', '$password', 'Pak Guru', 'Ruang Guru', 'peminjam', 'default_profil.png')",
        "('siswa2', '$password', 'Siti Aminah', 'Kelas XI TKJ 2', 'peminjam', 'default_profil.png')",
        "('siswa3', '$password', 'Doni Tata', 'Kelas X MM 1', 'peminjam', 'default_profil.png')"
    ];

    $sql_users = "INSERT INTO pengguna (username, password, nama_lengkap, alamat, level, foto_profil) VALUES " . implode(", ", $users);
    
    if (mysqli_query($conn, $sql_users)) {
        echo "[BERHASIL] " . count($users) . " Pengguna ditambahkan.<br>";
    } else {
        echo "[GAGAL] Insert Pengguna: " . mysqli_error($conn) . "<br>";
    }
}

// Check if this file is run directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    seedPengguna($conn);
}
?>
