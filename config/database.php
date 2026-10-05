<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_pinjamyuk";

// Enable Exception Reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set timezone agar sesuai area pengguna (WIB)
date_default_timezone_set('Asia/Jakarta');
?>
