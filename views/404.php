<div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 text-center py-5">
    <div class="mb-4">
        <!-- SVG Illustration / Icon yang Keren -->
        <i class="fas fa-ghost fa-10x text-muted opacity-25 animate__animated animate__bounceIn" style="font-size: 8rem;"></i>
    </div>
    
    <h1 class="display-1 fw-bold text-dark mb-0 animate__animated animate__fadeInDown">404</h1>
    <h2 class="h4 text-secondary mb-4 animate__animated animate__fadeInUp">Halaman tidak ditemukan</h2>
    
    <p class="lead text-muted mb-5 animate__animated animate__fadeIn" style="max-width: 500px;">
        Sepertinya link yang Anda tuju salah atau halaman telah dipindahkan. Jangan khawatir, mari kita kembali ke jalan yang benar.
    </p>

    <div class="animate__animated animate__fadeInUp animate__delay-1s">
        <?php 
        $backLink = 'index.php'; // Default fallback
        if (isset($_SESSION['level'])) {
            $backLink = base_url($_SESSION['level'] . '/index.php');
        }
        ?>
        <a href="<?= $backLink ?>" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<style>
    /* Tambahan style khusus untuk 404 jika perlu */
    .display-1 {
        letter-spacing: -5px;
        color: #e73c7e; /* Warna primary aplikasi */
        background: -webkit-linear-gradient(45deg, #e73c7e, #23a6d5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
