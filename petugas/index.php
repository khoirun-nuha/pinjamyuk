<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/core/helper.php';
require_once __DIR__ . '/../functions/core/auth.php';

// Cek login dan level petugas
define('ACCESS', true);
cek_login();
cek_level('petugas');

require_once __DIR__ . '/../layouts/header.php';
?>

<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="main-content">
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
    <div class="p-4">
        <?php 
        require_once __DIR__ . '/../routes/petugas.php'; 
        ?>
    </div>
    
    <?php require_once __DIR__ . '/../layouts/copyright.php'; ?>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
