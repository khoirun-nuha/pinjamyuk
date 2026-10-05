<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Peminjaman Alat</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- jQuery (Moved from Footer for Global Access) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/layout.css?v='.time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v='.time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/petugas.css?v='.time()) ?>">
</head>
<body>
<script>
    // Prevent flash of white mode by applying class immediately
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>


