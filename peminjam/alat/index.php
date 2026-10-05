<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/core/helper.php';
require_once __DIR__ . '/../../functions/core/auth.php';
require_once __DIR__ . '/../../admin/logic/alat_logic.php';
require_once __DIR__ . '/../../admin/logic/kategori_logic.php';

cek_login();
cek_level('peminjam');

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $id_alat = $_POST['id_alat'];
    $nama    = $_POST['nama_alat'];
    $stok    = $_POST['stok']; // Stok tersedia
    
    // Init keranjang
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Cek apakah item sudah ada
    if (isset($_SESSION['cart'][$id_alat])) {
        // Cek stok
        if ($_SESSION['cart'][$id_alat]['qty'] < $stok) {
            $_SESSION['cart'][$id_alat]['qty']++;
        } else {
            alert('Stok tidak mencukupi!');
        }
    } else {
        $_SESSION['cart'][$id_alat] = [
            'id' => $id_alat,
            'nama' => $nama,
            'qty' => 1,
            'max_stok' => $stok
        ];
    }

    // Redirect langsung ke keranjang
    redirect('peminjam/index.php?page=keranjang');
}
?>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Daftar Alat</h1>
                <a href="index.php?page=keranjang" class="btn btn-success position-relative">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= count($_SESSION['cart']) ?>
                    </span>
                    <?php endif; ?>
                </a>
            </div>



<?php
// Konfigurasi Pagination
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8; // Default 8 if not set
if ($limit < 1) $limit = 8; // Prevent invalid limits

$page_active = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$start = ($limit * $page_active) - $limit;
$keyword = isset($_GET['q']) ? $_GET['q'] : '';
$kategori_id = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Ambil data
$alats = ambil_alat_pagination($keyword, $limit, $start, $kategori_id);
$total_data = hitung_jumlah_alat($keyword, $kategori_id);
$total_pages = ceil($total_data / $limit);
$kategories = ambil_semua_kategori();
?>


<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between flex-wrap align-items-center gap-3">
            <h5 class="m-0 fw-bold text-primary"><i class="fas fa-box-open me-2"></i>Daftar Koleksi Alat</h5>
            
            <form action="" method="GET" class="d-flex gap-3 align-items-center flex-wrap" onsubmit="return false;">
                <input type="hidden" name="page" value="alat">
                
                <!-- Show Entries -->
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-semibold">Show entries:</span>
                    <select name="limit" id="limit" class="form-select form-select-sm border-secondary-subtle" style="width: auto; cursor: pointer;">
                        <option value="8" <?= $limit == 8 ? 'selected' : '' ?>>8</option>
                        <option value="16" <?= $limit == 16 ? 'selected' : '' ?>>16</option>
                        <option value="24" <?= $limit == 24 ? 'selected' : '' ?>>24</option>
                        <option value="48" <?= $limit == 48 ? 'selected' : '' ?>>48</option>
                    </select>
                </div>
                
                <!-- Filter Kategori -->
                <select name="kategori" id="kategori" class="form-select form-select-sm border-secondary-subtle" style="width: 160px; cursor: pointer;">
                    <option value="">Semua Kategori</option>
                    <?php while($kat = mysqli_fetch_assoc($kategories)): ?>
                        <option value="<?= $kat['id_kategori'] ?>" <?= ($kategori_id == $kat['id_kategori']) ? 'selected' : '' ?>>
                            <?= $kat['nama_kategori'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Search -->
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light border-secondary-subtle"><i class="fas fa-search"></i></span>
                    <input type="text" name="q" id="keyword" class="form-control border-secondary-subtle" placeholder="Cari nama alat..." value="<?= htmlspecialchars($keyword) ?>" autocomplete="off">
                </div>
            </form>
        </div>
    </div>
</div>

<div id="container-alat">
    <?php if (mysqli_num_rows($alats) > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            <?php while($row = mysqli_fetch_assoc($alats)): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 hover-transform" style="transition: transform 0.2s;">
                    <div class="position-relative">
                        <img src="<?= base_url('assets/img/' . $row['gambar']) ?>" class="card-img-top object-fit-cover" alt="<?= $row['nama_alat'] ?>" style="height: 200px;">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-white text-dark shadow-sm opacity-75">
                            <i class="fas fa-tag text-primary me-1"></i> <?= $row['nama_kategori'] ?>
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <h5 class="card-title fw-bold text-dark mb-1"><?= $row['nama_alat'] ?></h5>
                        <p class="card-text small text-muted mb-3" style="line-height: 1.4;">
                            <?= substr($row['deskripsi'], 0, 60) ?><?= strlen($row['deskripsi']) > 60 ? '...' : '' ?>
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="small text-muted">Stok Tersedia</span>
                                <?php if($row['stok'] > 0): ?>
                                    <span class="fw-bold text-success"><?= $row['stok'] ?> Unit</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($row['stok'] > 0): ?>
                            <form method="POST" class="d-grid">
                                <input type="hidden" name="id_alat" value="<?= $row['id_alat'] ?>">
                                <input type="hidden" name="nama_alat" value="<?= $row['nama_alat'] ?>">
                                <input type="hidden" name="stok" value="<?= $row['stok'] ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary rounded-pill btn-sm py-2 fw-semibold">
                                    <i class="fas fa-cart-plus me-2"></i>Pinjam Sekarang
                                </button>
                            </form>
                            <?php else: ?>
                                <button class="btn btn-secondary rounded-pill btn-sm w-100 py-2" disabled>
                                    <i class="fas fa-clock me-2"></i>Menunggu Restock
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mb-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page_active <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link rounded-circle mx-1" href="?page=alat&halaman=<?= $page_active - 1 ?>&q=<?= $keyword ?>&limit=<?= $limit ?>&kategori=<?= $kategori_id ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page_active) ? 'active' : '' ?>">
                        <a class="page-link rounded-circle mx-1" href="?page=alat&halaman=<?= $i ?>&q=<?= $keyword ?>&limit=<?= $limit ?>&kategori=<?= $kategori_id ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page_active >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link rounded-circle mx-1" href="?page=alat&halaman=<?= $page_active + 1 ?>&q=<?= $keyword ?>&limit=<?= $limit ?>&kategori=<?= $kategori_id ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">Barang tidak ditemukan</h4>
                    <p class="text-muted">Coba kata kunci lain atau reset pencarian.</p>
                    <a href="index.php?page=alat" class="btn btn-outline-primary rounded-pill mt-2">Reset Pencarian</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


<script>
    $(document).ready(function() {
        // Fungsi Load Data
        function loadData() {
            var keyword = $('#keyword').val();
            var kategori = $('#kategori').val();
            var limit = $('#limit').val();
            
            $('#container-alat').css('opacity', '0.5');

            $.get('alat/ajax.php?q=' + keyword + '&kategori=' + kategori + '&limit=' + limit, function(data) {
                $('#container-alat').html(data);
                $('#container-alat').css('opacity', '1');
            })
            .fail(function() {
                alert("Gagal memuat data.");
                $('#container-alat').css('opacity', '1');
            });
        }

        // Event Listeners
        $('#keyword').on('keyup', loadData);
        $('#kategori').on('change', loadData);
        $('#limit').on('change', loadData);
    });
</script>

<style>
    .hover-transform:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .page-link { border: none; color: #333; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 600; }
    .page-item.active .page-link { background-color: #0d6efd; color: white; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3); }
    .page-link:hover { background-color: #e9ecef; color: #0d6efd; }
</style>

        </main>

