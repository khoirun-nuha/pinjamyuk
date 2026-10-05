<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2">Data Peminjaman</h1>
    
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="" method="GET" class="d-flex gap-2 align-items-center me-2">
            <input type="hidden" name="page" value="peminjaman">
            
            <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">- Semua Bulan -</option>
                <?php
                $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
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
                $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
                $tahun_sekarang = date('Y');
                for ($i = $tahun_sekarang; $i >= 2020; $i--) {
                    $selected = ($tahun == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-file-invoice-dollar me-2"></i>Riwayat Semua Peminjaman</h6>
        <div class="small text-white-50">
            <?= $bulan ? "Bulan: " . $bulan_list[$bulan] : "Semua Bulan" ?> | <?= $tahun ? "Tahun: " . $tahun : "Semua Tahun" ?>
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
                        <th>Tgl Kembali (Real)</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Ptgs Pinjam</th>
                        <th>Ptgs Kembali</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($peminjaman)): 
                        // Ambil detail items untuk JSON Modal using existing logic
                        $detail_data = ambil_detail_peminjaman($row['id_peminjaman']);
                        $items = $detail_data['items'];
                        
                        $arr_items = [];
                        foreach($items as $item){
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
                        <td class="fw-bold"><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                        <td><i class="far fa-calendar me-1"></i><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><?= $row['tgl_kembali_real'] ? '<i class="far fa-calendar-check me-1"></i>'.date('d-m-Y', strtotime($row['tgl_kembali_real'])) : '-' ?></td>
                        <td>
                            <?php
                            $status_class = '';
                            switch ($row['status']) {
                                case 'diajukan': $status_class = 'bg-warning text-dark'; break;
                                case 'dipinjam': $status_class = 'bg-primary'; break;
                                case 'dikembalikan': $status_class = 'bg-success'; break;
                                case 'ditolak': $status_class = 'bg-danger'; break;
                                default: $status_class = 'bg-secondary';
                            }
                            ?>
                            <span class="badge <?= $status_class ?>"><?= ucfirst($row['status']) ?></span>
                        </td>

                        <td>
                            <?php if($row['denda'] > 0): ?>
                                <span class="text-danger fw-bold">Rp <?= number_format($row['denda'], 0, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <!-- PETUGAS PINJAM -->
                        <td><?= !empty($row['nama_petugas']) ? htmlspecialchars($row['nama_petugas']) : '<span class="text-muted">-</span>' ?></td>
                        <!-- PETUGAS KEMBALI -->
                        <td><?= !empty($row['nama_petugas_kembali']) ? htmlspecialchars($row['nama_petugas_kembali']) : '<span class="text-muted">-</span>' ?></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm btn-detail text-white" 
                                    data-id="<?= $row['id_peminjaman'] ?>"
                                    data-peminjam="<?= htmlspecialchars($row['nama_peminjam']) ?>"
                                    data-items="<?= $json_items ?>"
                                    data-denda="<?= number_format($row['denda'], 0, ',', '.') ?>"
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

<!-- Modal Detail Transaksi -->
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
    document.addEventListener('DOMContentLoaded', function() {
        var modalElement = document.getElementById('modalDetail');
        if (modalElement) { // Check if element exists to avoid bad constructor call
          var modalDetail = new bootstrap.Modal(modalElement);
          var btnDetails = document.querySelectorAll('.btn-detail');

          btnDetails.forEach(function(btn) {
              btn.addEventListener('click', function() {
                  var id = this.getAttribute('data-id');
                  var peminjam = this.getAttribute('data-peminjam');
                  var denda = this.getAttribute('data-denda');
                  var itemsData = JSON.parse(this.getAttribute('data-items'));

                  document.getElementById('modalTransId').textContent = id;
                  document.getElementById('modalPeminjamName').textContent = peminjam;
                  document.getElementById('modalDenda').textContent = 'Rp ' + denda;

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
        }
    });
</script>
