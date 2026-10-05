<?php
require_once __DIR__ . '/../functions/core/auth.php';
require_once __DIR__ . '/../functions/general/transaksi_func.php';

cek_login();
cek_level('petugas');

$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

$type = $_GET['type'] ?? 'excel';

// Nama file download
$filename = "Laporan_Peminjaman";
if ($bulan && $tahun) {
    $filename .= "_{$bulan}-{$tahun}";
} elseif ($tahun) {
    $filename .= "_{$tahun}";
}

if ($type == 'excel') {
    $filename .= ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
}

// Data
$query = ambil_semua_peminjaman($bulan, $tahun, $_SESSION['id_pengguna']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-print { display: none; }
        @media print {
            .no-print { display: none; }
        }
    </style>
    <?php if ($type == 'pdf'): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <?php endif; ?>
</head>
<body id="content">
</head>
<body>
    <center>
        <h2>Laporan Peminjaman Alat</h2>
        <?php if ($bulan && $tahun): ?>
            <p>Periode: <?= date('F', mktime(0, 0, 0, $bulan, 10)) ?> <?= $tahun ?></p>
        <?php elseif ($tahun): ?>
            <p>Tahun: <?= $tahun ?></p>
        <?php endif; ?>
    </center>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali (Rencana)</th>
                <th>Tgl Kembali (Real)</th>
                <th>Status</th>
                <th>Denda</th>
                <th>P.Pinjam</th>
                <th>P.Kembali</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($query)): 
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama_peminjam'] ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                <td><?= $row['tgl_kembali_real'] ? date('d-m-Y', strtotime($row['tgl_kembali_real'])) : '-' ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>Rp <?= number_format($row['denda'], 0, ',', '.') ?></td>
                <td><?= !empty($row['nama_petugas']) ? $row['nama_petugas'] : '-' ?></td>
                <td><?= !empty($row['nama_petugas_kembali']) ? $row['nama_petugas_kembali'] : '-' ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        <?php if ($type == 'print'): ?>
        window.print();
        <?php elseif ($type == 'pdf'): ?>
        var element = document.getElementById('content');
        var opt = {
            margin:       0.5,
            filename:     '<?= $filename ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };
        html2pdf().set(opt).from(element).save();
        <?php endif; ?>
    </script>
</body>
</html>
