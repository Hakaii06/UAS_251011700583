<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include 'config.php';
$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Produk UMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; color: #000; font-size: 13px; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body onload="window.print();">
<div class="container mt-5">
    <div class="text-center border-bottom pb-3 mb-4">
        <h3 class="fw-bold mb-1">LAPORAN REKAPITULASI DATA PRODUK UMKM BINAAN</h3>
        <p class="text-muted small mb-0">Dokumen digenerate otomatis secara tersistem pada: <?= date('d-m-Y H:i'); ?> WIB</p>
    </div>
    
    <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Preview</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Nama UMKM</th>
                <th>Deskripsi Singkat</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><img src="uploads/<?= $row['gambar']; ?>" width="45" height="45" style="object-fit:cover; border-radius:4px;"></td>
                <td class="fw-semibold"><?= $row['nama_produk']; ?></td>
                <td><?= $row['kategori']; ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                <td><?= $row['stok']; ?></td>
                <td><?= $row['nama_umkm']; ?></td>
                <td class="text-start small"><?= $row['deskripsi']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <div class="text-center mt-5 no-print">
        <button onclick="window.print();" class="btn btn-primary btn-sm me-2">Cetak Ulang</button>
        <a href="index.php" class="btn btn-secondary btn-sm">Kembali ke Beranda</a>
    </div>
</div>
</body>
</html>