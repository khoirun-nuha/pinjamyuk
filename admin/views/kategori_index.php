<?php if(!defined('ACCESS')) { header("Location: ../../index.php"); exit; } ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2">Manajemen Kategori</h1>
    <a href="index.php?page=kategori_tambah" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Kategori</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tags me-2"></i>Daftar Kategori</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Nama Kategori</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // $kategoris variable provided by controller
                    while($row = mysqli_fetch_assoc($kategoris)): 
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-semibold"><?= $row['nama_kategori'] ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="index.php?page=kategori_edit&id=<?= $row['id_kategori'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Updated Link for Hapus to use Route -->
                                <a href="index.php?page=kategori_hapus&id=<?= $row['id_kategori'] ?>" class="btn btn-danger btn-sm confirm-action" data-message="Yakin ingin menghapus kategori ini?" data-icon="error" title="Hapus">
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
