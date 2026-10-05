<?php if(!defined('ACCESS')) { header("Location: ../../index.php"); exit; } ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2">Manajemen Pengguna</h1>
    <a href="index.php?page=pengguna_tambah" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pengguna</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users me-2"></i>Daftar Pengguna</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Alamat</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // $users provided by controller
                    while($row = mysqli_fetch_assoc($users)): 
                    ?>
                    <tr class="<?= ($row['status'] == 'blokir') ? 'table-secondary opacity-75' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td class="fw-bold"><?= $row['nama_lengkap'] ?> <?= ($row['status'] == 'blokir') ? '<i class="fas fa-lock small text-muted ms-1"></i>' : '' ?></td>
                        <td class="text-muted"><?= $row['username'] ?></td>
                        <td>
                            <span class="badge rounded-pill bg-<?= ($row['level'] == 'admin') ? 'danger' : (($row['level'] == 'petugas') ? 'warning' : 'info') ?>">
                                <?= ucfirst($row['level']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'aktif'): ?>
                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary text-white border border-secondary px-2 py-1"><i class="fas fa-ban me-1"></i>Diblokir</span>
                            <?php endif; ?>
                        </td>
                        <td class="small"><?= $row['alamat'] ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="index.php?page=pengguna_aksi&aksi=reset&id=<?= $row['id_pengguna'] ?>" class="btn btn-warning btn-sm confirm-action" data-message="Reset password user ini menjadi 12345678?" title="Reset Password"><i class="fas fa-key"></i></a>
                                
                                <?php if ($row['level'] != 'admin'): ?>
                                    <?php if ($row['status'] == 'aktif'): ?>
                                        <a href="index.php?page=pengguna_aksi&aksi=blokir&id=<?= $row['id_pengguna'] ?>" class="btn btn-outline-danger btn-sm confirm-action" data-message="Blokir pengguna ini?" title="Blokir User">
                                            <i class="fas fa-user-lock"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="index.php?page=pengguna_aksi&aksi=buka_blokir&id=<?= $row['id_pengguna'] ?>" class="btn btn-success btn-sm confirm-action" data-message="Buka blokir pengguna ini?" title="Buka Blokir">
                                            <i class="fas fa-unlock"></i>
                                        </a>
                                    <?php endif; ?>
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
