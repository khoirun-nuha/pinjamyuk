<?php
require_once 'config/database.php';
require_once 'functions/core/helper.php'; // Includes log_func.php

session_start();
if (isset($_SESSION['id_pengguna'])) {
    catat_log($_SESSION['id_pengguna'], 'logout', 'Logout berhasil');
}
session_destroy();
header("Location: index.php");
exit;
?>
