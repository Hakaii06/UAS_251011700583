<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include 'config.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['submit'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori    = $_POST['kategori'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $nama_umkm   = mysqli_real_escape_string($conn, $_POST['nama_umkm']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar_lama = $_POST['gambar_lama'];

    if ($_FILES['gambar']['error'] === 4) {
        $newName = $gambar_lama;
    } else {
        $filename = $_FILES['gambar']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newName = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $newName);
        if (file_exists("uploads/" . $gambar_lama)) { unlink("uploads/" . $gambar_lama); }
    }

    $query = "UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga='$harga', stok='$stok', nama_umkm='$nama_umkm', deskripsi='$deskripsi', gambar='$newName' WHERE id=$id";
    if (mysqli_query($conn, $query)) { header("Location: index.php"); exit; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - UMKM Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .form-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: none; }
    </style>
</head>
<body class="py-5">
<div class="container" style="max-width: 650px;">
    <div class="card form-card p-4">
        <h4 class="fw-bold mb-1 text-dark text-center">Form Perbarui Produk</h4>
        <p class="text-muted text-center small mb-4">Ubah entri spesifikasi produk UMKM terpilih</p>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="gambar_lama" value="<?= $row['gambar']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control form-control-sm" value="<?= $row['nama_produk']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Kategori Kelompok</label>
                    <select name="kategori" class="form-select form-select-sm" required>
                        <option value="Makanan" <?= $row['kategori'] == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                        <option value="Minuman" <?= $row['kategori'] == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                        <option value="Kerajinan" <?= $row['kategori'] == 'Kerajinan' ? 'selected' : ''; ?>>Kerajinan</option>
                        <option value="Fashion" <?= $row['kategori'] == 'Fashion' ? 'selected' : ''; ?>>Fashion</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Harga Jual Satuan (Rp)</label>
                    <input type="number" name="harga" class="form-control form-control-sm" value="<?= $row['harga']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Volume Stok</label>
                    <input type="number" name="stok" class="form-control form-control-sm" value="<?= $row['stok']; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Nama UMKM / Pemilik Produsen</label>
                <input type="text" name="nama_umkm" class="form-control form-control-sm" value="<?= $row['nama_umkm']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Rincian Deskripsi Produk</label>
                <textarea name="deskripsi" class="form-control form-control-sm" rows="3" required><?= $row['deskripsi']; ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary d-block mb-2">Gambar Saat Ini</label>
                <img src="uploads/<?= $row['gambar']; ?>" width="80" class="img-thumbnail rounded mb-2 d-block">
                <input type="file" name="gambar" class="form-control form-control-sm" accept="image/*">
                <small class="text-muted" style="font-size: 11px;">*Abaikan field ini jika tidak ingin merubah gambar produk</small>
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-light btn-sm px-3 rounded-2 fw-medium">Kembali</a>
                <button type="submit" name="submit" class="btn btn-warning btn-sm px-4 rounded-2 fw-semibold text-white">Update Data</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
