<?php
if(!defined('ACCESS')) { header("Location: ../index.php"); exit; }
// Pastikan user sudah login (seharusnya sudah dicek di index.php tapi untuk keamanan ganda)
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../functions/core/profil_func.php';

$user_id = $_SESSION['id_pengguna'];
$profil = get_my_profile($user_id);

// Proses Form jika ada POST
// Proses Form jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hasil = update_my_profile($user_id, $_POST, $_FILES);
    
    if ($hasil['status']) {
        set_flash($hasil['pesan'], 'success');
        // Redirect sesuai level user agar path benar
        redirect($_SESSION['level'] . '/index.php?page=profil');
    } else {
        alert($hasil['pesan'], 'error');
    }
}
?>

<div class="container-fluid px-4">
    <h3 class="mt-4 mb-4 fw-bold text-dark">Pengaturan Profil</h3>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row g-5">
            <!-- Sidebar / Left Column: Foto Profil -->
            <div class="col-md-4 col-lg-3 text-center">
                <div class="d-flex flex-column align-items-center">
                    <div class="position-relative mb-3">
                        <?php 
                            $foto = !empty($profil['gambar']) ? '../assets/img/profil/' . $profil['gambar'] : '../assets/img/default-user.png';
                            if (!file_exists(__DIR__ . '/../' . str_replace('../', '', $foto))) {
                                $foto = 'https://ui-avatars.com/api/?name='.urlencode($profil['nama_lengkap']).'&background=random&size=200';
                            }
                        ?>
                        <div class="image-container shadow-sm border p-1 rounded-circle bg-white">
                            <img src="<?= $foto ?>" id="profile-image-preview" alt="Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <label for="gambar" id="btn-ganti-foto" class="btn btn-primary btn-sm rounded-pill position-absolute bottom-0 start-50 translate-middle-x mb-2 px-3 shadow-sm scale-hover d-none">
                            <i class="fas fa-camera me-1"></i> Ganti
                        </label>
                        <input type="file" name="gambar" id="gambar" class="d-none" accept="image/*" onchange="if(validateImageInput(this)) previewImage(this)">
                    </div>
                    
                    <h6 class="fw-bold mb-1"><?= $profil['nama_lengkap'] ?></h6>
                    <p class="text-muted small text-uppercase letter-spacing-1"><?= $profil['level'] ?></p>
                    
                    <div class="d-grid gap-2 w-100 mt-3">
                        <div class="p-3 border rounded-3 bg-light text-start">
                            <small class="text-muted d-block mb-1">Status Akun</small>
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main / Right Column: Form Inputs -->
            <div class="col-md-8 col-lg-9">
                <div class="bg-white p-4 rounded-3 border h-100 position-relative">
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary"><i class="fas fa-id-card me-2"></i>Data Diri</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="nama" class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
                            <input type="text" class="form-control bg-light border-0 profile-input" id="nama" name="nama" value="<?= $profil['nama_lengkap'] ?>" required disabled placeholder="Nama Lengkap Anda">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold text-secondary small">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted">@</span>
                                <input type="text" class="form-control bg-light border-0 profile-input" id="username" name="username" value="<?= $profil['username'] ?>" required disabled placeholder="Username">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="alamat" class="form-label fw-semibold text-secondary small">Alamat</label>
                            <textarea class="form-control bg-light border-0 profile-input" id="alamat" name="alamat" rows="3" disabled placeholder="Alamat Lengkap Anda"><?= $profil['alamat'] ?></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3 text-danger"><i class="fas fa-shield-alt me-2"></i>Ganti Password</h6>
                            <div class="alert alert-light border-start border-danger border-4 py-2 small text-muted">
                                Kosongkan jika tidak ingin mengganti password.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold text-secondary small">Password Baru</label>
                            <input type="password" class="form-control bg-light border-0 profile-input" id="password" name="password" disabled placeholder="Masukkan password baru">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="konfirmasi_password" class="form-label fw-semibold text-secondary small">Ulangi Password</label>
                            <input type="password" class="form-control bg-light border-0 profile-input" id="konfirmasi_password" name="konfirmasi_password" disabled placeholder="Ulangi password baru">
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end align-items-center gap-3">
                            <button type="button" id="btn-edit-profil" class="btn btn-sm btn-warning px-4 rounded-pill fw-bold text-dark shadow-sm hover-scale">
                                <i class="fas fa-edit me-2"></i>Ubah Profil
                            </button>
                            
                            <div id="action-buttons" class="d-none">
                                <button type="button" id="btn-batal" class="btn btn-sm btn-secondary px-4 rounded-pill fw-semibold me-2">Batal</button>
                                <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold shadow-lg scale-hover">
                                    <i class="fas fa-save me-2"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="<?= base_url('assets/js/profil.js') ?>?v=<?= time() ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/profil.css') ?>">
