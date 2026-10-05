<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/core/helper.php';
require_once __DIR__ . '/../../functions/core/auth.php';
require_once __DIR__ . '/../../functions/general/transaksi_func.php';
require_once __DIR__ . '/../../functions/core/debug.php';

cek_login();
cek_level('petugas');

// Handle Process Return (Directly from 'dipinjam' status)
if (isset($_POST['proses_kembali'])) {
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
            
            // Rumus: 2000 per item per hari
            $tarif_per_item = 2000;
            $denda = $total_item * $tarif_per_item * $days;
        }
    } catch (Exception $e) {
        debug_log("Date calculation error: " . $e->getMessage());
    }

    if (proses_kembali($id_pinjam, $tgl_kembali_real, $denda, $id_petugas)) {
        alert("Pengembalian berhasil diproses! Denda: Rp " . number_format($denda, 0, ',', '.'));
        redirect('petugas/index.php?page=pengembalian');
    } else {
        alert('Gagal memproses pengembalian!');
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Proses Pengembalian</h1>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-box-open me-2"></i>Daftar Barang Dipinjam</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Peminjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Barang</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Hanya tampilkan yang statusnya 'dipinjam'
                    $query = mysqli_query($conn, "SELECT p.*, u.nama_lengkap FROM peminjaman p JOIN pengguna u ON p.id_pengguna = u.id_pengguna WHERE p.status = 'dipinjam' ORDER BY p.tgl_pinjam ASC");
                    while($row = mysqli_fetch_assoc($query)): 
                        $items = ambil_detail_peminjaman($row['id_peminjaman']);
                        $list_items = "";
                        while($item = mysqli_fetch_assoc($items)){
                            $list_items .= "<li class='small'>{$item['nama_alat']} <span class='badge bg-light text-dark border ms-1'>{$item['jumlah']}</span></li>";
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-semibold"><?= $row['nama_lengkap'] ?></td>
                        <td><i class="far fa-calendar me-1 text-muted"></i><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><i class="far fa-calendar-check me-1 text-muted"></i><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                        <td><ul class="mb-0 ps-3 text-muted"><?= $list_items ?></ul></td>
                        <td class="text-center">
                            <form method="POST">
                                <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                <input type="hidden" name="tgl_rencana" value="<?= $row['tgl_kembali_rencana'] ?>">
                                <button type="submit" name="proses_kembali" class="btn btn-primary btn-sm confirm-action" data-message="Proses pengembalian barang ini? Pastikan barang sudah dicek kondisinya.">
                                    <i class="fas fa-check-circle me-1"></i> Proses Kembali
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
