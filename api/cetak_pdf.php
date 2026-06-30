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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; color: #000; font-size: 13px; }
        .table th { background-color: #212529 !important; color: #fff !important; text-align: center; vertical-align: middle; }
        .table td { vertical-align: middle; }
        @media print { .no-print { display: none !important; } body { padding: 0; } }
    </style>
</head>
<body onload="window.print();">
<div class="container-fluid px-4 mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase mb-1">Laporan Rekapitulasi Data Produk UMKM Binaan</h2>
        <p class="text-muted small">Dokumen digenerate otomatis pada: <?= date('d-m-Y H:i'); ?> WIB</p>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th width="40">No</th>
                    <th width="80">Preview</th>
                    <th width="200">Nama Produk</th>
                    <th width="100">Kategori</th>
                    <th width="110">Harga Jual</th>
                    <th width="60">Stok</th>
                    <th width="180">Nama UMKM</th>
                    <th>Deskripsi Singkat</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td class="text-center">
                        <?php if(!empty($row['gambar']) && file_exists(dirname(__DIR__) . '/uploads/' . $row['gambar'])): ?>
                            <img src="uploads/<?= $row['gambar']; ?>" width="50" height="50" style="object-fit:cover;">
                        <?php else: ?>
                            <div class="bg-light rounded text-muted small d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-size: 9px;">No Img</div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-semibold"><?= htmlspecialchars($row['nama_produk']); ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['kategori']); ?></td>
                    <td class="text-center fw-medium">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td class="text-center"><?= $row['stok']; ?></td>
                    <td><?= htmlspecialchars($row['nama_umkm']); ?></td>
                    <td class="text-secondary" style="font-size: 12px;"><?= htmlspecialchars($row['deskripsi']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4 mb-5 no-print">
        <button onclick="window.print();" class="btn btn-primary px-4 me-2">Cetak Ulang</button>
        <a href="/index" class="btn btn-secondary px-4">Kembali ke Beranda</a>
    </div>
</div>
</body>
</html>
