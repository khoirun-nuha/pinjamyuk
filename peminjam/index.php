<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/core/helper.php';
require_once __DIR__ . '/../functions/core/auth.php';

define('ACCESS', true);
cek_login();
cek_level('peminjam');

// Init keranjang if not exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

require_once __DIR__ . '/../layouts/header.php';
?>

<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="main-content">
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
    <div class="px-4 pt-1 pb-4">
        <?php 
        require_once __DIR__ . '/../routes/peminjam.php'; 
        ?>
    </div>

    <?php require_once __DIR__ . '/../layouts/copyright.php'; ?>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
