<?php
require_once '../functions/transaksi_func.php';
?>


<?php
$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Peminjaman</h1>
    
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="GET" class="d-flex gap-2 align-items-center me-2">
            <input type="hidden" name="page" value="peminjaman">
            
            <select name="bulan" class="form-select form-select-sm">
                <option value="">- Semua Bulan -</option>
                <?php
                $bulan_list = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                foreach ($bulan_list as $k => $v) {
                    $selected = ($bulan == $k) ? 'selected' : '';
                    echo "<option value='$k' $selected>$v</option>";
                }
                ?>
            </select>
            
            <select name="tahun" class="form-select form-select-sm">
                <option value="">- Semua Tahun -</option>
                <?php
                $tahun_sekarang = date('Y');
                for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 5; $i--) {
                    $selected = ($tahun == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
            
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
        </form>

        <a href="cetak_laporan.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = ambil_semua_peminjaman($bulan, $tahun);
                    while($row = mysqli_fetch_assoc($query)): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_peminjam'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><?= $row['tgl_kembali_real'] ? date('d-m-Y', strtotime($row['tgl_kembali_real'])) : '-' ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= ucfirst($row['status']) ?></span>
                        </td>
                        <td>Rp <?= number_format($row['denda'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
