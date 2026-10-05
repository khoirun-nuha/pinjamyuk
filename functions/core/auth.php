<?php
session_start();

// Cek apakah user (pengguna) sudah login
function cek_login() {
    if (!isset($_SESSION['id_pengguna'])) {
        redirect('index.php');
    }
}

// Cek hak akses user (middleware sederhana)
function cek_level($level_diizinkan) {
    global $conn;
    $id = $_SESSION['id_pengguna'];
    $query = mysqli_query($conn, "SELECT level FROM pengguna WHERE id_pengguna = '$id'");
    $user = mysqli_fetch_assoc($query);

    if ($user['level'] !== $level_diizinkan) {
        // Jika level tidak sesuai, kembalikan ke halaman sesuai levelnya
        if ($user['level'] == 'admin') redirect('admin/index.php');
        else if ($user['level'] == 'petugas') redirect('petugas/index.php');
        else redirect('peminjam/index.php');
    }
}
?>
