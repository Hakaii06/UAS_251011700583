<?php
session_start();
if (!isset($_SESSION['login'])) { 
    header("Location: login"); 
    exit; 
}

// Menggunakan jalur absolut agar aman di serverless Vercel
include dirname(__DIR__) . '/config.php';

$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UMKM Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .navbar-brand { font-weight: 700; color: #4f46e5 !important; }
        .main-card { background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: none; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container">
        <a class="navbar-brand" href="#">UMKM Hub</a>
        <a href="logout" class="btn btn-outline-danger btn-sm rounded-2 fw-medium">Keluar</a>
    </div>
</nav>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Daftar Komoditas UMKM</h4>
            <p class="text-muted small mb-0">Manajemen data katalog produk binaan daerah</p>
        </div>
        <div>
            <a href="tambah" class="btn btn-primary btn-sm px-3 rounded-2 fw-semibold">Tambah Produk</a>
        </div>
    </div>

    <div class="card main-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>UMKM Produsen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_produk']); ?></td>
                        <td><span class="badge bg-light text-primary border border-primary-subtle px-2 py-1"><?= htmlspecialchars($row['kategori']); ?></span></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><?= $row['stok']; ?> unit</td>
                        <td><?= htmlspecialchars($row['nama_umkm']); ?></td>
                        <td class="text-center">
                            <a href="edit?id=<?= $row['id']; ?>" class="btn btn-light btn-sm text-warning border fw-medium px-2 py-1 me-1">Edit</a>
                        </td>
                    </tr>
                    <?php endwhile; if(mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data produk komoditas.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
