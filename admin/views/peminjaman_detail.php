<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Peminjaman</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?page=peminjaman">Data Peminjaman</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>

    <div class="row">
        <!-- Info Peminjaman -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-info-circle me-1"></i> Informasi Peminjam
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Nama</th>
                            <td>: <?= htmlspecialchars($info['nama_peminjam']) ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: <?= htmlspecialchars($info['alamat_peminjam']) ?></td>
                        </tr>
                        <tr>
                            <th>Tgl Pinjam</th>
                            <td>: <?= date('d-m-Y H:i', strtotime($info['tgl_pinjam'])) ?></td>
                        </tr>
                        <tr>
                            <th>Batas Kembali</th>
                            <td>: <?= date('d-m-Y', strtotime($info['tgl_kembali_rencana'])) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                <span class="badge bg-secondary"><?= ucfirst($info['status']) ?></span>
                            </td>
                        </tr>
                        <?php if ($info['tgl_kembali_real']): ?>
                        <tr>
                            <th>Dikembalikan</th>
                            <td>: <?= date('d-m-Y H:i', strtotime($info['tgl_kembali_real'])) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($info['denda'] > 0): ?>
                        <tr>
                            <th>Denda</th>
                            <td class="text-danger fw-bold">: Rp <?= number_format($info['denda'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            
             <a href="index.php?page=peminjaman" class="btn btn-secondary w-100 mb-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Daftar Barang -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-boxes me-1"></i> Daftar Barang Dipinjam
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered text-start">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="15%">Gambar</th>
                                <th>Nama Alat</th>
                                <th>Kategori</th>
                                <th width="10%" class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($items as $item): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center">
                                    <img src="../assets/img/<?= $item['gambar'] ?>" alt="Alat" width="50" class="img-thumbnail">
                                </td>
                                <td><?= htmlspecialchars($item['nama_alat']) ?></td>
                                <td><?= htmlspecialchars($item['nama_kategori']) ?></td>
                                <td class="text-center fw-bold"><?= $item['jumlah'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
