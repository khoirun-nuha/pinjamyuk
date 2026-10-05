<?php
require_once __DIR__ . '/../../functions/general/transaksi_func.php';
?>


<?php
$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2">Laporan Peminjaman</h1>
    
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="GET" class="d-flex gap-2 align-items-center me-2">
            <input type="hidden" name="page" value="laporan">
            
            <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
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
            
            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">- Semua Tahun -</option>
                <?php
                $tahun_sekarang = date('Y');
                for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 5; $i--) {
                    $selected = ($tahun == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
        </form>

        <div class="btn-group">
            <button onclick="printLaporan('print', '<?= $bulan ?>', '<?= $tahun ?>')" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Cetak
            </button>
            <button onclick="printLaporan('pdf', '<?= $bulan ?>', '<?= $tahun ?>')" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <a href="cetak_laporan.php?type=excel&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-file-alt me-2"></i>Data Laporan Peminjaman</h6>
        <div class="small text-white-50">
            <?= $bulan ? "Bulan: " . $bulan : "Semua Bulan" ?> | <?= $tahun ? "Tahun: " . $tahun : "Semua Tahun" ?>
        </div>
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
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>

                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = ambil_semua_peminjaman($bulan, $tahun, $_SESSION['id_pengguna']);
                    while($row = mysqli_fetch_assoc($query)): 
                        // Ambil detail items untuk JSON
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
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-bold"><?= $row['nama_peminjam'] ?></td>
                        <td><i class="far fa-calendar me-1"></i><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><i class="far fa-calendar-times me-1"></i><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                        <td><?= $row['tgl_kembali_real'] ? '<i class="far fa-calendar-check me-1"></i>'.date('d-m-Y', strtotime($row['tgl_kembali_real'])) : '-' ?></td>
                        <td>
                            <?php if($row['status'] == 'kembali'): ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= ucfirst($row['status']) ?></span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($row['denda'] > 0): ?>
                                <span class="text-danger fw-bold">Rp <?= number_format($row['denda'], 0, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm btn-detail text-white" 
                                    data-id="<?= $row['id_peminjaman'] ?>"
                                    data-peminjam="<?= $row['nama_peminjam'] ?>"
                                    data-items="<?= $json_items ?>"
                                    data-denda="<?= number_format($row['denda'], 0, ',', '.') ?>"
                                    data-ptgs-pinjam="<?= !empty($row['nama_petugas']) ? $row['nama_petugas'] : '-' ?>"
                                    data-ptgs-kembali="<?= !empty($row['nama_petugas_kembali']) ? $row['nama_petugas_kembali'] : '-' ?>"
                                    title="Lihat Detail Barang">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi (Sama dengan Verifikasi) -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="fas fa-box-open me-2 text-primary"></i>Detail Barang Dipinjam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Peminjam</p>
                        <h5 class="fw-bold text-dark" id="modalPeminjamName">-</h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">ID: <span id="modalTransId">#</span></span>
                        <div>
                            <span class="text-muted small">Denda:</span>
                            <span class="fw-bold text-danger fs-5" id="modalDenda">Rp 0</span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3 bg-light p-2 rounded mx-1">
                    <div class="col-6">
                        <small class="text-muted d-block">Petugas Peminjaman:</small>
                        <span class="fw-bold text-dark" id="modalPtgsPinjam">-</span>
                    </div>
                    <div class="col-6 text-end">
                        <small class="text-muted d-block">Petugas Pengembalian:</small>
                        <span class="fw-bold text-dark" id="modalPtgsKembali">-</span>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 border-bottom pb-2">Daftar Barang</h6>
                <div class="row g-3" id="modalItemsContainer">
                    <!-- Items will be injected here via JS -->
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function printLaporan(type, bulan, tahun) {
        var url = 'cetak_laporan.php?type=' + type + '&bulan=' + bulan + '&tahun=' + tahun;
        var iframe = document.getElementById('printFrame');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'printFrame';
            iframe.style.position = 'fixed';
            iframe.style.left = '-9999px';
            iframe.style.top = '0';
            iframe.style.width = '1px';
            iframe.style.height = '1px';
            document.body.appendChild(iframe);
        }
        iframe.src = url;
    }

    // Modal Detail Logic (Mirip verifikasi.js tapi inline)
    document.addEventListener('DOMContentLoaded', function() {
        var modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
        var btnDetails = document.querySelectorAll('.btn-detail');

        btnDetails.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var peminjam = this.getAttribute('data-peminjam');
                var denda = this.getAttribute('data-denda');
                var itemsData = JSON.parse(this.getAttribute('data-items'));

                var ptgsPinjam = this.getAttribute('data-ptgs-pinjam');
                var ptgsKembali = this.getAttribute('data-ptgs-kembali');

                document.getElementById('modalTransId').textContent = id;
                document.getElementById('modalPeminjamName').textContent = peminjam;
                document.getElementById('modalDenda').textContent = 'Rp ' + denda;
                document.getElementById('modalPtgsPinjam').textContent = ptgsPinjam;
                document.getElementById('modalPtgsKembali').textContent = ptgsKembali;

                var container = document.getElementById('modalItemsContainer');
                container.innerHTML = '';

                if (itemsData.length > 0) {
                    itemsData.forEach(function(item) {
                        var html = `
                            <div class="col-md-6">
                                <div class="d-flex align-items-center border p-2 rounded">
                                    <img src="${item.gambar}" class="rounded me-3" width="50" height="50" style="object-fit:cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold">${item.nama}</h6>
                                        <small class="text-muted">Jumlah: ${item.jumlah}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', html);
                    });
                } else {
                    container.innerHTML = '<p class="text-center text-muted col-12">Tidak ada data barang.</p>';
                }

                modalDetail.show();
            });
        });
    });
</script>
