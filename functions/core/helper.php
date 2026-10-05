<?php
// Base URL aplikasi - Otomatis deteksi host (Localhost / Live Server)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . $host . '/ukk_pinjam/');

function base_url($path = '') {
    return BASE_URL . $path;
}

function redirect($url) {
    echo "<script>window.location.href='" . base_url($url) . "';</script>";
    exit;
}

// Set Flash Message
function set_flash($message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}


require_once __DIR__ . '/../general/log_func.php';

// Wrapper untuk alert lama - otomatis deteksi based on keywords
// Wrapper untuk alert dengan opsi tipe eksplisit
function alert($message, $type = null) {
    if ($type === null) {
        $type = 'success';
        $msg_lower = strtolower($message);
        
        // Keywords untuk Error
        $error_keywords = [
            'gagal', 'salah', 'error', 'stok tidak cukup', 'sudah digunakan',
            'tidak ditemukan', 'tidak valid', 'tidak cocok', 'dibatalkan', 
            'ditolak', 'dilarang', 'kosong', 'belum diisi', 'hak akses',
            'tidak mencukupi'
        ];

        foreach ($error_keywords as $keyword) {
            if (strpos($msg_lower, $keyword) !== false) {
                $type = 'error';
                break;
            }
        }

        // Keywords untuk Warning (jika belum error)
        if ($type == 'success') {
            $warning_keywords = ['peringatan', 'perhatian', 'harap', 'pastikan'];
            foreach ($warning_keywords as $keyword) {
                if (strpos($msg_lower, $keyword) !== false) {
                    $type = 'warning';
                    break;
                }
            }
        }
    }
    
    set_flash($message, $type);
}
?>
