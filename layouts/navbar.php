<?php
// Logic Fetch Profile untuk Navbar
$id_user = $_SESSION['id_pengguna'] ?? 0;
$conn_header = $conn ?? null; 

// Jika $conn belum ada
if (!$conn_header && file_exists(__DIR__ . '/../config/database.php')) {
    include_once __DIR__ . '/../config/database.php';
    $conn_header = $conn;
}

$foto_nav = 'default.jpg'; 
$nama_nav = $_SESSION['nama'] ?? 'User';

if ($conn_header && $id_user) {
    try {
        $q_nav = mysqli_query($conn_header, "SELECT gambar, nama_lengkap FROM pengguna WHERE id_pengguna = '$id_user'");
        if ($q_nav && mysqli_num_rows($q_nav) > 0) {
            $d_nav = mysqli_fetch_assoc($q_nav);
            $foto_nav = $d_nav['gambar'] ?? 'default.jpg';
            $nama_nav = $d_nav['nama_lengkap'] ?? $nama_nav;
        }
    } catch (Exception $e) { }
}

$foto_path = 'assets/img/profil/' . $foto_nav;
if (!file_exists(__DIR__ . '/../' . $foto_path)) {
     if (file_exists(__DIR__ . '/../assets/img/' . $foto_nav)) {
         $foto_path = 'assets/img/' . $foto_nav;
     } else {
         $foto_path = 'assets/img/default-user.png'; 
     }
}
?>

<!-- Top Navbar inside Main Content -->
<!-- Top Profile Bar -->
<!-- Top Profile Bar styles -->
<link rel="stylesheet" href="<?= base_url('assets/css/navbar.css') ?>">
<div class="d-flex justify-content-between align-items-center py-2 px-4 navbar-top navbar-theme">
    
    <!-- Left: Sidebar Toggle -->
    <button class="nav-icon-btn" id="sidebarToggle" onclick="toggleSidebar()" title="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Right: Actions & Profile -->
    <div class="d-flex align-items-center">
        <!-- Action Buttons -->
        <div class="d-flex align-items-center me-3 gap-2">
            <!-- Theme Toggle -->
            <button class="nav-icon-btn" onclick="toggleTheme()" title="Ubah Tema">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            <!-- Fullscreen -->
            <button class="nav-icon-btn" onclick="toggleFullScreen()" title="Full Screen">
                <i class="fas fa-expand"></i>
            </button>
        </div>

        <div class="border-start mx-3" style="height: 30px;"></div>

        <!-- User User Profile Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="me-2 text-end d-none d-sm-block">
                    <div class="fw-bold small"><?= htmlspecialchars($nama_nav) ?></div>
                    <div class="text-muted" style="font-size: 0.75rem;"><?= ucfirst($_SESSION['level'] ?? '') ?></div>
                </div>
                <img src="<?= base_url($foto_path) ?>" alt="user" width="40" height="40" class="rounded-circle border border-1 border-secondary shadow-sm" style="object-fit: cover;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small shadow border-0 mt-2" aria-labelledby="dropdownUser1">
                <li><h6 class="dropdown-header">Profil Pengguna</h6></li>
                <li><a class="dropdown-item" href="<?= base_url($_SESSION['level'] . '/index.php?page=profil') ?>"><i class="fas fa-user-circle me-2"></i>Edit Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger confirm-action" href="<?= base_url('logout.php') ?>" data-message="Yakin ingin logout?"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<script>
    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    // Check localStorage on load
    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme');
        const icon = document.getElementById('themeIcon');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            if(icon) icon.classList.replace('fa-moon', 'fa-sun');
        }
        
        // Sidebar preference (Optional)
        const sidebarState = localStorage.getItem('sidebar');
        if (sidebarState === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        }
    });

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
             document.body.classList.toggle('sidebar-mobile-open');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
            if(document.body.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebar', 'collapsed');
            } else {
                localStorage.setItem('sidebar', 'expanded');
            }
        }
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && document.body.classList.contains('sidebar-mobile-open')) {
            const sidebar = document.getElementById('sidebarMenu');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            // If click is outside sidebar and not on the toggle button
            if (sidebar && !sidebar.contains(event.target) && (!toggleBtn || !toggleBtn.contains(event.target))) {
                document.body.classList.remove('sidebar-mobile-open');
            }
        }
    });

    function toggleTheme() {
        const icon = document.getElementById('themeIcon');
        document.body.classList.toggle('dark-mode');
        
        if (document.body.classList.contains('dark-mode')) {
            icon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('theme', 'dark');
        } else {
            icon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('theme', 'light');
        }
    }
</script>
