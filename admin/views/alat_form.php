<?php if(!defined('ACCESS')) { header("Location: ../../index.php"); exit; } ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-2 border-bottom">
    <h1 class="h2"><?= $id ? 'Edit Alat' : 'Tambah Alat' ?></h1>
    <a href="index.php?page=alat" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_alat" value="<?= $id ?>">
            <input type="hidden" name="gambarLama" value="<?= $alat['gambar'] ?? 'default.jpg' ?>">
            
            <div class="mb-3">
                <label>Nama Alat</label>
                <input type="text" name="nama" class="form-control" required value="<?= $alat['nama_alat'] ?? '' ?>" placeholder="Contoh: Laptop Acer Aspire 5">
            </div>
            
            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($kategoris as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= ($alat && $alat['id_kategori'] == $k['id_kategori']) ? 'selected' : '' ?>>
                            <?= $k['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" required value="<?= $alat['stok'] ?? '' ?>" placeholder="Contoh: 10">
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Contoh: Kondisi bagus, warna silver, RAM 8GB..."><?= $alat['deskripsi'] ?? '' ?></textarea>
            </div>

            <div class="mb-3">
                <label>Gambar</label>
                <?php if ($id && $alat['gambar']): ?>
                    <br><img src="<?= base_url('assets/img/' . $alat['gambar']) ?>" width="100" class="mb-2">
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*" onchange="validateImageInput(this)">
            </div>
            
            <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
