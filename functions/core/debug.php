<?php
function debug_log($message) {
    // Pastikan path benar relatif terhadap file yang memanggil atau gunakan absolute path jika perlu
    // Kita simpan di root project agar mudah dicek
    $logfile = __DIR__ . '/../debug_stok.log'; 
    $timestamp = date("Y-m-d H:i:s");
    $log_entry = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logfile, $log_entry, FILE_APPEND);
}
?>
