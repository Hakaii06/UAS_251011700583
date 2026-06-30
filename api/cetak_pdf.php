<?php
include dirname(__DIR__) . '/config.php';

if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Data Produk UMKM Binaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; font-size: 13px; }
        .table th { background-color: #212529 !important; color: #fff !important; text-align: center; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body onload="window.print();">
<div class="container-fluid px-4 mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase mb-1">Laporan Rekapitulasi Data Produk UMKM Binaan</h2>
        <p class="text-muted small">Generated on: <?= date('d-m-Y H:i'); ?> WIB</p>
    </div>
    <table class="table table-bordered align-middle">
        <thead>
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
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center">
                    <?php if(!empty($row['gambar'])): ?>
                        <img src="uploads/<?= $row['gambar']; ?>" width="50" height="50" style="object-fit:cover;" class="rounded border">
                    <?php else: ?>
                        <span class="text-muted" style="font-size: 10px;">No Img</span>
                    <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($row['nama_produk']); ?></td>
                <td><?= htmlspecialchars($row['kategori']); ?></td>
                <td class="fw-medium">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                <td class="text-center"><?= $row['stok']; ?></td>
                <td><?= htmlspecialchars($row['nama_umkm']); ?></td>
                <td class="text-secondary" style="font-size: 12px;"><?= htmlspecialchars($row['deskripsi']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
