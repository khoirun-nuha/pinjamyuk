<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../admin/logic/alat_logic.php';
require_once __DIR__ . '/../../functions/core/helper.php';


// Tangkap keyword dan kategori
$keyword = isset($_GET['q']) ? $_GET['q'] : '';
$kategori_id = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
if ($limit < 1) $limit = 8;

// Ambil data dengan filter
$alats = ambil_alat_pagination($keyword, $limit, 0, $kategori_id);


if (mysqli_num_rows($alats) > 0): 
?>
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
    <?php 
    $total_data = hitung_jumlah_alat($keyword, $kategori_id);
    $total_pages = ceil($total_data / $limit);
    $page_active = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    ?>
    
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

                <p class="text-muted">Tidak ada alat yang cocok dengan kata kunci atau kategori yang dipilih.</p>
            </div>
        </div>
    </div>
<?php endif; ?>
