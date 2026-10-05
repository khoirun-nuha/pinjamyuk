<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/core/helper.php';
require_once __DIR__ . '/../functions/core/auth.php';

// Cek apakah user sudah login dan levelnya admin
define('ACCESS', true);
cek_login();
cek_level('admin');

require_once __DIR__ . '/../layouts/header.php';
?>

<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="main-content">
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
    <div class="px-4 pt-1 pb-4">
        <?php 
        // Memanggil file routing
        require_once __DIR__ . '/../routes/admin.php'; 
        ?>
    </div>
    
    <?php require_once __DIR__ . '/../layouts/copyright.php'; ?>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
