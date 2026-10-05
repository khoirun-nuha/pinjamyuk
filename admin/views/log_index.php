<?php if(!defined('ACCESS')) { header("Location: ../../index.php"); exit; } ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2">Log Aktivitas</h1>
    <div class="d-flex gap-2">
         <form action="" method="GET" class="d-flex align-items-center gap-2">
            <input type="hidden" name="page" value="log_aktivitas">
            <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php
                $bulan_array = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($bulan_array as $kode => $nama): ?>
                    <option value="<?= $kode ?>" <?= ($bulan == $kode) ? 'selected' : '' ?>><?= $nama ?></option>
                <?php endforeach; ?>
            </select>
            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php for($i = date('Y'); $i >= 2020; $i--): ?>
                    <option value="<?= $i ?>" <?= ($tahun == $i) ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </form>
        <!-- <a href="#" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print"></i> Cetak</a> -->
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i>Riwayat Aktivitas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%">Waktu</th>
                        <th width="20%">Pengguna</th>
                        <th width="10%">Role</th>
                        <th width="10%">Aktivitas</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // $logs provided by controller
                    foreach($logs as $row): 
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-nowrap small text-muted"><i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($row['waktu'])) ?></td>
                        <td class="fw-semibold align-middle">
                            <?php if ($row['username']): ?>
                                <span><?= $row['username'] ?></span>
                            <?php else: ?>
                                <span class="text-muted fst-italic">User Terhapus</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <?php if ($row['username']): ?>
                                <span class="badge bg-secondary rounded-pill small" style="font-size: 0.7em;"><?= ucfirst($row['level']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $badge_color = 'secondary';
                            if ($row['aktivitas'] == 'login') $badge_color = 'success';
                            if ($row['aktivitas'] == 'logout') $badge_color = 'warning text-dark';
                            if ($row['aktivitas'] == 'tambah') $badge_color = 'primary';
                            if ($row['aktivitas'] == 'edit') $badge_color = 'info text-dark';
                            if ($row['aktivitas'] == 'hapus') $badge_color = 'danger';
                            if ($row['aktivitas'] == 'peminjaman') $badge_color = 'primary';
                            ?>
                            <span class="badge bg-<?= $badge_color ?> bg-opacity-75"><?= ucfirst($row['aktivitas']) ?></span>
                        </td>
                        <td><?= $row['keterangan'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
