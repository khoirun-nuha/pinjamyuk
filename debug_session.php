<?php
require_once 'config/database.php';
require_once 'functions/core/helper.php';
session_start();

echo "<pre>";
echo "SESSION DATA:\n";
print_r($_SESSION);

echo "\n\nDATABASE DATA for ID " . ($_SESSION['id_pengguna'] ?? 'NULL') . ":\n";
if (isset($_SESSION['id_pengguna'])) {
    $id = $_SESSION['id_pengguna'];
    $q = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$id'");
    if ($q) {
        print_r(mysqli_fetch_assoc($q));
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }
} else {
    echo "No ID in session.";
}
echo "</pre>";
?>
