<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/core/helper.php';
require_once __DIR__ . '/../../functions/core/auth.php';
require_once __DIR__ . '/../../admin/logic/alat_logic.php';
require_once __DIR__ . '/../../functions/general/transaksi_func.php';

cek_login();
cek_level('peminjam');

// Update Qty
if (isset($_POST['update_update'])) {
    foreach ($_POST['qty'] as $id => $val) {
        $val = (int)$val;
        if ($val < 1) {
            unset($_SESSION['cart'][$id]);
        } else {
            // Validasi Max Stok
            $max_stok = isset($_SESSION['cart'][$id]['max_stok']) ? $_SESSION['cart'][$id]['max_stok'] : 9999;
            if ($val > $max_stok) {
                $_SESSION['cart'][$id]['qty'] = $max_stok;
            } else {
                $_SESSION['cart'][$id]['qty'] = $val;
            }
        }
    }
}


// Hapus Item
if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][$_GET['hapus']]);
    redirect('peminjam/index.php?page=keranjang');
}

// Hapus Semua Item
if (isset($_GET['hapus_semua'])) {
    unset($_SESSION['cart']); // Hapus seluruh isi sesi cart
    redirect('peminjam/index.php?page=keranjang');
}


// Checkout
if (isset($_POST['checkout'])) {
    // $tgl_kembali = $_POST['tgl_kembali']; // DIHAPUS: Peminjam tidak input tanggal lagi
    $id_pengguna = $_SESSION['id_pengguna'];
    $cart        = $_SESSION['cart'];

    if (!empty($cart)) {
        // Panggil fungsi ajukan_pinjam tanpa parameter tgl_kembali
        if (ajukan_pinjam($id_pengguna, $cart)) {
            unset($_SESSION['cart']); // Clear cart
            alert('Pengajuan berhasil! Silahkan tunggu verifikasi petugas.');
            redirect('peminjam/index.php?page=riwayat');
        } else {
            $msg = isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : 'Gagal mengajukan peminjaman.';
            unset($_SESSION['error_msg']);
            alert($msg);
        }
    } else {
        alert('Keranjang kosong!');
    }
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-shopping-cart me-2"></i> Keranjang Peminjaman</h1>
        <p class="text-muted mb-0">Periksa kembali daftar alat yang ingin Anda pinjam.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="index.php?page=alat" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Tambah Barang
        </a>
    </div>
</div>

<form action="" method="POST">
    <div class="row g-4">
        <!-- Kolom Daftar Item -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-list me-2"></i>Daftar Item</h6>
                </div>
                <!-- ... (Kode Tabel Item tetap sama) ... -->
                <div class="card-body p-0">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Alat</th>
                                        <th width="15%">Qty</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-md bg-light rounded p-2 me-3">
                                                    <i class="fas fa-tools fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 text-dark fw-bold"><?= $item['nama'] ?></h6>
                                                    <small class="text-muted">ID: #<?= $id ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="qty[<?= $id ?>]" 
                                                   value="<?= $item['qty'] ?>" 
                                                   class="form-control form-control-sm" 
                                                   min="1" 
                                                   max="<?= isset($item['max_stok']) ? $item['max_stok'] : '' ?>" 
                                                   oninput="if(parseInt(this.value) > <?= isset($item['max_stok']) ? $item['max_stok'] : 9999 ?>) { 
                                                       this.value = <?= isset($item['max_stok']) ? $item['max_stok'] : 1 ?>; 
                                                   }" 
                                                   onchange="this.form.submit()">
                                        </td>
                                        <td>
                                            <a href="index.php?page=keranjang&hapus=<?= $id ?>" class="btn btn-danger btn-sm rounded-circle shadow-sm confirm-action" data-message="Hapus item ini dari keranjang?" data-icon="warning" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 bg-light border-top d-flex justify-content-between">
                            <a href="index.php?page=alat" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali Pilih Alat
                            </a>
                            <!-- Hidden input to trigger update logic on auto-submit -->
                            <input type="hidden" name="update_update" value="true">
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-shopping-basket fa-4x text-black-50"></i>
                            </div>
                            <h5 class="text-muted">Keranjang Anda masih kosong</h5>
                            <p class="text-muted small mb-4">Yuk, cari alat yang Anda butuhkan untuk kegiatan praktik!</p>
                            <a href="index.php?page=alat" class="btn btn-primary px-4 py-2 shadow-sm rounded-pill">
                                <i class="fas fa-search me-2"></i>Cari Alat
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Kolom Checkout -->
        <?php if (!empty($_SESSION['cart'])): ?>
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Formulir Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-uppercase text-muted">Tanggal Pengajuan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" class="form-control bg-light" value="<?= date('d-m-Y') ?>" readonly>
                        </div>
                        <div class="form-text small mt-2"><i class="fas fa-info-circle me-1"></i> Tanggal kembali akan ditentukan otomatis (7 hari) setelah disetujui petugas.</div>
                    </div>
                    
                    <!-- DIHAPUS: Input Rencana Kembali -->

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="checkout" class="btn btn-success btn-lg shadow hover-scale transition-3d-hover">
                            <i class="fas fa-paper-plane me-2"></i> Ajukan Peminjaman
                        </button>
                        <a href="index.php?page=keranjang&hapus_semua=true" class="btn btn-outline-danger btn-sm mt-2 confirm-action" data-message="Kosongkan semua keranjang?" data-icon="warning">
                            <i class="fas fa-trash me-1"></i> Kosongkan Keranjang
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info shadow-sm mt-3 border-0 rounded-lg d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1">Informasi</h6>
                    <p class="mb-0 small">Pastikan alat yang dipinjam sesuai kebutuhan. Keterlambatan pengembalian dapat dikenakan sanksi.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</form>




