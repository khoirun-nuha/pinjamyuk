<?php
require_once __DIR__ . '/../functions/general/transaksi_func.php';

$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

// Nama file download
$filename = "Laporan_Peminjaman";
if ($bulan && $tahun) {
    $filename .= "_{$bulan}-{$tahun}";
} elseif ($tahun) {
    $filename .= "_{$tahun}";
}
$filename .= ".xls";

// Header untuk download Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$filename");

// Data
$query = ambil_semua_peminjaman($bulan, $tahun);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
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
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
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
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($query)): 
                // Ambil petugas jika ada id_petugas
                $nama_petugas = '-';
                if ($row['id_petugas']) {
                    $q_petugas = mysqli_query($conn, "SELECT nama_lengkap FROM pengguna WHERE id_pengguna = '".$row['id_petugas']."'");
                    $d_petugas = mysqli_fetch_assoc($q_petugas);
                    $nama_petugas = $d_petugas['nama_lengkap'];
                }
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama_peminjam'] ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                <td><?= $row['tgl_kembali_real'] ? date('d-m-Y', strtotime($row['tgl_kembali_real'])) : '-' ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>Rp <?= number_format($row['denda'], 0, ',', '.') ?></td>
                <td><?= $nama_petugas ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
