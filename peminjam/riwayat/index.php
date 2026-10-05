<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/core/helper.php';
require_once __DIR__ . '/../../functions/core/auth.php';
require_once __DIR__ . '/../../functions/general/transaksi_func.php';

cek_login();
cek_level('peminjam');
cek_level('peminjam');

// Handle Ajukan Kembali
if (isset($_POST['ajukan_kembali'])) {
    $id_pinjam = $_POST['id_peminjaman'];
    if (ajukan_kembali($id_pinjam)) {
        alert("Pengajuan pengembalian berhasil dikirim!");
        redirect("peminjam/index.php?page=riwayat");
    } else {
        alert("Gagal mengajukan pengembalian!");
    }
}

// Handle Batalkan Pengajuan
if (isset($_POST['batalkan_pengajuan'])) {
    $id_pinjam = $_POST['id_peminjaman'];
    if (batalkan_peminjaman($id_pinjam, $_SESSION['id_pengguna'])) {
        alert("Peminjaman berhasil dibatalkan!");
        redirect("peminjam/index.php?page=riwayat");
    } else {
        alert("Gagal membatalkan peminjaman! Pastikan status masih diajukan.");
    }
}
?>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Riwayat Peminjaman</h1>
            </div>

                </div>
            </div>

            <!-- Tab Navigasi Status (Shopee Style) -->
            <?php $s = isset($_GET['status']) ? $_GET['status'] : ''; ?>
            <ul class="nav nav-tabs mb-3 border-bottom-0">
                <li class="nav-item">
                    <a class="nav-link <?= $s == '' ? 'active fw-bold border border-bottom-0' : 'text-muted border-0' ?>" href="index.php?page=riwayat">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $s == 'diajukan' ? 'active fw-bold border border-bottom-0' : 'text-muted border-0' ?>" href="index.php?page=riwayat&status=diajukan">Diajukan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $s == 'dipinjam' ? 'active fw-bold border border-bottom-0' : 'text-muted border-0' ?>" href="index.php?page=riwayat&status=dipinjam">Dipinjam</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $s == 'diajukan_kembali' ? 'active fw-bold border border-bottom-0' : 'text-muted border-0' ?>" href="index.php?page=riwayat&status=diajukan_kembali">Menunggu Verifikasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $s == 'dikembalikan' ? 'active fw-bold border border-bottom-0' : 'text-muted border-0' ?>" href="index.php?page=riwayat&status=dikembalikan">Selesai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $s == 'ditolak' ? 'active fw-bold border border-bottom-0' : 'text-muted border-0' ?>" href="index.php?page=riwayat&status=ditolak">Ditolak</a>
                </li>

            </ul>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i>Riwayat Peminjaman</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th>Status</th>
                                    <th>Denda</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
                                $data = ambil_peminjaman_user($_SESSION['id_pengguna'], $status_filter);
                                while($row = mysqli_fetch_assoc($data)): 
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <?php 
                                            // Jika belum dipinjam (masih diajukan/ditolak), tampilkan tgl pengajuan
                                            $tgl_show = $row['tgl_pinjam'] ? $row['tgl_pinjam'] : $row['tgl_pengajuan_pinjam'];
                                            echo '<i class="far fa-calendar me-1 text-muted"></i>' . date('d-m-Y H:i', strtotime($tgl_show));
                                            ?>
                                        </td>
                                        <td>
                                            <?php if($row['tgl_kembali_rencana']): ?>
                                                <i class="far fa-calendar-check me-1 text-muted"></i><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($row['status'] == 'diajukan'): ?>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">Menunggu Konfirmasi</span>
                                            <?php elseif($row['status'] == 'dipinjam'): ?>
                                                <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1">Dipinjam</span>
                                            <?php elseif($row['status'] == 'dikembalikan'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1">Selesai</span>
                                            <?php elseif($row['status'] == 'diajukan_kembali'): ?>
                                                <span class="badge bg-info-subtle text-info border border-info px-2 py-1">Menunggu Konfirmasi Kembali</span>
                                            <?php elseif($row['status'] == 'dibatalkan'): ?>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">Dibatalkan</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">Ditolak</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-danger">
                                            <?php if($row['denda'] > 0): ?>
                                                Rp <?= number_format($row['denda'], 0, ',', '.') ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <!-- Tombol Detail -->
                                                <?php 
                                                // Prepare data for JS
                                                $details = ambil_detail_peminjaman($row['id_peminjaman']);
                                                $arr_items = [];
                                                while($item = mysqli_fetch_assoc($details)):
                                                    $img = !empty($item['gambar']) ? '../assets/img/'.$item['gambar'] : '../assets/img/default-alat.jpg';
                                                    $arr_items[] = [
                                                        'nama' => $item['nama_alat'],
                                                        'jumlah' => $item['jumlah'],
                                                        'gambar' => $img
                                                    ];
                                                endwhile;
                                                $json_items = htmlspecialchars(json_encode($arr_items), ENT_QUOTES, 'UTF-8');
                                                ?>
                                                
                                                <button type="button" class="btn btn-info btn-sm text-white btn-detail-history" 
                                                        data-id="<?= $row['id_peminjaman'] ?>"
                                                        data-tgl="<?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?>"
                                                        data-items="<?= $json_items ?>"
                                                        title="Detail Barang">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Aksi Kembalikan -->
                                                <?php if($row['status'] == 'dipinjam'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                                        <button type="submit" name="ajukan_kembali" class="btn btn-warning btn-sm text-dark confirm-action" data-message="Ajukan pengembalian barang ini?" data-icon="question" title="Kembalikan">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                <?php elseif($row['status'] == 'diajukan'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                                        <button type="submit" name="batalkan_pengajuan" class="btn btn-danger btn-sm text-white confirm-action" data-message="Yakin ingin membatalkan pengajuan ini?" data-icon="warning" title="Batalkan">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

<!-- Modal Detail History (Global) -->
<div class="modal fade" id="modalDetailHistory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="fas fa-box-open me-2 text-primary"></i>Detail Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Waktu Peminjaman</p>
                        <h5 class="fw-bold text-dark"><i class="far fa-calendar-alt me-2"></i><span id="histModalTgl">-</span></h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="badge bg-info px-3 py-2 rounded-pill text-white">ID: <span id="histModalId">#</span></span>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3 border-bottom pb-2">Item Dipinjam</h6>
                <div class="row g-3" id="histModalContainer">
                    <!-- Injected by JS -->
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/riwayat.js') ?>"></script>


