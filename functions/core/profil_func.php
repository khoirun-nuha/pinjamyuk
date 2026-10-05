<?php
require_once __DIR__ . '/../../config/database.php';

function get_my_profile($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$id'");
    return mysqli_fetch_assoc($result);
}

function update_my_profile($id, $data, $file) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $alamat = mysqli_real_escape_string($conn, $data['alamat']);
    
    // Cek username unik (kecuali milik sendiri)
    $cek = mysqli_query($conn, "SELECT id_pengguna FROM pengguna WHERE username = '$username' AND id_pengguna != '$id'");
    if (mysqli_num_rows($cek) > 0) {
        return ['status' => false, 'pesan' => 'Username sudah digunakan user lain!'];
    }

    // Handle Upload Foto
    $gambar_query = "";
    if ($file['gambar']['error'] === 0) {
        $ext = strtolower(pathinfo($file['gambar']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($ext, $valid_ext)) {
            return ['status' => false, 'pesan' => 'Format gambar tidak valid! Gunakan JPG/PNG.'];
        }
        
        if ($file['gambar']['size'] > 2000000) { // 2MB
            return ['status' => false, 'pesan' => 'Ukuran gambar terlalu besar! Maksimal 2MB.'];
        }
        
        $new_name = uniqid() . '.' . $ext;
        $dest = __DIR__ . '/../../assets/img/profil/' . $new_name;
        
        if (move_uploaded_file($file['gambar']['tmp_name'], $dest)) {
            $gambar_query = ", gambar = '$new_name'";
        } else {
            return ['status' => false, 'pesan' => 'Gagal mengupload gambar!'];
        }
    }

    // Handle Password
    $pass_query = "";
    if (!empty($data['password'])) {
        if ($data['password'] !== $data['konfirmasi_password']) {
            return ['status' => false, 'pesan' => 'Konfirmasi password tidak cocok!'];
        }
        $password = md5($data['password']);
        $pass_query = ", password = '$password'";
    }

    $query = "UPDATE pengguna SET 
              nama_lengkap = '$nama', 
              username = '$username', 
              alamat = '$alamat' 
              $gambar_query 
              $pass_query 
              WHERE id_pengguna = '$id'";
              
    if (mysqli_query($conn, $query)) {
        if (mysqli_affected_rows($conn) == 0 && empty($gambar_query) && empty($pass_query)) {
             // Opsional: Query sukses tapi tidak ada data berubah, tetap return true
        }
        
        // Update session jika nama berubah
        if (isset($_SESSION['nama'])) $_SESSION['nama'] = $nama;
        if (isset($_SESSION['nama_lengkap'])) $_SESSION['nama_lengkap'] = $nama;
        return ['status' => true, 'pesan' => 'Profil berhasil diperbarui!'];
    } else {
        return ['status' => false, 'pesan' => 'Gagal memperbarui profil: ' . mysqli_error($conn)];
    }
}
?>
