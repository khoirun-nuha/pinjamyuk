<?php if(!defined('ACCESS')) { header("Location: ../../index.php"); exit; } ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2"><?= $id ? 'Edit Kategori' : 'Tambah Kategori' ?></h1>
    <a href="index.php?page=kategori" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST">
            <input type="hidden" name="id_kategori" value="<?= $id ?>">
            
            <div class="mb-3">
                <label>Nama Kategori</label>
                <input type="text" name="nama" class="form-control" required value="<?= $kategori['nama_kategori'] ?? '' ?>" placeholder="Contoh: Elektronik">
            </div>
            
            <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
