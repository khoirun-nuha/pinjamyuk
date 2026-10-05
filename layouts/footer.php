    <!-- JQuery & Bootstrap JS -->
    <!-- Bootstrap JS (jQuery moved to Header) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JS -->
    <script src="<?= base_url('assets/js/main.js') ?>?v=<?= time() ?>"></script>

    <script>
        // SweetAlert2 Flash Message Logic (Requires PHP Session)
        <?php if (isset($_SESSION['flash_message'])): ?>
            <?php if ($_SESSION['flash_type'] === 'login_success'): ?>
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
                    icon: 'success',
                    title: '<?= $_SESSION['flash_message'] ?>'
                });
            <?php else: ?>
                Swal.fire({
                    icon: '<?= $_SESSION['flash_type'] ?>',
                    title: '<?= ucfirst($_SESSION['flash_type'] == 'error' ? 'Gagal' : ($_SESSION['flash_type'] == 'warning' ? 'Peringatan' : 'Berhasil')) ?>',
                    text: '<?= $_SESSION['flash_message'] ?>',
                    showConfirmButton: true,
                    timer: 3000 
                });
            <?php endif; ?>
            
            <?php 
            // Hapus session setelah ditampilkan
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            ?>
        <?php endif; ?>
    </script>
</body>
</html>
