<?php
require_once 'config/database.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SHOW COLUMNS FROM pengguna");
if (!$result) {
    die("Error: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
?>
