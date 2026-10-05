<?php if(!defined('ACCESS')) { header("Location: ../../index.php"); exit; } ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2"><?= $id ? 'Edit Pengguna' : 'Tambah Pengguna' ?></h1>
    <a href="index.php?page=pengguna" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST">
            <input type="hidden" name="id_pengguna" value="<?= $id ?>">
            
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required value="<?= $user['nama_lengkap'] ?? '' ?>" placeholder="Contoh: Budi Santoso">
            </div>
            
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required value="<?= $user['username'] ?? '' ?>" placeholder="Contoh: budisantoso123">
            </div>
            
            <div class="mb-3">
                <label>Password <?= $id ? '<small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>' : '' ?></label>
                <input type="password" name="password" class="form-control" <?= $id ? '' : 'required' ?> placeholder="<?= $id ? 'Masukkan password baru jika ingin mengubah' : 'Masukkan password...' ?>">
            </div>
            
            <div class="mb-3">
                <label>Level</label>
                <select name="level" class="form-select" required>
                    <option value="peminjam" <?= ($user['level'] ?? '') == 'peminjam' ? 'selected' : '' ?>>Peminjam (Siswa/Guru)</option>
                    <option value="petugas" <?= ($user['level'] ?? '') == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    <option value="admin" <?= ($user['level'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Contoh: Jl. Merdeka No. 10, Jakarta"><?= $user['alamat'] ?? '' ?></textarea>
            </div>
            
            <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
