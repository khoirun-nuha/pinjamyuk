<?php
require_once 'config/database.php';
require_once 'functions/core/helper.php';

session_start();

// Jika sudah login, redirect sesuai level
if (isset($_SESSION['id_pengguna'])) {
    if ($_SESSION['level'] == 'admin') redirect('admin/index.php');
    elseif ($_SESSION['level'] == 'petugas') redirect('petugas/index.php');
    else redirect('peminjam/index.php');
}

// Proses Login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 1. Ambil data user berdasarkan username
    $query = mysqli_query($conn, "SELECT * FROM pengguna WHERE username = '$username'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $db_password = $data['password'];

        // 2. Verifikasi Password
        $isValid = false;

        // Cek hash modern (Bcrypt)
        if (password_verify($password, $db_password)) {
            $isValid = true;
        } 
        // Cek Legacy MD5 (hanya jika verify gagal & format md5)
        else if (md5($password) === $db_password) {
            $isValid = true;
            // Auto-upgrade ke hash yang aman
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            $uid = $data['id_pengguna'];
            mysqli_query($conn, "UPDATE pengguna SET password = '$new_hash' WHERE id_pengguna = '$uid'");
        }

        if ($isValid) {
            // Cek apakah akun diblokir
            if ($data['status'] == 'blokir') {
                $error = "Akun Anda diblokir! Hubungi admin.";
            } else {
                $_SESSION['id_pengguna'] = $data['id_pengguna'];
                $_SESSION['nama'] = $data['nama_lengkap'];
                $_SESSION['level'] = $data['level'];
                $_SESSION['gambar'] = $data['gambar'];

                // Catat Log
                catat_log($data['id_pengguna'], 'login', 'Login berhasil');
                
                // Set Flash Message untuk SweetAlert di Dashboard
                $_SESSION['flash_message'] = "Selamat datang kembali, " . $data['nama_lengkap'] . "!";
                $_SESSION['flash_type'] = "login_success";

                if ($data['level'] == 'admin') redirect('admin/index.php');
                elseif ($data['level'] == 'petugas') redirect('petugas/index.php');
                else redirect('peminjam/index.php');
            }
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Peminjaman</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

    <!-- Background Animation -->
    <ul class="circles">
        <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
    </ul>

    <div class="container d-flex justify-content-center">
        <div class="login-card animate__animated animate__fadeInDown">
            <div class="login-header">
                <div class="bg-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3 shadow" style="width: 80px; height: 80px;">
                    <i class="fas fa-school fa-2x text-primary" style="color: #e73c7e !important;"></i>
                </div>
                <h3 class="fw-bold mb-0">PinjamYuk</h3>
                <small class="text-white-50">Silakan login untuk melanjutkan</small>
            </div>

            <!-- Alert Error dihapus, diganti SweetAlert -->

            <form action="" method="POST">
                <div class="position-relative mb-4">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="off">
                </div>

                <div class="position-relative mb-4">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" name="login" class="btn btn-login">
                        MASUK SEKARANG <i class="fas fa-arrow-right ms-2 animate__animated animate__fadeInLeft animate__infinite" style="animation-duration: 2s;"></i>
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <small class="text-white text-opacity-75">Jika Anda belum memiliki akun atau lupa password, silakan hubungi admin.</small>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/js/login.js') ?>"></script>

    <!-- Logic SweetAlert -->
    <script>
        <?php if (isset($error)): ?>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: 'error',
                title: '<?= $error ?>'
            });
        <?php endif; ?>
    </script>
</body>
</html>
