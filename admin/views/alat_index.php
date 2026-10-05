<?php if(!defined('ACCESS')) { header("Location: ../../index.php"); exit; } ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2">Manajemen Alat</h1>
    
    <div class="d-flex gap-2">
        <form action="" method="GET" class="d-flex align-items-center">
            <input type="hidden" name="page" value="alat">
            <select name="kategori" class="form-select form-select-sm me-2" onchange="this.form.submit()" style="width: 150px;">
                <option value="">Semua Kategori</option>
                <?php while($kat = mysqli_fetch_assoc($kategories)): ?>
                    <option value="<?= $kat['id_kategori'] ?>" <?= ($kategori_id == $kat['id_kategori']) ? 'selected' : '' ?>>
                        <?= $kat['nama_kategori'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <!-- <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-filter"></i></button> -->
        </form>
        <a href="index.php?page=alat_tambah" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Alat</a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-boxes me-2"></i>Daftar Alat</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%">Gambar</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Deskripsi</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // $alats provided by controller
                    while($row = mysqli_fetch_assoc($alats)): 
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <div class="ratio ratio-1x1" style="width: 60px;">
                                <img src="<?= base_url('assets/img/' . $row['gambar']) ?>" alt="img" class="object-fit-cover rounded shadow-sm border">
                            </div>
                        </td>
                        <td class="fw-semibold"><?= $row['nama_alat'] ?></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-tag me-1 text-secondary"></i> <?= $row['nama_kategori'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['stok'] > 0): ?>
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                                    <?= $row['stok'] ?> Unit
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                                    Habis
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?= substr($row['deskripsi'], 0, 60) ?><?= strlen($row['deskripsi']) > 60 ? '...' : '' ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="index.php?page=alat_edit&id=<?= $row['id_alat'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?page=alat_hapus&id=<?= $row['id_alat'] ?>" class="btn btn-danger btn-sm confirm-action" data-message="Yakin ingin menghapus alat ini?" data-icon="error" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
