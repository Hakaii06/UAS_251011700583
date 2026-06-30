<?php
session_start();
include('config.php');

// Proteksi halaman: Jika belum login, tendang balik ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit;
}

// Ambil data produk dari TiDB Cloud
$query = "SELECT * FROM produk";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - UMKM Hub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">UMKM Hub Dashboard</span>
        <a href="logout" class="btn btn-danger btn-sm">Logout</a> </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Daftar Produk UMKM</h3>
        <a href="tambah" class="btn btn-success">Tambah Produk</a> </div>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($result) > 0):
                while($row = mysqli_fetch_assoc($result)): 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= $row['stok']; ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                </tr>
            <?php 
                endwhile;
            else: 
            ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada data produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
