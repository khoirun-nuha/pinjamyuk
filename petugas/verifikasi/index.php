<?php
require_once __DIR__ . '/../../functions/general/transaksi_func.php';

// Handle Verify Loan (Peminjaman)
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = $_GET['aksi']; // 'dipinjam' or 'ditolak'
    $id_petugas = $_SESSION['id_pengguna'];

    if (verifikasi_pinjam($id, $status, $id_petugas)) {
        alert('Status peminjaman diperbarui!');
        redirect('petugas/index.php?page=verifikasi');
    } else {
        alert('Gagal memproses verifikasi!');
    }
}

// Handle Approve Return (Pengembalian)
if (isset($_POST['terima_kembali'])) {
    
    // DEBUG: Log parameter
    require_once __DIR__ . '/../../functions/core/debug.php';
    debug_log("Action: terima_kembali triggered.");
    debug_log("POST DATA: " . print_r($_POST, true));

    $id_pinjam = $_POST['id_peminjaman'];
    $tgl_rencana = $_POST['tgl_rencana'];
    $tgl_kembali_real = date('Y-m-d H:i:s');
    $id_petugas = $_SESSION['id_pengguna'];

    // Hitung Denda
    $denda = 0;
    try {
        $date1 = new DateTime($tgl_rencana);
        $date2 = new DateTime($tgl_kembali_real);
        
        if ($date2 > $date1) {
            $interval = $date1->diff($date2);
            $days = $interval->days; 
            
            // Hitung Total Item yang dipinjam
            $q_total = mysqli_query($conn, "SELECT SUM(jumlah) as total_item FROM detail_peminjaman WHERE id_peminjaman = '$id_pinjam'");
            $d_total = mysqli_fetch_assoc($q_total);
            $total_item = $d_total['total_item'] ? $d_total['total_item'] : 0;
            
            // Rumus baru: 2000 per item per hari
            $tarif_per_item = 2000;
            $denda = $total_item * $tarif_per_item * $days;
            
            debug_log("Denda Logic: Item($total_item) * Tarif($tarif_per_item) * Hari($days) = $denda");
        }
    } catch (Exception $e) {
        debug_log("Date calculation error: " . $e->getMessage());
    }
    
    debug_log("Calculated Denda: $denda");

    if (proses_kembali($id_pinjam, $tgl_kembali_real, $denda, $id_petugas)) {
        alert("Pengembalian disetujui! Denda: Rp " . number_format($denda, 0, ',', '.'));
        redirect('petugas/index.php?page=verifikasi');
    } else {
        alert('Gagal memproses pengembalian!');
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2">Verifikasi Transaksi</h1>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-tasks me-2"></i>Daftar Permintaan Verifikasi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Peminjam</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Batas Kembali</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Ambil status 'diajukan' (Peminjaman Baru) ATAU 'diajukan_kembali' (Pengembalian)
                    $query = mysqli_query($conn, "SELECT p.*, u.nama_lengkap FROM peminjaman p JOIN pengguna u ON p.id_pengguna = u.id_pengguna WHERE p.status IN ('diajukan', 'diajukan_kembali') ORDER BY p.tgl_pinjam ASC");
                    while($row = mysqli_fetch_assoc($query)): 
                        $items = ambil_detail_peminjaman($row['id_peminjaman']);
                        $arr_items = [];
                        while($item = mysqli_fetch_assoc($items)){
                            $img = !empty($item['gambar']) ? '../assets/img/'.$item['gambar'] : '../assets/img/default-alat.jpg';
                            $arr_items[] = [
                                'nama' => $item['nama_alat'],
                                'jumlah' => $item['jumlah'],
                                'gambar' => $img
                            ];
                        }
                        $json_items = htmlspecialchars(json_encode($arr_items), ENT_QUOTES, 'UTF-8');
                        
                        // Tentukan Jenis & Tanggal Relevan
                        if ($row['status'] == 'diajukan') {
                            $jenis_label = '<span class="badge bg-primary"><i class="fas fa-arrow-up me-1"></i>Peminjaman</span>';
                            $tgl_label = date('d-m-Y H:i', strtotime($row['tgl_pengajuan_pinjam']));
                            $batas_kembali = '<span class="badge bg-secondary">Otomatis (+7 Hari)</span>';
                        } else {
                            $jenis_label = '<span class="badge bg-info text-dark"><i class="fas fa-arrow-down me-1"></i>Pengembalian</span>';
                            $tgl_label = date('d-m-Y H:i', strtotime($row['tgl_pengajuan_kembali']));
                            $batas_kembali = '<i class="far fa-calendar-check me-1 text-muted"></i>' . date('d-m-Y', strtotime($row['tgl_kembali_rencana']));
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-semibold"><?= $row['nama_lengkap'] ?></td>
                        <td><?= $jenis_label ?></td>
                        <td><i class="far fa-calendar me-1 text-muted"></i><?= $tgl_label ?></td>
                        <td><?= $batas_kembali ?></td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <!-- Tombol Detail -->
                                <button type="button" class="btn btn-info btn-sm btn-detail text-white" 
                                        data-id="<?= $row['id_peminjaman'] ?>"
                                        data-peminjam="<?= $row['nama_lengkap'] ?>"
                                        data-items="<?= $json_items ?>"
                                        title="Lihat Detail Barang">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <?php if ($row['status'] == 'diajukan'): ?>
                                    <!-- Aksi Verifikasi Peminjaman -->
                                    <a href="index.php?page=verifikasi&aksi=dipinjam&id=<?= $row['id_peminjaman'] ?>" class="btn btn-success btn-sm confirm-action" data-message="Setujui peminjaman ini? Tanggal pinjam akan dimulai SEKARANG." title="Setuju"><i class="fas fa-check"></i></a>
                                    <a href="index.php?page=verifikasi&aksi=ditolak&id=<?= $row['id_peminjaman'] ?>" class="btn btn-danger btn-sm confirm-action" data-message="Tolak peminjaman ini?" title="Tolak"><i class="fas fa-times"></i></a>
                                
                                <?php else: ?>
                                    <!-- Aksi Verifikasi Pengembalian -->
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                        <input type="hidden" name="tgl_rencana" value="<?= $row['tgl_kembali_rencana'] ?>">
                                        <button type="submit" name="terima_kembali" class="btn btn-warning text-dark btn-sm confirm-action" data-message="Setujui pengembalian barang ini? Pastikan barang sudah diterima." data-icon="question" title="Terima Barang">
                                            <i class="fas fa-box-open me-1"></i> Terima
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

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-box-open me-2 text-white"></i>Detail Barang Dipinjam</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Peminjam</p>
                        <h5 class="fw-bold text-dark" id="modalPeminjamName">-</h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="badge bg-primary px-3 py-2 rounded-pill">ID: <span id="modalTransId">#</span></span>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3 border-bottom pb-2">Daftar Barang</h6>
                <div class="row g-3" id="modalItemsContainer">
                    <!-- Items will be injected here via JS -->
                </div>
            </div>
            <div class="modal-footer bg-white" id="modalFooterActions">
                <!-- Action buttons will be injected here via JS -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/verifikasi.js') ?>"></script>
