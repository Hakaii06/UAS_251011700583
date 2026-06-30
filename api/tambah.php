<?php
session_start();
include('config.php');

if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga       = intval($_POST['harga']);
    $stok        = intval($_POST['stok']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $query = "INSERT INTO produk (nama_produk, harga, stok, deskripsi) VALUES ('$nama_produk', $harga, $stok, '$deskripsi')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index"); // Sukses, balik ke /index tanpa .php
        exit;
    } else {
        $msg = "Gagal menambah produk: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - UMKM Hub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Tambah Produk Baru</h5>
                </div>
                <div class="card-body">
                    <?php if($msg): ?>
                        <div class="alert alert-danger"><?= $msg; ?></div>
                    <?php endif; ?>
                    <form action="tambah" method="POST"> <div class="mb-3">
                            <label>Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
